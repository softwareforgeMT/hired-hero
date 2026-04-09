<?php

namespace App\CentralLogics;

use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MockInterview
{
    public static function getApiKey($keyName)
    {
        return env($keyName);
    }

    public static function generateQuestionsUsingOpenAI(string $jobDescription, string $difficultyLevel, int $questionLimit = 1): array
    {
        $url = 'https://api.openai.com/v1/chat/completions';

        $data = [
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Generate {$questionLimit} interview questions based on the provided job description. The questions should be tailored to match the specified difficulty level but should not explicitly mention the difficulty level."
                ],
                [
                    'role' => 'user',
                    'content' => "Generate {$questionLimit} interview questions for a job position described as follows: {$jobDescription}. The difficulty level is {$difficultyLevel}."
                ],
            ],
        ];

        try {
            [$body, $respHeaders, $httpCode] = self::makeCurlRequest(
                $url,
                self::getApiKey('OPENAI_API_KEY'),
                $data
            );
        } catch (\Throwable $e) {
            throw new \Exception('Failed to generate questions using OpenAI');
        }

        $responseArray = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from OpenAI: ' . json_last_error_msg());
        }

        if (!isset($responseArray['choices'][0]['message']['content'])) {
            throw new \Exception('Unexpected response or no content from OpenAI.');
        }

        $raw = trim($responseArray['choices'][0]['message']['content']);
        $rawLines = array_map('trim', preg_split('/\r\n|\r|\n/', $raw));
        $questions = array_values(array_filter(array_map(
            fn($q) => preg_replace('/^\d+\.\s*/', '', $q),
            $rawLines
        )));

        return array_slice($questions, 0, $questionLimit);
    }

    public static function convertTextToSpeech($text, $key, $voice = 'iP95p4xoKVk53GoZ742B')
    {
        $url = 'https://api.elevenlabs.io/v1/text-to-speech/' . $voice . '/stream';

        $requestData = [
            'text' => $text,
            'model_id' => 'eleven_monolingual_v1',
            'voice_settings' => [
                'stability' => 0.5,
                'similarity_boost' => 0.75
            ]
        ];

        try {
            [$body, $responseHeaders, $httpCode] = self::makeCurlRequest(
                $url,
                null,
                $requestData,
                [
                    'Accept: audio/mpeg',
                    'Content-Type: application/json',
                    'xi-api-key: ' . self::getApiKey('ELEVENLABS_API_KEY')
                ]
            );

            $contentType = $responseHeaders['content-type'] ?? '';

            if (stripos($contentType, 'audio') === false) {
                $json = json_decode($body, true);

                $message = 'Unknown error';
                if (isset($json['detail'][0]['msg'])) {
                    $message = json_encode($json['detail']);
                } elseif (isset($json['message'])) {
                    $message = $json['message'];
                } elseif (!empty($json)) {
                    $message = json_encode($json);
                }

                throw new \Exception("ElevenLabs Error: {$message}");
            }

            $file = uniqid('audio_') . '.mp3';
            self::upload('mock/audio', $file, $body);

            return asset("mock/audio/" . $file);

        } catch (\Exception $e) {
            throw new \Exception('Failed to Load');
        }
    }

    public static function processInterviewData($jobDescription, $interviewResultsData)
    {
        $evaluation = "
            Overall Evaluation
            1. The response to the first question lacks depth and specificity...
            2. The response to the second question is also vague and lacks elaboration...
            Overall score: 1.5/5
            Summary: Candidate responses lack depth and specifics relative to the job description.
        ";

        if (env('APP_ENV') === 'local') {
            return $evaluation;
        }

        $fullPrompt = "Below are questions derived from the job description and the candidate's responses. Score each question response based on its adequacy and relevance to the question on a scale of 0 to 5 in bullet points for each question. Also conclude with an overall score out of 5 and a summary evaluation.\n\n";
        $fullPrompt .= "Job Description: {$jobDescription}\n\n";

        foreach ($interviewResultsData as $data) {
            $q = $data['question_text'] ?? '';
            $a = $data['transcription'] ?? '';
            $fullPrompt .= "Question: {$q}\nAnswer: {$a}\nScore the response:\n";
        }

        try {
            [$body, $respHeaders, $httpCode] = self::makeCurlRequest(
                'https://api.openai.com/v1/chat/completions',
                self::getApiKey('OPENAI_API_KEY'),
                [
                    'model' => 'gpt-3.5-turbo-1106',
                    'messages' => [
                        ['role' => 'system', 'content' => "Score each response on a 0–5 scale with bullets per question, then provide an overall /5 and a brief summary of fit."],
                        ['role' => 'user', 'content' => $fullPrompt]
                    ]
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate questions using OpenAI: ' . $e->getMessage());
        }

        $responseArray = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('OpenAI body not valid JSON', ['err' => json_last_error_msg(), 'code' => $httpCode ?? null]);
            throw new \Exception('Unexpected OpenAI response format.');
        }

        if (!isset($responseArray['choices'][0]['message']['content'])) {
            \Log::error('OpenAI content missing', ['keys' => array_keys($responseArray)]);
            throw new \Exception('Unexpected response or no content from OpenAI.');
        }

        return $responseArray['choices'][0]['message']['content'];
    }

    public static function transcribeAudioWithWhisper($audioFilePath)
    {
        $url = 'https://api.openai.com/v1/audio/transcriptions';

        if (!file_exists($audioFilePath) || !is_readable($audioFilePath)) {
            return json_encode(['error' => 'File does not exist or is not readable']);
        }

        $cfile = new \CURLFile(realpath($audioFilePath), 'audio/mpeg', basename($audioFilePath));
        $data = [
            'file' => $cfile,
            'model' => 'whisper-1',
            'language' => 'en'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . self::getApiKey('OPENAI_API_KEY'),
            'Content-Type: multipart/form-data'
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Log::error('Whisper Transcription Failed: ' . $err);
            throw new \Exception('cURL Error:' . $err);
        } else {
            return $response;
        }
    }

    public static function processPresentationData($presentationData)
    {
        $evaluation = "
            Overall Evaluation
            1. The response to the first question lacks depth and specificity...
            2. The response to the second question is also vague and lacks elaboration...
            Overall score: 1.5/5
            Summary: Candidate responses lack depth and specifics relative to the topic.
        ";

        if (env('APP_ENV') === 'local') {
            return $evaluation;
        }

        $topic = $presentationData['presentation_topic'] ?? '';
        $trans = $presentationData['transcription'] ?? '';
        $fullPrompt = "Given the transcription of a presentation and its topic, evaluate the content, clarity, engagement level, and overall effectiveness based on the topic. Provide detailed feedback and suggestions for improvement.\n\nTopic: {$topic}\nTranscription:\n{$trans}";

        try {
            [$body, $respHeaders, $httpCode] = self::makeCurlRequest(
                'https://api.openai.com/v1/chat/completions',
                self::getApiKey('OPENAI_API_KEY'),
                [
                    'model' => 'gpt-3.5-turbo-1106',
                    'messages' => [
                        ['role' => 'system', 'content' => "Evaluate the presentation based on the topic and transcription in terms of content, clarity, engagement, and overall effectiveness. Provide detailed feedback and improvement suggestions."],
                        ['role' => 'user', 'content' => $fullPrompt]
                    ]
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate feedback using OpenAI: ' . $e->getMessage());
        }

        $responseArray = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('OpenAI body not valid JSON (presentation)', ['err' => json_last_error_msg(), 'code' => $httpCode ?? null]);
            throw new \Exception('Unexpected OpenAI response format.');
        }

        if (!isset($responseArray['choices'][0]['message']['content'])) {
            \Log::error('OpenAI content missing (presentation)', ['keys' => array_keys($responseArray)]);
            throw new \Exception('Unexpected response or no content from OpenAI.');
        }

        return $responseArray['choices'][0]['message']['content'];
    }

    public static function upload($dir, $filename, $fileContent)
    {
        $directory = public_path($dir);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        $filePath = $directory . '/' . $filename;
        file_put_contents($filePath, $fileContent);
        return $filePath;
    }

    /**
     * Make a POST request with JSON body.
     * Returns: [$body (string), $responseHeaders (array lowercased keys), $httpCode (int)]
     */
    private static function makeCurlRequest(string $url, ?string $apiKey = null, array $data = [], array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $defaultHeaders = [];
        $headerKeys = array_map(function ($h) {
            return strtolower(explode(':', $h)[0]);
        }, $headers);

        if (!in_array('content-type', $headerKeys)) {
            $defaultHeaders[] = 'Content-Type: application/json';
        }

        if ($apiKey) {
            $defaultHeaders[] = 'Authorization: Bearer ' . $apiKey;
        }

        $allHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $raw = curl_exec($ch);

        if ($raw === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $headerString = substr($raw, 0, $headerSize);
        $body         = substr($raw, $headerSize);

        $responseHeaders = [];
        foreach (explode("\r\n", $headerString) as $line) {
            if (strpos($line, ':') !== false) {
                [$k, $v] = explode(':', $line, 2);
                $responseHeaders[strtolower(trim($k))] = trim($v);
            }
        }

        return [$body, $responseHeaders, $httpCode];
    }
}

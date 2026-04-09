<?php

namespace App\Http\Controllers\Front;

use App\Models\InterviewAttempt;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\MockInterview;
use App\CentralLogics\Helpers;
class MockInterviewController extends Controller
{
    public function __construct(Request $request)
    {

        $this->middleware(['auth', 'check.subscription:interviewAccess'])->only([
            'addJobDetails', 'startInterview', 'uploadAudio', 'showResult'
        ]);
    }
    
    public function addJobDetails()
    {
        $userId = auth()->id();
        $activePlan = Helpers::getActivePlan($userId);
        $availableLevels = [];
        if ($activePlan) {
            $accessSection = is_array($activePlan->access_section) ? $activePlan->access_section : json_decode($activePlan->access_section, true);
            $availableLevels = $accessSection['questionAccess']['levels'] ?? [];
        }


        return view('front.mock_interview.add_job_details', compact('availableLevels'));
        
    }



    public function startInterview(Request $request)
    {
         
        // Check user's access limits
        if (!Helpers::hasAccess(auth()->id(), 'interviewAccess')) {
            return back()->with('error', 'You have reached the limit of your mock interviews for this period.');
        }

        $validatedData = $request->validate([
            'job_description' => 'required|string|max:8000|min:150',
            'difficulty_level' => [
                'required',
                function ($attribute, $value, $fail) {
                    $userId = auth()->id();
                    $activeOrder = Helpers::getActivePlan($userId);
                    if (!$activeOrder) {
                        return $fail('You do not have an active subscription.');
                    }
                    $accessSection = $activeOrder->access_section;
                    $allowedLevels = $accessSection['questionAccess']['levels'] ?? [];

                    if (!in_array($value, $allowedLevels)) {
                        return $fail('Selected difficulty level is not available in your subscription.');
                    }
                }
            ],
        ]);

        try {
            session()->forget('interview_data');
            session()->forget('interview_results_data');

            // Generate interview questions using helper function
            $jobDescription = $validatedData['job_description'];
            $difficultyLevel = $validatedData['difficulty_level'];

             // Get the active plan to determine question limit
            $activeOrder = Helpers::getActivePlan(auth()->id());
            $accessSection = is_array($activeOrder->access_section) ? $activeOrder->access_section : json_decode($activeOrder->access_section, true);

            $questionLimit = $accessSection['interviewAccess']['questions'];
            // Call the function to generate questions
            $questions = MockInterview::generateQuestionsUsingOpenAI($jobDescription, $difficultyLevel, $questionLimit);
            // For each question, generate audio and store the data in the session
            $questionData = [];
            foreach ($questions as $key => $question) {
                $audioFilePath = MockInterview::convertTextToSpeech($question, $key);
                $questionData[] = [
                    'question' => $question,
                    'audio_file' => $audioFilePath,
                ];
            }

            // Combine all the data into one parent array
            $interviewData = [
                'job_description' => $jobDescription,
                'user_id' => auth()->id(),
                'questions' => $questionData,
                'difficulty_level' => $difficultyLevel
            ];

            // Store the parent array in the session
            $request->session()->put('interview_data', $interviewData);

            // Pass job details to the interview view
            return view('front.mock_interview.interview', compact('interviewData'));
        } catch (\Exception $e) {
            Helpers::logError($e);
            // Handle the error gracefully, redirect back with an error message
            return back()->with('error', $e->getMessage());
        }
    }


    public function uploadAudio(Request $request)
    {
        try {
            // Validate required input
            if (!$request->hasFile('audio') || !$request->filled(['questionIndex', 'questionText'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Required data not received',
                    'details' => $request->only(['audio', 'questionIndex', 'questionText'])
                ]);
            }

            $audioFile = $request->file('audio');
            $questionIndex = $request->input('questionIndex');
            $questionText = $request->input('questionText');
            $speechTranscription = $request->input('transcription', '');

            // Generate a unique filename and store the audio
            $filename = "audio_{$questionIndex}_" . time() . ".webm";
            $filePath = MockInterview::upload('mock/recordings', $filename, file_get_contents($audioFile));

            if (env('APP_ENV') === 'local') {
                $transcription = $speechTranscription;
            }else{
                // Attempt transcription using Whisper
                $transcriptionResultjson = MockInterview::transcribeAudioWithWhisper($filePath);
                $transcriptionResult = json_decode($transcriptionResultjson, true);
                $transcription = $transcriptionResult['text'] ?? $speechTranscription;
            }
            

            // Store interview data in the session
            $interviewData = $request->session()->get('interview_results_data', []);
            $interviewData[] = [
                'question_index' => $questionIndex,
                'question_text' => $questionText,
                'transcription' => $transcription,
                'audio_filename' => $filename
            ];
            $request->session()->put('interview_results_data', $interviewData);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded and processed successfully',
                'transcription' => $transcription,
                'questionText' => $questionText,
                'speech' => $speechTranscription
            ]);
        } catch (\Exception $e) {
            Helpers::logError($e);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the audio',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function showResult(Request $request)
    {
        try {

            $interviewData = $request->session()->get('interview_data', []);
            $jobDescription = $interviewData['job_description'] ?? null;
            $interviewResultsData = $request->session()->get('interview_results_data', []);

            if (empty($jobDescription) || empty($interviewResultsData)) {
                $error = 'No interview data found.';
                return view('front.mock_interview.result', compact('error'));
            }

            // Record user activity
            Helpers::recordActivity(auth()->id(), 'interviewAccess');

            $result = MockInterview::processInterviewData($jobDescription, $interviewResultsData);
            // ==== SAVE ATTEMPT ====
if (Auth::check()) {
    try {
        InterviewAttempt::create([
            'user_id'        => Auth::id(),
            'question_count' => count($interviewResultsData ?? []),
            'score'          => $result['overall']['score'] ?? null, // ok if null
            'payload'        => [
                'overall'   => $result['overall'] ?? [],
                'questions' => $result['questions'] ?? [],
                'job'       => $jobDescription,
                'raw'       => $interviewResultsData, // your transcripts/audio per question
            ],
            'completed_at'   => now(),
        ]);
    } catch (\Throwable $e) {
        // Don't break the page if saving fails; log and continue
        \Log::error('Failed to save interview attempt: '.$e->getMessage());
    }
}
// ==== END SAVE ====
            return view('front.mock_interview.result', compact('result', 'interviewResultsData'));
        } catch (\Exception $e) {
            Helpers::logError($e);
            $error = 'An error occurred while processing the interview.';
            return view('front.mock_interview.result', compact('error'));
        }
    }



}

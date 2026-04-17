<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\MockInterview;
use App\CentralLogics\Helpers;
class PresentationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum,web', 'check.subscription:presentationAccess'])->only([
            'createPresentation', 'recordPresentation', 'uploadAudio', 'provideFeedback'
        ]);
    }


    // Step 1: Define the presentation topic
    public function createPresentation()
    {   
        return view('front.presentation.create');
    }

    // Step 2: Record the presentation
    public function recordPresentation(Request $request)
    {

        // Check user's access limits
        if (!Helpers::hasAccess(auth()->id(), 'presentationAccess')) {
            return back()->with('error', 'You have reached the limit of your presentation for this period.');
        }

        session()->forget('presentation_data');
        $validatedData = $request->validate([
            'presentation_topic' => 'required|string|max:255',
        ]);

        try {
            // Store the topic in the session to use during the recording and feedback

            $presentation_data = [
                'presentation_topic' => $validatedData['presentation_topic'],
            ];
            $request->session()->put('presentation_data', $presentation_data);


            // Redirect to recording page
            return view('front.presentation.record', ['topic' => $validatedData['presentation_topic']]);
        } catch (Exception $e) {
            Helpers::logError($e);
            return back()->with('error', 'An error occurred while setting up the recording.');
        }
    }


    public function uploadAudio(Request $request)
    {
        try {
            // Validate required input
            if (!$request->hasFile('audio')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No audio file received',
                ]);
            }

            $audioFile = $request->file('audio');
            $speechTranscription = $request->input('transcription', '');

            // Generate a unique filename and store the audio
            $filename = "presentation_" . time() . ".webm";
            $filePath = MockInterview::upload('presentations/recordings', $filename, file_get_contents($audioFile));

            if (env('APP_ENV') === 'local') {
                $transcription = $speechTranscription;
            } else {
                // Attempt transcription using Whisper or another service if available
                $transcriptionResultjson = MockInterview::transcribeAudioWithWhisper($filePath);
                $transcriptionResult = json_decode($transcriptionResultjson, true);
                $transcription = $transcriptionResult['text'] ?? $speechTranscription;
            }

            // Retrieve existing session data
            $presentation_data = $request->session()->get('presentation_data', []);

            // Update session data with new details
            $presentation_data['audio_filename'] = $filename;
            $presentation_data['transcription'] = $transcription;

            // Save updated data back to the session
            $request->session()->put('presentation_data', $presentation_data);

            return response()->json([
                'success' => true,
                'message' => 'Audio file uploaded and processed successfully',
                'transcription' => $transcription,
                'audio_filename' => $filename
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


    // Step 3: Provide feedback
    public function provideFeedback(Request $request)
    {
        try {
            // Retrieve presentation data from the session
            $presentationData = $request->session()->get('presentation_data', []);

            // Check if the necessary presentation data is available
            if (empty($presentationData) || empty($presentationData['presentation_topic']) || empty($presentationData['transcription'])) {
                $error = 'Incomplete presentation data found. Please ensure both topic and transcription are provided.';
                return view('front.presentation.feedback', compact('error'));
            }



            // If data is complete, process it to generate feedback
            $feedback = MockInterview::processPresentationData($presentationData);

                        // Record user activity
            Helpers::recordActivity(auth()->id(), 'presentationAccess');

            return view('front.presentation.feedback', compact('feedback', 'presentationData'));
        } catch (\Exception $e) {
            Helpers::logError($e);
            $error = 'An error occurred while processing the presentation.';
            return view('front.presentation.feedback', compact('error'));
        }
    }


}
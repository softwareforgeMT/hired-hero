@extends('front.layouts.master-without-nav-footer')
@section('title') Home @endsection
@section('css')
@section('css')
<style>
    /* Avatar video */
    #avatar-video {
        border-radius: 10px;
        width: 100%;
        height: auto;
    }

    /* Navbar logo sizing (THIS FIXES THE ISSUE) */
    #navbar .navbar-logo {
        height: 42px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
    }
</style>
@endsection

@section('content')

   <nav class="navbar navbar-expand-lg navbar-landing fixed-top" style="background-color: #000000;" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('front.index') }}">
            <img src="{{ asset('assets/images/landing/hiredhero_brain.png') }}"
                class="navbar-logo"
                alt="HiredHeroAI Logo">
            </a>
        </div>
    </nav>

    <!-- end navbar -->
    <div class="page__content">
        <section class="section" style="padding: 85px 0px;">
            <div class="container">
                <h1 class="text-center">AI-Powered Interview</h1>
                
                <div class="row justify-content-center">
                    <div class="col-md-7 text-center"> <!-- Added 'text-center' class here -->
                        <video id="avatar-video" muted loop playsinline>
                            <source src="{{asset('mock/video/video4.mp4')}} " type="video/mp4">
                        </video> 

                        <div id="audioContainer">
                        </div>
                        <div id="live-transcription" style="display:none" class="text-center text-white"></div>
                        <div class="" style="min-height: 100px;">
                            <p class="text-center interview_start" >Click 'Ready' when you are prepared to start the interview.</p>
                            <button class="btn btn-success w-100 mt-2" id="startInterview" type="button">Ready</button>
                            <button class="btn btn-success w-100 mt-2" id="processingInterview" type="button" style="display:none" disabled>Processing ...</button>
                            <div id="answerDone" class="w-100" style="display:none;">
                                <button class="btn btn-primary w-100 mt-2" id="" >
                                    <span id="timer"></span> Answer Done
                                </button>
                                <p class="text-center interview_info mt-2"> If you are done with your answer, just press the button below.</p>
                            </div>
                        </div>
                        

                    </div> 
                </div>       
            </div>
        </section>
    </div>
@endsection

@section('script')

<script>
    var interviewData = @json($interviewData);
    var questions = interviewData.questions;
    var audioUrls = questions.map(question => question.audio_file);
    var currentQuestion = 0;
    var mediaRecorder;
    var recordedBlobs = [];
    var recordingTimer;
    var countdownTimer;
    var maxRecordingTime = 120; // Maximum recording time in seconds
    var speechRecognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    speechRecognition.continuous = true;
    speechRecognition.interimResults = true;
    speechRecognition.lang = 'en-US'; // Set to your desired language
    var speechTranscript = ''; // To hold the transcription

    function loadQuestions() {
        questions.forEach(function(question, index) {
            var audio = new Audio(audioUrls[index]);
            audio.id = 'questionAudio' + index;
            audio.onended = handleAudioEnd;
            document.getElementById('audioContainer').appendChild(audio);
        });
    }

    function handleAudioEnd() {
        document.getElementById('avatar-video').pause();
        document.getElementById('avatar-video').currentTime = 0;
        
        startRecording();
    }


    // document.getElementById('startInterview').addEventListener('click', function() {
    //     document.getElementById('avatar-video').play();
    //     document.getElementById('questionAudio' + currentQuestion).play();


    //     this.disabled = true; // Disable the button to prevent multiple clicks
    //     this.style.display = 'none'; // Hide the button           
    //     $('.interview_start').hide();
        
    // });

    document.getElementById('startInterview').addEventListener('click', function() {
        var videoElement = document.getElementById('avatar-video');
        var audioElement = document.getElementById('questionAudio' + currentQuestion);

        videoElement.muted = true;
        videoElement.playsInline = true;

        this.style.display = 'none'; // Hide the button
        $('.interview_start').hide();

        // Reload audio element to ensure it plays on iOS
        audioElement.load();
        audioElement.play().then(() => {
            videoElement.play().then(() => {
                this.disabled = true; // Disable the button to prevent multiple clicks
            }).catch(videoError => {
                console.error('Video play error:', videoError);
            });
        }).catch(audioError => {
            console.error('Audio play error:', audioError);
            console.error('Audio error details:', audioError);
        });
    });

    function startRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {

                mediaRecorder = new MediaRecorder(stream);
                recordedBlobs = [];
                speechTranscript = ''; // Reset transcript for current question

                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        recordedBlobs.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    sendRecording(currentQuestion, speechTranscript);
                };

                mediaRecorder.start();
                startCountdown();
                startTranscription();
            }).catch(error => {
                console.error('Error accessing media devices.', error);
            });
    }

    function startTranscription() {
        speechRecognition.start();
        speechRecognition.onresult = function(event) {
            const transcript = Array.from(event.results)
                .map(result => result[0])
                .map(result => result.transcript)
                .join('');
            document.getElementById('live-transcription').textContent = transcript;
            speechTranscript = transcript; // Accumulate the transcription
        };
    }

    function stopRecording() {
        mediaRecorder.stop();
        speechRecognition.stop();
        clearInterval(countdownTimer);
    }

    function sendRecording(questionIndex, transcription) {
        const audioBlob = new Blob(recordedBlobs, { type: 'audio/webm' });
        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('questionIndex', questionIndex - 1);
        formData.append('questionText', questions[questionIndex - 1].question);
        formData.append('transcription', transcription); // Append current question's transcription
        if (questionIndex === questions.length) {
            $('#processingInterview').show();
        }
        $.ajax({
            url: '{{ route("mock.upload.audio") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                console.log('Upload successful', data);
                if (questionIndex === questions.length) {
                    endInterview();

                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error while saving the recording:', error);
                console.error('Error saving the recording:', error);
            }
        });
    }

    document.getElementById('answerDone').addEventListener('click', function() {
        

        // Check if the speechTranscript is empty
        if (!speechTranscript.trim()) {
            alert('Please provide a response before proceeding to the next question.');
            speechRecognition.abort();
            setTimeout(startTranscription, 400);
            // Optionally, you can reset or restart transcription
            // startTranscription();
            // this.style.display = 'block'; // Re-display the button if needed
             return;
        }
        stopRecording();

        this.style.display = 'none';
        currentQuestion++;

        if (currentQuestion < questions.length) {
            var videoElement = document.getElementById('avatar-video');
            var audioElement = document.getElementById('questionAudio' + currentQuestion);
            // Reload audio element to ensure it plays on iOS
            audioElement.load();
            audioElement.play().then(() => {
                videoElement.play().then(() => {
                    this.disabled = true; // Disable the button to prevent multiple clicks
                }).catch(videoError => {
                    console.error('Video play error:', videoError);
                });
            }).catch(audioError => {
                console.error('Audio play error:', audioError);
                console.error('Audio error details:', audioError);
            });
        }
        // if (currentQuestion < questions.length) {
        //     document.getElementById('questionAudio' + currentQuestion).play();
        //     document.getElementById('avatar-video').play();
            
        // }
    });

    function endInterview() {
        $('#processingInterview').hide();
        console.log('Interview completed.');
        const resultsRoute = '{{ route("mock.result.index") }}';
        window.location.href = resultsRoute;
    }

    function startCountdown() {
        let timeLeft = maxRecordingTime;
        document.getElementById('timer').textContent = timeLeft + 's remaining'; // Immediately set the timer text
        document.getElementById('answerDone').style.display = 'inline-block'; // Immediately display the button

        countdownTimer = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                

                if (!speechTranscript.trim()) {
                    alert('No response detected. Restarting the interview.');
                    restartInterview();
                    return
                } else {
                    stopRecording();
                    document.getElementById('answerDone').click();
                }


            } else {
                document.getElementById('timer').textContent = timeLeft + 's remaining';
            }
        }, 1000);
    }
    function restartInterview() {
        location.reload();
    }
    $(document).ready(function() {
        loadQuestions();
    });
</script>



@endsection

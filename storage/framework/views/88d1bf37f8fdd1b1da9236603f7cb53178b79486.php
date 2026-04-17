<?php $__env->startSection('title'); ?> Record Your Presentation <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<style>
    #presentation-video {
        border-radius: 10px;
        width: 100%;
        height: auto; 
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <div class="d-inline-flex">
            <a class="navbar-brand" href="<?php echo e(route('front.index')); ?>">
                <img src="<?php echo Helpers::image($gs->logo_dark, 'logo/'); ?>" class="card-logo card-logo-dark" alt="logo dark" height="17">
                <img src="<?php echo Helpers::image($gs->logo_light, 'logo/'); ?>" class="card-logo card-logo-light" alt="logo light" height="17">
            </a>
        </div>
    </div>
</nav>
<!-- end navbar -->
<div class="page__content">
    <section class="section" style="padding: 85px 0px;">
        <div class="container">
            <h1 class="text-center mb-2 top_heading">Are you ready?</h1>
            <div class="row justify-content-center">
                <div class="col-md-7 text-center">
                    <video id="presentation-video" autoplay muted loop>
                        <source src="<?php echo e(asset('presentations/intro.mp4')); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <img id="judges-image" src="<?php echo e(asset('presentations/judges.png')); ?>" style="display:none; width: 100%; border-radius: 10px;">

                    <div id="audioContainer"></div>
                    <div id="live-transcription" class="text-center text-white" style="display: none;"></div>
                    <div class="" style="min-height: 100px;">
                        <p class="text-center" id="startPrompt">Click 'Start' when you are ready to begin your presentation.</p>
                        <button class="btn btn-success w-100 mt-2" id="startRecording" type="button">Start</button>
                        <div id="recordingDone" class="w-100" style="display:none;">
                            <button class="btn btn-primary w-100 mt-2" id="stopRecording">
                                <span id="timer"></span> Stop Recording
                            </button>
                            <p class="text-center mt-2">When you are done, press the button to stop recording.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    var mediaRecorder;
    var recordedBlobs = [];
    var recordingTimer;
    var countdownTimer;
    var maxRecordingTime = 300; // Maximum recording time in seconds
    var speechRecognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    speechRecognition.continuous = true;
    speechRecognition.interimResults = true;
    speechRecognition.lang = 'en-US'; // Set to your desired language
    var speechTranscript = ''; // To hold the transcription

    document.getElementById('startRecording').addEventListener('click', function() {
        
        $('.top_heading').text('Recording your presenation...');
        document.getElementById('presentation-video').style.display = 'none';  // Hide the video if still showing
        document.getElementById('judges-image').style.display = 'block';

        this.disabled = true; // Disable the button to prevent multiple clicks
        document.getElementById('recordingDone').style.display = 'block'; // Show the stop recording button
        startRecording();
    });

    document.getElementById('stopRecording').addEventListener('click', function() {
        stopRecording();
    });

    function startRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                recordedBlobs = [];
                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        recordedBlobs.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    sendRecording();
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
            speechTranscript= transcript; // Accumulate the transcription
        };
    }

    function stopRecording() {
        $('.top_heading').text('Processing your presenation...')
         $('#stopRecording').text('Processing...').prop('disabled', true);
        mediaRecorder.stop();
        speechRecognition.stop();
        clearInterval(countdownTimer);
    }

    function sendRecording() {
        const audioBlob = new Blob(recordedBlobs, { type: 'audio/webm' });
        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('transcription', speechTranscript); // Append transcription

        $.ajax({
            url: '<?php echo e(route("presentation.upload.audio")); ?>', // Ensure this route is defined in your web.php file
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                console.log('Upload successful', data);
                endPresentation();
                // Redirect or inform user of successful upload
            },
            error: function(xhr, status, error) {
                toastr.error('Error saving the recording:', error);
                console.error('Error saving the recording:', error);
            }
        });
    }

    function endPresentation() {
        console.log('presentation completed.');
        const resultsRoute = '<?php echo e(route("presentation.feedback")); ?>';
        window.location.href = resultsRoute;
    }

    // function startCountdown() {
    //     let timeLeft = maxRecordingTime;
    //     document.getElementById('timer').textContent = timeLeft + 's remaining'; // Set the timer text
    //     countdownTimer = setInterval(() => {
    //         timeLeft--;
    //         if (timeLeft <= 0) {
    //             clearInterval(countdownTimer);
    //             document.getElementById('stopRecording').click();
    //         } else {
    //             document.getElementById('timer').textContent = timeLeft + 's remaining';
    //         }
    //     }, 1000);
    // }

     function startCountdown() {
        let timeLeft = maxRecordingTime;
        updateTimerDisplay(timeLeft);
        countdownTimer = setInterval(() => {
            timeLeft--;
            updateTimerDisplay(timeLeft);
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                document.getElementById('stopRecording').click();
            }
        }, 1000);
    }

    function updateTimerDisplay(seconds) {
        let minutes = Math.floor(seconds / 60);
        let remainingSeconds = seconds % 60;
        document.getElementById('timer').textContent = `${minutes}m ${remainingSeconds}s remaining`;
    }
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.master-without-nav-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/front/presentation/record.blade.php ENDPATH**/ ?>
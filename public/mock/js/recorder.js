    <script>
      var questions = <?php echo json_encode($questions); ?>;
      var audioUrls = <?php echo json_encode($audioUrls); ?>;
      var currentQuestion = 0;
      var mediaRecorder;
      var recordedBlobs = [];

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
          document.getElementById('avatar-video').currentTime=0;
          document.getElementById('answerDone').style.display = 'inline-block';
          startRecording();
      }

      document.getElementById('startInterview').addEventListener('click', function() {
          document.getElementById('avatar-video').play();
          document.getElementById('questionAudio' + currentQuestion).play();
          this.disabled = true;
      });


      function startRecording() {
          navigator.mediaDevices.getUserMedia({ audio: true })
              .then(stream => {
                  mediaRecorder = new MediaRecorder(stream);
                  recordedBlobs = [];  // Ensure the array is cleared at the start of a new recording

                  mediaRecorder.ondataavailable = event => {
                      if (event.data.size > 0) {
                          recordedBlobs.push(event.data);
                      }
                  };

                  mediaRecorder.onstop = sendRecording;  // Call sendRecording on stop

                  mediaRecorder.start();
                  recordingStartTime = new Date();
                  recordingTimer = setInterval(updateRecordingTimer, 1000);
              }).catch(error => {
                  console.error('Error accessing media devices.', error);
              });
      }

      function stopRecording() {
          mediaRecorder.stop();  // This will trigger the onstop event
          clearInterval(recordingTimer);
      }

      function sendRecording() {
          const audioBlob = new Blob(recordedBlobs, { type: 'audio/webm' });
          const formData = new FormData();
          formData.append('audio', audioBlob);
          formData.append('questionIndex', currentQuestion-1);
          formData.append('questionText', questions[currentQuestion-1]);

          // console.log( questions[currentQuestion],currentQuestion);
          // Logging FormData contents
          // for (let [key, value] of formData.entries()) {
          //     console.log(key, value);
          // }

          fetch('upload_audio_backend.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              console.log('Upload successful', data);
          })
          .catch(error => {
              console.error('Error saving the recording:', error);
          });
      }



      document.getElementById('answerDone').addEventListener('click', function() {
          stopRecording();
          this.style.display = 'none';
          currentQuestion++;
          if (currentQuestion < questions.length) {
              document.getElementById('avatar-video').play();
              document.getElementById('questionAudio' + currentQuestion).play();
          } else {
              endInterview();
          }
      });

      function updateRecordingTimer() {
          var elapsed = new Date() - recordingStartTime;
          var seconds = Math.floor(elapsed / 1000);
          document.getElementById('timer').textContent = seconds + 's';
      }

      
      function endInterview() {
          console.log('Interview completed.');
          // Delay redirect by 10 seconds (10000 milliseconds)
          setTimeout(function() {
              window.location.href = 'result.php';
          }, 6000);
      }


      $(document).ready(function() {
          loadQuestions();
      });
    </script>

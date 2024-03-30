<?php

include('navbar.html');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Facial Emotion Detection</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@2.4.0/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/face-landmarks-detection@0.0.1/dist/face-landmarks-detection.js"></script>
    <!-- Include Spotify Web Playback SDK script -->
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
</head>
<body>
    <canvas id="output"></canvas>
    <video id="webcam" playsinline style="visibility: hidden;"></video>
    <h1 id="status">Loading...</h1>

    <!-- Add a button to play the playlist -->
    <button id="playPlaylistBtn" onclick="submit()" style="display: none;">Play Playlist</button>

    <script>

function submit() {
    let str = document.getElementById("status").innerText;
    let result = str.split(': ')[1];


    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the destination URL where you want to send the data
    var url = '../index.php'; // Replace 'process_data.php' with the URL of your target page

    // Define the data to be sent (in this case, a single value)
    var data = 'pllst=' + encodeURIComponent(result); // Encode the value to ensure proper transmission

    // Set up the request
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define a function to handle the response from the server
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request was successful
                console.log('Data sent successfully.');                
                window.location.href = '../index.php?pllst2='+result;
            } else {
                // Error handling
                console.error('Error:', xhr.status);
            }
        }
    };

    // Send the data
    xhr.send(data);

    






}

        function setText(text) {
            document.getElementById("status").innerText = text;
        }

        async function setupWebcam() {
            return new Promise((resolve, reject) => {
                const webcamElement = document.getElementById("webcam");
                const navigatorAny = navigator;
                navigator.getUserMedia = navigator.getUserMedia ||
                    navigatorAny.webkitGetUserMedia || navigatorAny.mozGetUserMedia ||
                    navigatorAny.msGetUserMedia;
                if (navigator.getUserMedia) {
                    navigator.getUserMedia({ video: true },
                        stream => {
                            webcamElement.srcObject = stream;
                            webcamElement.addEventListener("loadeddata", resolve, false);
                        },
                        error => reject());
                } else {
                    reject();
                }
            });
        }

        const emotions = ["angry","fear", "sad", "happy", "Neutral"];
        let emotionModel = null;

        let output = null;
        let model = null;

        async function predictEmotion(points) {
            let result = tf.tidy(() => {
                const xs = tf.stack([tf.tensor1d(points)]);
                return emotionModel.predict(xs);
            });
            let prediction = await result.data();
            result.dispose();
            // Get the index of the maximum value
            let id = prediction.indexOf(Math.max(...prediction));
            return emotions[id];
        }

        async function trackFace() {
            const video = document.querySelector("video");
            const faces = await model.estimateFaces({
                input: video,
                returnTensors: false,
                flipHorizontal: false,
            });
            output.drawImage(
                video,
                0, 0, video.width, video.height,
                0, 0, video.width, video.height
            );

            let points = null;
            faces.forEach(face => {
                // Draw the bounding box
                const x1 = face.boundingBox.topLeft[0];
                const y1 = face.boundingBox.topLeft[1];
                const x2 = face.boundingBox.bottomRight[0];
                const y2 = face.boundingBox.bottomRight[1];
                const bWidth = x2 - x1;
                const bHeight = y2 - y1;

                // Add just the nose, cheeks, eyes, eyebrows & mouth
                const features = [
                    "noseTip",
                    "leftCheek",
                    "rightCheek",
                    "leftEyeLower1", "leftEyeUpper1",
                    "rightEyeLower1", "rightEyeUpper1",
                    "leftEyebrowLower",
                    "rightEyebrowLower",
                    "lipsLowerInner",
                    "lipsUpperInner",
                ];
                points = [];
                features.forEach(feature => {
                    face.annotations[feature].forEach(x => {
                        points.push((x[0] - x1) / bWidth);
                        points.push((x[1] - y1) / bHeight);
                    });
                });
            });

            if (points) {
                let emotion = await predictEmotion(points);
                setText(`Detected: ${emotion}`);
                // Show play button
                document.getElementById('playPlaylistBtn').style.display = 'block';
                // Add event listener to play button
                document.getElementById('playPlaylistBtn').addEventListener('click', function() {
                    playPlaylist(emotion);
                });
            } else {
                setText("No Face");
            }

            requestAnimationFrame(trackFace);
        }

        async function playPlaylist(emotion) {
            // Your Spotify playlist selection logic here
            // Replace the following example playlist URIs with your own
            const playlists = {
                angry: 'spotify:playlist:37i9dQZF1DX4sWSpwq3LiO',
                happy: 'spotify:playlist:37i9dQZF1DXdPec7aLTmlC',
                sad: 'spotify:playlist:37i9dQZF1DX7qK8ma5wgG1',
                // Add more emotions and their corresponding playlists as needed
            };

            const playlistURI = playlists[emotion];
            if (!playlistURI) {
                console.error(`Playlist for ${emotion} not found`);
                return;
            }

            // Initialize Spotify player
            const player = new Spotify.Player({
                name: 'Web Playback SDK Quick Start Player',
                getOAuthToken: cb => {
                    // Pass access token here
                    // You need to implement this function to retrieve an access token
                    // using the authentication method you choose (e.g., OAuth 2.0)
                    const accessToken = 'YOUR_ACCESS_TOKEN';
                    cb(accessToken);
                }
            });

            // Error handling
            player.addListener('initialization_error', ({ message }) => { console.error(message); });
            player.addListener('authentication_error', ({ message }) => { console.error(message); });
            player.addListener('account_error', ({ message }) => { console.error(message); });
            player.addListener('playback_error', ({ message }) => { console.error(message); });

            // Playback status updates
            player.addListener('player_state_changed', state => { console.log(state); });

            // Ready
            player.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID', device_id);
                // Play the playlist
                fetch(`https://api.spotify.com/v1/me/player/play?device_id=${device_id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ uris: [playlistURI] }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}`
                    },
                });
            });

            // Connect to the player
            player.connect();
        }

        (async () => {
            await setupWebcam();
            const video = document.getElementById("webcam");
            video.play();
            let videoWidth = video.videoWidth;
            let videoHeight = video.videoHeight;
            video.width = videoWidth;
            video.height = videoHeight;

            let canvas = document.getElementById("output");
            canvas.width = video.width;
            canvas.height = video.height;

            output = canvas.getContext("2d");
            output.translate(canvas.width, 0);
            output.scale(-1, 1); // Mirror cam
            output.fillStyle = "#fdffb6";
            output.strokeStyle = "#fdffb6";
            output.lineWidth = 2;

            // Load Face Landmarks Detection
            model = await faceLandmarksDetection.load(
                faceLandmarksDetection.SupportedPackages.mediapipeFacemesh
            );
            // Load Emotion Detection
            emotionModel = await tf.loadLayersModel('web/model/facemo.json');

            setText("Loaded!");

            trackFace();
        })();
    </script>
</body>
</html>

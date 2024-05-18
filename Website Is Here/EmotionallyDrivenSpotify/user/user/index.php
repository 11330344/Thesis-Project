<?php
include('../config.php');
extract($_POST);
extract($_GET);
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
}
$username = $_SESSION['username'];
include('navbar.html');
$j = 0;

$client_id = '5afed67045fd492ab0e1696f0a5d8846';
$client_secret = 'b36a4180a4ad443bbc0584b124d838c4';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php';

if (!isset($_SESSION['access_token'])) {
    header('Location: ../index.php');
}
if (isset($_SESSION['access_token'])) {
    $access_token = $_SESSION['access_token'];
    $profile_url = 'https://api.spotify.com/v1/me';

    $ch_profile = curl_init($profile_url);
    curl_setopt($ch_profile, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
    curl_setopt($ch_profile, CURLOPT_RETURNTRANSFER, true);

    $profile_response = curl_exec($ch_profile);
    curl_close($ch_profile);

    $profile_data = json_decode($profile_response, true);
    $user_id = isset($profile_data['id']) ? $profile_data['id'] : '';

    $client_name = $_SESSION['name'];

    echo '<center><h1>Welcome, ' . $client_name . '!</h1></center><div id="left">';
    echo '<form action="index.php" method="post"> Search : <input placeholder="search for playlist" name="pllst" type="text"></form>';
} elseif (isset($_GET['code'])) {
    $code = $_GET['code'];

    $token_url = 'https://accounts.spotify.com/api/token';
    $data = array(
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    );

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $token_info = json_decode($response, true);

    if (isset($token_info['access_token'])) {
        $access_token = $token_info['access_token'];

        $_SESSION['access_token'] = $access_token;
    } else {
        echo 'Access token not found.';
    }
} else {
    echo 'Authorization code not found.';
}

if (!isset($_POST['pllst']) || $_POST['pllst'] == '') {
    $search_query = 'emotion';
} else {
    $search_query = $_POST['pllst'];
}
if (isset($_GET['pllst2'])) {
    $search_query = $_GET['pllst2'];
    $emotionPlaylists = [];
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = '" . $search_query . ($i + 1) . "'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if ($row['playlist_id'] != '') {
            $emotionPlaylists["emotion" . ($j + 1)] = $row['playlist_id'];
            $j++;
        }
    }
}

$type = 'playlist';

$search_url = "https://api.spotify.com/v1/search?q=$search_query&type=$type";

$ch = curl_init($search_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

curl_close($ch);

$search_result = json_decode($response, true);

if ($j > 0) {
    $playlistId = $emotionPlaylists['emotion1'];
    echo '<h1>Playlists related to ' . $search_query . ':</h1>';
    foreach ($emotionPlaylists as $plst) {
        $playlist_url = "https://api.spotify.com/v1/playlists/$plst";

        $ch = curl_init($playlist_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

        $playlist_info = json_decode($response, true);

        if (isset($playlist_info['id'])) {
            $plstname = $playlist_info['name'] == '' ? 'No Name' : $playlist_info['name'];
            echo '<ul id="playlists">';
            $playlist_id = $plst;
            echo '<li onclick="getplaylist(`' . $playlist_url . '`)"><a>' . $plstname . '</a>';
            echo '<div class="button-container">';
            echo ' <button onclick="sharePlaylist(`' . $playlist_url . '`)">Share</button>';
            echo ' <button onclick="savePlaylist(`' . $playlist_url . '`)">Save</button>';
            echo '</div></li>';
            echo '</ul>';
        } else {
            echo 'Playlist not found or access denied.';
        }
    }
} elseif (isset($search_result['playlists']['items'])) {
    $playlists = $search_result['playlists']['items'];

    echo '<h1>Playlists related to ' . $search_query . ':</h1>';
    echo '<ul id="playlists">';
    foreach ($playlists as $playlist) {
        $playlistUrl = $playlist['external_urls']['spotify'];
        echo '<li onclick="getplaylist(`' . $playlistUrl . '`)"><a>' . $playlist['name'] . '</a>';
        echo '<div class="button-container">';
        echo ' <button onclick="sharePlaylist(`' . $playlistUrl . '`)">Share</button>';
        echo ' <button onclick="savePlaylist(`' . $playlistUrl . '`)">Save</button>';
        preg_match('/playlist\/(\w+)/', $playlistUrl, $matches);
        $playlistId = isset($matches[1]) ? $matches[1] : null;
        echo '</div></li>';
    }
    echo '</ul>';
} else {
    echo 'No playlists found for emotions.';
}

echo '</div>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emotionally Driven Spotify</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #left {
            padding: 20px;
        }

        #right {
            position: fixed;
            top: 0;
            right: 0;
            width: 600px;
            height: 100%;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
        }

        ul#playlists {
            list-style: none;
            padding: 0;
        }

        ul#playlists li {
            background-color: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        ul#playlists li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            flex-grow: 1;
        }

        ul#playlists li .button-container {
            display: flex;
            gap: 10px;
        }

        ul#playlists li button {
            background-color: #4267B2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        ul#playlists li button:hover {
            background-color: #365899;
        }

        h1 {
            color: #333;
        }

        form input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form {
            margin-bottom: 20px;
        }

        /* Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="right">
        <iframe id='spotifyPlayer' src="https://open.spotify.com/embed/playlist/<?php echo $playlistId; ?>?autoplay=true" width="600" height="580" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
    </div>

    <!-- Modal Structure -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Save Playlist</h2>
            <input type="text" hidden id="playlistUrlInput" value="" readonly>
            Select Emotion :
            <select id="emotion">
                <option value="happy">Happy</option>
                <option value="Sad">Sad</option>
                <option value="angry">Angry</option>
                <option value="neutral">Neutral</option>
                <option value="fear">Fear</option>
            </select>
            <Button onclick="saveemotion()">Save</Button>
        </div>
    </div>

    <script>
        
        
        function saveemotion(){            
            var playlistid = document.getElementById('playlistUrlInput').value;
            var emotion = document.getElementById('emotion').value; 
            
            var xhttp = new XMLHttpRequest();

            // Define a callback function to handle the response
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.response);
                }
            };
            var url = "updateplaylist2.php?playlistid=" + playlistid + "&emotion=" + emotion;

            xhttp.open("GET", url, true);
            xhttp.send();     
                  
        }

        function getplaylist(str) {
            const playlistUrl = str;
            const parts = playlistUrl.split('/');
            const playlistId = parts[parts.length - 1];

            const newSrc = 'https://open.spotify.com/embed/playlist/' + playlistId;
            const spotifyPlayer = document.getElementById('spotifyPlayer');
            spotifyPlayer.src = newSrc;
        }

        function sharePlaylist(playlistUrl) {
            const fbShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(playlistUrl)}`;
            window.open(fbShareUrl, '_blank', 'width=600,height=400');
        }

        function savePlaylist(playlistUrl) {
            const parts = playlistUrl.split('/');
            const playlistId = parts[parts.length - 1];
            document.getElementById('playlistUrlInput').value = playlistId;
            document.getElementById('myModal').style.display = "block";
        }

        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
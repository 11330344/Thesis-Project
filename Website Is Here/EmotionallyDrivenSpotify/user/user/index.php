<?php
include('../config.php');
extract($_POST);
extract($_GET);
if(!isset($_SESSION['username'])){
header("Location: ../index.php");
}
$username=$_SESSION['username'];
include('navbar.html');
$j=0;
/*// Define your Spotify application credentials
$client_id = 'afdca4875a4c45d0b8e504d6fbc9cb4a';
$client_secret = 'b9bfe0a0ae6b47deb6ff15f1423a68d5';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php'; // Update with your redirect URI*/

$client_id = '5afed67045fd492ab0e1696f0a5d8846';
$client_secret = 'b36a4180a4ad443bbc0584b124d838c4';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php'; // Update with your redirect URI

if(!isset($_SESSION['access_token'])){
    header('Location: ../index.php');
}
// Check if the access token is already stored in the session
if (isset($_SESSION['access_token'])) {
    //$access_token = $_SESSION['access_token'];
        $access_token = $_SESSION['access_token'];
        // Fetch user's profile information
        $profile_url = 'https://api.spotify.com/v1/me';

        $ch_profile = curl_init($profile_url);
        curl_setopt($ch_profile, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
        curl_setopt($ch_profile, CURLOPT_RETURNTRANSFER, true);
    
        $profile_response = curl_exec($ch_profile);
        curl_close($ch_profile);
    
        $profile_data = json_decode($profile_response, true);
        $user_id = isset($profile_data['id']) ? $profile_data['id'] : '';

        // Extract client's name (display name)
        $client_name = $_SESSION['name'];

    echo '<center><h1>Welcome, ' . $client_name . '!</h1></center><div id="left">';
    echo '<form action="index.php" method="post"> Search : <input placeholder="search for playlist" name="pllst" type="text"></form>' ; // Display the client's name on the page
    
} elseif (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange authorization code for access token using cURL
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

    // Access token and other information
    if (isset($token_info['access_token'])) {
        $access_token = $token_info['access_token'];

        // Store the access token in the session for future use
        $_SESSION['access_token'] = $access_token;
    } else {
        echo 'Access token not found.';
    }
} else {
    echo 'Authorization code not found.';
}
// Step 2: Search for playlists with happy emotions

if(!isset($_POST['pllst']) || $_POST['pllst']==''){
$search_query = 'emotion'; 
}else{
$search_query = $_POST['pllst']; 
}
if(isset($_GET['pllst2'])){
    $search_query = $_GET['pllst2']; 
    $emotionPlaylists=[];
// Display the playlist(s) related to happy emotions
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = '".$search_query.($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row['playlist_id']!=''){
            $emotionPlaylists["emotion" . ($j+1)] = $row['playlist_id'];
            $j++;
        }
    } 
    }

$type = 'playlist'; // Search type (playlist, track, etc.)

$search_url = "https://api.spotify.com/v1/search?q=$search_query&type=$type";

$ch = curl_init($search_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

curl_close($ch);

$search_result = json_decode($response, true);


    if($j>0){
        $playlistId = $emotionPlaylists['emotion1'];
        echo '<h1>Playlists related to '.$search_query.':</h1>';
        foreach($emotionPlaylists as $plst){
        // Construct the URL to fetch the playlist details
        $playlist_url = "https://api.spotify.com/v1/playlists/$plst";

        $ch = curl_init($playlist_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

        $playlist_info = json_decode($response, true);

        if (isset($playlist_info['id'])) {
            if($playlist_info['name']==''){
            $plstname='No Name';
            }else{
            $plstname=$playlist_info['name'];
            }
            echo '<ul id="playlists">';
                $playlist_id = $plst;
                echo '<li onclick="getplaylist(`' . $playlist_url. '`)"><a>' .$plstname. '</a>';
                echo ' <button onclick="sharePlaylist(`' . $playlist_url. '`)">Share</button></li>';
            
         /*   echo '<li>Playlist Name: ' . $playlist_info['name'] . '</li>';
            echo '<li>Owner: ' . $playlist_info['owner']['display_name'] . '</li>';
            echo '<li>Total Tracks: ' . $playlist_info['tracks']['total'] . '</li>';*/
            // Add more details as needed
            echo '</ul>';
        } else {
            echo 'Playlist not found or access denied.';
        }


    }
}elseif(isset($search_result['playlists']['items'])) {
        $playlists = $search_result['playlists']['items'];

        echo '<h1>Playlists related to '.$search_query.':</h1>';
        echo '<ul id="playlists">';
        foreach ($playlists as $playlist) {
            $playlistUrl = $playlist['external_urls']['spotify'];
            echo '<li onclick="getplaylist(`' . $playlistUrl. '`)"><a>' . $playlist['name']. '</a>';
            echo ' <button onclick="sharePlaylist(`' . $playlistUrl. '`)">Share</button></li>';
            preg_match('/playlist\/(\w+)/', $playlistUrl, $matches);
            $playlistId = isset($matches[1]) ? $matches[1] : null;
        }
        echo '</ul>';
    } else {
        echo 'No playlists found for  emotions.';
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
    </style>
</head>
<body>
    <div id="right">
        <iframe id='spotifyPlayer' src="https://open.spotify.com/embed/playlist/<?php echo $playlistId;?>?autoplay=true" width="600" height="580" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
    </div>
</body>
</html>

<script>
function getplaylist(str){
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
</script>

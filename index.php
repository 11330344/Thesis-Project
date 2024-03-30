<?php
session_start();
include('navbar.html');
// Define your Spotify application credentials
$client_id = 'afdca4875a4c45d0b8e504d6fbc9cb4a';
$client_secret = 'b9bfe0a0ae6b47deb6ff15f1423a68d5';
$redirect_uri = 'http://localhost/Emotionally Driven Spotify/user/index.php'; // Update with your redirect URI
if(!isset($_SESSION['access_token'])){
    header('Location: ../index.php');
}
// Check if the access token is already stored in the session
if (isset($_SESSION['access_token'])) {
     $access_token = $_SESSION['access_token'];

        // Fetch user's profile information
        $profile_url = 'https://api.spotify.com/v1/me';

        $ch_profile = curl_init($profile_url);
        curl_setopt($ch_profile, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
        curl_setopt($ch_profile, CURLOPT_RETURNTRANSFER, true);
    
        $profile_response = curl_exec($ch_profile);
        curl_close($ch_profile);
    
        $profile_data = json_decode($profile_response, true);
    
        // Extract client's name (display name)
        $client_name = isset($profile_data['display_name']) ? $profile_data['display_name'] : 'User';

            // Display the client's name on the page
    echo '<h1>Welcome, ' . $client_name . '!</h1>';

    
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
$search_query = 'emotion'; // Search query for happy emotions
$type = 'playlist'; // Search type (playlist, track, etc.)

$search_url = "https://api.spotify.com/v1/search?q=$search_query&type=$type";

$ch = curl_init($search_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$search_result = json_decode($response, true);

// Display the playlist(s) related to happy emotions
if (isset($search_result['playlists']['items'])) {
    $playlists = $search_result['playlists']['items'];

    echo '<h1>Playlists related to  Emotions:</h1>';
    echo '<ul id="playlists">';
    foreach ($playlists as $playlist) {
        echo '<li onclick="getplaylist(`' . $playlist['external_urls']['spotify']. '`)"><a>' . $playlist['name']. '</a></li>';

        $playlistUrl = $playlist['external_urls']['spotify'];
        preg_match('/playlist\/(\w+)/', $playlistUrl, $matches);
        $playlistId = isset($matches[1]) ? $matches[1] : null;
    }
    echo '</ul>';
} else {
    echo 'No playlists found for  emotions.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emotionally Driven Spotify</title>
</head>
<body>
    <!-- Embed Spotify player with Playlist URI -->
    <iframe id='spotifyPlayer' src="https://open.spotify.com/embed/playlist/<?php echo $playlistId;?>?autoplay=true" width="600" height="580" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
</body>
</html>

<script>
function getplaylist(str){
    const playlistUrl = str;
const parts = playlistUrl.split('/');
const playlistId = parts[parts.length - 1];

    const newSrc = 'https://open.spotify.com/embed/playlist/'+playlistId;
    const spotifyPlayer = document.getElementById('spotifyPlayer');
    spotifyPlayer.src = newSrc;

}



</script>
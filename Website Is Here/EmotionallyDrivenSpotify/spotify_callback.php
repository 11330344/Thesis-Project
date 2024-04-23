<?php
// Define your Spotify application credentials
$client_id = 'afdca4875a4c45d0b8e504d6fbc9cb4a';
$client_secret = 'b9bfe0a0ae6b47deb6ff15f1423a68d5';
$redirect_uri = 'http://localhost/Emotionally Driven Spotify/user/index.php'; // Update with your redirect URI

// Step 1: Get access token (assuming you have already obtained it)

// Step 3: Handle callback from Spotify
if (isset($_GET['code'])) {
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
        // Store or use $access_token as needed
    } else {
        echo 'Access token not found.';
    }
} else {
    echo 'Authorization code not found.';
}



// Step 2: Search for playlists with happy emotions
$access_token = $token_info['access_token'];

$search_query = 'happy'; // Search query for happy emotions
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

    echo '<h1>Playlists related to Happy Emotions:</h1>';
    echo '<ul>';
    foreach ($playlists as $playlist) {
        echo '<li><a href="' . $playlist['external_urls']['spotify'] . '">' . $playlist['name'] . '</a></li>';
    }
    echo '</ul>';
} else {
    echo 'No playlists found for happy emotions.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Player</title>
</head>
<body>
    <!-- Embed Spotify player with Playlist URI -->
    <iframe src="https://open.spotify.com/embed/playlist/37i9dQZF1DX1H4LbvY4OJi?autoplay=true" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
</body>
</html>

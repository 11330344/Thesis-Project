<?php
/*// Define your Spotify application credentials
$client_id = 'afdca4875a4c45d0b8e504d6fbc9cb4a';
$client_secret = 'b9bfe0a0ae6b47deb6ff15f1423a68d5';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php'; // Update with your redirect URI*/

$client_id = '5afed67045fd492ab0e1696f0a5d8846';
$client_secret = 'b36a4180a4ad443bbc0584b124d838c4';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php'; // Update with your redirect URI

// Step 1: Redirect user to Spotify login page
$scopes = 'user-read-private user-read-email'; // Add required scopes
$auth_url = "https://accounts.spotify.com/authorize?client_id=$client_id&response_type=code&redirect_uri=$redirect_uri&scope=$scopes";
header('Location: ' . $auth_url);
exit;
?>

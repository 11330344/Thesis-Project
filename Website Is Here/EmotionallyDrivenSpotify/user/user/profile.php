<style>

body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: 10px;
  padding: 20px;
}


.grid-item {
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  padding: 20px;
  text-align: center;
}
.grid-item input {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 5px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}



</style>    
<?php
include('../config.php');

extract($_POST);
extract($_GET);
if(!isset($_SESSION['username'])){
header("Location: ../index.php");
}
include('navbar.html');

// Define Spotify credentials and redirect URI
$client_id = '5afed67045fd492ab0e1696f0a5d8846';
$client_secret = 'b36a4180a4ad443bbc0584b124d838c4';
$redirect_uri = 'http://localhost/EmotionallyDrivenSpotify/user/index.php';

// Check if access token is stored in session
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}
$username=$_SESSION['username'];
    $Playlists = [];
    $sadPlaylists = [];
    $neutralPlaylists = [];
    $fearPlaylists = [];
    $angryPlaylists = [];

    // Fetch the first 5 rows
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = 'happy".($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row){
            $Playlists["happy" . ($i + 1)] = $row['playlist_id'];
        }else{
            $Playlists["happy" . ($i + 1)] = '';    
            }
    }
        // Fetch the first 5 rows
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = 'Sad".($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row){
            $Playlists["Sad" . ($i + 1)] = $row['playlist_id'];
        }else{
            $Playlists["Sad" . ($i + 1)] = '';                
            }
    }
        // Fetch the first 5 rows
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = 'angry".($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row){
            $Playlists["angry" . ($i + 1)] = $row['playlist_id'];
        }else{
            $Playlists["angry" . ($i + 1)] = '';                
            }
    }
        // Fetch the first 5 rows
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = 'neutral".($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row){
            $Playlists["neutral" . ($i + 1)] = $row['playlist_id'];
        }else{
            $Playlists["neutral" . ($i + 1)] = '';                
            }
    }
        // Fetch the first 5 rows
    for ($i = 0; $i < 5; $i++) {
        $slct = "SELECT playlist_id FROM user_preferences WHERE username = '$username' AND emotion = 'fear".($i+1)."'";
        $qry = $conn->query($slct);
        $row = $qry->fetch_assoc();
        if($row){
            $Playlists["fear" . ($i + 1)] = $row['playlist_id'];
        }else{
            $Playlists["fear" . ($i + 1)] = '';                
            }
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
    <div class="grid-container">
        
            <div class="grid-item">
            <center>Happy Playlists</center>
            <form method="POST" action="updateplaylist.php">
            <input type="text" name="happy1" placeholder="Playlist 1" value="<?php echo $Playlists['happy1']?>">
            <input type="text" name="happy2" placeholder="Playlist 2" value="<?php echo $Playlists['happy2']?>">
            <input type="text" name="happy3" placeholder="Playlist 3" value="<?php echo $Playlists['happy3']?>">
            <input type="text" name="happy4" placeholder="Playlist 4" value="<?php echo $Playlists['happy4']?>">
            <input type="text" name="happy5" placeholder="Playlist 5" value="<?php echo $Playlists['happy5']?>">
            <input type="submit" name="value" value="Update Happy Playlist">
            </form>
            </div>
            <div class="grid-item">
            <center>Sad Playlists</center>
            <form method="POST" action="updateplaylist.php">
            <input type="text" name="Sad1" placeholder="Playlist 1" value="<?php echo $Playlists['Sad1']?>">
            <input type="text" name="Sad2" placeholder="Playlist 2" value="<?php echo $Playlists['Sad2']?>">
            <input type="text" name="Sad3" placeholder="Playlist 3" value="<?php echo $Playlists['Sad3']?>">
            <input type="text" name="Sad4" placeholder="Playlist 4" value="<?php echo $Playlists['Sad4']?>">
            <input type="text" name="Sad5" placeholder="Playlist 5" value="<?php echo $Playlists['Sad5']?>">
            <input type="submit" name="value" value="Update Sad Playlist">
            </form>
            </div>
            <div class="grid-item">
            <center>Angry Playlists</center>
            <form method="POST" action="updateplaylist.php">
            <input type="text" name="angry1" placeholder="Playlist 1" value="<?php echo $Playlists['angry1']?>">
            <input type="text" name="angry2" placeholder="Playlist 2" value="<?php echo $Playlists['angry2']?>">
            <input type="text" name="angry3" placeholder="Playlist 3" value="<?php echo $Playlists['angry3']?>">
            <input type="text" name="angry4" placeholder="Playlist 4" value="<?php echo $Playlists['angry4']?>">
            <input type="text" name="angry5" placeholder="Playlist 5" value="<?php echo $Playlists['angry5']?>">
            <input type="submit" name="value" value="Update Angry Playlist">
            </form>
            </div>
            <div class="grid-item">
            <center>Neutral Playlists</center>
            <form method="POST" action="updateplaylist.php">
            <input type="text" name="neutral1" placeholder="Playlist 1" value="<?php echo $Playlists['neutral1']?>">
            <input type="text" name="neutral2" placeholder="Playlist 2" value="<?php echo $Playlists['neutral2']?>">
            <input type="text" name="neutral3" placeholder="Playlist 3" value="<?php echo $Playlists['neutral3']?>">
            <input type="text" name="neutral4" placeholder="Playlist 4" value="<?php echo $Playlists['neutral4']?>">
            <input type="text" name="neutral5" placeholder="Playlist 5" value="<?php echo $Playlists['neutral5']?>">
            <input type="submit" name="value" value="Update Neutral Playlist">
            </form>
            </div>
            <div class="grid-item">
            <center>Fear Playlists</center>
            <form method="POST" action="updateplaylist.php">
            <input type="text" name="fear1" placeholder="Playlist 1" value="<?php echo $Playlists['fear1']?>">
            <input type="text" name="fear2" placeholder="Playlist 2" value="<?php echo $Playlists['fear2']?>">
            <input type="text" name="fear3" placeholder="Playlist 3" value="<?php echo $Playlists['fear3']?>">
            <input type="text" name="fear4" placeholder="Playlist 4" value="<?php echo $Playlists['fear4']?>">
            <input type="text" name="fear5" placeholder="Playlist 5" value="<?php echo $Playlists['fear5']?>">
            <input type="submit" name="value" value="Update Fear Playlist">
            </form>
            </div>
            
        
    </div>
</body>
</html>

<?php
include('../config.php');

extract($_POST);
if(!isset($_SESSION['username'])){
header("Location: ../index.php");
}
$username = $_SESSION['username'];

if($_POST['value'] == 'Update Happy Playlist'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'happy'.$i;
        $emotions[$emotion] = $_POST[$emotion];
    }

    foreach($emotions as $emotion => $value){
        echo $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            // Update the existing row
           echo $update = "UPDATE user_preferences SET playlist_id = '$value' WHERE username = '$username' AND emotion = '$emotion'";
            $conn->query($update);
        } else {
            // Insert a new row
           echo $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$value')";
            $conn->query($insert);
        }
    }
}
if($_POST['value'] == 'Update Sad Playlist'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'Sad'.$i;
        $emotions[$emotion] = $_POST[$emotion];
    }

    foreach($emotions as $emotion => $value){
        echo $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            // Update the existing row
           echo $update = "UPDATE user_preferences SET playlist_id = '$value' WHERE username = '$username' AND emotion = '$emotion'";
            $conn->query($update);
        } else {
            // Insert a new row
           echo $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$value')";
            $conn->query($insert);
        }
    }
}
if($_POST['value'] == 'Update Angry Playlist'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'angry'.$i;
        $emotions[$emotion] = $_POST[$emotion];
    }

    foreach($emotions as $emotion => $value){
        echo $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            // Update the existing row
           echo $update = "UPDATE user_preferences SET playlist_id = '$value' WHERE username = '$username' AND emotion = '$emotion'";
            $conn->query($update);
        } else {
            // Insert a new row
           echo $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$value')";
            $conn->query($insert);
        }
    }
}
if($_POST['value'] == 'Update Neutral Playlist'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'neutral'.$i;
        $emotions[$emotion] = $_POST[$emotion];
    }

    foreach($emotions as $emotion => $value){
        echo $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            // Update the existing row
           echo $update = "UPDATE user_preferences SET playlist_id = '$value' WHERE username = '$username' AND emotion = '$emotion'";
            $conn->query($update);
        } else {
            // Insert a new row
           echo $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$value')";
            $conn->query($insert);
        }
    }
}
if($_POST['value'] == 'Update Fear Playlist'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'fear'.$i;
        $emotions[$emotion] = $_POST[$emotion];
    }

    foreach($emotions as $emotion => $value){
        echo $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            // Update the existing row
           echo $update = "UPDATE user_preferences SET playlist_id = '$value' WHERE username = '$username' AND emotion = '$emotion'";
            $conn->query($update);
        } else {
            // Insert a new row
           echo $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$value')";
            $conn->query($insert);
        }
    }
}
header("Location: profile.php");
?>
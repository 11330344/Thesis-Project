<?php
include('../config.php');

extract($_GET);
if(!isset($_SESSION['username'])){
header("Location: ../index.php");
}
$username = $_SESSION['username'];
$ctr=0;
if($emotion == 'happy'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'happy'.$i;
        $emotions[$emotion] = $emotion;
    }

    foreach($emotions as $emotion => $value){
        $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            if($row['playlist_id']==''){
            // Update the existing row
           $update = "UPDATE user_preferences SET playlist_id = '$playlistid' WHERE username = '$username' AND emotion = '$emotion'";
           $conn->query($update);
           $ctr++;
           break;
            }
        } else {
            // Insert a new row
            if($ctr==0){
            $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$playlistid')";
            $conn->query($insert);
            $ctr++; }
            else{
                $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '')";
                $conn->query($insert);
            }
        }
    }
}
if($emotion == 'Sad'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'Sad'.$i;
        $emotions[$emotion] = $emotion;
    }

    foreach($emotions as $emotion => $value){
        $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            if($row['playlist_id']==''){
            // Update the existing row
           $update = "UPDATE user_preferences SET playlist_id = '$playlistid' WHERE username = '$username' AND emotion = '$emotion'";
           $conn->query($update);
           $ctr++;
           break;
            }
        } else {
            // Insert a new row
            if($ctr==0){
            $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$playlistid')";
            $conn->query($insert);
            $ctr++; }
            else{
                $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '')";
                $conn->query($insert);
            }
        }
    }
}
if($emotion == 'angry'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'angry'.$i;
        $emotions[$emotion] = $emotion;
    }

    foreach($emotions as $emotion => $value){
        $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            if($row['playlist_id']==''){
            // Update the existing row
           $update = "UPDATE user_preferences SET playlist_id = '$playlistid' WHERE username = '$username' AND emotion = '$emotion'";
           $conn->query($update);
           $ctr++;
           break;
            }
        } else {
            // Insert a new row
            if($ctr==0){
            $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$playlistid')";
            $conn->query($insert);
            $ctr++; }
            else{
                $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '')";
                $conn->query($insert);
            }
        }
    }
}
if($emotion == 'neutral'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'neutral'.$i;
        $emotions[$emotion] = $emotion;
    }

    foreach($emotions as $emotion => $value){
        $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            if($row['playlist_id']==''){
            // Update the existing row
           $update = "UPDATE user_preferences SET playlist_id = '$playlistid' WHERE username = '$username' AND emotion = '$emotion'";
           $conn->query($update);
           $ctr++;
           break;
            }
        } else {
            // Insert a new row
            if($ctr==0){
            $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$playlistid')";
            $conn->query($insert);
            $ctr++; }
            else{
                $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '')";
                $conn->query($insert);
            }
        }
    }
}
if($emotion == 'fear'){ 
    $emotions = []; // Initialize an array to hold emotion values
    for($i = 1; $i < 6; $i++){
        $emotion = 'fear'.$i;
        $emotions[$emotion] = $emotion;
    }

    foreach($emotions as $emotion => $value){
        $slct = "SELECT * FROM user_preferences WHERE username = '$username' AND emotion = '$emotion'";  
        $qry = $conn->query($slct);

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            if($row['playlist_id']==''){
            // Update the existing row
           $update = "UPDATE user_preferences SET playlist_id = '$playlistid' WHERE username = '$username' AND emotion = '$emotion'";
           $conn->query($update);
           $ctr++;
           break;
            }
        } else {
            // Insert a new row
            if($ctr==0){
            $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '$playlistid')";
            $conn->query($insert);
            $ctr++; }
            else{
                $insert = "INSERT INTO user_preferences (username, emotion, playlist_id) VALUES ('$username', '$emotion', '')";
                $conn->query($insert);
            }
        }
    }
}
if($ctr>0){
    echo 'Playlist saved successfully!' ;
}else{
    echo 'Playlist List is Full' ;
}
?>
<?php
session_start();

extract($_POST);
extract($_GET);
if(!isset($_SESSION['username'])){
header("Location: ../../index.php");
}
include('navbar.html');
include('index.html');


?>
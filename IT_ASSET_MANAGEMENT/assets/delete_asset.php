<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

$id = $_GET['id'];

$query = "DELETE FROM assets WHERE asset_id='$id'";

mysqli_query($conn,$query);

header("Location: view_assets.php");

?>
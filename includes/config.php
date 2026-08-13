<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "it_asset_management"
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

?>
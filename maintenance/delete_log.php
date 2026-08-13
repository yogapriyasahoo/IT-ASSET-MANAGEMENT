<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    /* Get asset_id before deleting */

    $query = mysqli_query(
        $conn,
        "SELECT asset_id
         FROM maintenance_logs
         WHERE log_id='$id'"
    );

    $row = mysqli_fetch_assoc($query);

    $asset_id = $row['asset_id'];

    /* Delete selected log */

    mysqli_query(
        $conn,
        "DELETE FROM maintenance_logs
         WHERE log_id='$id'"
    );

    /* Check if any remaining logs are Pending or In Progress */

    $status_query = mysqli_query(
        $conn,
        "SELECT *
         FROM maintenance_logs
         WHERE asset_id='$asset_id'
         AND (
              maintenance_status='Pending'
              OR maintenance_status='In Progress'
         )"
    );

    if(mysqli_num_rows($status_query) > 0)
    {
        mysqli_query(
            $conn,
            "UPDATE assets
             SET status='Under Repair'
             WHERE asset_id='$asset_id'"
        );
    }
    else
    {
        mysqli_query(
            $conn,
            "UPDATE assets
             SET status='Active'
             WHERE asset_id='$asset_id'"
        );
    }
}

header("Location: view_logs.php");

?>
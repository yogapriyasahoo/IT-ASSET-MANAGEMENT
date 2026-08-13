<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

$selected_asset = "";

if(isset($_GET['asset_id']))
{
    $selected_asset = $_GET['asset_id'];
}

/* Insert Maintenance Log */

if(isset($_POST['add_log']))
{
    $asset_id = $_POST['asset_id'];

    $issue_description =
    mysqli_real_escape_string(
        $conn,
        $_POST['issue_description']
    );

    $maintenance_status =
    $_POST['maintenance_status'];

    $technician =
    mysqli_real_escape_string(
        $conn,
        $_POST['technician']
    );

    /* Insert Maintenance Record */

    $query = "INSERT INTO maintenance_logs
             (
                asset_id,
                issue_description,
                maintenance_status,
                technician
             )

             VALUES
             (
                '$asset_id',
                '$issue_description',
                '$maintenance_status',
                '$technician'
             )";

    mysqli_query($conn, $query);

    /* Automatically Update Asset Status */

    if(
        $maintenance_status == "Pending"
        ||
        $maintenance_status == "In Progress"
    )
    {
        mysqli_query(
            $conn,
            "UPDATE assets
             SET status='Under Repair'
             WHERE asset_id='$asset_id'"
        );
    }
    elseif($maintenance_status == "Completed")
    {
        mysqli_query(
            $conn,
            "UPDATE assets
             SET status='Active'
             WHERE asset_id='$asset_id'"
        );
    }

    header("Location: view_logs.php");
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Maintenance Log</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header">

<h3>

<i class="bi bi-tools"></i>
Add Maintenance Log

</h3>

</div>

<div class="card-body">

<form method="POST">

<!-- Asset Dropdown -->

<div class="mb-3">

<label class="form-label">

Asset

</label>

<select
name="asset_id"
class="form-control"
required>

<option value="">

Select Asset

</option>

<?php

$asset_query =
mysqli_query(
    $conn,
    "SELECT asset_id, asset_name
     FROM assets
     ORDER BY asset_id ASC"
);

while($asset =
mysqli_fetch_assoc($asset_query))
{
?>

<option
value="<?php echo $asset['asset_id']; ?>"

<?php
if($selected_asset == $asset['asset_id'])
{
    echo "selected";
}
?>
>

<?php
echo $asset['asset_id']
. " - "
. $asset['asset_name'];
?>

</option>

<?php
}

?>

</select>

</div>

<!-- Issue Description -->

<div class="mb-3">

<label class="form-label">

Issue Description

</label>

<textarea
name="issue_description"
class="form-control"
rows="3"
required></textarea>

</div>

<!-- Status -->

<div class="mb-3">

<label class="form-label">

Maintenance Status

</label>

<select
name="maintenance_status"
class="form-control"
required>

<option value="Pending">

Pending

</option>

<option value="In Progress">

In Progress

</option>

<option value="Completed">

Completed

</option>

</select>

</div>

<!-- Technician -->

<div class="mb-3">

<label class="form-label">

Technician

</label>

<input
type="text"
name="technician"
class="form-control">

</div>

<!-- Buttons -->

<button
type="submit"
name="add_log"
class="btn btn-primary">

<i class="bi bi-plus-circle"></i>
Add Log

</button>

<a href="view_logs.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</body>

</html>
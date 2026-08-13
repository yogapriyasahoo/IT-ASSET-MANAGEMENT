<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM maintenance_logs
     WHERE log_id='$id'"
);

$row = mysqli_fetch_assoc($query);

/* Update Log */

if(isset($_POST['update_log']))
{
    $maintenance_status =
    $_POST['maintenance_status'];

    $technician =
    mysqli_real_escape_string(
        $conn,
        $_POST['technician']
    );

    mysqli_query(
        $conn,
        "UPDATE maintenance_logs
         SET maintenance_status='$maintenance_status',
             technician='$technician'
         WHERE log_id='$id'"
    );

    /* Synchronize Asset Status */

    $asset_id = $row['asset_id'];

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

<title>Edit Maintenance Log</title>

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

<i class="bi bi-pencil-square"></i>
Edit Maintenance Log

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label class="form-label">

Issue Description

</label>

<textarea
class="form-control"
rows="3"
readonly><?php echo $row['issue_description']; ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Maintenance Status

</label>

<select
name="maintenance_status"
class="form-control"
required>

<option value="Pending"
<?php if($row['maintenance_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="In Progress"
<?php if($row['maintenance_status']=="In Progress") echo "selected"; ?>>

In Progress

</option>

<option value="Completed"
<?php if($row['maintenance_status']=="Completed") echo "selected"; ?>>

Completed

</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Technician

</label>

<input
type="text"
name="technician"
class="form-control"
value="<?php echo $row['technician']; ?>">

</div>

<button
type="submit"
name="update_log"
class="btn btn-primary">

<i class="bi bi-save"></i>
Update Log

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
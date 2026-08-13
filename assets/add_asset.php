<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

if(isset($_POST['save']))
{
    $asset_id = trim($_POST['asset_id']);
    $asset_name = $_POST['asset_name'];
    $asset_type = $_POST['asset_type'];
    $brand = $_POST['brand'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $assigned_to = $_POST['assigned_to'];
    $employee_id = $_POST['employee_id'];
    $assigned_date = $_POST['assigned_date'];

    if(!empty($asset_id))
    {
        $check = mysqli_query($conn,
        "SELECT * FROM assets WHERE asset_id='$asset_id'");

        if(mysqli_num_rows($check) > 0)
        {
            $error = "Asset ID already exists!";
        }
        else
        {
            $query = "INSERT INTO assets
            (asset_id, asset_name, asset_type, brand, department, status,
            assigned_to, employee_id, assigned_date)
            VALUES
            ('$asset_id','$asset_name','$asset_type','$brand',
            '$department','$status',
            '$assigned_to','$employee_id','$assigned_date')";

            mysqli_query($conn,$query);

            $success = "Asset Added Successfully";
        }
    }
    else
    {
        $query = "INSERT INTO assets
        (asset_name, asset_type, brand, department, status,
        assigned_to, employee_id, assigned_date)
        VALUES
        ('$asset_name','$asset_type','$brand',
        '$department','$status',
        '$assigned_to','$employee_id','$assigned_date')";

        mysqli_query($conn,$query);

        $success = "Asset Added Successfully";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Add Asset</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">
<h3>Add New Asset</h3>
</div>

<div class="card-body">

<?php

if(isset($success))
{
    echo "<div class='alert alert-success'>$success</div>";
}

if(isset($error))
{
    echo "<div class='alert alert-danger'>$error</div>";
}

?>

<form method="POST">

<div class="mb-3">
<label>Asset ID (Optional)</label>
<input
type="number"
name="asset_id"
class="form-control"
placeholder="Leave blank for Auto Increment">
</div>

<div class="mb-3">
<label>Asset Name</label>
<input
type="text"
name="asset_name"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Asset Type</label>
<input
type="text"
name="asset_type"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Brand</label>
<input
type="text"
name="brand"
class="form-control">
</div>

<div class="mb-3">

<label>Department</label>

<select name="department" class="form-control" required>

<option value="">Select Department</option>

<?php

$dept_query = mysqli_query($conn,
"SELECT * FROM departments ORDER BY department_name ASC");

while($dept = mysqli_fetch_assoc($dept_query))
{
?>

<option value="<?php echo $dept['department_name']; ?>">

<?php echo $dept['department_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="mb-3">
<label>Status</label>

<select name="status" class="form-control">

<option value="Active">Active</option>

<option value="Under Repair">Under Repair</option>

<option value="Retired">Retired</option>

</select>

</div>

<div class="mb-3">
<label>Assigned To</label>
<input
type="text"
name="assigned_to"
class="form-control"
placeholder="Employee Name">
</div>

<div class="mb-3">
<label>Employee ID</label>
<input
type="text"
name="employee_id"
class="form-control"
placeholder="EMP001">
</div>

<div class="mb-3">
<label>Assigned Date</label>
<input
type="date"
name="assigned_date"
class="form-control">
</div>

<button
type="submit"
name="save"
class="btn btn-primary">

Save Asset

</button>

<a href="view_assets.php" class="btn btn-secondary">
    View Assets
</a>

</form>

</div>

</div>

</div>

</body>
</html>
<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

$id = $_GET['id'];
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$query = "SELECT * FROM assets WHERE asset_id='$id'";
$result = mysqli_query($conn,$query);

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $asset_name = $_POST['asset_name'];
    $asset_type = $_POST['asset_type'];
    $brand = $_POST['brand'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    /* If asset is Retired, clear assignment details */

    if($status == "Retired")
    {
        $assigned_to = NULL;
        $employee_id = NULL;
        $assigned_date = NULL;
    }
    else
    {
        $assigned_to = trim($_POST['assigned_to']);

        if(empty($assigned_to))
        {
            $assigned_to = NULL;
            $employee_id = NULL;
            $assigned_date = NULL;
        }
        else
        {
            $employee_id = trim($_POST['employee_id']);

            $assigned_date = !empty($_POST['assigned_date'])
                ? $_POST['assigned_date']
                : NULL;
        }
    }

    $update = "UPDATE assets SET

    asset_name='$asset_name',

    asset_type='$asset_type',

    brand='$brand',

    department='$department',

    status='$status',

    assigned_to=" . ($assigned_to ? "'$assigned_to'" : "NULL") . ",

    employee_id=" . ($employee_id ? "'$employee_id'" : "NULL") . ",

    assigned_date=" . ($assigned_date ? "'$assigned_date'" : "NULL") . "

    WHERE asset_id='$id'";

    mysqli_query($conn, $update);

header("Location: view_assets.php?page=$page");
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Asset</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">
<h3>Edit Asset</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>Asset Name</label>
<input
type="text"
name="asset_name"
value="<?php echo $row['asset_name']; ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Asset Type</label>
<input
type="text"
name="asset_type"
value="<?php echo $row['asset_type']; ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Brand</label>
<input
type="text"
name="brand"
value="<?php echo $row['brand']; ?>"
class="form-control">
</div>

<div class="mb-3">

<label>Department</label>

<select name="department" class="form-control" required>

<?php

$dept_query = mysqli_query($conn,
"SELECT * FROM departments ORDER BY department_name ASC");

while($dept = mysqli_fetch_assoc($dept_query))
{
?>

<option
value="<?php echo $dept['department_name']; ?>"
<?php
if($dept['department_name'] == $row['department'])
{
    echo "selected";
}
?>>

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

<option value="Active"
<?php if($row['status']=="Active") echo "selected"; ?>>
Active
</option>

<option value="Under Repair"
<?php if($row['status']=="Under Repair") echo "selected"; ?>>
Under Repair
</option>

<option value="Retired"
<?php if($row['status']=="Retired") echo "selected"; ?>>
Retired
</option>

</select>

</div>

<div class="mb-3">
<label>Assigned To</label>
<input
type="text"
name="assigned_to"
value="<?php echo $row['assigned_to']; ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Employee ID</label>
<input
type="text"
name="employee_id"
value="<?php echo $row['employee_id']; ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Assigned Date</label>
<input
type="date"
name="assigned_date"
value="<?php echo $row['assigned_date']; ?>"
class="form-control">
</div>

<button
type="submit"
name="update"
class="btn btn-success">

Update Asset

</button>

<a href="view_assets.php" class="btn btn-secondary">
    Back to Assets
</a>

</form>

</div>

</div>

</div>

</body>

</html>
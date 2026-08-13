<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

/* Add Department */

if(isset($_POST['add_department']))
{
    $department_id = trim($_POST['department_id']);
    $department_name = trim($_POST['department_name']);

    if(!empty($department_name))
    {
        if(!empty($department_id))
        {
            // Check if ID already exists

            $check_id = mysqli_query($conn,
            "SELECT * FROM departments
             WHERE department_id='$department_id'");

            if(mysqli_num_rows($check_id) > 0)
            {
                $error = "Department ID already exists!";
            }
            else
            {
                mysqli_query($conn,
                "INSERT INTO departments
                (department_id, department_name)
                VALUES
                ('$department_id','$department_name')");

                $success = "Department Added Successfully";
            }
        }
        else
        {
            mysqli_query($conn,
            "INSERT INTO departments(department_name)
             VALUES('$department_name')");

            $success = "Department Added Successfully";
        }
    }
}

/* Delete Department */

if(isset($_GET['delete']))
{
    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM departments
     WHERE department_id='$id'");

    header("Location: manage_departments.php");
}

/* Always show departments in ascending ID order */

$result = mysqli_query($conn,
"SELECT * FROM departments
 ORDER BY department_id ASC");

?>

<!DOCTYPE html>
<html>

<head>

<title>Department Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">

<h3>Department Management</h3>

</div>

<div class="card-body">

<?php
if(isset($_GET['from']) && $_GET['from'] == 'reports')
{
?>
    <a href="../reports.php" class="btn btn-info mb-3">
        <- Back to Reports
    </a>
<?php
}
?>

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

<div class="row mb-3">

<div class="col-md-3">

<input
type="number"
name="department_id"
class="form-control"
placeholder="Department ID (Optional)">

</div>

<div class="col-md-7">

<input
type="text"
name="department_name"
class="form-control"
placeholder="Enter Department Name"
required>

</div>

<div class="col-md-2">

<button type="submit" class="btn btn-primary">

    <i class="bi bi-plus-circle"></i>
    Add Department

</button>

</div>

</div>

</form>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th class="text-center">ID</th>
<th class="text-center">Department Name</th>
<th class="text-center">Actions</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td class="text-center">
    <?php echo $row['department_id']; ?>
</td>

<td><?php echo $row['department_name']; ?></td>

<td class="text-center">

	 <div class="d-flex justify-content-center">
<a href="?delete=<?php echo $row['department_id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this department?')">

Delete

</a>

</td>

</tr>

<?php
    }
}
else
{
    echo "<tr>
            <td colspan='3' class='text-center text-danger'>
            No Departments Found
            </td>
          </tr>";
}
?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>
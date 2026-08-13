<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

$records_per_page = 10;

$page = isset($_GET['page'])
    ? $_GET['page']
    : 1;

$offset = ($page - 1) * $records_per_page;


if(isset($_GET['search']) && !empty($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );

    $query = "SELECT maintenance_logs.*,
              assets.asset_name
              FROM maintenance_logs
              INNER JOIN assets
              ON maintenance_logs.asset_id = assets.asset_id

              WHERE maintenance_logs.log_id LIKE '%$search%'
              OR assets.asset_name LIKE '%$search%'
              OR maintenance_logs.technician LIKE '%$search%'
              OR maintenance_logs.maintenance_status LIKE '%$search%'

              ORDER BY log_id DESC
              LIMIT $offset, $records_per_page";

    $count_query = mysqli_query(
        $conn,

        "SELECT COUNT(*) AS total
         FROM maintenance_logs
         INNER JOIN assets
         ON maintenance_logs.asset_id = assets.asset_id

         WHERE maintenance_logs.log_id LIKE '%$search%'
         OR assets.asset_name LIKE '%$search%'
         OR maintenance_logs.technician LIKE '%$search%'
         OR maintenance_logs.maintenance_status LIKE '%$search%'"
    );
}
else
{
    $query = "SELECT maintenance_logs.*,
              assets.asset_name
              FROM maintenance_logs
              INNER JOIN assets
              ON maintenance_logs.asset_id = assets.asset_id

              ORDER BY log_id DESC
              LIMIT $offset, $records_per_page";

    $count_query = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM maintenance_logs"
    );
}

$result = mysqli_query($conn, $query);

$count_row = mysqli_fetch_assoc($count_query);

$total_records = $count_row['total'];

$total_pages = ceil(
    $total_records / $records_per_page
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Maintenance Logs</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

.action-btn{
    min-width:90px;
}

.actions-column{
    min-width:320px;
}

.table tbody tr:hover{
    background-color:#f8f9fa;
    transition:0.2s;
}

</style>

</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

<h3>
    <i class="bi bi-tools"></i>
    Maintenance Logs
</h3>

</div>

<div class="card-body">

 <!-- Search + Add Log block -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <form method="GET" class="d-flex flex-grow-1 me-3">

            <div class="input-group">

                <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Log ID, Asset Name, Technician or Status"
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

                <button
                type="submit"
                class="btn btn-primary">

                    <i class="bi bi-search"></i>
                    Search

                </button>

            </div>

        </form>

        <a href="add_log.php"
        class="btn btn-success">

            <i class="bi bi-plus-circle"></i>
            Add Log

        </a>

    </div>


<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th class="text-center">Log ID</th>
<th class="text-center">Asset ID</th>
<th>Asset Name</th>
<th>Issue Description</th>
<th class="text-center">Reported Date</th>
<th class="text-center">Status</th>
<th>Technician</th>
<th class="actions-column text-center">Actions</th>

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
<?php echo $row['log_id']; ?>
</td>

<td class="text-center">
<?php echo $row['asset_id']; ?>
</td>

<td>
<?php echo $row['asset_name']; ?>
</td>

<td>
<?php echo $row['issue_description']; ?>
</td>

<td class="text-center">
<?php echo $row['reported_date']; ?>
</td>

<td class="text-center">

<?php

if($row['maintenance_status'] == "Pending")
{
    echo '<span class="badge bg-warning text-dark">
            Pending
          </span>';
}
elseif($row['maintenance_status'] == "In Progress")
{
    echo '<span class="badge bg-primary">
            In Progress
          </span>';
}
elseif($row['maintenance_status'] == "Completed")
{
    echo '<span class="badge bg-success">
            Completed
          </span>';
}

?>

</td>

<td>
<?php echo $row['technician']; ?>
</td>

<td class="text-center">

<div class="d-flex justify-content-center flex-wrap">

<a href="../assets/view_assets.php?search=<?php echo $row['asset_id']; ?>"
class="btn btn-success btn-sm action-btn me-1 mb-1">

<i class="bi bi-laptop"></i>
View Asset

</a>

<a href="edit_log.php?id=<?php echo $row['log_id']; ?>"
class="btn btn-warning btn-sm action-btn me-1 mb-1">

<i class="bi bi-pencil-square"></i>
Edit

</a>

<a href="delete_log.php?id=<?php echo $row['log_id']; ?>"
class="btn btn-danger btn-sm action-btn mb-1"
onclick="return confirm('Delete this log?')">

<i class="bi bi-trash"></i>
Delete

</a>

</div>

</td>

</tr>

<?php
    }
}
else
{
    echo "
    <tr>
    <td colspan='8'
    class='text-center text-danger'>
    No Maintenance Logs Found
    </td>
    </tr>";
}
?>

</tbody>

</table>

<div class="mt-3 text-center">

<?php if($page > 1) { ?>

href="?page=<?php echo $page-1; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
class="btn btn-outline-primary">

Previous

</a>

<?php } ?>

<span class="mx-3 fw-bold">

Page <?php echo $page; ?> of <?php echo $total_pages; ?>

</span>

<?php if($page < $total_pages) { ?>

href="?page=<?php echo $page+1; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
class="btn btn-outline-primary">

Next

</a>

<?php } ?>

</div>

</div>

</div>

</div>

</body>

</html>
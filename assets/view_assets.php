<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../login.php");
}

include '../includes/config.php';

/* Pagination */

$records_per_page = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$offset = ($page - 1) * $records_per_page;


/* Filter / Search Queries */

if(isset($_GET['filter']))
{
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);

    if($filter == "all")
    {
        $query = "SELECT * FROM assets
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
    elseif($filter == "assigned")
    {
        $query = "SELECT * FROM assets
                  WHERE assigned_to IS NOT NULL
                  AND assigned_to <> ''
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
    elseif($filter == "unassigned")
    {
        $query = "SELECT * FROM assets
                  WHERE assigned_to IS NULL
                  OR assigned_to = ''
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
    else
    {
        $query = "SELECT * FROM assets
                  WHERE status='$filter'
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
}
elseif(isset($_GET['department']))
{
    $department =
    mysqli_real_escape_string(
        $conn,
        $_GET['department']
    );

    $query = "SELECT * FROM assets
              WHERE department='$department'
              ORDER BY asset_id ASC
              LIMIT $offset, $records_per_page";
}
elseif(isset($_GET['search']) && !empty($_GET['search']))
{
    $search =
    mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );

    if(is_numeric($search))
    {
        $query = "SELECT * FROM assets
                  WHERE asset_id = $search
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
    else
    {
        $query = "SELECT * FROM assets
                  WHERE asset_name LIKE '%$search%'
                  OR asset_type LIKE '%$search%'
                  OR brand LIKE '%$search%'
                  OR department LIKE '%$search%'
                  OR status LIKE '%$search%'
                  OR assigned_to LIKE '%$search%'
                  OR employee_id LIKE '%$search%'
                  OR assigned_date LIKE '%$search%'
                  ORDER BY asset_id ASC
                  LIMIT $offset, $records_per_page";
    }
}
else
{
    $query = "SELECT * FROM assets
              ORDER BY asset_id ASC
              LIMIT $offset, $records_per_page";
}

/* Execute Query */

$result = mysqli_query($conn, $query);

$search_count = mysqli_num_rows($result);

/* Pagination Count */

if(isset($_GET['filter']))
{
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);

    if($filter == "all")
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets";
    }
    elseif($filter == "assigned")
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets
         WHERE assigned_to IS NOT NULL
         AND assigned_to <> ''";
    }
    elseif($filter == "unassigned")
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets
         WHERE assigned_to IS NULL
         OR assigned_to = ''";
    }
    else
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets
         WHERE status='$filter'";
    }
}
elseif(isset($_GET['department']))
{
    $department =
    mysqli_real_escape_string(
        $conn,
        $_GET['department']
    );

    $count_sql =
    "SELECT COUNT(*) AS total
     FROM assets
     WHERE department='$department'";
}
elseif(isset($_GET['search']) && !empty($_GET['search']))
{
    $search =
    mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );

    if(is_numeric($search))
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets
         WHERE asset_id = $search";
    }
    else
    {
        $count_sql =
        "SELECT COUNT(*) AS total
         FROM assets
         WHERE asset_name LIKE '%$search%'
         OR asset_type LIKE '%$search%'
         OR brand LIKE '%$search%'
         OR department LIKE '%$search%'
         OR status LIKE '%$search%'
         OR assigned_to LIKE '%$search%'
         OR employee_id LIKE '%$search%'
         OR assigned_date LIKE '%$search%'";
    }
}
else
{
    $count_sql =
    "SELECT COUNT(*) AS total
     FROM assets";
}

$count_query =
mysqli_query($conn, $count_sql);

$count_row =
mysqli_fetch_assoc($count_query);

$total_records =
$count_row['total'];

$total_pages =
ceil(
    $total_records /
    $records_per_page
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>View Assets</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	
	<style>

	.badge{
		font-size:14px;
		padding:8px 12px;
	}

	/* Row Hover Effect */

	.table tbody tr{
		transition:0.2s;
	}

	.table tbody tr:hover{
		background-color:#dbeafe;
		 box-shadow:0 0 8px rgba(0,0,0,0.15);
		cursor:pointer;
	}
	.action-btn{
    min-width:90px;
}

	.actions-column{
    min-width:320px;
}

</style>
</head>

<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h3>

			<?php

			if(isset($_GET['filter']))
			{
				if($_GET['filter'] == "all")
				{
					echo "All Assets";
				}
				else
				{
					echo $_GET['filter'] . " Assets";
				}
			}
			else
			{
				echo "All Assets";
			}

			?>

			</h3>

            <a href="add_asset.php" class="btn btn-primary">

				<i class="bi bi-plus-circle"></i>
				Add New Asset

			</a>

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

            <!-- Search Form -->

            <form method="GET" class="mb-4">

                <div class="row">

                    <div class="col-md-8">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="🔍 Search by Asset ID, Name, Type, Brand, Department, Status, Employee ID, Assigned To or Date"
                            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

                    </div>

                    <div class="col-md-2">

                        <button type="submit" class="btn btn-primary w-100">

							<i class="bi bi-search"></i>
							Search

						</button>

                    </div>

                    <div class="col-md-2">

                        <a href="view_assets.php"
						class="btn btn-secondary w-100">

							<i class="bi bi-arrow-clockwise"></i>
							Reset

						</a>

                    </div>

                </div>

            </form>
			<?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>

			<div class="alert alert-info">

				<i class="bi bi-search"></i>

				Showing

				<strong>
					<?php echo $search_count; ?>
				</strong>

				matching asset<?php echo ($search_count != 1) ? 's' : ''; ?>

				for:

				<strong>
					"<?php echo htmlspecialchars($_GET['search']); ?>"
				</strong>

			</div>

<?php } ?>

            <!-- Asset Table -->

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                <tr>

                      <th class="text-center">ID</th>
					  <th class="text-center">Asset Name</th>
					  <th class="text-center">Asset Type</th>
					  <th class="text-center">Brand</th>
					  <th class="text-center">Department</th>
					  <th class="text-center">Status</th>
					  <th class="text-center">Assigned To</th>
					  <th class="text-center">Employee ID</th>
					  <th class="text-center">Assigned Date</th>
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
					<?php echo $row['asset_id']; ?>
					</td>

                    <td><?php echo $row['asset_name']; ?></td>

                    <td><?php echo $row['asset_type']; ?></td>

                    <td><?php echo $row['brand']; ?></td>

                    <td><?php echo $row['department']; ?></td>

					<td class="text-center">

					<?php

					if($row['status'] == 'Active')
					{
						echo '<span class="badge bg-success">
								Active
							</span>';
					}
					elseif($row['status'] == 'Under Repair')
					{
						echo '<span class="badge bg-warning text-dark">
								Under Repair
							</span>';
					}
					elseif($row['status'] == 'Retired')
					{
						echo '<span class="badge bg-secondary">
								Retired
						</span>';
					}
					else
					{
						echo '<span class="badge bg-secondary">'
								.$row['status'].
							'</span>';
					}

?>

</td>

					<td><?php echo $row['assigned_to']; ?></td>

					<td class="text-center">
					<?php echo $row['employee_id']; ?>
					</td>

					<td class="text-center">
					<?php echo $row['assigned_date']; ?>
					</td>

                    <td>

    <div class="d-flex justify-content-center flex-wrap">

<?php if($row['status'] == 'Active') { ?>

<a href="../maintenance/add_log.php?asset_id=<?php echo $row['asset_id']; ?>"
class="btn btn-primary btn-sm action-btn me-1 mb-1">

    <i class="bi bi-tools"></i>
    Log Issue

</a>

<?php } ?>

<a href="edit_asset.php?id=<?php echo $row['asset_id']; ?>&page=<?php echo $page; ?>"
class="btn btn-warning btn-sm action-btn me-1 mb-1">

    <i class="bi bi-pencil-square"></i>
    Edit

</a>

<a href="delete_asset.php?id=<?php echo $row['asset_id']; ?>"
class="btn btn-danger btn-sm action-btn mb-1"
onclick="return confirm('Delete this asset?')">

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
                    echo "<tr>
                            <td colspan='10' class='text-center text-danger'>
                                No Assets Found
                            </td>
                          </tr>";
                }
                ?>

                </tbody>

            </table>
			<br>

<?php

$query_string = $_GET;
unset($query_string['page']);

$base_url = http_build_query($query_string);

?>

<nav>

<ul class="pagination justify-content-center">

<?php if($page > 1) { ?>

<li class="page-item">

<a class="page-link"
href="?<?php echo $base_url; ?>&page=<?php echo $page - 1; ?>">

Previous

</a>

</li>

<?php } ?>

<?php for($i = 1; $i <= $total_pages; $i++) { ?>

<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">

<a class="page-link"
href="?<?php echo $base_url; ?>&page=<?php echo $i; ?>">

<?php echo $i; ?>

</a>

</li>

<?php } ?>

<?php if($page < $total_pages) { ?>

<li class="page-item">

<a class="page-link"
href="?<?php echo $base_url; ?>&page=<?php echo $page + 1; ?>">

Next

</a>

</li>

<?php } ?>

</ul>

</nav>

        </div>

    </div>

</div>

</body>

</html>
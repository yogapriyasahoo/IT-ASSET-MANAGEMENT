<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
}

include 'includes/config.php';

/* Dashboard Statistics */

$total_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets")
);

$active_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets WHERE status='Active'")
);

$repair_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets WHERE status='Under Repair'")
);

$retired_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets WHERE status='Retired'")
);

$total_logs = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM maintenance_logs")
);

$pending_logs = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM maintenance_logs
     WHERE maintenance_status='Pending'")
);

$in_progress_logs = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM maintenance_logs
     WHERE maintenance_status='In Progress'")
);

$completed_logs = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM maintenance_logs
     WHERE maintenance_status='Completed'")
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>NALCO IT Asset Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

	.card{
		border:none;
		border-radius:18px;
		transition:0.3s;
	}

	.card:hover{
		transform:translateY(-5px);
	}

	.stats-number{
		font-size:40px;
		font-weight:bold;
	}

	.card-link{
		text-decoration:none;
		color:inherit;
	}

	.card-link:hover{
		text-decoration:none;
		color:inherit;
	}

	.card h5 i{
		margin-right:8px;
	}

	</style>

</head>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container mt-4">

    <div class="card shadow border-0 mb-4">

    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-md-2 text-center">

                <img src="assets/images/nalco_logo.png"
                     alt="NALCO Logo"
                     class="img-fluid"
                     style="max-height:100px;">

            </div>

            <div class="col-md-10">

                <h2 class="fw-bold text-primary">

                    Welcome,
                    <?php echo $_SESSION['username']; ?>

                </h2>

                <p class="mb-1 fs-5">

                    National Aluminium Company Limited (NALCO)

                </p>

                <p class="text-muted">

                    IT Asset Management System

                </p>

                <hr>

                <p>

                    Manage, monitor and track organizational IT assets efficiently across departments.

                </p>

            </div>

        </div>

    </div>

</div>

    <div class="row">

        <!-- Total Assets -->

        <div class="col-md-3">

            <a href="assets/view_assets.php?filter=all"
			class="card-link">

                <div class="card bg-primary text-white shadow">

                    <div class="card-body">

                        <h5>
							<i class="bi bi-pc-display"></i>
							Total Assets
						</h5>

                        <h2 class="stats-number">
							<?php echo $total_assets; ?>
						</h2>

                    </div>

                </div>

            </a>

        </div>

        <!-- Active Assets -->

        <div class="col-md-3">

            <a href="assets/view_assets.php?filter=Active"
			class="card-link">

                <div class="card bg-success text-white shadow">

                    <div class="card-body">

                        <h5>
							<i class="bi bi-check-circle-fill"></i>
							Active Assets
						</h5>

                        <h2><?php echo $active_assets; ?></h2>

                    </div>

                </div>

            </a>

        </div>

        <!-- Under Repair -->

        <div class="col-md-3">

            <a href="assets/view_assets.php?filter=Under Repair"
			class="card-link">

                <div class="card bg-warning text-dark shadow">

                    <div class="card-body">

                        <h5>
							<i class="bi bi-tools"></i>
							Under Repair
						</h5>

                        <h2><?php echo $repair_assets; ?></h2>

                    </div>

                </div>

            </a>

        </div>

        <!-- Retired Assets -->

        <div class="col-md-3">

            <a href="assets/view_assets.php?filter=Retired"
			class="card-link">

                <div class="card bg-danger text-white shadow">

                    <div class="card-body">

                        <h5>
							<i class="bi bi-x-circle-fill"></i>
							Retired Assets
						</h5>

                        <h2><?php echo $retired_assets; ?></h2>

                    </div>

                </div>

            </a>

        </div>

    </div>

<div class="row mt-4">

    <!-- Total Logs -->
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h4>
                    <i class="bi bi-tools"></i>
                    Total Logs
                </h4>

                <h1 class="fw-bold mt-3">
                    <?php echo $total_logs; ?>
                </h1>
            </div>
        </div>
    </div>

    <!-- Pending Logs -->
    <div class="col-md-3 mb-3">
        <div class="card bg-warning shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h4>
                    <i class="bi bi-hourglass-split"></i>
                    Pending Logs
                </h4>

                <h1 class="fw-bold mt-3">
                    <?php echo $pending_logs; ?>
                </h1>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h4>
                    <i class="bi bi-gear-wide-connected"></i>
                    In Progress
                </h4>

                <h1 class="fw-bold mt-3">
                    <?php echo $in_progress_logs; ?>
                </h1>
            </div>
        </div>
    </div>

    <!-- Completed Logs -->
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h4>
                    <i class="bi bi-check-circle-fill"></i>
                    Completed Logs
                </h4>

                <h1 class="fw-bold mt-3">
                    <?php echo $completed_logs; ?>
                </h1>
            </div>
        </div>
    </div>

</div>
    <br>

    <div class="card shadow">

        <div class="card-header">
            Quick Actions
        </div>

        <div class="card-body">

            <a href="assets/add_asset.php"
            class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
				Add Asset
            </a>

            <a href="assets/view_assets.php"
            class="btn btn-success">
               <i class="bi bi-pc-display"></i>
				View Assets
            </a>

            <a href="departments/manage_departments.php"
            class="btn btn-info">
                <i class="bi bi-building"></i>
				Manage Departments
            </a>
			
			<a href="reports.php"
			class="btn btn-secondary">

				<i class="bi bi-bar-chart-fill"></i>
				Reports

			</a>

        </div>

    </div>

</div>

</body>

</html>


<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
}

include 'includes/config.php';

/* Statistics */

$total_assets = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM assets")
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

$assigned_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets
     WHERE assigned_to IS NOT NULL
     AND assigned_to <> ''")
);

$unassigned_assets = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM assets
     WHERE assigned_to IS NULL
     OR assigned_to = ''")
);

$total_departments = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM departments")
);

/* Department Chart Data */

$department_labels = [];
$department_counts = [];

$chart_query = mysqli_query($conn,
"SELECT department, COUNT(*) AS total
 FROM assets
 GROUP BY department");

while($row = mysqli_fetch_assoc($chart_query))
{
    $department_labels[] = $row['department'];
    $department_counts[] = $row['total'];
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Reports Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

.card{
    border:none;
    border-radius:15px;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.report-link{
    text-decoration:none;
}

/* Equal height for chart cards */

.chart-card{
    height:500px;
}

.card h5 i{
    margin-right:8px;
}

</style>

</head>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container mt-4">

<h2>
    <i class="bi bi-bar-chart-fill"></i>
    Asset Reports & Analytics
</h2>

<hr>

<div class="row">

<!-- Total Assets -->

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=all&from=reports"
class="report-link">

<div class="card bg-primary text-white shadow">

<div class="card-body">

<h5>
    <i class="bi bi-pc-display"></i>
    Total Assets
</h5>

<h2><?php echo $total_assets; ?></h2>

</div>

</div>

</a>

</div>

<!-- Active Assets -->

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=Active&from=reports"
class="report-link">

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

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=Under Repair&from=reports"
class="report-link">

<div class="card bg-warning shadow">

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

<!-- Retired -->

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=Retired&from=reports"
class="report-link">

<div class="card bg-danger text-white shadow">

<div class="card-body">

<h5>
    <i class="bi bi-x-archive-fill"></i>
    Retired Assets
</h5>

<h2><?php echo $retired_assets; ?></h2>

</div>

</div>

</a>

</div>

<!-- Assigned -->

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=assigned&from=reports"
class="report-link">

<div class="card bg-info text-white shadow">

<div class="card-body">

<h5>
    <i class="bi bi-person-check-fill"></i>
    Assigned Assets
</h5>

<h2><?php echo $assigned_assets; ?></h2>

</div>

</div>

</a>

</div>

<!-- Unassigned -->

<div class="col-md-4 mb-3">

<a href="assets/view_assets.php?filter=unassigned&from=reports"
class="report-link">

<div class="card bg-secondary text-white shadow">

<div class="card-body">

<h5>
    <i class="bi bi-person-x-fill"></i>
    Unassigned Assets
</h5>

<h2><?php echo $unassigned_assets; ?></h2>

</div>

</div>

</a>

</div>

<!-- Total Departments -->

<div class="col-md-4 mb-3">

<a href="departments/manage_departments.php?from=reports"
class="report-link">

<div class="card bg-dark text-white shadow">

<div class="card-body">

<h5>
    <i class="bi bi-building"></i>
    Total Departments
</h5>

<h2><?php echo $total_departments; ?></h2>

</div>

</div>

</a>

</div>

</div>

<div class="row mt-4">

    <!-- Pie Chart -->

    <div class="col-md-4">

        <div class="card shadow chart-card">

            <div class="card-header">

                <h5>Asset Status Distribution</h5>

            </div>

            <div class="card-body">

                <div style="height:400px;">
					<canvas id="statusChart"></canvas>
				</div>

            </div>

        </div>

    </div>

    <!-- Bar Chart -->

    <div class="col-md-8">

        <div class="card shadow chart-card">

            <div class="card-header">

                <h5>Assets by Department</h5>

            </div>

            <div class="card-body">

                <div style="height:400px;">
					<canvas id="departmentChart"></canvas>
				</div>

            </div>

        </div>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* Pie Chart */

const statusChart = new Chart(
document.getElementById('statusChart'),
{
    type: 'pie',

    data:
    {
        labels:
        [
            'Active',
            'Under Repair',
            'Retired'
        ],

        datasets:
        [{
            data:
            [
                <?php echo $active_assets; ?>,
                <?php echo $repair_assets; ?>,
                <?php echo $retired_assets; ?>
            ]
        }]
    },

    options:
    {
        responsive:true,
        maintainAspectRatio:false,

        onClick: function(evt, elements)
        {
            if(elements.length > 0)
            {
                let index = elements[0].index;

                let status =
                this.data.labels[index];

                window.location.href =
                'assets/view_assets.php?filter='
                + encodeURIComponent(status)
                + '&from=reports';
            }
        }
    }
});

/* Department Bar Chart */

const departmentChart = new Chart(
document.getElementById('departmentChart'),
{
    type: 'bar',

    data:
    {
        labels:
        <?php echo json_encode($department_labels); ?>,

        datasets:
        [{
            label:'Assets',

            data:
            <?php echo json_encode($department_counts); ?>
        }]
    },

    options:
    {
        responsive:true,
        maintainAspectRatio:false,

        scales:
        {
            x:
            {
                ticks:
                {
                    autoSkip:false
                }
            },

            y:
            {
                beginAtZero:true
            }
        },

        onClick: function(evt, elements)
        {
            if(elements.length > 0)
            {
                let index =
                elements[0].index;

                let department =
                this.data.labels[index];

                window.location.href =
                'assets/view_assets.php?department='
                + encodeURIComponent(department)
                + '&from=reports';
            }
        }
    }
});

</script>

</body>

</html>


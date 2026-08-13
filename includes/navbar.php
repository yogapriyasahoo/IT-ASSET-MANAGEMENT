<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container-fluid">

 <a class="navbar-brand fw-bold d-flex align-items-center" href="#">

    <img src="<?php echo (dirname($_SERVER['PHP_SELF']) == '/it_asset_management')
    ? 'assets/images/nalco_logo.png'
    : '../assets/images/nalco_logo.png'; ?>"
     alt="NALCO Logo"
     height="40"
     class="me-2 bg-white p-1 rounded">

    NALCO IT Asset Management System

</a>

        <div class="d-flex align-items-center">

            <span class="text-white me-3">

                Welcome,
                <strong>
                    <?php echo $_SESSION['username']; ?>
                </strong>

            </span>

            <a href="/it_asset_management/dashboard.php"
            class="btn btn-light btn-sm me-2">

              <i class="bi bi-house-door"></i>
				Dashboard

            </a>
			
			<a href="/it_asset_management/maintenance/view_logs.php"
			class="btn btn-light btn-sm me-2">

				<i class="bi bi-tools"></i>
				Maintenance

			</a>
			
            <a href="/it_asset_management/logout.php"
            class="btn btn-danger btn-sm">

               <i class="bi bi-box-arrow-right"></i>
				Logout

            </a>

        </div>

    </div>

</nav>
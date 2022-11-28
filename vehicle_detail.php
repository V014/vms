<?php
include_once "./includes/utils.php";
include_once "./includes/entity/vehicle.php";

$vehicle = Vehicle::find($_GET["id"]);
$vehicleHistory = $vehicle->history();
$vehicleStats = $vehicle->stats();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - <?php echo $vehicle->make . " (" . $vehicle->registrationNo . ")"; ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include_once('./components/nav_bar.php') ?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </form>
                        <?php navActions(); ?>
                    </div>
                </nav>
                <div class="container-fluid">
                    <!-- Vehicle Section -->
                    <section style="background-color: #eee;">
                        <div class="container py-5">
                            <!-- Breadcrumb Section -->
                            <div class="row">
                                <div class="col">
                                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                                        <ol class="breadcrumb mb-0">
                                            <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "admin_dashboard.php"; ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "vehicle_list.php"; ?>">Vehicle</a></li>
                                            <li class="breadcrumb-item active" aria-current="page"><?php echo $vehicle->make . " (" . $vehicle->registrationNo . ")"; ?></li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-body text-center">
                                            <div class="mb-3"><i class="fas fa-truck fa-9x"></i></div>
                                            <p class="h4"><?php echo $vehicle->make . " (" . $vehicle->year . ")"; ?></p>
                                            <p class="h4"><?php echo $vehicle->registrationNo; ?></p>
                                            <p class="h3"><?php echo $vehicle->capacity . "L"; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="card shadow border-start-primary py-2">
                                                <div class="card-body">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col me-2">
                                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Quantity</span></div>
                                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $vehicleStats["total_quantity"] . "L"; ?></span></div>
                                                        </div>
                                                        <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="card shadow border-start-primary py-2">
                                                <div class="card-body">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col me-2">
                                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Profit</span></div>
                                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo "K" . number_format($vehicleStats["total_profit"]); ?></span></div>
                                                        </div>
                                                        <div class="col-auto"><i class="fas fa-money-bill fa-2x text-gray-300"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-4">
                                            <div class="card shadow border-start-primary py-2">
                                                <div class="card-body">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col me-2">
                                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Orders</span></div>
                                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $vehicleStats["total_orders"]; ?></span></div>
                                                        </div>
                                                        <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Brand 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>

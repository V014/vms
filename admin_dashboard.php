<?php
include_once "./includes/utils.php";
include_once "./includes/auth.php";
include_once "./includes/entity/order.php";
include_once "./includes/entity/user.php";
include_once "./includes/entity/vehicle.php";
include_once "./includes/entity/fuel.php";
include_once "./includes/entity/trips.php";

$user = Auth::getUser();

$userTotalStats = User::totalStats();
$orderTotalStats = Order::totalStats();
$parsedMonthlyStats = Order::parsedMonthlyOrderStats();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
                    <div class="d-sm-flex justify-content-between align-items-center mb-5">
                        <h3 class="text-dark mb-0">Dashboard</h3>
                    </div>
                    <h5>Users</h5>
                    <div class="row my-4">
                        <div class="col-md-4">
                            <div class="card shadow border-start-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Users</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $userTotalStats["total_users"]; ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow border-start-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Companies</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $userTotalStats["total_companies"]; ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-building fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow border-start-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Drivers</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $userTotalStats["total_drivers"]; ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5>Orders</h5>
                    <!-- Order Section -->
                    <div class="row my-4">
                        <!-- Order Total Stats Section -->
                        <div class="row my-4">
                            <div class="col-md-3">
                                <div class="card shadow border-start-primary py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col me-2">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Expected Income (2022)</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><span><?php echo "K" . number_format($orderTotalStats["total_profit"], 2); ?></span></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-money-bill fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow border-start-primary py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col me-2">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Quantity Ordered (2022)</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><span><?php echo number_format($orderTotalStats["total_quantity"], 2) . "L"; ?></span></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-oil-can fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow border-start-primary py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col me-2">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Pending Orders</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><span><?php echo $orderTotalStats["total_pending"]; ?></span></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow border-start-primary py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col me-2">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Delivered Orders</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><span><?php echo $orderTotalStats["total_delivered"]; ?></span></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-check fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Order Total Stats Section -->
                        <!-- Order Chart Section -->
                        <div class="row">
                            <div class="col-lg-7 col-xl-8">
                                <div class="card shadow mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="text-primary fw-bold m-0">Orders Overview</h6>
                                        <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                            <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                                <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" href="#">&nbsp;Action</a><a class="dropdown-item" href="#">&nbsp;Another action</a>
                                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">&nbsp;Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="totalOrdersChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-xl-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="text-primary fw-bold m-0">Fuel Types Ordered</h6>
                                        <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                            <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                                <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" href="#">&nbsp;Action</a><a class="dropdown-item" href="#">&nbsp;Another action</a>
                                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">&nbsp;Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="fuelTypesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg">
                                <div class="card shadow mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="text-primary fw-bold m-0">Earnings Overview</h6>
                                        <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                            <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                                <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" href="#">&nbsp;Action</a><a class="dropdown-item" href="#">&nbsp;Another action</a>
                                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">&nbsp;Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="earningsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Order Chart Section -->
                    </div>
                </div>
                <footer class="bg-white sticky-footer">
                    <div class="container my-auto">
                        <div class="text-center my-auto copyright"><span>Copyright © VMS 2022</span></div>
                    </div>
                </footer>
            </div>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    <script>
        let data = {
            labels: <?php echo json_encode($parsedMonthlyStats["months"]); ?>,
            datasets: [{
                label: 'Orders',
                data: <?php echo json_encode($parsedMonthlyStats["orderCount"]); ?>,
                borderWidth: 1,
                backgroundColor: <?php echo json_encode($parsedMonthlyStats["colors"]); ?>
            }]
        };

        let config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        new Chart(
            document.getElementById('totalOrdersChart'),
            config
        );

        data = {
            labels: ['Petrol', 'Diesel'],
            datasets: [{
                label: 'Fuel Orders',
                backgroundColor: ["<?php echo dynamicColor(); ?>", "<?php echo dynamicColor(); ?>"],
                data: [<?php echo $orderTotalStats["total_petrol_orders"]; ?>, <?php echo $orderTotalStats["total_diesel_orders"]; ?>],
                hoverOffSet: 4,
            }]
        };

        config = {
            type: 'pie',
            data: data,
            options: {}
        };


        new Chart(
            document.getElementById('fuelTypesChart'),
            config
        );

        data = {
            labels: <?php echo json_encode($parsedMonthlyStats["months"]); ?>,
            datasets: [{
                label: 'Earnings',
                data: <?php echo json_encode($parsedMonthlyStats["totalProfit"]); ?>,
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        };

        config = {
            type: 'line',
            data: data
        }

        new Chart(
            document.getElementById('earningsChart'),
            config
        );
    </script>
</body>

</html>

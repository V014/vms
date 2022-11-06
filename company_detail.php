<?php
include_once "./includes/utils.php";
include_once "./includes/auth.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/user.php";

$authUser = Auth::getUser();
$company = Company::find($_GET["id"]);
$userDetail = User::find($company->userID);

$totalStats = totalStats($userDetail->id);
$monthlyStats = monthlyOrderStats($userDetail->id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - <?php echo $company->name; ?></title>
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
                    <!-- Profile Section -->
                    <section style="background-color: #eee;">
                        <div class="container py-5">
                            <!-- Breadcrumb Section -->
                            <div class="row">
                                <div class="col">
                                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                                        <?php
                                        if ($authUser->role === "admin") {
                                        ?>
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "admin_dashboard.php"; ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "company_list.php"; ?>">Companies</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        } elseif ($authUser->role === "company") {
                                        ?>
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "company_dashboard.php"; ?>">Home</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        }
                                        ?>
                                    </nav>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-body text-center">
                                            <img src="<?php echo $userDetail->profilePicture; ?>" alt="avatar" style="width: 150px;">
                                            <h5 class="my-3"><?php echo $company->name; ?></h5>
                                            <p class="text-muted mb-1"><?php echo $company->established; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Company Name</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $company->name; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Email</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $userDetail->email; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Phone</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $userDetail->phoneNumber; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card mb-4 mb-md-0">
                                                <div class="card-body">
                                                    <p class="mb-4"><span class="text-primary font-italic me-1"><a href="orders_list.php">orders</a></span> Order Status and Details</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="card shadow border-start-primary py-2">
                                        <div class="card-body">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col me-2">
                                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Orders</span></div>
                                                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo $totalStats["total_orders"]; ?></span></div>
                                                </div>
                                                <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="card shadow border-start-primary py-2">
                                        <div class="card-body">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col me-2">
                                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Spend</span></div>
                                                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo "K" . number_format($totalStats["total_cost"], 2); ?></span></div>
                                                </div>
                                                <div class="col-auto"><i class="fas fa-money-bill fa-2x text-gray-300"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="card shadow border-start-primary py-2">
                                        <div class="card-body">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col me-2">
                                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Deliveries Fulfilled</span></div>
                                                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo $totalStats["total_delivered"]; ?></span></div>
                                                </div>
                                                <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="card shadow border-start-primary py-2">
                                        <div class="card-body">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col me-2">
                                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Deliveries Pending</span></div>
                                                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo $totalStats["total_pending"]; ?></span></div>
                                                </div>
                                                <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Profile Section -->
                    <!-- Start Monthly Cost Chart -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">Monthly Order Cost</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="costChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Monthly Cost Chart -->
                <div class="row">
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
    <script>
        <?php $months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"]; ?>

        let data = {
            labels: <?php echo json_encode(array_map(function ($month) {
                        return ucfirst($month);
                    }, $months)); ?>,
            datasets: [{
                label: 'Monthly Order Spend (K)',
                data: <?php
                        echo json_encode(array_map(function ($month) {
                            global $monthlyStats;
                            return array_key_exists($month, $monthlyStats) ? $monthlyStats[$month]["total_cost"] : 0;
                        }, $months)); ?>,
                fill: true,
                borderColor: '<?php echo dynamicColor(); ?>',
                tension: 0.1
            }]
        };

        let config = {
            type: 'line',
            data: data,
        };

        new Chart(
            document.getElementById('costChart'),
            config
        );

        data = {
            labels: <?php echo json_encode(array_map(function ($month) {
                        return ucfirst($month);
                    }, $months)); ?>,
            datasets: [{
                label: 'Orders',
                data: <?php
                        echo json_encode(array_map(function ($month) {
                            global $monthlyStats;
                            return array_key_exists($month, $monthlyStats) ? $monthlyStats[$month]["total_orders"] : 0;
                        }, $months)); ?>,
                borderWidth: 1,
                backgroundColor: <?php echo json_encode(array_map(function () {
                                        return dynamicColor();
                                    }, $months)); ?>
            }]
        };

        config = {
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
            labels: ['Petrol', 'Diesel', 'Paraffin'],
            datasets: [{
                label: 'Fuel Orders',
                backgroundColor: ["<?php echo dynamicColor(); ?>", "<?php echo dynamicColor(); ?>", "<?php echo dynamicColor(); ?>"],
                data: [<?php echo $totalStats["total_petrol_orders"]; ?>, <?php echo $totalStats["total_diesel_orders"]; ?>, <?php echo $totalStats["total_paraffin_orders"]; ?>],
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
    </script>
</body>

</html>

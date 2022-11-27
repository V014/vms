<?php
include_once "./includes/utils.php";
include_once "./includes/auth.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/driver.php";
include_once "./includes/entity/order.php";
include_once "./includes/entity/user.php";
include_once "./includes/entity/vehicle.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["update"]) {
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order = Order::find($_POST["order_id"]);
    $order->delivered();

    redirect(BASE_DIR . "order_detail.php?id=" . $order->id);
}

$authUser = Auth::getUser();
$order = Order::find($_GET["id"]);

$company = Company::find($order->userID);
$companyUser = User::find($company->userID);

$driver = Driver::find($order->driverID);
$driverUser = User::find($driver->userID);

$vehicle = Vehicle::find($order->vehicleID);
$coords = getDriverCoords($order->id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Tracking <?php echo $company->name; ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <style>
        #map {
            height: 480px;
            width: 100%;
        }
    </style>
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
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "orders_list.php"; ?>">Orders</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        } elseif ($authUser->role === "company") {
                                        ?>
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "company_dashboard.php"; ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "user_orders.php"; ?>">Orders</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        } else {
                                        ?>
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "driver_dashboard.php"; ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "user_orders.php"; ?>">Orders</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        }
                                        ?>
                                    </nav>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div id="map"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-body text-center">
                                            <p>Company</p>
                                            <img src="<?php echo $companyUser->profilePicture; ?>" alt="avatar" style="width: 150px;">
                                            <h5 class="my-3"><?php echo $company->name; ?></h5>
                                        </div>
                                    </div>
                                    <?php if (isAssigned($order->id)) { ?>
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <p>Driver</p>
                                                <img src="<?php echo $driverUser->profilePicture; ?>" alt="avatar" style="width: 150px;">
                                                <h5 class="my-3"><?php echo $driver->firstName . " " . $driver->lastName; ?></h5>
                                                <p class="text-muted mb-1"><?php echo $driver->nationalID; ?></p>
                                            </div>
                                        </div>
                                    <?php } elseif (!isAssigned($order->id) && $authUser->role === "admin") { ?>
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <p>Driver</p>
                                                <a class="btn btn-primary" href="assign_order.php?id=<?php echo $order->id; ?>">Assign Driver</a>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <p>Driver</p>
                                                <p>Not assigned</p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Vehicle Registration Number</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $vehicle->registrationNo ? $vehicle->registrationNo : "N/A"; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Vehicle Make</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $vehicle->make ? $vehicle->make : "N/A"; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Order Date</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $order->orderDate; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <?php
                                                if ($order->status === 'pending' && $authUser->role === 'admin') {
                                                ?>
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Status</p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="text-muted mb-0"><?php echo ucfirst($order->status); ?></p>
                                                    </div>
                                                    <?php if (isAssigned($order->id)) { ?>
                                                        <div class="col-sm-3">
                                                            <form method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                                <input class="btn btn-primary" type="submit" value="MARK DELIVERED">
                                                            </form>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="col-sm-3"></div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Status</p>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted mb-0"><?php echo ucfirst($order->status); ?></p>
                                                    </div>
                                                <?php }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="card shadow border-start-primary py-2">
                                                <div class="card-body">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col me-2">
                                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Quantity</span></div>
                                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $order->quantity . "L"; ?></span></div>
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
                                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Cost</span></div>
                                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo "K" . number_format($order->cost); ?></span></div>
                                                        </div>
                                                        <div class="col-auto"><i class="fas fa-money-bill fa-2x text-gray-300"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Profile Section -->
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Brand 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script>
        const map = L.map('map').setView([<?php echo $coords["longitude"]; ?>, <?php echo $coords["latitude"]; ?>], 10);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const driverMarker = L.marker(
            [<?php echo $coords["longitude"]; ?>, <?php echo $coords["latitude"]; ?>],
            {
                title: '<?php echo $driver->firstName . ' ' . $driver->lastName; ?>',
            }
        ).addTo(map);

        const companyMarker = L.marker(
            [<?php echo $company->longitude; ?>, <?php echo $company->latitude; ?>],
            {
                title: '<?php echo $company->name; ?>',
            }
        ).addTo(map);

        const control = L.Routing.control({
            waypoints: [
                L.latLng(<?php echo $coords["longitude"]; ?>, <?php echo $coords["latitude"]; ?>),
                L.latLng(<?php echo $company->longitude; ?>, <?php echo $company->latitude; ?>)
            ]
        }).on('routesfound', routeHandler).addTo(map);

        function routeHandler(e) {
            e.routes[0].coordinates.forEach(function(coord, index) {
                setTimeout(() => {
                    driverMarker.setLatLng([coord.lat, coord.lng]);
                }, 2500 * index);
            });
        }
    </script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>

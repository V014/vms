<?php
include_once "./includes/utils.php";
include_once "./includes/entity/order.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/vehicle.php";
include_once "./includes/entity/driver.php";
include_once "./includes/entity/user.php";

// Retrieve id of the order to assign a driver and vehicle to
$order = Order::find($_GET["id"]);
$company = Company::find($order->userID);
$companyUser = User::find($company->userID);

/*
 * If post request, extract post data such as the order id and the id of the driver
 * The ids are used to create the order driver entry in the vms database. Ensuring
 * The order that the company has placed is given the correct driver to execute
 */
if ($_REQUEST["METHOD"] === "POST") {
    $orderID = $_POST["order_id"];
    $driverID = $_POST["driver_id"];
    $vehicleID = $_POST["vehicle_id"];
}

$drivers = Driver::all();
$vehicles = Vehicle::all();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Assign Order</title>
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
                    <div class="row">
                        <div class="col-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <p>Company</p>
                                    <img src="<?php echo $companyUser->profilePicture; ?>" alt="avatar" style="width: 150px;">
                                    <h5 class="my-3"><?php echo $company->name; ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Fuel Type</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo ucfirst($order->fuelName); ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
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
</body>

</html>

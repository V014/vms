<?php
include_once "./includes/utils.php";
include_once "./includes/entity/order.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/vehicle.php";
include_once "./includes/entity/driver.php";
include_once "./includes/entity/user.php";
include_once "./includes/entity/order_driver.php";

/*
 * If post request, extract post data such as the order id and the id of the driver
 * The ids are used to create the order driver entry in the vms database. Ensuring
 * The order that the company has placed is given the correct driver to execute
 */
if ($_REQUEST["METHOD"] === "POST") {
    $orderDriver = [
        "order_id" => $_POST["order_id"],
        "driver_id" => $_POST["driver_id"],
        "vehicle_id" => $_POST["vehicle_id"]
    ];

    OrderDriver::create($orderDriver);
    redirect(BASE_DIR . "order_detail.php?id=" . $_POST["order_id"]);
}

// Retrieve id of the order to assign a driver and vehicle to
$order = Order::find($_GET["id"]);
$company = Company::find($order->userID);
$companyUser = User::find($company->userID);


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
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Quantity</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $order->quantity; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Cost</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $order->cost; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Status</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo ucfirst($order->status); ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Order Date</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo ucfirst($order->orderDate); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Assign Driver & Vehicle</h6>
                                    <form method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                        <!-- Driver Selection -->
                                        <div class="mb-5">
                                            <label for="driver_id" class="form-label">Driver</label>
                                            <select name="driver_id" class="form-select">
                                                <?php
                                                foreach ($drivers as $driver) {
                                                ?>
                                                    <option value="<?php echo $driver->userID; ?>"><?php echo $driver->firstName . " " . $driver->lastName . " ( {$driver->nationalID} )"; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- Vehicle Selection -->
                                        <div class="mb-5">
                                            <label for="vehicle_id" class="form-label">Vehicle</label>
                                            <select name="vehicle_id" class="form-select">
                                                <?php
                                                foreach ($vehicles as $vehicle) {
                                                ?>
                                                    <option value="<?php echo $vehicle->id; ?>"><?php echo $vehicle->make . "( {$vehicle->registrationNo} )"; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary d-block btn-user w-100" type="submit">Assign</button>
                                    </form>
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

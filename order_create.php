<?php
include_once "./includes/utils.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/order.php";
include_once "./includes/entity/order_driver.php";
include_once "./includes/entity/fuel.php";
include_once "./includes/entity/vehicle.php";
include_once "./includes/entity/driver.php";
include_once "./includes/auth.php";

$user = Auth::getUser();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fuel = Fuel::find($_POST["type_id"]);

    if ($user->role === "admin") {
        $order = [
            "company_id" => $_POST["company_id"],
            "type_id" => $_POST["type_id"],
            "quantity" => $_POST["quantity"],
            "cost" => $fuel->cost * $_POST["quantity"],
            "status" => "pending",
            "order_date" => date("Y/m/d", strtotime("today")),
        ];

        $order = Order::create($order);

        $orderDriver = [
            "order_id" => $order->id,
            "vehicle_id" => $_POST["vehicle_id"],
            "driver_id" => $_POST["driver_id"]
        ];

        OrderDriver::create($orderDriver);

        redirect(BASE_DIR . "orders_list");
    } elseif ($user->role === "company") {
        $order = [
            "company_id" => $user->id,
            "type_id" => $_POST["type_id"],
            "quantity" => $_POST["quantity"],
            "cost" => $fuel->cost * $_POST["quantity"],
            "status" => "pending",
            "order_date" => date("Y/m/d", strtotime("today")),
        ];

        Order::create($order);

        redirect(BASE_DIR . "user_orders");
    }
}

$companies = Company::all();
$fuelTypes = Fuel::all();
$drivers = Driver::all();
$vehicles = Vehicle::all();

$costs = [];

foreach ($fuelTypes as $fuelType) {
    $costs[$fuelType->name] = $fuelType->cost;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Order Create</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <div class="row align-items-center px-5" style="width: 650px; margin: 0 auto;">
                        <div class="col-12">
                            <?php if ($user->role === "admin") { ?>
                                <h3 style="text-align: center;">Create Order For Company</h3>
                                <p style="text-align: center;">Provide the details of the company to add it to the system</p>
                                <form class="user" name="company" method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <!-- Company Selection -->
                                        <div class="mb-5">
                                            <label for="company_id" class="form-label">Company</label>
                                            <select name="company_id" class="form-select">
                                                <?php
                                                foreach ($companies as $company) {
                                                ?>
                                                    <option value="<?php echo $company->userID; ?>"><?php echo $company->name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- Fuel Selection -->
                                        <div class="mb-5">
                                            <label for="type_id" class="form-label">Fuel Type</label>
                                            <select name="type_id" class="form-select">
                                                <?php
                                                foreach ($fuelTypes as $fuelType) {
                                                ?>
                                                    <option value="<?php echo $fuelType->id; ?>"><?php echo ucfirst($fuelType->name) . " (K" . $fuelType->cost . ")"; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
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
                                        <div class="mb-5">
                                            <input class="form-control" type="number" max="30000" placeholder="Quantity in Litres" name="quantity">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Create Order</button>
                                    <hr>
                                </form>
                            <?php } elseif ($user->role === "company") { ?>
                                <h3 style="text-align: center;">Place Your Order</h3>
                                <p style="text-align: center;">Provide the details of your order</p>
                                <form class="user" name="company" method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <!-- Fuel Selection -->
                                        <div class="mb-5">
                                            <label for="type_id" class="form-label">Fuel Type</label>
                                            <select name="type_id" class="form-select">
                                                <?php
                                                foreach ($fuelTypes as $fuelType) {
                                                ?>
                                                    <option value="<?php echo $fuelType->id; ?>"><?php echo ucfirst($fuelType->name) . " (K" . $fuelType->cost . ")"; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <input class="form-control" type="number" max="30000" placeholder="Quantity in Litres" name="quantity">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Create Order</button>
                                    <hr>
                                </form>
                            <?php } ?>
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

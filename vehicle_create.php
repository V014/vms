<?php
include_once "./includes/utils.php";
include_once "./includes/entity/vehicle.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Vehicle::create($_POST);
    redirect(BASE_DIR . "vehicle_list.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Vehicle Create</title>
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
                    <div class="row align-items-center px-5" style="width: 650px; margin: 0 auto;">
                        <div class="col-12">
                            <h3 style="text-align: center;">Add Vehicle</h3>
                            <p style="text-align: center;">Provide the details of the vehicle to add it to the system</p>
                            <form class="user" name="vehicle" method="POST" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>">
                                <div class="row mb-3">
                                    <div class="mb-3 mb-sm-0">
                                        <input class="form-control form-control-user" type="text" id="exampleFirstName" placeholder="Registration Number" name="registration_no" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control form-control-user" type="text" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Make of Vehicle" name="make">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input class="form-control form-control-user" type="number" id="exampleFirstName" placeholder="Capacity" name="capacity" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-user" type="number" id="exampleFirstName" placeholder="Year" name="year" required>
                                    </div>
                                </div>
                                <button class="btn btn-primary d-block btn-user w-100" type="submit">Add Vehicle</button>
                                <hr>
                            </form>
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

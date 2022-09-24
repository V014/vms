<?php
include_once "./includes/utils.php";
include_once "./includes/entity/company.php";
include_once "./includes/entity/user.php";

$company = Company::find($_GET["id"]);
$userCompany = User::find($company->userID);

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
                                        if ($user->role === "admin") {
                                        ?>
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "admin_dashboard.php"; ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo BASE_DIR . "company_list.php"; ?>">Companies</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo $company->name; ?></li>
                                            </ol>
                                        <?php
                                        } elseif ($user->role === "company") {
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
                                            <img src="<?php echo $userCompany->profilePicture; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
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
                                                    <p class="text-muted mb-0"><?php echo $userCompany->email; ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Phone</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0"><?php echo $userCompany->phoneNumber; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card mb-4 mb-md-0">
                                                <div class="card-body">
                                                    <p class="mb-4"><span class="text-primary font-italic me-1"><a href="orders_list.php">orders</a></span> Order Status and Details
                                                    </p>
                                                    <p class="mb-1" style="font-size: .77rem;">Total Orders</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="mt-4 mb-1" style="font-size: .77rem;">Total Spend</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="mt-4 mb-1" style="font-size: .77rem;">Diesel Orders</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="mt-4 mb-1" style="font-size: .77rem;">Petrol Orders</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="mt-4 mb-1" style="font-size: .77rem;">Deliveries Fulfilled</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="mt-4 mb-1" style="font-size: .77rem;">Deliveries Pending</p>
                                                    <div class="progress rounded" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>

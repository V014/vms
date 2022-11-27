<?php
include_once "./includes/utils.php";
include_once "./includes/auth.php";

$user = Auth::getUser();
$orders = findUserOrders();
$trips = getTrips($user->id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>VMS - Order List</title>
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
                    <h3 class="text-dark mb-4">Orders</h3>
                    <p>Manage, Create and View Companies</p>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Order List</p>
                        </div>
                        <a href="order_create.php"><button type="button" class="btn btn-primary">Create Order</button></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-nowrap">
                                    <span><?php echo count($orders); ?> Orders</span>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end dataTables_filter" id="dataTable_filter"><label class="form-label"><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table table-primary my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Fuel Type</th>
                                            <th>Quantity (Litres)</th>
                                            <th>Cost</th>
                                            <th>Status</th>
                                            <th>Date Ordered</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Order List -->
                                        <?php
                                        foreach ($orders as $order) {
                                        ?>
                                            <tr>
                                                <td><?php echo $order->name; ?></td>
                                                <td><?php echo ucfirst($order->fuelName); ?></td>
                                                <td><?php echo $order->quantity . "L"; ?></td>
                                                <td><?php echo number_format($order->cost); ?></td>
                                                <td><?php echo ucfirst($order->status); ?></td>
                                                <td><?php echo $order->orderDate; ?></td>
                                                <td><a href="order_detail.php?id=<?php echo $order->id; ?>"><button type="button" class="btn btn-secondary">View</button></a></td>
                                                <?php if (!isAssigned($order->id)) { ?>
                                                    <td class="btn">Assignment Pending</td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                                <?php if (in_array($order->id, $trips)) { ?>
                                                    <td><a href="tracking.php?id=<?php echo $order->id; ?>"><button type="button" class="btn btn-primary">Tracking</button></a></td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>

                                            <td><strong>Company Name</strong></td>
                                            <td><strong>Fuel Type</strong></td>
                                            <td><strong>Quantity</strong></td>
                                            <td><strong>Cost</strong></td>
                                            <td><strong>Status</strong></td>
                                            <td><strong>Date Ordered</strong></td>
                                            <td><strong></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 27</p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            /* included footer here */
            <?php include "footer.php" ?>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>

<?php
include_once "./includes/auth.php";

$currentPage = $_SERVER['SCRIPT_NAME'];
$user = Auth::getUser();

switch ($user->role) {
    case 'admin': ?>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/admin_dashboard/", $currentPage) == 1 ? "active" : "" ?>" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/company_list/", $currentPage) == 1 ? "active" : "" ?>" href="company_list.php"><i class="fas fa-building"></i><span>Companies</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/driver_list/", $currentPage) == 1 ? "active" : "" ?>" href="driver_list.php"><i class="fas fa-car"></i><span>Drivers</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/orders_list/", $currentPage) == 1 ? "active" : "" ?>" href="orders_list.php"><i class="fas fa-truck"></i><span>Orders</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/vehicle_list/", $currentPage) == 1 ? "active" : "" ?>" href="vehicle_list.php"><i class="fas fa-truck"></i><span>Vehicles</span></a></li>
    <?php
        break;
    case 'driver': ?>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/driver_dashboard/", $currentPage) == 1 ? "active" : "" ?>" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/user_orders/", $currentPage) == 1 ? "active" : "" ?>" href="user_orders.php"><i class="fas fa-truck"></i><span>Orders</span></a></li>
    <?php
        break;
    case 'company':
    ?>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/company_dashboard/", $currentPage) == 1 ? "active" : "" ?>" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link <?php echo preg_match("/user_orders/", $currentPage) == 1 ? "active" : "" ?>" href="user_orders.php"><i class="fas fa-truck"></i><span>Orders</span></a></li>
<?php
        break;
}

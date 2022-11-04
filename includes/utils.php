<?php

include_once dirname(__FILE__) . "/auth.php";
include_once dirname(__FILE__) . "/entity/order.php";

const BASE_DIR = "http://localhost/vms/";
const ADMIN_DASHBOARD = BASE_DIR . "admin_dashboard.php";
const DRIVER_DASHBOARD = BASE_DIR . "driver_dashboard.php";
const COMPANY_DASHBOARD = BASE_DIR . "company_dashboard.php";

function dynamicColor()
{
    return 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')'; #using the inbuilt random function
}

function siteNav($bgColor = "bg-secondary", $txtColor = "text-white")
{
?>
    <nav class="navbar navbar-expand-lg <?php echo $bgColor; ?> text-uppercase fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand <?php echo $txtColor ?>" href="#page-top">VMS</a>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded <?php echo $txtColor ?>" href="index.php">Home</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded <?php echo $txtColor ?>" href="login.php">Log In</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded <?php echo $txtColor ?>" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php
}

/**
 * Redirects the browser to the given location
 */
function redirect($iocation)
{
    header("Location: {$iocation}");
    exit;
}

function uploadProfile()
{
    $uploadsDir = dirname(__FILE__) . "/../uploads/profiles/";
    $name = "";
    $tmpPath = "";
    $newPath = "";

    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0777, true)) {
            return null;
        }
    }

    if (isset($_FILES["profile_picture"]) && ($_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK)) {
        $name = basename($_FILES["profile_picture"]["name"]);
        $tmpPath  = $_FILES["profile_picture"]["tmp_name"];
        $newPath =  $uploadsDir . $name;
    } else {
        $name = basename($uploadsDir . "user-profile.png");
        $newPath = $uploadsDir . $name;
    }

    if (!file_exists($newPath)) {
        move_uploaded_file($tmpPath, $newPath);
    }

    return $name;
}

function formatParams($arr)
{
    $newArr = array();
    foreach ($arr as $key => $value) $newArr[":{$key}"] = $value;
    return $newArr;
}

function navActions()
{
    $user = Auth::getUser();
?>
    <ul class="navbar-nav flex-nowrap ms-auto">
        <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
            <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                <form class="me-auto navbar-search w-100">
                    <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                        <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                    <h6 class="dropdown-header">alerts center</h6><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="me-3">
                            <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                        </div>
                        <div><span class="small text-gray-500">December 12, 2019</span>
                            <p>A new monthly report is ready to download!</p>
                        </div>
                    </a><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="me-3">
                            <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                        </div>
                        <div><span class="small text-gray-500">December 7, 2019</span>
                            <p>$290.29 has been deposited into your account!</p>
                        </div>
                    </a><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="me-3">
                            <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                        </div>
                        <div><span class="small text-gray-500">December 2, 2019</span>
                            <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                        </div>
                    </a><a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter">7</span><i class="fas fa-envelope fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                    <h6 class="dropdown-header">alerts center</h6><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image me-3"><img class="rounded-circle" src="assets/img/avatars/avatar4.jpeg">
                            <div class="bg-success status-indicator"></div>
                        </div>
                        <div class="fw-bold">
                            <div class="text-truncate"><span>Hi there! I am wondering if you can help me with a problem I've been having.</span></div>
                            <p class="small text-gray-500 mb-0">Emily Fowler - 58m</p>
                        </div>
                    </a><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image me-3"><img class="rounded-circle" src="assets/img/avatars/avatar2.jpeg">
                            <div class="status-indicator"></div>
                        </div>
                        <div class="fw-bold">
                            <div class="text-truncate"><span>I have the photos that you ordered last month!</span></div>
                            <p class="small text-gray-500 mb-0">Jae Chun - 1d</p>
                        </div>
                    </a><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image me-3"><img class="rounded-circle" src="assets/img/avatars/avatar3.jpeg">
                            <div class="bg-warning status-indicator"></div>
                        </div>
                        <div class="fw-bold">
                            <div class="text-truncate"><span>Last month's report looks great, I am very happy with the progress so far, keep up the good work!</span></div>
                            <p class="small text-gray-500 mb-0">Morgan Alvarez - 2d</p>
                        </div>
                    </a><a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="dropdown-list-image me-3"><img class="rounded-circle" src="assets/img/avatars/avatar5.jpeg">
                            <div class="bg-success status-indicator"></div>
                        </div>
                        <div class="fw-bold">
                            <div class="text-truncate"><span>Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</span></div>
                            <p class="small text-gray-500 mb-0">Chicken the Dog · 2w</p>
                        </div>
                    </a><a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                </div>
            </div>
            <div class="shadow dropdown-list dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown"></div>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item dropdown no-arrow">
            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?php echo  ucfirst($user->username); ?></span><img class="border rounded-circle img-profile" src="<?php echo $user->profilePicture; ?>"></a>
                <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                    <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                </div>
            </div>
        </li>
    </ul>
<?php
}

function totalStats($id)
{
    $conn = DBConnection::getConnection();
    $user = User::find($id);
    $sql = '';

    switch ($user->role) {
        case 'company':
            $sql = "SELECT
                        SUM(o.quantity) AS total_quantity,
                        SUM(o.cost) AS total_cost,
                        (SELECT COUNT(*) FROM orders WHERE company_id = :total_orders_id) AS total_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 2 AND o.company_id = :diesel_id) AS total_diesel_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 3 AND o.company_id = :petrol_id) AS total_petrol_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 1 AND o.company_id = :paraffin_id) AS total_paraffin_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'delivered' AND o.company_id = :delivered_id) AS total_delivered,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'pending' AND o.company_id = :pending_id) AS total_pending
                    FROM order_driver AS od
                    LEFT JOIN orders AS o ON o.id = od.order_id
                    INNER JOIN companies AS c ON o.company_id = c.user_id
                    WHERE c.user_id = :id AND YEAR(o.order_date) > 2021";
            break;
        case 'driver':
            $sql = "SELECT
                        SUM(o.quantity) AS total_quantity,
                        SUM(o.cost) AS total_cost,
                        (SELECT COUNT(*) FROM orders WHERE company_id = :total_orders_id) AS total_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 2 AND od.driver_id = :diesel_id) AS total_diesel_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 3 AND od.driver_id = :petrol_id) AS total_petrol_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 1 AND od.driver_id = :paraffin_id) AS total_paraffin_orders,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'delivered' AND od.driver_id = :delivered_id) AS total_delivered,
                        (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'pending' AND od.driver_id = :pending_id) AS total_pending
                    FROM order_driver AS od
                    LEFT JOIN orders AS o ON o.id = od.order_id
                    INNER JOIN drivers AS d ON od.driver_id = d.user_id
                    WHERE d.user_id = :id AND YEAR(o.order_date) > 2021";
            break;
    }

    $sth = $conn->prepare($sql);
    $sth->execute([
        ':total_orders_id' => $id,
        ':diesel_id' => $id,
        ':petrol_id' => $id,
        ':paraffin_id' => $id,
        ':delivered_id' => $id,
        ':pending_id' => $id,
        ':id' => $id,
    ]);

    return $sth->fetch();
}

function monthlyOrderStats($id)
{
    $monthlyStats = [];
    $conn = DBConnection::getConnection();

    $user = User::find($id);
    $sql = '';

    switch ($user->role) {
        case 'company':
            $sql = "SELECT
                        MONTHNAME(o.order_date) AS month,
                        COUNT(*) AS total_orders,
                        SUM(o.cost) AS total_cost
                    FROM order_driver AS od
                    LEFT JOIN orders AS o ON o.id = od.order_id
                    INNER JOIN companies AS c ON c.user_id = o.company_id
                    WHERE YEAR(o.order_date) = 2022 AND
                    c.user_id = :id
                    GROUP BY MONTH(o.order_date)";
            break;
        case 'driver':
            $sql = "SELECT
                        MONTHNAME(o.order_date) AS month,
                        COUNT(*) AS total_orders,
                        SUM(o.cost) AS total_cost
                    FROM order_driver AS od
                    LEFT JOIN orders AS o ON o.id = od.order_id
                    INNER JOIN drivers AS d ON d.user_id = od.driver_id
                    WHERE YEAR(o.order_date) = 2022 AND
                    d.user_id = :id
                    GROUP BY MONTH(o.order_date)";
            break;
    }

    $sth = $conn->prepare($sql);
    $sth->execute([':id' => $id]);

    foreach ($sth->fetchAll() as $stat) {
        $monthlyStats[strtolower($stat["month"])] = [
            "total_orders" => $stat["total_orders"],
            "total_cost" => $stat["total_cost"],
        ];
    }

    return $monthlyStats;
}

function totalVehicleStats($id)
{
    $conn = DBConnection::getConnection();

    $sql = "SELECT
                SUM(o.quantity) AS total_quantity,
                SUM(o.cost) AS total_cost,
                (SELECT COUNT(*) FROM order_driver WHERE driver_id = 7) AS total_orders,
                (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 2 AND od.driver_id = 7) AS total_diesel_orders,
                (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 3 AND od.driver_id = 7) AS total_petrol_orders,
                (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.type_id = 1 AND od.driver_id = 7) AS total_paraffin_orders,
                (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'delivered' AND od.driver_id = 7) AS total_delivered,
                (SELECT COUNT(*) FROM order_driver AS od LEFT JOIN orders AS o ON o.id = od.order_id WHERE o.status = 'pending' AND od.driver_id = 7) AS total_pending
            FROM order_driver AS od
            LEFT JOIN orders AS o ON o.id = od.order_id
            INNER JOIN drivers AS d ON od.driver_id = d.id
            WHERE od.driver_id = 7 AND YEAR(o.order_date) > 2021";

    $sth = $conn->prepare($sql);
    $sth->execute([
        ':total_orders_id' => $id,
        ':diesel_id' => $id,
        ':petrol_id' => $id,
        ':paraffin_id' => $id,
        ':delivered_id' => $id,
        ':pending_id' => $id,
        ':id' => $id,
    ]);

    return $sth->fetch();
}

/**
 * Returns a boolean result after querying the database checking if the order
 * has been assigned a driver and vehicle
 */
function isAssigned($id)
{
    $conn = DBConnection::getConnection();

    // Query to find the id of the order in the order_driver table, if found
    // then order has been assigned driver otherwise it has not
    $sql = "SELECT
                o.id
            FROM order_driver AS od
            INNER JOIN orders AS o ON o.id = od.order_id
            WHERE o.id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();

    if (!is_array($result)) {
        return false;
    }

    return true;
}

/**
 * Retrieves orders for the logged in user, based on role will either return
 * orders for drivers or company
 */
function findUserOrders()
{
    $conn = DBConnection::getConnection();
    $orders = [];
    $sql = "";
    $user = Auth::getUser();

    switch ($user->role) {
        case 'company':
            $sql = "SELECT
                        o.id,
                        c.id AS company_id,
                        c.name,
                        ST_X(c.location) AS longitude,
                        ST_Y(c.location) AS latitude,
                        ft.name AS fuel_name,
                        o.quantity,
                        o.cost,
                        o.status,
                        o.order_date,
                        od.driver_id,
                        od.vehicle_id
                    FROM orders AS o
                    INNER JOIN companies AS c ON c.user_id = o.company_id
                    INNER JOIN fuel_types AS ft ON ft.id = o.type_id
                    LEFT JOIN order_driver AS od ON od.order_id = o.id
                    WHERE c.user_id = :id";
            break;
        case 'driver':
            $sql = "";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([":id" => $user->id]);
    $result = $stmt->fetchAll();

    foreach ($result as $order) {
        $orders[] = new Order($order);
    }

    return $orders;
}

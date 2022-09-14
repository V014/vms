<?php

const BASE_DIR = "http://localhost/vms/";
const ADMIN_DASHBOARD = BASE_DIR . "admin_dashboard.php";
const DRIVER_DASHBOARD = BASE_DIR . "driver_dashboard.php";
const COMPANY_DASHBOARD = BASE_DIR . "company_dashboard.php";

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
    $uploadsDir = dirname(__FILE__) . "./public/uploads/profiles/";
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

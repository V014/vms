<?php

include_once dirname(__FILE__) . "/auth.php";

const BASE_DIR = "http://localhost/vms/";
const ADMIN_DASHBOARD = BASE_DIR . "admin_dashboard.php";
const DRIVER_DASHBOARD = BASE_DIR . "driver_dashboard.php";
const COMPANY_DASHBOARD = BASE_DIR . "company_dashboard.php";

function rndRGBColorCode()
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

<?php
include_once "./includes/connection.php";
include_once "./includes/auth.php";


DBConnection::init();

if (!Auth::isLoggedIn()) {
    redirect(BASE_DIR . "home.php");
}

$user = Auth::getUser();

switch ($user->role) {
    case 'admin':
        redirect(ADMIN_DASHBOARD);
        break;
    case 'driver':
        redirect(DRIVER_DASHBOARD);
        break;
    case 'company':
        redirect(COMPANY_DASHBOARD);
        break;
}

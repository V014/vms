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
        # code...
        break;
    case 'driver':
        # code...
        break;
    case 'company':
        # code...
        break;
}

<?php
include_once "./includes/connection.php";
include_once "./includes/auth.php";


DBConnection::init();

if (!Auth::isLoggedIn()) {
    redirect(BASE_DIR . "home.php");
}

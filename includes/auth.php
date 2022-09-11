<?php

declare(strict_types=1);

session_start();

include_once dirname(__FILE__) . "/dbconnection.php";
include_once dirname(__FILE__) . "/validator.php";
include_once dirname(__FILE__) . "/utils.php";

class Auth
{
    /**
     * Logs the admin in and sets the approapriate session
     * variables on
     */
    public static function login($credentials)
    {
        $connection = DBConnection::getConnection();
        $username = $credentials["username"];
        $password = $credentials["password"];

        $sql = "SELECT 	id, username, password FROM users AS u WHERE u.username = :username";
        $sth = $connection->prepare($sql);
        $sth->execute([":username" => $username]);
        $user = $sth->fetch();

        if (!$user) {
            setcookie("errors", "Username/Password Incorrect");
            redirect("http://localhost:8080/vms/user_login.html.php");
        }

        if (password_verify($password, $user["password"])) {
            $_SESSION["id"] = $user["id"];
            setcookie("message", "Login Successful");
            redirect("http://localhost:8080/vms/user.html.php");
        } else {
            setcookie("errors", "Username/Password Incorrect");
            redirect("http://localhost:8080/vms/user_login.html.php");
        }
    }

    /**
     * Logs the logged in user out of the system by unsetting some of
     * the session variables
     */
    public static function logout()
    {
        if (self::isLoggedIn()) {
            unset($_SESSION["id"]);
            setcookie("message", "Logout successful");
        }

        redirect("http://localhost:8080/vms/");
    }

    /**
     * Check if a user is currently logged into the system
     */
    public static function isLoggedIn()
    {
        if (isset($_SESSION["id"])) {
            return true;
        }

        return false;
    }

    /**
     * Return the role of the logged in user, could be admin, farmer, supplier
     */
    public static function role()
    {
        if (isset($_SESSION["id"])) {
            $user = User::find($_SESSION["id"]);
            $user->role;
        }

        return null;
    }

    /**
     * Create the new user with the given credentials and add them
     * into the database, return an array of invalid fields on error
     */
    public static function register($userCredentials)
    {
        //TODO: Implement register
        $errors = [];

        $connection = DBConnection::getConnection();
        $connection->beginTransaction();
        $profilePicture = uploadProfile();

        if (!$profilePicture) {
            $errors["profile_picture"] = "Failed to upload profile picture";
        }

        if (!FormValidator::isUnique($_POST["username"], "username", "users")) {
            $errors["username"] = "Username is not unique";
        }

        if (!FormValidator::isUnique($_POST["email"], "email", "users")) {
            $errors["email"] = "Email is not unique";
        }

        if (!FormValidator::isUnique($_POST["phone_number"], "phone_number", "users")) {
            $errors["phone_number"] = "Phone number is not unique";
        }

        if (empty($errors)) {
            $user = User::create(array_merge($_POST, ["profile_picture" => $profilePicture]));
            $id = $user->id;

            if ($_POST["role"] === "company") {
                Company::create(["user_id" => $id]);
            } else if ($_POST["role"] === "driver") {
                Driver::create(["user_id" => $id]);
            }

            $connection->commit();
            $_SESSION["id"] = $user->id;
            $_SESSION["role"] = $user->role;
            setcookie("message", "Registration Successful");
            redirect("http://localhost:8080/vms/");
        }

        return $errors;
    }

    /**
     * Returns an instance of the user model of the currently
     * logged in user
     */
    public static function getUser()
    {
        return User::find($_SESSION["id"]);
    }
}
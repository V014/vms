<?php

declare(strict_types=1);

session_start();

include_once dirname(__FILE__) . "/dbconnection.php";
include_once dirname(__FILE__) . "/models/user.php";
include_once dirname(__FILE__) . "/helpers.php";

class Auth
{
    /**
     * Logs the admin in and sets the approapriate session
     * variables on
     */
    public static function adminLogin(array $credentials)
    {
        $connection = DBConnection::getConnection();
        $username = $credentials["username"];
        $password = $credentials["password"];

        $sql = "SELECT 	id, username, password FROM admins AS a WHERE a.username = :username";
        $sth = $connection->prepare($sql);
        $sth->execute([":username" => $username]);
        $admin = $sth->fetch();

        if (!$admin) {
            setcookie("errors", "Username/Password Incorrect");
            redirectTo("http://localhost:8080/farmersconnect/public/admin_login.html.php");
        }

        if (password_verify($password, $admin["password"])) {
            $_SESSION["id"] = $admin["id"];
            $_SESSION["role"] = "admin";
            setcookie("message", "Login Successful");
            redirectTo("http://localhost:8080/farmersconnect/public/admin.html.php");
        } else {
            setcookie("errors", "Username/Password Incorrect");
            redirectTo("http://localhost:8080/farmersconnect/public/admin_login.html.php");
        }
    }

    /**
     * Logs the user into their account if the credentials
     * they've provided much a given user within the
     * database
     */
    public static function userLogin(array $credentials)
    {
        $connection = DBConnection::getConnection();
        $username = $credentials["username"];
        $password = $credentials["password"];

        $sql = "SELECT * FROM users AS u WHERE u.username = :username";
        $sth = $connection->prepare($sql);

        $sth->execute([":username" => $username]);
        $user = $sth->fetch();

        if (!$user) {
            setcookie("errors", "Username/Password Incorrect");
            redirectTo("http://localhost:8080/farmersconnect/public/login.html.php");
        }

        if (password_verify($password, $user["password"])) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            setcookie("message", "Login Successful");
            redirectTo("http://localhost:8080/farmersconnect/public/profile.html.php?id={$user['id']}");
        } else {
            setcookie("errors", "Username/Password Incorrect");
            redirectTo("http://localhost:8080/farmersconnect/public/login.html.php");
        }
    }

    /**
     * Logs the logged in user out of the system by unsetting some of
     * the session variables
     */
    public static function logout(): void
    {
        if (self::isLoggedIn()) {
            unset($_SESSION["id"]);
            unset($_SESSION["role"]);
            setcookie("message", "Logout successful");
        }

        redirectTo("http://localhost:8080/farmersconnect/public/");
    }

    /**
     * Check if their is a user currently logged into the system
     */
    public static function isLoggedIn(): bool
    {
        if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {
            return true;
        }

        return false;
    }

    /**
     * Return the role of the logged in user, could be admin, farmer, supplier
     */
    public static function role(): ?string
    {
        if (isset($_SESSION["role"])) {
            return $_SESSION["role"];
        }

        return null;
    }

    /**
     * Create the new user with the given credentials and add them
     * into the database, return an array of invalid fields on error
     */
    public static function register(array $user): array
    {
        //TODO: Implement register
        $errors = [];

        $connection = DBConnection::getConnection();
        $connection->beginTransaction();
        $profilePicture = uploadProfilePicture();

        if (!$profilePicture) {
            $errors["profile_picture"] = "Failed to upload profile picture";
        }

        if (!Validator::uniqueUsername($_POST["username"])) {
            $errors["username"] = "Username is not unique";
        }

        if (!Validator::uniqueEmail($_POST["email"])) {
            $errors["email"] = "Email is not unique";
        }

        if (!Validator::uniquePhoneNumber($_POST["phone_number"])) {
            $errors["phone_number"] = "Phone number is not unique";
        }

        if (empty($errors)) {
            $user = User::create(array_merge($_POST, ["profile_picture" => $profilePicture]));
            $id = $user->id;

            if ($_POST["role"] === "supplier") {
                Supplier::create(["user_id" => $id]);
            } else if ($_POST["role"] === "farmer") {
                Farmer::create(["user_id" => $id]);
            }

            $connection->commit();
            $_SESSION["id"] = $user->id;
            $_SESSION["role"] = $user->role;
            setcookie("message", "Registration Successful");
            redirectTo("http://localhost:8080/farmersconnect/public/profile.html.php?id={$id}");
        }

        return $errors;
    }

    /**
     * Returns an instance of the user model of the currently
     * logged in user
     */
    public static function getUser(): User
    {
        return User::find($_SESSION["id"]);
    }
}

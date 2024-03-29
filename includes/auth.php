<?php

session_start();

include_once dirname(__FILE__) . "/connection.php";
include_once dirname(__FILE__) . "/utils.php";
include_once dirname(__FILE__) . "/entity/user.php";

class Auth
{
    /**
     * Log the user into the system redirecting them to their respective views based on their roles
     */
    public static function login($credentials)
    {
        $connection = DBConnection::getConnection();
        $email = $credentials["email"];
        $password = $credentials["password"];

        $sql = "SELECT 	id, email, role, password FROM users AS u WHERE u.email = :email";
        $sth = $connection->prepare($sql);
        $sth->execute([":email" => $email]);
        $user = $sth->fetch();

        if (!$user) {
            setcookie("errors", "Email/Password Incorrect");
            redirect(BASE_DIR . "login.php");
        }

        if (password_verify($password, $user["password"])) {
            $_SESSION["id"] = $user["id"];

            switch ($user["role"]) {
                case 'admin':
                    setcookie("message", "Login Successful");
                    redirect(ADMIN_DASHBOARD);
                    break;
                case 'company':
                    setcookie("message", "Login Successful");
                    redirect(COMPANY_DASHBOARD);
                    break;
                case 'driver':
                    setcookie("message", "Login Successful");
                    redirect(DRIVER_DASHBOARD);
                    break;
                default:
                    setcookie("errors", "Username/Password Incorrect");
                    redirect(BASE_DIR . "login.php");
                    break;
            }
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

        redirect(BASE_DIR);
    }

    /**
     * Check if a user is currently logged into the system
     */
    public static function isLoggedIn()
    {
        if (!isset($_SESSION["id"])) {
            return false;
        }

        return true;
    }

    /**
     * Create the new user with the given credentials and add them
     * into the database, return an array of invalid fields on error
     */
    public static function register($credentials)
    {
        $connection = DBConnection::getConnection();
        $connection->beginTransaction();
        $profilePicture = uploadProfile();

        $user = User::create(array_merge($_POST, ["profile_picture" => $profilePicture]));
        $id = $user->id;

        if ($_POST["role"] === "company") {
            Company::create(["user_id" => $id]);
        } else if ($_POST["role"] === "driver") {
            //TODO: Touch up driver
            Driver::create(["user_id" => $id]);
        }

        $connection->commit();
        $_SESSION["id"] = $user->id;
        setcookie("message", "Registration Successful");
        redirect(BASE_DIR);
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

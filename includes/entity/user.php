<?php

declare(strict_types=1);

include_once dirname(__FILE__) . "/../connection.php";
include_once dirname(__FILE__) . "/../utils.php";

class User
{
    const TABLE = "users";
    const COLUMNS = "u.id, u.username, u.profile_picture, u.created_at as date_joined, u.phone_number, u.email, u.role";
    const INSERT_COLS = "username, password, profile_picture, phone_number, email, role, created_at";
    const PLACEHOLDERS = ":username, :password, :profile_picture, :phone_number, :email, :role, :created_at";

    public $id;
    public $username;
    public $profilePicture;
    public $phoneNumber;
    public $email;
    public $role;
    public $createdAt;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->username = $data["username"];
        $this->profilePicture = $data["profile_picture"];
        $this->phoneNumber = $data["phone_number"];
        $this->email = $data["email"];
        $this->role = $data["role"];
        $this->createdAt = $data["date_joined"];
    }

    /**
     * Retrieve user by id
     */
    public static function find($id)
    {
        $table = self::TABLE;
        $columns = self::COLUMNS;
        $connection = DBConnection::getConnection();
        $sth = $connection->prepare("SELECT {$columns} FROM {$table} AS u WHERE u.id = :id");

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return new User($result);
    }

    /**
     * Creates the user in the database and returns an instance
     * of the User class with the data filled in from the matching
     * row
     */
    public static function create($user)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($user);
        $params[":password"] = password_hash($user['password'], PASSWORD_DEFAULT);
        $profilePicture = $user["profile_picture"];
        $params[":profile_picture"] = "uploads/profiles/$profilePicture";

        if (!array_key_exists(":created_at", $params)) {
            $params[":created_at"] = date("Y/m/d", strtotime("today"));
        }

        $userTable = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $query = "INSERT INTO {$userTable} ({$insertCols}) VALUES ({$placeholders})";
        $sth = $connection->prepare($query);
        unset($params[':password_repeat']);
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }

        return self::find($connection->lastInsertId());
    }

    public function delete()
    {
        $connection = DBConnection::getConnection();
        $params = formatParams(["id" => $this->id]);

        $userTable = self::TABLE;
        $sql = "DELETE FROM {$userTable} WHERE id = :id";
        $sth = $connection->prepare($sql);
        $sth->execute($params);
    }

    public static function findByRole($role = null)
    {
        $users = [];
        $query = "";
        $columns = self::COLUMNS;
        switch ($role) {
            case 'driver':
                $query = "SELECT {$columns} FROM users WHERE role = 'driver'";
                break;
            case 'company':
                $query = "SELECT {$columns} FROM users WHERE role = 'company'";
                break;
            default:
                $query = "SELECT {$columns} FROM users WHERE role != 'admin'";
                break;
        }

        $connection = DBConnection::getConnection();
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $user) {
                $users[] = new User($user);
            }
        }

        return $users;
    }

    public static function type($role)
    {
    }

    public static function all()
    {
    }
}

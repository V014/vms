<?php

declare(strict_types=1);

include_once dirname(__FILE__) . "/../dbconnection.php";
include_once dirname(__FILE__) . "/../utils.php";

class User
{
    const TABLE = "users";
    const COLUMNS = "u.id, u.username, u.about, u.profile_picture, u.created_at as date_joined, u.phone_number, u.email, u.role";
    const INSERT_COLS = "username, about, password, profile_picture, phone_number, email, role, created_at";
    const PLACEHOLDERS = ":username, :about, :password, :profile_picture, :phone_number, :email, :role, :created_at";

    public $id;
    public $username;
    public $profilePicture;
    public $phoneNumber;
    public $email;
    public $role;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->username = $data["username"];
        $this->profilePicture = $data["profile_picture"];
        $this->phoneNumber = $data["phone_number"];
        $this->email = $data["email"];
        $this->role = $data["role"];
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
        $params[":profile_picture"] = "./uploads/profiles/$profilePicture";

        if (!array_key_exists(":created_at", $params)) {
            $params[":created_at"] = date("Y/m/d", strtotime("today"));
        }

        $userTable = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $sth = $connection->prepare("INSERT INTO {$userTable} ({$insertCols}) VALUES ({$placeholders})");
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
}

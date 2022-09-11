<?php

class Company
{
    const TABLE = "companies";
    const COLUMNS = "u.username, u.profile_picture, u.created_at, u.phone_number, u.email, c.name, c.established, c.location";

    public $username;
    public $profile_picture;
    public $created_at;
    public $phone_number;
    public $email;
    public $name;
    public $established;
    public $location;

    public function __construct($data)
    {
        $this->username;
        $this->profile_picture;
        $this->created_at;
        $this->phone_number;
        $this->email;
        $this->name;
        $this->established;
        $this->location;
    }

    public static function byUserID($userID)
    {
        $table = self::TABLE;
        $columns = self::COLUMNS;
        $query = "SELECT {$columns} FROM {$table} INNER JOIN users AS u ON u.id = c.user_id WHERE u.id = :id";
        $connection = DBConnection::getConnection();
        $sth = $connection->prepare($query);

        if (!$sth->execute([":id" => $userID])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return new Company($result);
    }
}

<?php

class Driver
{
    const TABLE = "drivers";
    const COLUMNS = "user_id, national_id, dob, first_name, last_name";

    public $userID;
    public $nationalID;
    public $dob;
    public $firstName;
    public $lastName;

    public function __construct($data)
    {
        $this->userID = $data["userID"];
        $this->nationalID = $data["nationalID"];
        $this->dob = $data["dob"];
        $this->firstName = $data["firstName"];
        $this->lastName = $data["lastName"];
    }

    public static function create($data)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($data);

        $driverTable = self::TABLE;
        $columns = self::COLUMNS;

        $sth = $connection->prepare("INSERT INTO {$driverTable} ({$columns}) VALUES (:user_id, :national_id, :dob, :firstname, :lastname)");
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }

        return self::find($connection->lastInsertId());
    }

    public static function find($id)
    {
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;
        $query = "SELECT {$columns} FROM {$table} AS d INNER JOIN users AS u ON u.id = :id";
        $sth = $connection->prepare($query);

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return new Driver($result);
    }
}

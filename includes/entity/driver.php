<?php

class Driver
{
    const TABLE = "drivers";
    const COLUMNS = "u.id, d.first_name, d.last_name, d.dob, d.national_id";

    public $userID;
    public $nationalID;
    public $dob;
    public $firstName;
    public $lastName;

    public function __construct($data)
    {
        $this->userID = $data["id"];
        $this->nationalID = $data["national_id"];
        $this->dob = $data["dob"];
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
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

    public static function all()
    {
        $drivers = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM users AS u INNER JOIN drivers AS d ON d.user_id = u.id";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $driver) {
                $drivers[] = new Driver($driver);
            }
        }

        return $drivers;
    }
}

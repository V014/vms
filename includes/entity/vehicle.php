<?php

class Vehicle
{
    const TABLE = "vehicles";
    const COLUMNS = "id, registration_no, make, year, capacity";

    public $id;
    public $registrationNo;
    public $make;
    public $year;
    public $capacity;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->registrationNo = $data["registration_no"];
        $this->make = $data["make"];
        $this->year = $data["year"];
        $this->capacity = $data["capacity"];
    }


    public static function all()
    {
        $vehicles = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table}";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $vehicle) {
                $vehicles[] = new Vehicle($vehicle);
            }
        }

        return $vehicles;
    }

    public static function find($id)
    {
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table} WHERE id = :id";
        $sth = $connection->prepare($query);

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        return new Vehicle($sth->fetch());
    }

    public function history()
    {
        $connection = DBConnection::getConnection();
        $query = "SELECT * FROM vehicles AS v INNER JOIN order_driver AS od INNER JOIN drivers AS d ON od.driver_id = d.user_id INNER JOIN orders AS o ON o.id = od.order_id WHERE v.id = :id";
        $sth = $connection->prepare($query);
        $sth->execute([":id" => $this->id]);
        return $sth->fetchAll();
    }
}

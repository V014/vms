<?php

class Vehicle
{
    const TABLE = "vehicles";
    const COLUMNS = "id, registration_no, make, year";

    public $id;
    public $registrationNo;
    public $make;
    public $year;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->registrationNo = $data["registration_no"];
        $this->make = $data["make"];
        $this->year = $data["year"];
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
}

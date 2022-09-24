<?php

class Fuel
{
    const TABLE = "fuel_types";
    const COLUMNS = "id, name, cost_per_litre";

    public $id;
    public $name;
    public $cost;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->cost = $data["cost_per_litre"];
    }

    public static function costs()
    {
    }

    public static function type($type)
    {
    }

    public static function all()
    {
        $fuelTypes = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table}";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $fuelType) {
                $fuelTypes[] = new Fuel($fuelType);
            }
        }

        return $fuelTypes;
    }

    public static function find($id)
    {
        $table = self::TABLE;
        $columns = self::COLUMNS;
        $connection = DBConnection::getConnection();
        $sth = $connection->prepare("SELECT {$columns} FROM {$table} WHERE id = :id");

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return new Fuel($result);
    }
}

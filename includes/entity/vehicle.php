<?php

class Vehicle
{
    const TABLE = "vehicles";
    const COLUMNS = "id, registration_no, make, year, capacity";
    const INSERT_COLS = "registration_no, make, year, capacity";
    const PLACEHOLDERS = ":registration_no, :make, :year, :capacity";

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

    public static function create($vehicle)
    {
        $conn = DBConnection::getConnection();
        $params = formatParams($vehicle);
        $vehicleTable = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $sql = "INSERT INTO {$vehicleTable} ({$insertCols}) VALUES ({$placeholders})";

        $sth = $conn->prepare($sql);
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }

        return self::find($conn->lastInsertId());
    }

    public function history()
    {
        $connection = DBConnection::getConnection();
        $query = "SELECT * FROM vehicles AS v INNER JOIN order_driver AS od ON od.vehicle_id = v.id INNER JOIN drivers AS d ON od.driver_id = d.user_id INNER JOIN orders AS o ON o.id = od.order_id WHERE v.id = :id";
        $sth = $connection->prepare($query);
        $sth->execute([":id" => $this->id]);
        return $sth->fetchAll();
    }

    public function stats()
    {
        $connection = DBConnection::getConnection();
        $query = "SELECT
                    COUNT(o.id) AS total_orders,
                    SUM(o.cost) AS total_profit,
                    (SELECT SUM(o.quantity) FROM order_driver AS od INNER JOIN orders AS o ON o.id = od.order_id WHERE od.vehicle_id = :total_quantity) AS total_quantity,
                    (SELECT COUNT(*) FROM order_driver INNER JOIN orders ON orders.id = order_driver.order_id WHERE orders.type_id = 2 AND order_driver.vehicle_id = :diesel_deliveries) AS diesel_deliveries,
                    (SELECT COUNT(*) FROM order_driver INNER JOIN orders ON orders.id = order_driver.order_id WHERE orders.type_id = 3 AND order_driver.vehicle_id = :petrol_deliveries) AS petrol_deliveries
                  FROM vehicles AS v INNER JOIN order_driver AS od ON od.vehicle_id = v.id INNER JOIN drivers AS d ON d.user_id = od.driver_id INNER JOIN orders AS o ON o.id = od.order_id WHERE v.id = :vehicle_id";

        $sth = $connection->prepare($query);
        $sth->execute([
            ":diesel_deliveries" => $this->id,
            ":petrol_deliveries" => $this->id,
            ":vehicle_id" => $this->id,
            ":total_quantity" => $this->id
        ]);
        return $sth->fetch();
    }
}

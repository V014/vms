<?php

class Driver
{
    const TABLE = "drivers";
    const COLUMNS = "u.id, d.first_name, d.last_name, d.dob, d.national_id";
    const INSERT_COLS = "user_id, national_id, dob, first_name, last_name";

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
        $insertCols = self::INSERT_COLS;

        $query = "INSERT INTO {$driverTable} ({$insertCols}) VALUES (:user_id, :national_id, :dob, :first_name, :last_name)";
        $sth = $connection->prepare($query);
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
        $query = "SELECT {$columns} FROM {$table} AS d INNER JOIN users AS u ON u.id = d.user_id WHERE u.id = :id";
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

    public function details()
    {
        $connection = DBConnection::getConnection();
        $query = "SELECT
                    COUNT(o.id) AS deliveries,
                    (SELECT COUNT(*) FROM order_driver LEFT JOIN orders ON orders.id = order_driver.order_id WHERE orders.status = 'delivered' AND order_driver.driver_id = :delivered_id) AS delivered,
                    (SELECT COUNT(*) FROM order_driver LEFT JOIN orders ON orders.id = order_driver.order_id WHERE orders.status = 'pending' AND order_driver.driver_id = :pending_id) AS pending,
                    SUM(o.quantity) AS quantity_delivered
                 FROM drivers AS d
                 LEFT JOIN order_driver AS od ON od.driver_id = d.user_id
                 LEFT JOIN orders AS o ON o.id = od.order_id
                 WHERE d.user_id = :user_id GROUP BY d.user_id";
        $sth = $connection->prepare($query);

        if (!$sth->execute(["user_id" => $this->userID, ":delivered_id" => $this->userID, ":pending_id" => $this->userID])) {
            return null;
        }

        $result = $sth->fetch();
        return $result;
    }
}

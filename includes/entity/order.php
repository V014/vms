<?php

include_once dirname(__FILE__) . "/../connection.php";
include_once dirname(__FILE__) . "/../utils.php";
class Order
{
    const TABLE = "orders";
    const COLUMNS = "o.id, c.user_id AS company_id, c.name, ST_X(c.location) AS longitude, ST_Y(c.location) AS latitude, f.name AS fuel_name, o.quantity, o.cost, o.status, o.order_date, od.driver_id AS driver_id, od.vehicle_id";
    const INSERT_COLS = "company_id, type_id, quantity, cost, status, order_date";
    const PLACEHOLDERS = ":company_id, :type_id, :quantity, :cost, :status, :order_date";

    public $id;
    public $userID;
    public $name;
    public $longitude;
    public $latitude;
    public $fuelName;
    public $quantity;
    public $cost;
    public $status;
    public $orderDate;
    public $driverID;
    public $vehicleID;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->userID = $data["company_id"];
        $this->name = $data["name"];
        $this->longitude = $data["longitude"];
        $this->latitude = $data["latitude"];
        $this->fuelName = $data["fuel_name"];
        $this->quantity = $data["quantity"];
        $this->cost = $data["cost"];
        $this->status = $data["status"];
        $this->orderDate = $data["order_date"];
        $this->driverID = $data["driver_id"];
        $this->vehicleID = $data["vehicle_id"];
    }

    /**
     * Creates the order in the database and returns an instance
     * of the Order class with the data filled in from the matching
     * row
     */
    public static function create($order)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($order);
        $table = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $query = "INSERT INTO {$table} ({$insertCols}) VALUES ({$placeholders})";
        $sth = $connection->prepare($query);
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }

        return self::find($connection->lastInsertId());
    }

    public static function find($id)
    {
        $table = self::TABLE;
        $columns = self::COLUMNS;
        $connection = DBConnection::getConnection();
        $query = "SELECT {$columns} FROM {$table} AS o INNER JOIN companies AS c ON c.user_id = o.company_id INNER JOIN fuel_types AS f ON f.id = o.type_id LEFT JOIN order_driver AS od ON od.order_id = o.id WHERE o.id = :id";
        $sth = $connection->prepare($query);

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        return new Order($sth->fetch());
    }

    /*
    Returns all the orders made by companies so far
     */
    public static function all()
    {
        $orders = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table} AS o INNER JOIN companies AS c ON c.user_id = o.company_id INNER JOIN fuel_types AS f ON f.id = o.type_id LEFT JOIN order_driver AS od ON od.order_id = o.id ORDER BY o.order_date DESC";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $order) {
                $orders[] = new Order($order);
            }
        }

        return $orders;
    }

    public static function byCompany($id)
    {
        $orders = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table} INNER JOIN companies AS c ON c.id = o.company_id INNER JOIN fuel_types AS f ON f.id = f.type_id WHERE o.company_id = :id";
        $sth = $connection->prepare($query);

        if ($sth->execute([":id" => $id])) {
            foreach ($sth->fetchAll() as $order) {
                $orders[] = new Order($order);
            }
        }

        return $orders;
    }

    public function company()
    {
    }

    public static function count($type = null)
    {
        $connection = DBConnection::getConnection();
        $orderTable = self::TABLE;

        switch ($type) {
            case 'delivered':
                $sql = "SELECT COUNT(*) AS total FROM {$orderTable} AS o WHERE o.status = '{$type}'";
                break;
            case 'pending':
                $sql = "SELECT COUNT(*) AS total FROM {$orderTable} AS o WHERE o.status = '{$type}'";
                break;
            default:
                $sql = "SELECT COUNT(*) AS total FROM {$orderTable} AS o WHERE o.status = '{$type}'";
                break;
        }

        $sth = $connection->prepare($sql);

        if (!$sth->execute()) {
            return null;
        }

        return $sth->fetch()["total"];
    }

    public static function sum()
    {
    }

    public static function totalStats()
    {
        $connection = DBConnection::getConnection();
        $sql = "SELECT
                    (SELECT
                        COUNT(*)
                        FROM orders AS o
                        INNER JOIN fuel_types AS ft ON o.type_id = ft.id WHERE ft.name = 'petrol') AS total_petrol_orders,
                        (SELECT
                        COUNT(*)
                        FROM orders AS o
                        INNER JOIN fuel_types AS ft ON o.type_id = ft.id WHERE ft.name = 'diesel') AS total_diesel_orders,
                        SUM(o.quantity) AS total_quantity,
                        SUM(o.cost) AS total_profit,
                        (SELECT COUNT(*) FROM orders AS o WHERE o.status = 'pending') AS total_pending,
                        (SELECT COUNT(*) FROM orders AS o WHERE o.status = 'delivered') AS total_delivered
                FROM orders AS o;";

        $sth = $connection->prepare($sql);
        $sth->execute();
        return $sth->fetch();
    }

    public static function monthlyOrderStats()
    {
        $connection = DBConnection::getConnection();
        $sql = "SELECT
                    MONTHNAME(o.order_date) AS month,
                    COUNT(*) AS total_orders,
                    SUM(o.cost) AS total_profit
                FROM orders AS o WHERE YEAR(o.order_date) = 2022 GROUP BY MONTH(o.order_date)";

        $sth = $connection->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function parsedMonthlyOrderStats()
    {
        $months = [];
        $orderCount = [];
        $totalProfit = [];
        $colors = [];

        foreach (self::monthlyOrderStats() as $_ => $order) {
            $months[] = $order["month"];
            $orderCount[] = $order["total_orders"];
            $totalProfit[] = $order["total_profit"];
            $colors[] = dynamicColor();
        }

        return [
            "months" => $months,
            "orderCount" => $orderCount,
            "totalProfit" => $totalProfit,
            "colors" => $colors
        ];
    }
}

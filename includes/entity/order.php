<?php

include_once dirname(__FILE__) . "/../connection.php";
class Order
{
    const TABLE = "orders";
    const COLUMNS = "o.id, c.name, ST_X(c.location) AS longitude, ST_Y(c.location) AS latitude, f.name AS fuel_name, o.quantity, o.cost, o.status, o.order_date";
    const INSERT_COLS = "company_id, type_id, quantity, cost, status, order_date";
    const PLACEHOLDERS = ":company_id, :type_id, :quantity, :cost, :status, :order_date";

    public $id;
    public $name;
    public $longitude;
    public $latitude;
    public $fuelName;
    public $quantity;
    public $cost;
    public $status;
    public $orderDate;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->longitude = $data["longitude"];
        $this->latitude = $data["latitude"];
        $this->fuelName = $data["fuel_name"];
        $this->quantity = $data["quantity"];
        $this->cost = $data["cost"];
        $this->status = $data["status"];
        $this->orderDate = $data["order_date"];
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

    /*
    Returns all the orders made by companies so far
     */
    public static function all()
    {
        $orders = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM orders AS o INNER JOIN companies AS c ON c.id = o.company_id INNER JOIN fuel_types AS f ON f.id = o.type_id";
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
}

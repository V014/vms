<?php

include_once dirname(__FILE__) . "/../connection.php";
include_once dirname(__FILE__) . "/../utils.php";

class Company
{
    const TABLE = "companies";
    const COLUMNS = "u.id AS user_id, u.created_at, c.name, c.established, ST_X(c.location) AS longitude, ST_Y(c.location) AS latitude, COUNT(o.id) AS total_orders, SUM(o.cost) AS total_spend";

    public $userID;
    public $name;
    public $established;
    public $latitude;
    public $longitude;
    public $totalOrders;
    public $totalSpend;
    public $createdAt;

    public function __construct($data)
    {
        $this->userID = $data["user_id"];
        $this->name = $data["name"];
        $this->established = $data["established"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
        $this->totalOrders = $data["total_orders"];
        $this->totalSpend = number_format($data["total_spend"]);
        $this->createdAt = $data["created_at"];
    }

    public static function create($company)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($company);

        $companyTable = self::TABLE;

        $sth = $connection->prepare("INSERT INTO {$companyTable} (user_id, name, established, location) VALUES (:user_id, :name, :established, :location)");
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
        $query = "SELECT {$columns} FROM {$table} AS c INNER JOIN users AS u ON u.id = c.user_id INNER JOIN orders AS o ON o.company_id = c.id WHERE c.id = :id";
        $sth = $connection->prepare($query);

        if (!$sth->execute([":id" => $id])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return new Company($result);
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

    public function orders()
    {
        $connection = DBConnection::getConnection();
        $orders = [];
        $id = $this->id;
        $query = "SELECT o.quantity, o.cost, o.status, o.order_date, f.name FROM orders AS o INNER JOIN fuel_types AS f ON f.id = o.type_id WHERE o.company_id = :id";
        $sth = $connection->prepare($query);

        if ($sth->execute([":id" => $id])) {
            foreach ($sth->fetchAll() as $order) {
                $products[] = new Order($order);
            }
        }

        return $orders;
    }

    public static function all()
    {
        $companies = array();
        $connection = DBConnection::getConnection();
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM companies AS c INNER JOIN users AS u ON u.id = c.user_id LEFT JOIN orders AS o ON o.company_id = u.id GROUP BY c.id ORDER BY created_at DESC";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $company) {
                $companies[] = new Company($company);
            }
        }

        return $companies;
    }

    public function countOrders($type = null)
    {
        $connection = DBConnection::getConnection();
        $query = "";

        switch ($type) {
            case 'pending':
                $query = "SELECT COUNT(*) FROM `orders` WHERE status = {$type} AND company_id = :id";
                break;
            case 'delivered':
                $query = "SELECT COUNT(*) FROM `orders` WHERE status = {$type} AND company_id = :id";
                break;
            default:
                $query = "SELECT COUNT(*) FROM `orders` WHERE company_id = :id";
                break;
        }

        $sth = $connection->prepare($query);
        $sth->execute([":id" => $this->userID]);
        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        return $result;
    }
}

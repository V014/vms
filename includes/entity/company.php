<?php

include_once dirname(__FILE__) . "/../dbconnection.php";
include_once dirname(__FILE__) . "/../utils.php";

class Company
{
    const TABLE = "companies";
    const COLUMNS = "c.id, u.username, u.profile_picture, u.created_at, u.phone_number, u.email, c.name, c.established, SET_X(c.location) AS longitude, SET_Y(c.location) AS latitude";

    public $id;
    public $username;
    public $profile_picture;
    public $created_at;
    public $phone_number;
    public $email;
    public $name;
    public $established;
    public $latitude;
    public $longitude;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->username = $data["username"];
        $this->profile_picture = $data["profile_picture"];
        $this->created_at = $data["created_at"];
        $this->phone_number = $data["phone_number"];
        $this->email = $data["email"];
        $this->name = $data["name"];
        $this->established = $data["established"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
    }

    public static function create($order)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($order);

        $companyTable = self::TABLE;

        $sth = $connection->prepare("INSERT INTO {$companyTable} (user_id, name, established, location) VALUES (:user_id, :name, :established, :location)");
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }

        return self::find($connection->lastInsertId());
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
}

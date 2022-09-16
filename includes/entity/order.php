<?php

include_once dirname(__FILE__) . "/../connection.php";
class Order
{
    const TABLE = "orders";
    const COLUMNS = "c.name, SET_X(c.location) AS longitude, SET_Y(c.location) AS latitude, c.established, f.name, o.quantity, o.cost, o.status, o.order_date";

    /*
    Returns all the orders made by companies so far
     */
    public static function all()
    {
        $orders = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table} INNER JOIN companies AS c ON c.id = o.company_id INNER JOIN fuel_types AS f ON f.id = f.type_id";
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
}

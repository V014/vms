<?php

include_once dirname(__FILE__) . "/../connection.php";
include_once dirname(__FILE__) . "/../utils.php";

class OrderDriver
{
    const TABLE = "order_driver";
    const INSERT_COLS = "order_id, driver_id, vehicle_id";
    const PLACEHOLDERS = ":order_id, :driver_id, :vehicle_id";

    public static function create($orderDriver)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($orderDriver);

        $table = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $query = "INSERT INTO {$table} ({$insertCols}) VALUES ({$placeholders})";
        $sth = $connection->prepare($query);
        $sth->execute($params);
    }
}

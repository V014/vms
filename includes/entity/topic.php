<?php

include_once dirname(__FILE__) . "/../connection.php";
include_once dirname(__FILE__) . "/../utils.php";

class Topic
{
    const TABLE = "topics";
    const COLUMNS = "id, title, user_id, date_created";
    const INSERT_COLS = "title, user_id, date_created";
    const PLACEHOLDERS = ":title, :user_id, :date_created";

    public $id;
    public $title;
    public $userID;
    public $dateCreated;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->userID = $data["user_id"];
        $this->dateCreated = $data["date_created"];
    }

    public static function create($topic)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($topic);
        $table = self::TABLE;
        $insertCols = self::INSERT_COLS;
        $placeholders = self::PLACEHOLDERS;

        $query = "INSERT INTO {$table} ({$insertCols}) VALUES ({$placeholders})";
        $sth = $connection->prepare($query);
        $result = $sth->execute($params);

        if (!$result) {
            return null;
        }
    }

    public static function all()
    {
        $topics = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table}";
        $sth = $connection->prepare($query);

        if ($sth->execute()) {
            foreach ($sth->fetchAll() as $order) {
                $topics[] = new Topic($order);
            }
        }

        return $topics;
    }
}

<?php

class Message
{
    const TABLE = "messages";
    const COLUMNS = "id, topic_id, user_id, body, date_created";
    const INSERT_COLS = "topic_id, user_id, body, date_created";
    const PLACEHOLDERS = ":topic_id, :user_id, :body, :date_created";

    public $topicID;
    public $userID;
    public $body;
    public $dateCreated;

    public function __construct($data)
    {
        $this->topicID = $data["topic_id"];
        $this->userID = $data["user_id"];
        $this->body = $data["body"];
        $this->dateCreated = $data["date_created"];
    }

    public static function create($message)
    {
        $connection = DBConnection::getConnection();
        $params = formatParams($message);
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

    public static function all($topicID)
    {
        $messages = [];
        $connection = DBConnection::getConnection();
        $table = self::TABLE;
        $columns = self::COLUMNS;

        $query = "SELECT {$columns} FROM {$table} WHERE topic_id = :topic_id";
        $sth = $connection->prepare($query);

        if ($sth->execute([":topic_id" => $topicID])) {
            foreach ($sth->fetchAll() as $order) {
                $messages[] = new Message($order);
            }
        }

        return $messages;
    }
}

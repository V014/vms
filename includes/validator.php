<?php

declare(strict_types=1);

include_once dirname(__FILE__) . "/dbconnection.php";

class FormValidator
{
    /**
     * Checks whether the database does not contain any rows with a column
     * for the given table that matches the contents in that cell
     */
    public static function isUnique(string $value, string $column, string $table)
    {
        $connection = DBConnection::getConnection();
        $sql = "SELECT COUNT(*) AS number FROM {$table} WHERE {$column} = :value";
        $sth = $connection->prepare($sql);

        if (!$sth->execute([":value" => $value])) {
            return null;
        }

        $result = $sth->fetch();

        if (!$result) {
            return null;
        }

        if ($result["number"] !== 0) {
            return false;
        }

        return true;
    }
}

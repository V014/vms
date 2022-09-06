<?php
class DBConnection
{
    private static $dbh;

    /**
     * Returns a handle to the database
     */
    public static function getConnection()
    {
        try {
            if (self::$dbh === null) {
                self::$dbh = new PDO("mysql:host=localhost;dbname=vms", "root", "");
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }

            return self::$dbh;
        } catch (PDOException $e) {
            die("Database connection failed " . $e->getMessage());
        }
    }

    /**
     * Initialise the database, creating the necessary tables for the application to function
     */
    public static function init()
    {
        // $schema = generateSQL();

        // try {
        //     $dbh = new PDO("mysql:host=localhost", "root", "");
        //     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     $dbQuery = $dbh->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'farmersconnect'");
        //     // Create the farmersconnect database if it does not exist
        //     if (!$dbQuery->fetch()) {
        //         $dbh->beginTransaction();
        //         $dbh->query("CREATE DATABASE IF NOT EXISTS farmersconnect");
        //         $dbh->query("USE farmersconnect");

        //         $dbh->query($schema);
        //         $dbh->commit();
        //     }
        // } catch (PDOException $e) {
        //     die("Database connection failed: " . $e->getMessage());
        // }
    }
}

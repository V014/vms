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
        $schema = self::generateSQL();

        try {
            $dbh = new PDO("mysql:host=localhost", "root", "");
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbQuery = $dbh->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'vms'");
            // Create the vms database if it does not exist
            if (!$dbQuery->fetch()) {
                $dbh->beginTransaction();
                $dbh->query("CREATE DATABASE IF NOT EXISTS vms");
                $dbh->query("USE vms");

                $dbh->query($schema);
                $dbh->commit();
            }
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private static function generateSQL()
    {
        $schema = "
CREATE TABLE IF NOT EXISTS users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL,
    updated_at DATE,
    phone_number VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    role ENUM('company', 'driver', 'admin') NOT NULL,
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS company (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    established INT NOT NULL,
    location POINT NOT NULL,
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS drivers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    national_id VARCHAR(50) NOT NULL,
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS orders(
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    quantity INT NOT NULL,
    location POINT NOT NULL,
    created_at DATE NOT NULL,
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS order_driver(
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    driver_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE
    current_location POINT NOT NULL,
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS vehicles(
    id INT PRIMARY KEY AUTO_INCREMENT,
    registration_no VARCHAR(50) NOT NULL,
    make TEXT NOT NULL,
    capacity INT NOT NULL,
) ENGINE = INNODB;
        ";
        return $schema;
    }
}

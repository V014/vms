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
        $password = password_hash("secret", PASSWORD_DEFAULT);
        $defaultProfile = "./uploads/profiles/user-profile.png";

        $phoneNumbers = self::generatePhoneNumbers(16);

        $weekDays = [
            "monday" => date('Y/m/d', strtotime("5 weeks ago")),
            "tuesday" => date('Y/m/d', strtotime("tuesday 5 weeks ago")),
            "wednesday" => date('Y/m/d', strtotime("wednesday 5 weeks ago")),
            "thursday" => date('Y/m/d', strtotime("thursday 5 weeks ago")),
            "friday" => date('Y/m/d', strtotime("friday 5 weeks ago")),
            "saturday" => date('Y/m/d', strtotime("saturday 5 weeks ago")),
            "sunday" => date('Y/m/d', strtotime("sunday 5 weeks ago"))
        ];

        $schema = "
CREATE TABLE IF NOT EXISTS users(
    id INT PRIMARY KEY A  UTO_INCREMENT,
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

INSERT INTO users (username, password, profile_picture, created_at, updated_at, phone_number, email, role) VALUES
    ('admin', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[0]}', 'admin@gmail.com', 'admin'),
    ('neonfuel', '{$password}', '{$defaultProfile}', '{$weekDays['tuesday']}', '{$weekDays['tuesday']}', '{$phoneNumbers[1]}', 'neonfuel@gmail.com', 'company')
    ('futurenergy', '{$password}', '{$defaultProfile}', '{$weekDays['thursday']}', '{$weekDays['thursday']}', '{$phoneNumbers[2]}', 'futurenergy@gmail.com', 'company')
    ('workfuel', '{$password}', '{$defaultProfile}', '{$weekDays['saturday']}', '{$weekDays['saturday']}', '{$phoneNumbers[3]}', 'workfuel@gmail.com', 'company')
    ('energyplus', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[4]}', 'energyplus@gmail.com', 'company')
    ('enegrade', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[5]}', 'enegrade@gmail.com', 'company')
    ('leon', '{$password}', '{$defaultProfile}', '{$weekDays['wednesday']}', '{$weekDays['wednesday']}', '{$phoneNumbers[6]}', 'leon@gmail.com', 'driver')
    ('bright', '{$password}', '{$defaultProfile}', '{$weekDays['friday']}', '{$weekDays['friday']}', '{$phoneNumbers[7]}', 'bright@gmail.com', 'driver')
    ('masala', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[8]}', 'masala@gmail.com', 'driver')
    ('john', '{$password}', '{$defaultProfile}', '{$weekDays['tuesday']}', '{$weekDays['tuesday']}', '{$phoneNumbers[9]}', 'john@gmail.com', 'driver')
    ('henry', '{$password}', '{$defaultProfile}', '{$weekDays['thursday']}', '{$weekDays['thursday']}', '{$phoneNumbers[10]}', 'henry@gmail.com', 'driver')
    ('benjamin', '{$password}', '{$defaultProfile}', '{$weekDays['wednesday']}', '{$weekDays['wednesday']}', '{$phoneNumbers[11]}', 'benjamin@gmail.com', 'driver')
    ('edward', '{$password}', '{$defaultProfile}', '{$weekDays['friday']}', '{$weekDays['friday']}', '{$phoneNumbers[12]}', 'edward@gmail.com', 'driver')
    ('daniel', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[13]}', 'daniel@gmail.com', 'driver')
    ('mike', '{$password}', '{$defaultProfile}', '{$weekDays['saturday']}', '{$weekDays['saturday']}', '{$phoneNumbers[14]}', 'mike@gmail.com', 'driver')
    ('stewart', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[15]}', 'stewart@gmail.com', 'driver')
        ";

        return $schema;
    }

    private static function generatePhoneNumbers($count)
    {
        $phoneNumbers = [];

        for ($i = 0; $i < $count; $i++) {
            // Auto generate a list of phone numbers in the form +265 992 475 963
            $providers = ["88", "99"];
            $phoneNumbers[] = implode([
                "+265",
                $providers[array_rand($providers)],
                random_int(0, 9),
                random_int(0, 9),
                random_int(0, 9),
                random_int(0, 9),
                random_int(0, 9),
                random_int(0, 9),
                random_int(0, 9)
            ]);
        }

        return $phoneNumbers;
    }
}

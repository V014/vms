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

        $orderDays = [
            date('Y/m/d', strtotime("1 days ago")),
            date('Y/m/d', strtotime("2 days ago")),
        ];

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
    role ENUM('company', 'driver', 'admin') NOT NULL
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS companies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    established INT NOT NULL,
    location POINT NOT NULL
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS drivers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    national_id VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS orders(
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type_id INT REFERENCES fuel_types(id) ON DELETE SET NULL,
    quantity INT NOT NULL,
    cost DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'delivered') NOT NULL,
    order_date DATE NOT NULL
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS order_driver(
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    driver_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS trips(
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    current_location POINT NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicles(
    id INT PRIMARY KEY AUTO_INCREMENT,
    registration_no VARCHAR(50) NOT NULL,
    make TEXT NOT NULL,
    capacity INT NOT NULL
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS fuel_types(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) UNIQUE NOT NULL,
    cost_per_litre DECIMAL(15, 2) NOT NULL
) ENGINE = INNODB;

INSERT INTO fuel_types(name, cost_per_litre) VALUES
    ('parafin', '1200'),
    ('diesel', '1850'),
    ('petrol', '2000');

INSERT INTO users (username, password, profile_picture, created_at, updated_at, phone_number, email, role) VALUES
    ('admin', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[0]}', 'admin@gmail.com', 'admin'),
    ('neonfuel', '{$password}', '{$defaultProfile}', '{$weekDays['tuesday']}', '{$weekDays['tuesday']}', '{$phoneNumbers[1]}', 'neonfuel@gmail.com', 'company'),
    ('futurenergy', '{$password}', '{$defaultProfile}', '{$weekDays['thursday']}', '{$weekDays['thursday']}', '{$phoneNumbers[2]}', 'futurenergy@gmail.com', 'company'),
    ('workfuel', '{$password}', '{$defaultProfile}', '{$weekDays['saturday']}', '{$weekDays['saturday']}', '{$phoneNumbers[3]}', 'workfuel@gmail.com', 'company'),
    ('energyplus', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[4]}', 'energyplus@gmail.com', 'company'),
    ('enegrade', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[5]}', 'enegrade@gmail.com', 'company'),
    ('leon', '{$password}', '{$defaultProfile}', '{$weekDays['wednesday']}', '{$weekDays['wednesday']}', '{$phoneNumbers[6]}', 'leon@gmail.com', 'driver'),
    ('bright', '{$password}', '{$defaultProfile}', '{$weekDays['friday']}', '{$weekDays['friday']}', '{$phoneNumbers[7]}', 'bright@gmail.com', 'driver'),
    ('masala', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[8]}', 'masala@gmail.com', 'driver'),
    ('john', '{$password}', '{$defaultProfile}', '{$weekDays['tuesday']}', '{$weekDays['tuesday']}', '{$phoneNumbers[9]}', 'john@gmail.com', 'driver'),
    ('henry', '{$password}', '{$defaultProfile}', '{$weekDays['thursday']}', '{$weekDays['thursday']}', '{$phoneNumbers[10]}', 'henry@gmail.com', 'driver'),
    ('benjamin', '{$password}', '{$defaultProfile}', '{$weekDays['wednesday']}', '{$weekDays['wednesday']}', '{$phoneNumbers[11]}', 'benjamin@gmail.com', 'driver'),
    ('edward', '{$password}', '{$defaultProfile}', '{$weekDays['friday']}', '{$weekDays['friday']}', '{$phoneNumbers[12]}', 'edward@gmail.com', 'driver'),
    ('daniel', '{$password}', '{$defaultProfile}', '{$weekDays['sunday']}', '{$weekDays['sunday']}', '{$phoneNumbers[13]}', 'daniel@gmail.com', 'driver'),
    ('mike', '{$password}', '{$defaultProfile}', '{$weekDays['saturday']}', '{$weekDays['saturday']}', '{$phoneNumbers[14]}', 'mike@gmail.com', 'driver'),
    ('stewart', '{$password}', '{$defaultProfile}', '{$weekDays['monday']}', '{$weekDays['monday']}', '{$phoneNumbers[15]}', 'stewart@gmail.com', 'driver');


INSERT INTO companies (user_id, name, established, location) VALUES
    ('2', 'Neon Fuel', '1958', ST_GeomFromText('POINT(-15.841323217433517 34.96090631240044)')),
    ('3', 'FuturEnergy', '1999', ST_GeomFromText('POINT(-15.913119660843408 35.05163534840747)')),
    ('4', 'Work Fuel', '2000', ST_GeomFromText('POINT(-15.670896629485052 34.92241982797238)')),
    ('5', 'EnergyPlus', '1978', ST_GeomFromText('POINT(-13.999479366532892 33.83301411828163)')),
    ('6', 'EneGrade', '1990', ST_GeomFromText('POINT(-13.932744464015217 33.802450662506544)'));

INSERT INTO drivers (user_id, national_id, dob, first_name, last_name) VALUES
    ('7', 'JK4893UI', '1998/05/12', 'Leon', 'Tsetsa'),
    ('8', 'IP0392NG', '1978/01/01', 'Bright', 'Magoba'),
    ('9', '10MDJ302', '1989/09/12', 'Masala', 'Dausi'),
    ('10', 'AS9032PO', '1990/12/12', 'John', 'Limpopo'),
    ('11', 'FG3849EM', '1970/11/20', 'Henry', 'Saladi'),
    ('12', 'LM2010WO', '1988/07/29', 'Benjamin', 'Mavuto'),
    ('13', 'HJG9430', '2000/02/01', 'Edward', 'Ulendo'),
    ('14', 'VG3928AS', '1969/05/04', 'Daniel', 'Gonjani'),
    ('15', 'RP2312QW', '1993/03/16', 'Mike', 'Pafupi'),
    ('16', 'MK9839ZZ', '1997/02/15', 'Stewart', 'Mwomba');

INSERT INTO orders (company_id, type_id, quantity, cost, status, order_date) VALUES
    ('2', '2', '3500', '6475000', 'delivered', '2022/01/22'),
    ('2', '2', '5000', '9250000', 'delivered', '2022/01/01'),
    ('2', '2', '7600', '12950000', 'delivered', '2022/03/10'),
    ('2', '3', '10000', '20000000', 'delivered', '2022/04/01'),
    ('2', '3', '7600', '15200000', 'delivered', '2022/07/05'),
    ('2', '3', '12000', '24000000', 'delivered', '2022/02/10'),
    ('2', '3', '20000', '40000000', 'delivered', '2021/12/22'),
    ('2', '2', '13000', '24050000', 'delivered', '2021/09/22'),
    ('2', '3', '8500', '17000000', 'pending', '{$orderDays[1]}'),
    ('2', '2', '7900', '14615000', 'pending', '{$orderDays[1]}'),
    ('3', '3', '10000', '20000000', 'delivered', '2022/03/22'),
    ('3', '2', '12000', '22200000', 'delivered', '2022/02/02'),
    ('3', '3', '5000', '10000000', 'delivered', '2022/06/09'),
    ('3', '3', '7000', '14000000', 'delivered', '2022/03/01'),
    ('3', '2', '8400', '15540000', 'delivered', '2022/05/01'),
    ('3', '2', '5000', '9250000', 'delivered', '2022/08/22'),
    ('3', '2', '7900', '14615000', 'delivered', '2022/07/01'),
    ('3', '3', '10000', '20000000', 'delivered', '2022/04/05'),
    ('3', '3', '8000', '16000000', 'delivered', '2021/10/20'),
    ('3', '2', '6000', '11100000', 'pending', '{$orderDays[0]}'),
    ('4', '2', '7000', '12950000', 'delivered', '2022/01/01'),
    ('4', '3', '9000', '18000000', 'delivered', '2022/01/31'),
    ('4', '2', '6900', '12765000', 'delivered', '2022/02/01'),
    ('4', '3', '8000', '16000000', 'delivered', '2022/02/27'),
    ('4', '3', '4000', '8000000', 'delivered', '2022/03/01'),
    ('4', '2', '7000', '12950000', 'delivered', '2022/03/31'),
    ('4', '2', '6900', '12765000', 'delivered', '2022/04/01'),
    ('4', '3', '15000', '30000000', 'pending', '{$orderDays[0]}'),
    ('4', '3', '27000', '54000000', 'pending', '{$orderDays[1]}'),
    ('4', '2', '14000', '25900000', 'pending', '{$orderDays[0]}'),
    ('5', '3', '7000', '14000000', 'delivered', '2022/01/01'),
    ('5', '2', '8000', '14800000', 'delivered', '2022/02/01'),
    ('5', '3', '20000', '40000000', 'delivered', '2022/03/01'),
    ('5', '2', '10000', '18500000', 'delivered', '2022/04/01'),
    ('5', '3', '8000', '16000000', 'delivered', '2022/05/01'),
    ('5', '2', '5000', '9250000', 'delivered', '2022/06/01'),
    ('5', '2', '9000', '16650000', 'delivered', '2022/07/01'),
    ('5', '2', '10000', '18500000', 'pending', '{$orderDays[0]}'),
    ('5', '3', '6700', '13400000', 'pending', '{$orderDays[1]}'),
    ('5', '3', '8000', '16000000', 'pending', '{$orderDays[0]}'),
    ('6', '2', '10000', '18500000', 'delivered', '2022/01/01'),
    ('6', '3', '7500', '15000000', 'delivered', '2022/02/01'),
    ('6', '2', '8000', '8000', 'delivered', '2022/03/01'),
    ('6', '2', '10000', '18500000', 'delivered', '2022/04/01'),
    ('6', '3', '5000', '10000000', 'delivered', '2022/05/01'),
    ('6', '2', '6700', '12395000', 'delivered', '2022/06/01'),
    ('6', '3', '7900', '15800000', 'delivered', '2022/07/01'),
    ('6', '3', '5000', '10000000', 'delivered', '2022/08/01'),
    ('6', '2', '6000', '11100000', 'delivered', '2021/12/01'),
    ('6', '2', '8000', '14800000', 'pending', '{$orderDays[0]}'),
    ('6', '2', '2000', '3700000', 'delivered', '2021/12/01'),
    ('6', '2', '20000', '37000000', 'delivered', '2021/11/01');

INSERT INTO order_driver(order_id, driver_id) VALUES
    ('1', '7'),
    ('2', '8'),
    ('3', '9'),
    ('4', '10'),
    ('5', '11'),
    ('6', '12'),
    ('7', '13'),
    ('8', '14'),
    ('9', '15'),
    ('10', '16'),
    ('11', '7'),
    ('12', '8'),
    ('13', '9'),
    ('14', '10'),
    ('15', '11'),
    ('16', '12'),
    ('17', '13'),
    ('18', '14'),
    ('19', '15'),
    ('20', '7'),
    ('21', '7'),
    ('22', '8'),
    ('23', '9'),
    ('24', '10'),
    ('25', '11'),
    ('26', '12'),
    ('27', '13'),
    ('28', '10'),
    ('29', '12'),
    ('30', '13'),
    ('31', '7'),
    ('32', '8'),
    ('33', '9'),
    ('34', '10'),
    ('35', '11'),
    ('36', '12'),
    ('37', '13'),
    ('38', '9'),
    ('39', '11'),
    ('40', '15'),
    ('41', '7'),
    ('42', '8'),
    ('43', '9'),
    ('44', '10'),
    ('45', '11'),
    ('46', '12'),
    ('47', '13'),
    ('48', '14'),
    ('49', '15'),
    ('50', '12'),
    ('51', '7'),
    ('52', '8');

INSERT INTO trips(order_id, current_location) VALUES
    ('9', ST_GeomFromText('POINT(-15.903729096859049 34.96071072503849)')),
    ('10', ST_GeomFromText('POINT(-15.915343251644021 34.95155782303004)')),
    ('20', ST_GeomFromText('POINT(-15.655988721809182 35.143702362244866)')),
    ('28', ST_GeomFromText('POINT(-15.909226669406822 34.957328091463125)')),
    ('29', ST_GeomFromText('POINT(-15.926078039830687 34.93828106190227)')),
    ('30', ST_GeomFromText('POINT(-15.972961359204037 34.90513923373642)')),
    ('38', ST_GeomFromText('POINT(-14.291448144608832 34.159836370404)')),
    ('39', ST_GeomFromText('POINT(-14.329918416337309 34.19418550406482)')),
    ('40', ST_GeomFromText('POINT(-14.346341916809385 34.22541198921102)')),
    ('50', ST_GeomFromText('POINT(-14.026737870268617 33.51245424399264)'));
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

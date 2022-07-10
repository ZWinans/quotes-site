<?php

// This class has a constructor to connect to a database. The given
// code assumes you have created a database named 'quotes' inside MariaDB.
//
// Call function startByScratch() to drop quotes if it exists and then create
// a new database named quotes and add the two tables (design done for you).
// The function startByScratch() is only used for testing code at the bottom.
//
// Author: Zach Winans
//
class DatabaseAdaptor
{

    private $DB;

    // The instance variable used in every method below
    // Connect to an existing data based named 'first'
    public function __construct()
    {
        $dataBase = 'mysql:dbname=quotes;charset=utf8;host=127.0.0.1';
        $user = 'root';
        $password = ''; // Empty string with XAMPP install
        try {
            $this->DB = new PDO($dataBase, $user, $password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ('Error establishing Connection');
            exit();
        }
    }

    // This function exists only for testing purposes. Do not call it any other time.
    public function startFromScratch()
    {
        $stmt = $this->DB->prepare("DROP DATABASE IF EXISTS quotes;");
        $stmt->execute();

        // This will fail unless you created database quotes inside MariaDB.
        $stmt = $this->DB->prepare("create database quotes;");
        $stmt->execute();

        $stmt = $this->DB->prepare("use quotes;");
        $stmt->execute();

        $update = " CREATE TABLE quotations ( " . " id int(20) NOT NULL AUTO_INCREMENT, added datetime, quote varchar(2000), " . " author varchar(100), rating int(11), flagged tinyint(1), PRIMARY KEY (id));";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();

        $update = "CREATE TABLE users ( " . "id int(6) unsigned AUTO_INCREMENT, username varchar(64),
            password varchar(255), PRIMARY KEY (id) );";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();
    }

    // ^^^^^^^ Keep all code above for testing ^^^^^^^^^

    // ///////////////////////////////////////////////////////////
    // Complete these five straightfoward functions and run as a CLI application
    public function getAllQuotations()
    {
        $stmt = $this->DB->prepare("SELECT * FROM quotations ORDER BY rating DESC");
        $stmt->execute();
        $arr = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $arr;
    }

    public function getAllUsers()
    {
        $stmt = $this->DB->prepare("SELECT * FROM users");
        $stmt->execute();
        $arr = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $arr;
    }

    public function addQuote($quote, $author)
    {
        $quote = htmlspecialchars($quote);
        $author = htmlspecialchars($author);
        $stmt = $this->DB->prepare("INSERT INTO quotations (added, quote, author, rating, flagged)
                                    VALUES ('" . date("Y-m-d H:i:s") . "', " . ":quote" . ", " . ":author" . ", '0', '0')
                                    ");
        $stmt->bindParam(':quote', $quote);
        $stmt->bindParam(':author', $author);
        $stmt->execute();
    }

    public function addUser($accountname, $psw)
    {
        $accountname = htmlspecialchars($accountname);
        $psw = htmlspecialchars($psw);
        $hashed_pwd = password_hash($psw, PASSWORD_DEFAULT);

        $stmt = $this->DB->prepare("INSERT INTO users (username, password)
                                    VALUES (" . ":bind_name" . ", " . ":pass_word" . ")
                                    ");
        $stmt->bindParam(':bind_name', $accountname);
        $stmt->bindParam(':pass_word', $hashed_pwd);
        $stmt->execute();
    }

    public function verifyCredentials($accountName, $psw)
    {
        $accountName = htmlspecialchars($accountName);
        $psw = htmlspecialchars($psw);

        $stmt = $this->DB->prepare("SELECT * FROM users where username=:bind_name");
        $stmt->bindParam(':bind_name', $accountName);
        $stmt->execute();
        $arr = $stmt->fetchALL(PDO::FETCH_ASSOC);

        if (sizeof($arr) === 0) {
            return false;
        }

        return password_verify($psw, $arr[0]['password']);
    }

    public function update($id, $direction)
    {
        if ($direction === 'increase') {
            $stmt = $this->DB->prepare("
                                    UPDATE quotations 
                                    SET 
                                        rating = rating + 1
                                    WHERE
                                        id = " . ":id" . ";
                                      ");
        }

        if ($direction === 'decrease') {
            $stmt = $this->DB->prepare("
                                    UPDATE quotations
                                    SET
                                        rating = rating - 1
                                    WHERE
                                        id = " . ":id" . ";
                                      ");
        }

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->DB->prepare("DELETE FROM quotations WHERE id=" . ":id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function containsUser($username)
    {
        $username = htmlspecialchars($username);

        $stmt = $this->DB->prepare("Select * FROM users WHERE username= :bind_name");
        $stmt->bindParam(':bind_name', $username);
        $stmt->execute();
        $arr = $stmt->fetchALL(PDO::FETCH_ASSOC);

        if (sizeof($arr) > 0) {
            return true;
        } else {
            return false;
        }
    }
} // End class DatabaseAdaptor

?>

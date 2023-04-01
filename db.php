<?php
class Db {
    private $conn;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'test';

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function saveTime($numeri, $username, $tempo) {
        $query = "INSERT INTO top10 (numeri, username, tempo) VALUES (:numeri, :username, :tempo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numeri', $numeri);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':tempo', $tempo);
        $stmt->execute();
    }

    public function getTop10($num) {
        $query = "SELECT * FROM top10 where numeri = $num ORDER BY tempo LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function checkPersonalBest($numeri, $username, $tempo) {
        $query = "SELECT MIN(tempo) AS best_time FROM top10 WHERE numeri = :numeri AND username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numeri', $numeri);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($tempo < $result['best_time']) {
            return true;
        } else {
            return false;
        }
    }

}
?>

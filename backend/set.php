<?php
require_once "includes/db.inc.php";
class Set{
    public function update($data){
        global $pdo;
        $data['username'] = filter_var($data['username'], FILTER_SANITIZE_STRING);
        echo $data['username'];
        echo "<br>";
        $data['username'] = explode(";", $data['username']);
        $data['username'] = trim($data['username'][0]);

        print_r($data['username']);
        echo "<br>";

        $sql = "SELECT * FROM setit where username = :username";

        $stmt = $pdo->connect->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        if ($stmt->execute()) {
            if($stmt->rowCount() > 0){
                $result = $stmt->fetch();
                
                echo $result->username;
            }
        }
    }
}

?>
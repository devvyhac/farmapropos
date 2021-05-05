<?php

require_once "../backend/includes/db.inc.php";
class PACKAGES extends DATABASE{
    public function __construct() {
         parent::__construct();
    }

    public function get_package($data) {
        $sql = "SELECT * FROM package WHERE id = :package";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(":package", $data);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
    
    public function update_funding($data, $sql) {
        $stmt = $this->connect->prepare($sql);
        if($stmt->execute()){
            echo "success";
        } else {
            echo "failed";
        }
        
    }
}

if (isset($_POST['pricing'])) {
    $pack = new PACKAGES();
    $pack = $pack->get_package($_POST['pricing']);
    $packs = json_encode($pack);
    echo $packs;
}

if(isset($_POST['update-funding'])){
    $sql = "UPDATE funding set funding.current = (select sum(price) from pricing as price INNER JOIN payments as pay on price.id = pay.pricing INNER JOIN funding AS f on pay.funding = f.funding_id where funding.funding_id = f.funding_id)";
    
    $update = new PACKAGES();
    $update->update_funding($_POST['update-funding'], $sql);

}



?>
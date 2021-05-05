<?php
class DATABASE{
    private $name = "Data base";
    private $error = [];
    public function __construct(){
        $host = "localhost";
        $username = "farmapro_ismail063_admin_god";
        $password = "JaN181997(1)";
        $dbname = "farmapro_farmapropos";

        $dsn = "mysql:host=".$host.";dbname=".$dbname;

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $this->connect = $pdo;

    }
    
    public function set_error($error, $message) {
        $this->error->arr_push([$error=>$message]);
    }
    
    public function db_error() {
        return $this->error;
    }
    
    public function get_data_by_user($table, $column, $param, $user){
        // $user = $this-sanitize($user);
        $sql = "SELECT * FROM ".$table." where ".$column."= :".$param;
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(":".$param, $user);
        try{
            if($stmt->execute()){
                return $stmt->fetchAll();
            } else {
                throw new Exception("Unable Fetch Data, Wrong Query Perhaps!");
            }
        } catch (Exception $e){
            $this->set_error("DB Error", $e->getMessage());
        }
    }

    public function sanitize($data){
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        $data = explode(";", $data);
        $data = explode("&", $data[0]);
        $data = explode("?", $data[0]);
        $data = $data[0];
        return $data;
    }
}

?>
<?php require_once "includes/header.php";

if(isset($_POST['table'])) {
    $database = new DATABASE();
    $table = $database->sanitize($_POST['table']);
    
    $sql = "SELECT * FROM ".$table;
    $stmt = $database->connect->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $result = $stmt->fetchAll();
    }
}

?>

<div class="container">
    <div class="row m-0 px-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class="text-light">All <?php echo $table ?></h3>
                </div>
                <div class="card-body">
                    <ol>
                        <?php foreach ($result as $key => $value) { ?>

                        <li><?php echo $value->category_name ?></li>

                        <?php } ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php" ?>
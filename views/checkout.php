<?php

if(!isset($_GET['funding']) && !isset($_GET['funding'])){
    header("Location: /projects");
}

if (isset($_SESSION["username"]) && isset($_SESSION["email"])) : ?>
<?php require_once "includes/head.php"; 

$funding_sql = "SELECT cat.category_name, type.type, p.tenor, p.project_status,
f.*, farm.name, farm.image, farm.state, farm.city FROM farm_category AS cat 
INNER JOIN farm_type AS type ON cat.category_id = type.category 
INNER JOIN farm_projects AS p ON type.type_id = p.farm_type
INNER JOIN funding AS f ON p.id = f.project AND f.funding_id = :funding
INNER JOIN farm_list AS farm";

// $pricing_sql = "SELECT pack.package_name, pack.description, pack.roi, p.*, f.category_name 
// FROM package AS pack 
// INNER JOIN pricing AS p ON pack.id = p.package and p.id = :pricing
// INNER JOIN farm_category AS f ON p.category = f.category_id";

class CHECKOUT extends DATABASE{
    public function __construct() {
        parent::__construct();
    }

    public function select_item($sql, $param, $data){
        $data = $this->sanitize($data);
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam($param ,$data);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
}

$checkout = new CHECKOUT();

$funding = $checkout->select_item($funding_sql, ":funding", $_GET['funding']);
// $pricing = $checkout->select_item($pricing_sql, ":pricing", $_GET['pricing']);
$units = $checkout->sanitize($_GET['units']);

// var_dump($funding);
// echo "<br>";
// var_dump($_GET);
// var_dump($_POST);
?>

<div class="container">
    <div class="row m-0 my-3">
        <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Error!</strong> <?php echo $_GET['error']; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php else: echo ""; ?>
        <?php endif; ?>
        <div class="card w-100">
            <div class="card-header bg-success">
                <h4 class="d-flex my-1 text-light"><span>Selected Project</span> - <span style="text-transform: capitalize;"><?php echo $funding->type ?></span></h4>
            </div>
            <div class="card-body">
                <form action="pay" method="POST">
                    <div class="row m-0">
                        <div class="col-sm-12 col-md-6">
                            <label for="fullname">Full Name</label>
                            <input type="text" class="form-control checkout" name="fullname" placeholder="" required title="This field must be filled">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="email">E-Mail</label>
                            <input type="text" class="form-control checkout" name="email" placeholder="Email" value="<?php echo $_SESSION['email']; ?>" required title="This field must be filled">
                        </div>
                    </div>

                    <input type="hidden" name="price" value="<?php echo ( $funding->amount * $units ); ?>">
                    
                    <input type="hidden" name="units" value="<?php echo $units; ?>">
                    
                    <input type="hidden" name="roi" value="<?php echo $funding->roi; ?>">

                    <input type="hidden" name="funding" value="<?php echo $funding->funding_id; ?>">

                    <div class="row m-0">
                        <div class="col-12 checkout-info">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Farm Type</th>
                                    <th>Price</th>
                                    <th>Units</th>
                                    <th>R.O.I</th>
                                    <th>Description</th>
                                </tr>

                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $funding->type ?></td>
                                    <td><?php echo ( $funding->amount * $units ) ?></td>
                                    <td><?php echo $units ?></td>
                                    <td><?php echo $funding->roi ?>%</td>
                                    <td style="text-transform: capitalize"><?php echo $funding->type ?> farm production</td>
                                </tr>
                            </table>
                        </div>
                        <button class="btn btn-success mt-3" name="pay" value="submit-payment">Checkout <i class="fa fa-credit-card pl-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php require_once "includes/footer.php" ?>
<?php else : 
    $_SESSION["funding"] = $_GET['funding'];
    $_SESSION["units"] = $_GET['units'];
    header("Location: accounts/login");
?>
<?php endif; ?>
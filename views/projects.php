<?php 

if(!isset($_GET['farm_id'])){
    header("Location: /farms");
}

require "includes/head.php";

// SANITIZING METHOD
function sanitize($input){
  $input = filter_var($input, FILTER_SANITIZE_STRING);
  $input = explode(";", $input);
  $input = explode("&", $input[0]);
  $input = trim($input[0]);
  return $input;
}

// sanitizing the get variable
$farm_id = sanitize($_GET["farm_id"]);

// the sql statement to get all the required data from the database
$sql = "SELECT cat.category_name, type.type, type.category, 
p.tenor, p.project_status, p.farm_id, p.started_on, p.project_pic, 
list.name, list.state, list.city, f.funding_id, f.status, f.project, f.targeted, f.current, f.started_on, f.end_on, f.amount, f.roi
FROM farm_type as type 
INNER JOIN farm_category as cat ON type.category = cat.category_id AND cat.category_id = :farm_id 
INNER JOIN farm_projects as p ON type.type_id = p.farm_type 
INNER JOIN funding as f ON p.id = f.project 
INNER JOIN farm_list as list ON p.farm_id = list.farm_list_id";

// the class for the functionlities
class ADDPROJECT extends DATABASE{

    public function __construct(){
        parent::__construct();
    }

    // public function add_project($data){

    // }

    public function bind_param_select($sql, $data=[], $param=[]){

      $stmt = $this->connect->prepare($sql);

      for ($i=0; $i < count($data); $i++) { 
        $stmt->bindParam($param[$i], $data[$i]);
      }

      $stmt->execute();
      $result = $stmt->fetch();
      return $result;

    }

    public function select($sql){

        $stmt = $this->connect->prepare($sql);

        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

    public function bind_select($sql, $data, $param){
      // $sql = $sql.$param;
      $stmt = $this->connect->prepare($sql);
      $stmt->bindParam($param, $data);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;

  }
}

$category = "SELECT * from farm_category as cat where cat.category_id = :farm_id";
// $pricing = "SELECT price.id, price.price, p.id, p.package_name, p.description, p.roi  
// from pricing as price INNER JOIN package as p 
// ON price.package = p.id and price.category = :farm_id";

$projects = new ADDPROJECT();

// result gotten from the category table in the database
$category = $projects->bind_select($category, $farm_id, ":farm_id");

// result gotten for the over all data needed for each project 
$result = $projects->bind_select($sql, $farm_id, ":farm_id");

// pricing details for dynamic selection of packages
// $pricing = $projects->bind_select($pricing, $farm_id, ":farm_id");

// var_dump($result);

?>

  <!-- ##### Breadcrumb Area Start ##### -->

  <!--<div class="famie-breadcrumb">-->
  <!--  <div class="container">-->
  <!--    <nav aria-label="breadcrumb">-->
  <!--      <ol class="breadcrumb">-->
  <!--        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home"></i> Home</a></li>-->
          <!--<li class="breadcrumb-item active" aria-current="page"><?php // echo $this->getAction(); ?></li>-->
  <!--        <li class="breadcrumb-item active"><span>-->
              <?php 
                // foreach ($category as $key => $value) {
                //   echo $value->category_name;
                // }
              ?>
  <!--            </span></li>-->
  <!--      </ol>-->
  <!--    </nav>-->
  <!--  </div>-->
  <!--</div>-->
  <!-- ##### Breadcrumb Area End ##### -->

  <!-- ##### Farming Practice Area Start ##### -->
  <section class="farming-practice-area bg-white pt-5">
    <div class="container">
        

      <div class="row">
        <div class="col-12">
          <div class="section-heading text-center">
            <p>Farm Project Category</p>
            <h2>
              <span>
              <?php 
                foreach ($category as $key => $value) {
                  echo $value->category_name;
                }
              ?>
              </span>
            </h2>

            <img src="img/core-img/decor2.png" alt="">
          </div>
        </div>
      </div>

    </div>
    <!-- Single Farming Practice Area -->
    <div class="container-fluid pb-5 bg-white m-0 p-0">
      <div class="row projects pb-5">
          <?php if ($result) : ?>
            <?php foreach ($result as $resultkey => $resultvalue) { ?>
                    <div class="project col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <form action="checkout" name="project-form" class="bg-white">
                <input type="hidden" name="funding" value="<?php echo $resultvalue->funding_id; ?>">
                        <div class="tag-label">Available</div>
                        <div class="project-card">
                            <div class="project-image">
                                <div class="image"  style="background-image: url('img/bg-img/<?php echo $resultvalue->project_pic; ?>');"></div>
                            </div>
                            <div class="project-desc">
                                <div class="info-block">
                                    <h4><?php echo $resultvalue->type;?></h4>
                                    <div class="row m-0 p-0">
                                        <div class="col-6 desc-info">
                                            <i class="fa fa-tag"></i>
                                            <div>
                                                <span>Price</span>
                                                <span><?php echo number_format($resultvalue->amount, 0, '.', '');?></span>
                                            </div>
                                        </div>
                                        <div class="col-6 desc-info">
                                            <i class="fas fa-chart-line"></i>
                                            <div>
                                                <span>ROI</span>
                                                <span><?php echo $resultvalue->roi;?>%</span>
                                            </div>
                                        </div>
                                        <div class="col-6 desc-info">
                                            <i class="fas fa-map-marked-alt"></i>
                                            <div>
                                                <span>Location</span>
                                                <span><?php echo $resultvalue->state;?></span>
                                            </div>
                                        </div>
                                        <div class="col-6 desc-info">
                                            <i class="fas fa-calendar-week"></i>
                                            <div>
                                                <span>Tenor</span>
                                                <span><?php echo $resultvalue->tenor;?> Months</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="units-number">
                                        <span>Units to Invest in:</span>
                                        <div class="number">
                                            <span class="minus">-</span>
                                            <input type="text" id="units" name="units" value="1"/>
                                            <span class="plus">+</span>
                                        </div>
                                        <div class="actions-or-share">
                                            <div class="actions">
                                                <button class="btn btn-success btn-md" name="submit">INVEST NOW</button>
                                            </div>
                                            <div class="share">
                                                <a href="#"><i class="far fa-eye"></i></a>
                                                <a href="#"><i class="fas fa-heart ml-1"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <?php } ?>
          <?php else: ?>
          
          <div class="container">
              <div class="row m-0 justify-content-center px-5">
                  <div class="col-8">
                      <img class="img img-responsive w-100" src="img/site_svg/undraw_empty_xct9.svg">
                  </div>
                  <h5 class="text-center my-3 text-danger">No Available <?php echo $value->category_name; ?> Project!</h5>
              </div>
          </div>
          <?php endif; ?>
          </div>
        </div>
  </section>
  <!-- ##### Farming Practice Area End ##### -->


  

<?php

require "includes/footer.php";

?>
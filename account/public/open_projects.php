<?php require_once "includes/header.php"; 

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
p.tenor, p.project_status, p.farm_id, p.started_on, p.image, 
list.name, list.state, list.city, f.funding_id, f.status, f.project, f.targeted, f.current, f.started_on, f.end_on, f.amount, f.roi
FROM farm_type as type 
INNER JOIN farm_category as cat ON type.category = cat.category_id AND cat.category_id = :farm_id 
INNER JOIN farm_projects as p ON type.type_id = p.farm_type 
INNER JOIN funding as f ON p.id = f.project 
INNER JOIN farm_list as list ON p.farm_id = list.id";

// the class for the functionlities
class OPEN_PROJECTS extends DATABASE{

    public function __construct(){
        parent::__construct();
    }

        // public function add_project($data){
}

$sl = "select * from farm_projects";

$category = "SELECT p.*, f.*, farm.*, type.*, cat.* FROM farm_projects AS p 
             INNER JOIN funding as f ON p.id = f.project 
             INNER JOIN farm_list as farm ON farm.farm_list_id = p.farm_id
             INNER JOIN farm_type as type ON type.type_id = p.farm_type 
             INNER JOIN farm_category as cat ON cat.category_id = type.category";

$projects = new OPEN_PROJECTS();

// result gotten for the over all data needed for each project 
$result = $projects->connect->prepare($category);
$result->execute();
$result = $result->fetchAll();

// foreach($result as $key => $value){
//     print_r($value);
    
//     echo "<br><br>";

//     // echo "<br>";
    
//     // var_dump($result[$key]);
// }

?>


<div class="container-fluid m-0 p-0">
      <div class="row projects">
          <?php if ($result) : ?>
            <?php foreach ($result as $resultkey => $resultvalue) { ?>
                <div class="project col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <form action="/checkout" name="project-form">
                        <input type="hidden" name="funding" value="<?php echo $resultvalue->funding_id; ?>">
                            <div class="tag-label">Available</div>
                            <div class="project-card">
                                    <div class="project-image">
                                        <div class="image"  style="background-image: url('../img/bg-img/<?php echo $resultvalue->project_pic; ?>');"></div>
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
                                                        <a href="#"><i
                                                            style="color: gray; font-size: 1.3rem;"
                                                            class="icon-share far fa-eye"></i></a>
                                                        <a href="#"><i
                                                            style="color: gray; font-size: 1.3rem; margin-left: 1rem;"
                                                            class="text-md icon-share fas fa-heart"></i></a>
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


<?php require_once "includes/footer.php" ?>
<?php require_once "includes/header.php"; 

$sql = "SELECT * from farm_type";
$sql2 = "SELECT * from farm_list";

class ADDPROJECT extends DATABASE{

    public function __construct(){
        parent::__construct();
    }

    public function check_duplicate(){

    }

    public function add_project($data, $file){
        $allowed_ext = ["png", "jpeg", "jpg", "gif", "pdf"];
        $target_dir = "images/projects/";

        if (isset($data['upload']) && isset($file['project-image'])) {

            // var_dump($data);

            $type = $this->sanitize($data['farm-type']);
            $farm = $this->sanitize($data['farm']);
            $tenor = $this->sanitize($data['tenor']);

            $filename = $this->sanitize(basename($file['project-image']['name']));
            $temp_name = $file['project-image']['tmp_name'];
            $target_file_path = $target_dir.$filename;
            $filetype = pathinfo($target_file_path, PATHINFO_EXTENSION);
            
            if (in_array($filetype, $allowed_ext)) {
                if (move_uploaded_file($temp_name, $target_file_path)) {
                    
                    $sql = "INSERT INTO farm_projects (tenor, farm_id, image, farm_type) 
                            VALUES(:tenor, :farm_id, :image, :farm_type)";
                    $stmt = $this->connect->prepare($sql);
                    $stmt->bindParam(":tenor", $tenor);
                    $stmt->bindParam(":farm_id", $farm);
                    $stmt->bindParam(":image", $filename);
                    $stmt->bindParam(":farm_type", $type);

                    if ($stmt->execute()) {
                        $message = "Project has been Successfully Uploaded!!!";
                    } else {
                        $message = "Unable to Upload Project, Please Try again!";
                    }
                } else {
                    $message = "Unable to upload File ".$filename.", Please try again!";
                }
            } else {
                $message = "Only ('png', 'jpg', 'jpeg', 'gif', 'pdf') File Extensions are Allowed";
            }
        }

        if (isset($message)) {
            echo "<script>alert(".$message.")</script>";
        }
    }

    public function select($sql){
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}

$add = new ADDPROJECT();

$farm_type = $add->select($sql);
$farm_list = $add->select($sql2);

$add->add_project($_POST, $_FILES);

?>

<div class="container">
    <div class="row m-0 p-0 justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">
                        <span>Add Project</span>
                        <!-- <i class="fa fa-plus"></i> -->
                    </h5>
                </div>
                <div class="card-body">
                    <form action="dashboard" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="farm-type">Farm Type</label>
                            <select class="form-control" name="farm-type" id="farm-type">
                                <?php foreach ($farm_type as $key => $value) { ?>
                                <option value="<?php echo $value->type_id; ?>">
                                    <?php echo $value->type; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="farm">Farms List</label>
                            <select class="form-control" name="farm" id="farm">
                                <?php foreach ($farm_list as $key => $value) { ?>
                                <option value="<?php echo $value->id; ?>">
                                    <?php echo $value->name; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tenor">Project Duration</label>
                            <select class="form-control" name="tenor" id="tenor">
                                <option value="6">6 Months</option>
                                <option value="7">7 Months</option>
                                <option value="9">9 Months</option>
                                <option value="12">12 Months</option>
                                <option value="14">14 Months</option>
                                <option value="16">16 Months</option>
                                <option value="18">18 Months</option>
                                <option value="24">24 Months</option>
                                <option value="36">36 Months</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="#">Add Project Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" name="project-image">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" name="upload" id="upload" value="upload">Upload <i class="fa fa-plus"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
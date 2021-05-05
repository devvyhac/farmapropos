<?php require_once "includes/header.php";

class PROFILE extends DATABASE{
    public function __construct(){
        parent::__construct();
    }
}

$account = new PROFILE();
$account_sql = "SELECT u.*, a.* from users as u 
                INNER JOIN address as a 
                ON u.user_id = a.user_id AND u.user_id = :user_id";

// $sql = "select * from users where user_id = :user_id";
                
$stmt = $account->connect->prepare($account_sql);
$stmt->bindParam(":user_id", $_SESSION["id"]);
$profile = [];

try{
    if($value = $stmt->execute()){
        $profile = $stmt->fetch();
    } else {
        throw new Exception("Unable to Access Data, Wrong Query Perhaps!");
    }
} catch (Exception $e) {
    $profile->error = $e->getMessage();
}

// print_r($_FILES);
// $msg = "";
// $allowed_format = ['png', 'jpg', 'jpeg', 'gif'];
// $image = $_FILES['image']['name'];
// $target = "images/".basename($image);

// $upload_img_sql = "INSERT INTO users (display_pic) VALUES(:display_pic) WHERE user_id = :user_id";
// $send_image = $account->connect->prepare($upload_sql);
// $send_image->bindParam(":display_pic", $image);
// $send_image->bindParam(":user_id", $_SESSION['id']);


// if($send_image->execute()) {
//     echo $send_image->execute();
//     if (move_uploaded_file($_FILES['image']['tmp_name'], $target)){
//         $msg = "profile Picture Uploaded Successfully!";
//     } else {
//         $msg = "Failed to upload Profile Picture";
//     }
// } else {
//     $msg = "Some Errors just Occured, Please Try again!";
// }


// echo "<script>console.log(".$profile.")</script>";
?>
<div class="profile-main">
    <div class="profile-wrapper" style="background-image: url(images/<?php echo $user->bg_pic; ?>);">
        <div class="profile-banner">
            <div class="profile-picture" style="background-image: url(images/<?php echo $user->display_pic; ?>);">
                
            </div>
            <div class="profile-info">
                <p><?php echo ucfirst($profile->firstname)." ".ucfirst($profile->lastname); ?></p>
                <span><?php echo $profile->email; ?></span>
            </div>
        </div>
    </div>

    <div class="details-show">
        <div class="entry">
            <span class="key">Fullname: </span>
            <span class="value"><?php echo ucfirst($profile->firstname)." ".ucfirst($profile->lastname); ?></span>
        </div>
        <div class="entry">
            <span class="key">Email: </span>
            <span class="value"><?php echo ucfirst($profile->email); ?></span>
        </div>
        <div class="entry">
            <span class="key">Phone: </span>
            <span class="value"><?php echo ucfirst($profile->phone); ?></span>
        </div>
        <div class="entry">
            <span class="key">Address: </span>
            <span class="value"><?php echo ($profile->address ? ucfirst($profile->address) : "No Entry..."); ?></span>
        </div>
        <div class="entry">
            <span class="key">Country: </span>
            <span class="value"><?php echo ($profile->country ? ucfirst($profile->country) : "No Entry..."); ?></span>
        </div>
        <div class="entry">
            <span class="key">State: </span>
            <span class="value"><?php echo ($profile->state ? ucfirst($profile->state) : "No Entry..."); ?></span>
        </div>
        <div class="entry">
            <span class="key">City: </span>
            <span class="value"><?php echo ($profile->city ? ucfirst($profile->city) : "No Entry..."); ?></span>
        </div>
        <div class="entry">
            <span class="key">Postal Code: </span>
            <span class="value"><?php echo ($profile->postal_code ? ucfirst($profile->postal_code) : "No Entry..."); ?></span>
        </div>
        <div class="entry">
            <span class="key">Date of Birth: </span>
            <span class="value"><?php echo ucfirst($profile->dob); ?></span>
        </div>
        <a href="#edit-profile" class="btn btn-primary mb-3 float-right" style="display: block;">Edit Profile</a>
    </div>

    <div class="container-fluid" id="edit-profile">
        <form action="profile" class="user-details" method="POST" enctype="multipart/form-data">
            <div class="row my-5 px-4">
                <h4 class="pl-3 mb-4">Edit Profile</h4>
                <div class="col-12">
                    <label for="name">Name</label>
                    <input required type="text" name="name" id="name" placeholder="fullname" value="<?php echo ucfirst($profile->firstname)." ".ucfirst($profile->lastname); ?>">
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12 col-lg-6">
                    <label for="email">Email</label>
                    <input required type="text" name="email" id="email" placeholder="email" value="<?php echo ucfirst($profile->email); ?>">
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12 col-lg-6">
                    <label for="phone">Phone</label>
                    <input required type="number" name="phone" id="phone" placeholder="phone" value="<?php echo ucfirst($profile->phone); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <label for="address">Address</label>
                    <input required type="text" name="address" id="address" placeholder="address" value="<?php echo ucfirst($profile->address1); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <label for="country">country</label>
                    <input required type="text" name="country" id="country" placeholder="country" value="<?php echo ucfirst($profile->country); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <label for="state">State</label>
                    <input required type="text" name="state" id="state" placeholder="state" value="<?php echo ucfirst($profile->state); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <label for="city">City</label>
                    <input required type="text" name="city" id="city" placeholder="city" value="<?php echo ucfirst($profile->city); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <label for="zipcode">Zipcode</label>
                    <input required type="text" name="zipcode" id="zipcode" placeholder="postal code" value="<?php echo ucfirst($profile->postal_code); ?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12" placeholder="date of birth" value="<?php echo $profile->dob; ?>">
                    <label for="dob">D.O.B</label>
                    <input required type="date" name="dob" id="dob">
                </div>
                <div class="col-12">
                    <label for="image">Profile Picture</label>
                    <input required type="file" name="image" id="image">
                </div>
                <button class="btn btn-primary ml-3 mt-3" type="submit" name="update_profile">Edit Profile</button>
            </div>
        </form>
    </div>
</div>
<?php require_once "includes/footer.php" ?>
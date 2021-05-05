<?php require_once "includes/header.php";

$sql = "select pay.*, u.*, fund.*, 
        proj.*, type.*, farm.* from payments as pay
        inner join users as u on pay.user_id = u.user_id and u.user_id = :user_id and pay.payment_status = 'success'
        inner join funding as fund on pay.funding = fund.funding_id
        inner join farm_projects as proj on proj.id = fund.project
        inner join farm_type as type on proj.farm_type = type.type_id
        inner join farm_list as farm on proj.farm_id = farm.farm_list_id";
        
class USER_ACCOUNT extends DATABASE{
    public function __construct(){
        parent::__construct();
    }
}

$transaction = new USER_ACCOUNT();

$stmt = $transaction->connect->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['id']);
$stmt->execute();
$result = $stmt->fetchAll();

// print_r($result);

?>
<div class="container-fluid" style="margin-top: 2rem; ">
  <div class="view-section p-3">
    <h4 style="text-transform: capitalize; "><?php echo $this->getAction(); ?></h4>
    <div class="row filter-section">
      <div class="limit-form col-md-6 col-sm-12 col-xs-12 col-lg-6">
        <p>
          <form action="#" method="post">
            <span>Showing</span> 
            <select name="limit" id="">
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="30">30</option>
            </select>
            <span>entries</span>
          </form>
        </p>
      </div>
      <div class="search-form col-md-6 col-sm-12 col-xs-12 col-lg-6">
        <p>
          <form action="#" method="post">
            <span>Search: </span>
            <input type="text" name="search-bar" class="">
          </form>
        </p>
      </div>
    </div>
    <div class="row row-of-table">
      <div class="col-12 column-of-table">
        <div class="table-wrapper">
          <table class="table custom-table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Project</th>
                <th scope="col">Amount</th>
                <th scope="col">Duration</th>
                <th scope="col">Date Invested</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if($result): ?>
                <?php
                // print_r($result);
                $count = 0;
                foreach($result as $key => $data){ 
                    $count++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $count; ?></th>
                        <td><?php echo $data->type; ?></td>
                        <td>&#8358;<?php echo $data->price; ?></td>
                        <td><?php echo $data->tenor; ?> Months</td>
                        <td><?php echo $data->date_payed; ?></td>
                        <td><?php echo $data->payment_status; ?></td>
                    </tr>
                <?php } ?>
              <?php else: ?>
              <tr>
                  <td>no transaction</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once "includes/footer.php" ?>
<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$ac_id = $_GET['id'];
$client_id = $_GET['cid'];
$lnk = $_GET['lnk'];
$name = $_SESSION['fs_admin_name'];

if($ac_id !='' && $client_id !=''){
	
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $query = "SELECT * FROM `tbl_fs_client_accounts` where fs_client_id = '$client_id' AND bl_live = 1 ORDER by ca_order_by DESC LIMIT 1;";

	  $result = $conn->prepare($query);
	  $result->execute();
	  $ca_order_by = 1;
	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $ca_order_by = $row['ca_order_by'];
		  $ca_order_by ++;
	  }


		$sql = "INSERT INTO `tbl_fs_client_accounts` (`fs_client_id`, `ac_account_id`, `ca_order_by`, `ca_linked`, `bl_live`, `created_by`, `created_date`) VALUES ('$client_id', '$ac_id', '$ca_order_by', '$lnk', '1', '$name', '$str_date')";
		$conn->exec($sql);

	$conn = null;
}




try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $query = "SELECT * FROM `tbl_fs_client_accounts` where fs_client_id = '$client_id' AND ca_linked = 0 AND bl_live = 1 ORDER by ca_order_by DESC;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $accounts[] = $row;
  }
	
	
  $query = "SELECT * FROM `tbl_fs_client_accounts` where fs_client_id = '$client_id' AND ca_linked = 1 AND bl_live = 1 ORDER by ca_order_by DESC;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $li_accounts[] = $row;
  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}


?>

<div class="account-table">
							<h2>Primary Accounts</h2>
                            <div class="account-table__head">
								<label>Client Code</label>
                                <label>Designation</label>
                                <label>Type</label>
                                <label>Display Name</label>
								<label>Priority</label>
                                <label>Delete</label>
                            </div><!--head-->
                           <div id="blank">
							 <?php foreach($accounts as $account) { 
									debug($account['ac_account_id']);
									$acc = getFields('tbl_accounts','id',$account['ac_account_id']); ?>
									<div class="account-table__body accounts">
										<p><?=$acc[0]['ac_client_code'];?></p>
										<p><?=$acc[0]['ac_designation'];?></p>
										<p><?=$acc[0]['ac_product_type'];?></p>
										<p><?=$acc[0]['ac_display_name'];?></p>
										<p><?=$account['ca_order_by'];?></p>
										<div class="radio-item">
											<a href="del_client_account.php?id=<?=$account['id']?>" class="delclientaccount"><input class="star-marker " name="del" type="checkbox" id="del" value="1">
											<?php define('__ROOT__', dirname(dirname(__FILE__)));
											include(__ROOT__.'/admin/images/delete.php'); ?></a>
										</div><!--radio-->
									</div><!--body-->
    			            <?php } ?>  
						   </div>
                        </div><!--account table-->
						
						
						<div class="account-table">
							<h2>Linked Accounts</h2>
                            <div class="account-table__head">
								<label>Client Code</label>
                                <label>Designation</label>
                                <label>Type</label>
                                <label>Display Name</label>
								<label>Priority</label>
                                <label>Delete</label>
                            </div><!--head-->
                           <div id="clientaccounts">
							 <?php foreach($li_accounts as $li_account) { 
									
									$acc = getFields('tbl_accounts','id',$li_account['ac_account_id']); ?>
									<div class="account-table__body accounts">
										<p><?=$acc[0]['ac_client_code'];?></p>
										<p><?=$acc[0]['ac_designation'];?></p>
										<p><?=$acc[0]['ac_product_type'];?></p>
										<p><?=$acc[0]['ac_display_name'];?></p>
										<p><?=$li_account['ca_order_by'];?></p>
										<div class="radio-item">
											<a href="del_client_account.php?id=<?=$li_account['id']?>" class="delclientaccount"><input class="star-marker " name="del" type="checkbox" id="del" value="1">
											<?php define('__ROOT__', dirname(dirname(__FILE__)));
											include(__ROOT__.'/admin/images/delete.php'); ?></a>
										</div><!--radio-->
									</div><!--body-->
    			            <?php } ?>  
						   </div>
                        </div><!--account table-->
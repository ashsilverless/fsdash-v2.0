<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();

$client_id = $_GET['id'];

//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


	$query = "SELECT *  FROM `tbl_fsusers` where id = $client_id;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$fs_client_code = $row['fs_client_code'];
		$user_prefix = $row['user_prefix'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$user_name = $row['user_name'];
		$destruct_date = $row['destruct_date'];
		$email_address = $row['email_address'];
		$telephone = $row['telephone'];
		$strategy = $row['strategy'];
		$desc = $row['fs_client_desc'];
		$googlecode = $row['googlecode'];
        $confirmed_by = $row['confirmed_by'];
        $confirmed_date = $row['confirmed_date']= date('d M Y');
	}


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

$qrCodeUrl 	= $ga->getQRCodeGoogleUrl($email_address, $googlecode,'www.featherstone.co.uk');

?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
		<h1 class="heading heading__2">Client Details</h1>
		<form action="editclient.php?id=<?=$client_id;?>" method="post" id="editclient" name="editclient" class="asset-form">

            <div class="content client">
                <div class="client__pers-details">
                    <div class="item prefix mb1">
                        <label>Prefix</label>
                        <div class="select-wrapper">
                            <select name="user_prefix" id="user_prefix" class="select-css">
                                <option value="Mr" <?php if($user_prefix=='Mr'){?>  selected="selected"<?php }?>>Mr</option>
          					  <option value="Mrs" <?php if($user_prefix=='Mrs'){?>  selected="selected"<?php }?>>Mrs</option>
          					  <option value="Miss" <?php if($user_prefix=='Miss'){?>  selected="selected"<?php }?>>Miss</option>
          					  <option value="Dr" <?php if($user_prefix=='Dr'){?>  selected="selected"<?php }?>>Dr</option>
                            </select>
                        </div><!--sel-->
                    </div>
                    <div class="item first-name">
                        <label>First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?= $first_name;?>">
                    </div>
                    <div class="item second-name">
                        <label>Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?= $last_name;?>">
                    </div>
                    <div class="item user-name mb1">
                        <label>Display Name</label>
                        <input type="text" id="user_name" name="user_name" value="<?= $user_name;?>">
                    </div>
                    <div class="item email mb1">
                        <label>Client Email</label>
                        <input type="text" id="client_email" name="client_email" value="<?=$email_address;?>">
                    </div>
                    <div class="item user-id">
                        <label>User ID</label>
							<input name="fs_client_code" id="fs_client_code" value="FS-00<?=$client_id;?>" readonly>
                    </div>
                    <div class="item mb1">
                        <label>Strategy</label>
                            <div class="select-wrapper">
                            <select name="strategy" id="strategy" class="select-css">
								<option value="" selected = 'selected'>Select</option>
								<?php $stratHeadings =  getTable('tbl_fs_strategy_names');
								foreach ($stratHeadings as $strathead): ?>
									<option value="<?=$strathead['strat_name'];?>" <?php if(strtolower ($strategy)==strtolower ($strathead['strat_name'])){?>selected = 'selected' <?php }?>><?=$strathead['strat_name'];?></option>
								<?php endforeach; ?>
                            </select>
                            <i class="fas fa-sort-down"></i>
                        </div>
                    </div>
                    <div class="item">
                        <label>Client Type</label>
                        <div class="select-wrapper">
                            <select name="fs_client_desc" id="fs_client_desc" class="select-css">
                                <option value="Private Client" <?php if(strtolower ($desc)=='private client'){?>selected = 'selected' <?php }?>>Private</option>
  					  <option value="Corporate Client" <?php if(strtolower ($desc)=='corporate client'){?>selected = 'selected' <?php }?>>Corporate</option>
                            </select>
                            <i class="fas fa-sort-down"></i>
                        </div>
                    </div>
                    <div class="item">
                        <label>Expires</label>
                        <input name="destruct_date" type="text" id="destruct_date" title="destruct_date" value="<?=$destruct_date;?>">
                    </div>
                    <div class="item qr">
                        <label>QR Code</label>
                        <img src='<?php echo $qrCodeUrl; ?>'/>
                    </div>
                </div><!--pers details-->

                <div class="client__pers-accounts">
                    <!--call accounts-->
                    <h3 class="heading heading__2">Accounts</h3>
					<!--add accounts-->
                    <h3 class="heading heading__4">Add Accounts</h3>
                    <div class="add-account">
                        <div class="add-account__existing">
							<div class="search-input"><input type="text" name="ac_name" size="80" class="ac_name" placeholder="Client Code or Account Display Name"><input type="hidden" id="ac_id" size="5"></div>
							<button class="button button__raised addaccount" value="Add Account">Add Account</button>
							<button class="button button__raised addlinkedaccount" value="Add Linked Account">Add Linked Account</button>
                        </div>
                    </div><!--add account-->
					
                    <div class="recess-box clientaccounts">
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
                           <div id="blank">
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
                    </div>
                    <div class="commit-changes">
						<p>Priority : <i>Higher numbers come first</i></p>
                        <input type="submit" class="button button__raised" value="Save Changes">
                    </div>

                    
                </div><!--client pers accounts-->

            </div><!--content-->

            <div class="control">
                <h3 class="heading heading__2">Account Actions</h3>
                <p class="mb1">Last edited by <?= $confirmed_by; ?> on <?= $confirmed_date; ?></p>
                <a href="#modalCLIENT" data-toggle="modal" class="button button__raised button__inline mb1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.59 19.59"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,9.79A9.84,9.84,0,0,1,9.79,0a9.85,9.85,0,0,1,9.8,9.79,9.85,9.85,0,0,1-9.8,9.8A9.85,9.85,0,0,1,0,9.79Zm16.11,5.76a8.53,8.53,0,1,0-12.63,0c.9-1.24,3.24-2.47,6.31-2.47S15.21,14.3,16.11,15.55ZM6.46,7.71a3.48,3.48,0,0,1,3.33-3.6,3.48,3.48,0,0,1,3.33,3.6A3.46,3.46,0,0,1,9.79,11.4,3.48,3.48,0,0,1,6.46,7.71Z"/></g></g></svg>Client Side View</a>
                <button type="submit" class="button button__raised mb1">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 21.8 21.8" style="enable-background:new 0 0 21.8 21.8;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#96E8C4;}
                        </style>
                        <g id="Layer_2_1_">
                            <g id="Layer_1-2">
                                <path class="st0" d="M7.7,19.4c-0.1-0.1-0.3-0.2-0.5-0.2H4.9c-1.6,0-2.3-0.7-2.3-2.3v-2.3c0-0.2-0.1-0.4-0.2-0.5l-1.6-1.6
                                    c-0.9-0.7-1.1-1.9-0.4-2.8c0.1-0.1,0.2-0.3,0.4-0.4l1.6-1.6c0.1-0.1,0.2-0.3,0.2-0.5V4.9c0-1.6,0.7-2.3,2.3-2.3h2.3
                                    c0.2,0,0.4-0.1,0.5-0.2l1.6-1.6c0.6-0.9,1.8-1.1,2.7-0.5c0.2,0.1,0.4,0.3,0.5,0.5l1.6,1.6c0.1,0.1,0.3,0.2,0.5,0.2h2.3
                                    c1.6,0,2.3,0.7,2.3,2.3v2.2c0,0.2,0.1,0.4,0.2,0.5L21,9.3c0.9,0.7,1.1,1.9,0.4,2.8c-0.1,0.1-0.2,0.3-0.4,0.4l-1.6,1.6
                                    c-0.2,0.1-0.2,0.3-0.2,0.5v2.3c0,1.6-0.7,2.3-2.3,2.3h-2.3c-0.2,0-0.4,0.1-0.5,0.2L12.5,21c-0.6,0.9-1.8,1.1-2.7,0.5
                                    c-0.2-0.1-0.3-0.3-0.5-0.5L7.7,19.4z M11.7,20.1l1.9-1.9c0.2-0.2,0.4-0.3,0.7-0.3H17c0.9,0,1.1-0.2,1.1-1.1v-2.7
                                    c0-0.3,0.1-0.5,0.3-0.7l1.9-1.9c0.6-0.6,0.6-0.9,0-1.5l-1.9-1.9C18.1,8.1,18,7.8,18,7.6V4.9c0-0.9-0.2-1.1-1.1-1.1h-2.7
                                    c-0.3,0-0.5-0.1-0.7-0.3l-1.9-1.9C11,1,10.8,1,10.1,1.7L8.3,3.5C8.1,3.7,7.8,3.9,7.6,3.8H4.9C4,3.8,3.8,4,3.8,4.9v2.7
                                    c0,0.3-0.1,0.5-0.3,0.7l-1.9,1.9C1,10.8,1,11,1.7,11.7l1.9,1.9c0.2,0.2,0.3,0.4,0.3,0.7v2.7C3.8,17.8,4,18,4.9,18h2.7
                                    c0.3,0,0.5,0.1,0.7,0.3l1.9,1.9C10.8,20.8,11,20.8,11.7,20.1L11.7,20.1z M8.9,15.4l-3.2-3.6c-0.1-0.1-0.2-0.3-0.2-0.4
                                    c0-0.4,0.3-0.6,0.7-0.6c0.2,0,0.3,0.1,0.4,0.2l2.7,3l5.1-7.2c0.2-0.3,0.6-0.4,0.9-0.2c0.2,0.1,0.3,0.3,0.3,0.5
                                    c0,0.1-0.1,0.3-0.1,0.4l-5.6,7.9c-0.1,0.2-0.3,0.2-0.5,0.2C9.2,15.5,9,15.5,8.9,15.4L8.9,15.4z"/>
                            </g>
                        </g>
                    </svg>
                    Save Changes
              </button>
                <a href="" class="button button__raised button__inline button__danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.82 21.82"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.71,19.39a.71.71,0,0,0-.54-.22H4.91c-1.57,0-2.26-.69-2.26-2.26V14.65a.67.67,0,0,0-.23-.53L.83,12.5a2,2,0,0,1,0-3.19l1.59-1.6a.72.72,0,0,0,.23-.54V4.92c0-1.59.69-2.27,2.26-2.27H7.17a.73.73,0,0,0,.54-.22L9.31.83a1.94,1.94,0,0,1,3.19,0l1.61,1.6a.71.71,0,0,0,.54.22h2.26c1.57,0,2.26.69,2.26,2.27V7.17a.72.72,0,0,0,.23.54L21,9.31a2,2,0,0,1,0,3.19L19.4,14.12a.67.67,0,0,0-.23.53v2.26c0,1.57-.69,2.26-2.26,2.26H14.65a.71.71,0,0,0-.54.22L12.5,21a1.94,1.94,0,0,1-3.18,0Zm4,.76,1.87-1.88a.89.89,0,0,1,.7-.29h2.67c.89,0,1.07-.17,1.07-1.07V14.23a1,1,0,0,1,.28-.69l1.89-1.87c.63-.64.63-.87,0-1.52L18.26,8.28a.94.94,0,0,1-.28-.7V4.92c0-.9-.18-1.08-1.07-1.08H14.24a.89.89,0,0,1-.7-.29L11.67,1.67C11,1,10.79,1,10.15,1.67L8.28,3.55a.89.89,0,0,1-.7.29H4.91C4,3.84,3.84,4,3.84,4.92V7.58a.94.94,0,0,1-.28.7L1.67,10.15c-.63.65-.63.88,0,1.52l1.89,1.87a1,1,0,0,1,.28.69v2.68c0,.9.17,1.07,1.07,1.07H7.58a.89.89,0,0,1,.7.29l1.87,1.88C10.79,20.79,11,20.79,11.67,20.15ZM6.89,14.38a.55.55,0,0,1,.18-.44l3-3-3-3a.54.54,0,0,1-.18-.44A.6.6,0,0,1,7.5,7a.54.54,0,0,1,.43.19l3,3,3-3A.57.57,0,0,1,14.32,7a.6.6,0,0,1,.61.6.58.58,0,0,1-.18.43l-3,3,3,3a.64.64,0,0,1,.19.45.61.61,0,0,1-.61.61.58.58,0,0,1-.45-.2l-3-3L8,14.79a.57.57,0,0,1-.45.2A.61.61,0,0,1,6.89,14.38Z"/></g></g></svg>Cancel</a>
            </div>

        </form>

    </div>
</div>

<?php
require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once('modals/login_as.php');
require_once('modals/delete-cat.php');
require_once(__ROOT__.'/global-scripts.php');?>
<script src="js/typeahead.js"></script>
    <script>
      feather.replace()
    </script>

    <script>
		
		$( document ).ready(function() {

			
			$('input.ac_name').typeahead({
                name: 'ac_name',
				display: 'value',
                remote: 'ac_name.php?query=%QUERY'
            }).on('typeahead:selected', function(event, selection) {
				$("#ac_id").val(selection.label)
			});
			
			$(".addaccount").click(function(e){
				e.preventDefault();
				var ac_id = $("#ac_id").val();
				$(".clientaccounts").html('&nbsp');
				$(".clientaccounts").load("addclientaccount.php?id="+ac_id+"&cid=<?=$client_id;?>&lnk=0");
				$('.ac_name').val('');
			});
			
			$(".addlinkedaccount").click(function(e){
				e.preventDefault();
				var ac_id = $("#ac_id").val();
				$(".clientaccounts").html('&nbsp');
				$(".clientaccounts").load("addclientaccount.php?id="+ac_id+"&cid=<?=$client_id;?>&lnk=1");
				$('.ac_name').val('');
			});
			
			$(document).on('click', '.delclientaccount', function(e) {
				e.preventDefault();
				var ac_id = getParameterByName('id',$(this).attr('href'));
				
				console.log(ac_id);
				
				//$("#clientaccounts").html('&nbsp');
				$(".clientaccounts").load("del_client_account.php?id="+ac_id+"&cid=<?=$client_id;?>");
			});

		});

		$('#modalCLIENT').on('show.bs.modal', function(e) {
			var url = 'https://dashboard.featherstonepartners.co.uk/admin/autologinas.php?cid=<?=$client_id;?>';
			$("#modalCLIENT iframe").attr("src", url);
		});

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addasset").click(function(e){
          e.preventDefault();
		  $("#assetdetails").load("add_asset.php");
		});

		$(".editasset").click(function(e){
          e.preventDefault();
		  var theme_id = getParameterByName('id',$(this).attr('href'));
			console.log(theme_id);
		  $("#assetdetails").load("edit_asset.php?id="+theme_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});


	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    </script>
  </body>
</html>

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    
     //    Get the products   ///

  $query = "SELECT DISTINCT isin_code FROM `tbl_fs_fund` where bl_live = 1;";
    
  $result = $conn->prepare($query); 
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $isincodes[] = $row['isin_code'];
  }

  $conn = null;        // Disconnect

}


catch(PDOException $e) {
  echo $e->getMessage();
}

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	
	<!-- Upload script -->
	<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

  </head>

  <body>

	<nav class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0">
		<div id="logo" class="col-md-2"><img src="images/fs_logo1.jpg" alt="" height="110" align="left"/></div>
		<div id="righthandside" class="col-md-10">
			<div id="title" style="cleath:both;"><h2><strong>Client Portal Admin Area</strong></h2></div>
			<div id="menuitems" class="mt-4">
				<a class="btn btn-admin shadow-sm " href="home.php">Dashboard</a>
				<a class="btn btn-admin shadow-sm active" href="funds.php">Funds</a>
				<a class="btn btn-admin shadow-sm" href="assets.php">Asset Allocation &amp; Holdings</a>
				<a class="btn btn-admin shadow-sm" href="themes.php">Themes</a>
				<a class="btn btn-admin shadow-sm " href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm" href="clients.php">Clients</a>
				<a class="btn btn-admin shadow-sm" href="staff.php">Staff</a>
				
				<span style="float:right;"><a class="btn btn-grey shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></span>
			</div>
		</div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky mt-115 ml-3">
			  <h4>Hello <?=$_SESSION['username'];?></h4>
			  <p>Last Login:<br>1:24pm on Tue 12 Dec 19.</p>
			  <p><a href="#">Not You? Click here</a></p>
			  <a class="btn btn-admin shadow-sm" href="#">Account Settings</a>
			  <a class="btn btn-admin shadow-sm" href="#">Help &amp; Support</a>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Daily & Historical Prices</h1>
			<a href="#" class="addfund btn btn-add"><i data-feather="plus-square"></i> Add Fund</a>
			
			<div id="funddetails" class="col-md-12"></div>
			
			<div class="table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
					  <td colspan="2" rowspan="2" valign="middle" bgcolor="#FFFFFF"><strong>Fund Name</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>ISIN Code</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>Fund Sedol</strong></td>
					  <td rowspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><strong>Benchmark</strong></td>
					  <td colspan="2" align="center" bgcolor="#FFFFFF" style="border-top:1px solid #666; border-left:1px solid #666; border-right:1px solid #666;"><strong>Current Price</strong></td>
					  <td colspan="2" align="center" bgcolor="#FFFFFF" style="border-top:1px solid #666; border-left:1px solid #666; border-right:1px solid #666;"><strong>Add Price</strong></td>
					  <td bgcolor="#FFFFFF">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-left:1px solid #666;"><strong>Price</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-right:1px solid #666;"><strong>As At</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-left:1px solid #666;"><strong>Price</strong></td>
					  <td align="center" bgcolor="#FFFFFF" style="border-bottom:1px solid #666; border-right:1px solid #666;"><strong>As At</strong></td>
					  <td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<?php
					try {
					  // Connect and create the PDO object
					  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
					  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

					  $codes = array();	
						 //    Get the funds   //
						
						foreach($isincodes as $code) {
							$query = "SELECT *  FROM `tbl_fs_fund` where isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

					  		$result = $conn->prepare($query); 
					  		$result->execute();

							  // Parse returned data
							  while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
								$codes[] = $row['isin_code']; 
								$as_at = date('j M y',strtotime($row['correct_at'])); ?>
				<form method="post" name="form<?=$row['isin_code'];?>" id="form<?=$row['isin_code'];?>">
								  <tr>
									  <td class="head<?=$row['isin_code'];?> normal"><?= $row['fund_name'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><a href="#" class="toggler indicator" data-prod-name="<?=$row['isin_code'];?>"><i class="fas fa-caret-up arrow<?=$row['isin_code'];?>" style="font-size:2em;"></i></a></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['isin_code'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['fund_sedol'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['benchmark'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $row['current_price'];?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><?= $as_at;?></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><input name="price<?=$row['isin_code'];?>" type="text" id="price<?=$row['isin_code'];?>" title="price" value="0.00" size="4"></td>
									  <td class="head<?=$row['isin_code'];?> normal text-nowrap" align="center"><input name="pricedate<?=$row['isin_code'];?>" type="text" id="pricedate<?=$row['isin_code'];?>" title="pricedate" value="" size="6"></td>
									  <td class="head<?=$row['isin_code'];?> normal"><input type="submit" style="font-size:0.8em" class="btn btn-admin" value="Add Price"></td>
								  </tr>
								</form>
								  <tr class="<?=$row['isin_code'];?>" style="font-size:0.8em; background-color:white; font-weight:bold; display:none;">
									<td align="center" colspan="10" id="daily_prices<?= $row['isin_code'];?>"></td>
								  </tr>
					<tr class="<?=$row['isin_code'];?>" style="font-size:0.8em; background-color:white; font-weight:bold; display:none;"><td colspan="10" align="center"><!--  #Delete Fund    <a href="#" data-href="deletefund.php?ic=<?= $row['isin_code'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.85em; font-weight:bold;">Delete Fund</a> --></td></tr>
					  <?php }
						}
					  $conn = null;        // Disconnect

					}

					catch(PDOException $e) {
					  echo $e->getMessage();
					}
					?>                   
			      </tbody>
				</table>
		  </div>
			
			
			
		  
			
		
            
		
            
        </main>
      </div>
    </div>


	  
<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

	  
<!-- Delete Modal-->
  <div class="modal deletefund" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Fund?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this fund and all it's data.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger btn-ok">Delete</a>
        </div>
      </div>
    </div>
  </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
      
     <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <!-- Date Picker -->	  

	  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>
	  
	<!-- Bootstrap Edit in Place --> 
	<link href="be/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
	<script src="be/js/bootstrap-editable.js"></script>
	<script src="be/js/moment.min.js"></script>
	  
    <script>
      feather.replace()
    </script>

   
      
    <script>
		
		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});
		
		$(".addfund").click(function(e){
          e.preventDefault();
		  $("#funddetails").load("add_fund.php");
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
		
	<?php for($a=0;$a<count($codes);$a++){ ?>
		
		
		$('#pricedate<?=$codes[$a]?>').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });
		
		$("#form<?=$codes[$a]?>").submit(function(e) {
			e.preventDefault(); // avoid to execute the actual submit of the form.
			var form = $(this);
			$.ajax({
				   type: "POST",
				   url: 'addfundprice.php?ic=<?=$codes[$a]?>',
				   data: form.serialize(), // serializes the form's elements.
				   success: function(data){ $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>"); }
				 });
		});
		
		
		$("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>");
		
		$(document).on('click', '.monthback<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });


        $(document).on('click', '.monthnext<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });
		
	<?php } ?>	
    
		
	 

    </script>
  </body>
</html>

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

//  Record per page
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}


	
if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 10;
}


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT id FROM `tbl_fsusers` where user_type LIKE 'user' AND bl_live = 1 ORDER BY fs_client_code ASC;";
	
    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$rows[] = $row;
	}

	$num_rows = count($rows);
	
	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;
	
	debug($num_rows);
	
	$query = "SELECT *  FROM `tbl_fsusers` where user_type LIKE 'user' AND bl_live = 1 ORDER BY fs_client_code ASC LIMIT $offset,$recordsPerPage;";

    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$userData[] = $row;
	}

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';
			  
if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){ 
  $endnotifier = "";
  $end = $totalPageNumber; 
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>'; 
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10">10</a>&nbsp;|&nbsp;<a href="?rpp=30">30</a>&nbsp;|&nbsp;<a href="?rpp=50">50</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';
	
$rspaging .= $endnotifier.$last.$ipp.'</div>';

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
				<a class="btn btn-admin shadow-sm " href="funds.php">Funds</a>
				<a class="btn btn-admin shadow-sm " href="assets.php">Asset Allocation &amp; Holdings</a>
				<a class="btn btn-admin shadow-sm " href="themes.php">Themes</a>
				<a class="btn btn-admin shadow-sm " href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm active" href="clients.php">Clients</a>
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

        <h1 class="h2">Clients</h1>
			<a href="add_client.php"><i data-feather="plus-square"></i> Add New Client</a>
			
			<div class="col-md-10  table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <thead>
					<tr>
				      <th width="30%" bgcolor="#FFFFFF"><strong>Client Name <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="20%" bgcolor="#FFFFFF"><strong>User ID <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="20%" bgcolor="#FFFFFF"><strong>Strategy <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="25%" bgcolor="#FFFFFF"><strong>Linked Account Names</strong></th>
					  <th width="5%" bgcolor="#FFFFFF"></td>
				  </tr>
				</thead>	
				<tbody>
					<?php
					foreach($userData as $client) {
						$linkedNames = '';
						if($client['linked_accounts']!=''){
							$lnk_array = explode('|',$client['linked_accounts']);
							for($b=0;$b<count($lnk_array);$b++){
								if($lnk_array[$b]!=''){  
									$linkedNames .= getUserName($lnk_array[$b]).'<br>';  
								};
							}
						}
						?>
								<tr>
								  <td style="border-right:1px dashed #999;"><?= $client['user_name'];?></td>
								  <td style="border-right:1px dashed #999;"><?= $client['fs_client_code'];?></td>
								  <td style="border-right:1px dashed #999;"><span class='<?= $client['strategy'];?>'><?= $client['strategy'];?></span></td>
								  <td style="font-size:0.85em;"><?=$linkedNames;?></td>
								  <td><a href="edit_client.php?id=<?= $client['id'];?>" class="btn btn-admin" style="font-size:0.8em; font-weight:bold;">Edit</a></td>
							  </tr>
						<?php } ?>             
			      </tbody>
				</table>
				
				
		  </div>
			
			<?=$rspaging;?>
			
		  
			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>
            
		<div id="assetdetails" class="col-md-12 mt-5"></div>
            
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
          <h5 class="modal-title" id="deleteModal">Delete this Asset?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Asset.</div>
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
	<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
	<script src="js/bootstrap-datepicker.min.js"></script>
	  
	<!-- Table Sorter -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/js/jquery.tablesorter.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/js/jquery.tablesorter.widgets.min.js"></script>
	  
    <script>
      feather.replace()
    </script>

   
      
    <script>
		
		$( document ).ready(function() {

			$(".table").tablesorter();

		});
		
		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});
		
		$(".addclient").click(function(e){
          e.preventDefault();
		  $("#clientdetails").load("add_client.php");
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

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT * FROM `tbl_fs_peers` where bl_live > 0 ORDER BY id ASC;";
	
    $result = $conn->prepare($query); 
    $result->execute();
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$peerGroup[] = $row;
	}

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

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
				<a class="btn btn-admin shadow-sm active" href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm " href="clients.php">Clients</a>
				<a class="btn btn-admin shadow-sm " href="staff.php">Staff</a>
				
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
            
			<div id="peergroups" class="col-md-12 mt-5">
					<div id="theme_details" class="col-md-8" style="float:left;">		
					  <h4>Peer Comparison</h4>
						<table class="table table-sm table-striped">
							<thead>
								<tr>
								  <th width="45%" valign="middle" bgcolor="#FFFFFF"><strong>Peer <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="15%" valign="middle" bgcolor="#FFFFFF"><strong>Return <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="15%" valign="middle" bgcolor="#FFFFFF"><strong>Volatility <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
								  <th width="5%" valign="middle" bgcolor="#FFFFFF"><strong>Trend<br>Line</strong></th>
								  <th width="20%" valign="middle" bgcolor="#FFFFFF"></td>
							  </tr>
							</thead>	
							<tbody>
								<?php foreach($peerGroup as $peer) {
									$peer['fs_trend_line'] == '0' ? $trendLine = '<img src="images/square.svg" width="15">' : $trendLine = '<img src="images/check-square.svg" width="15">';
									?>
                                    <tr>
										<td style="border-right:1px dashed #999;"><span class="c" style="--c: <?= $peer['fs_peer_color'];?>"><?= $peer['fs_peer_name'];?></span></td>
                                      <td style="border-right:1px dashed #999;"><?= $peer['fs_peer_return'];?></td>
                                      <td style="border-right:1px dashed #999;"><?= $peer['fs_peer_volatility'];?></td>
										<td style="border-right:1px dashed #999;"><a href="edittrend.php?id=<?= $peer['id'];?>&tl=<?=$peer['fs_trend_line'];?>" class="btn btn-admin" style="font-size:0.8em; font-weight:bold;"><?=$trendLine;?></a></td>
                                      <td><a href="edit_peer.php?id=<?= $peer['id'];?>" class="edit btn btn-admin" style="font-size:0.8em; font-weight:bold;">Edit</a><a href="#" data-href="deletepeer.php?id=<?= $peer['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.8em; font-weight:bold;">Delete</a></td>
                                  </tr>
									<?php } ?>             
							  </tbody>
							</table>


						
						

							
					</div>



					<div id="peer_actions" class="col-md-4" style="float:left;">
						<h5>Edit/Add Peer</h5>
						<div id="peer">
							<form action="addpeer.php" method="post" id="addpeer" name="addpeer">
								<table width="100%" border="0">
								  <tbody>
									<tr>
									  <td colspan="2"><p>Peer Group Name<br> <input type="text" id="fs_peer_name" name="fs_peer_name" style="width:90%;"></p></td>
									  </tr>
									<tr>
									  <td><p>Return<br><input type="text" name="fs_peer_return" id="fs_peer_return" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
										<p>Trend Line<br><input type="checkbox" name="fs_trend_line" id="fs_trend_line" value="1"><label for="fs_trend_line">Yes </label></p></td>
									  <td><p>Volatility<br><input type="text" name="fs_peer_volatility" id="fs_peer_volatility" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
										<p>Trend Colour<br><input size="7" id="fs_peer_color" name="fs_peer_color" class="jscolor {hash:true}" value="000000"></p>	</td>
									</tr>

									<tr>
									  <td colspan="2"><input type="submit" style="font-size:0.8em;" class="btn btn-grey" value="Save Changes" <?php if($_SESSION['agent_level']< '2'){ ?>disabled<?php }?>></td>
									  </tr>
								  </tbody>
								</table>
						  </form>
						</div>
					</div>

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
  <div class="modal deletepeer" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Peer Group ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Peer Group.</div>
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
	
	<!-- Colour Picker -->
	<script src="js/jscolor.js"></script>

    <script>
		
	$(document).ready(function() {
		
		feather.replace()
		
		$(".table").tablesorter();
		
		$(".edit").click(function(e){
          e.preventDefault();
		  var peer_id = getParameterByName('id',$(this).attr('href'));
		  $("#peer").load("edit_peer.php?id="+peer_id);
		});
		
		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});
		
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

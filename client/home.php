<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");	error_reporting(E_ALL);
    */

$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];


$client_code = '1111';


$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
$testVar = 'test';
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the general products data for Client   ///


  $query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";


  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];
	  $linked_accounts = $row['linked_accounts'];

  }


     //    Get the products   ///

  $query = "SELECT DISTINCT fs_product_type FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $products[] = $row;
  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>

<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once(__ROOT__.'/page-sections/header-elements.php');
require_once(__ROOT__.'/page-sections/sidebar-elements.php');
?>

    <div class="col-md-9">
        <div class="border-box main-content daily-data">
            <div class="main-content__head">
                <h1 class="heading heading__1">Daily Valuation Data</h1>
                <p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p>
                <div class="button button__raised data-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.59 19.59"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,9.79A9.84,9.84,0,0,1,9.79,0a9.85,9.85,0,0,1,9.8,9.79,9.85,9.85,0,0,1-9.8,9.8A9.85,9.85,0,0,1,0,9.79Zm15.48,6.38L9.61,10.41a.7.7,0,0,1-.22-.56V1.28a8.53,8.53,0,1,0,6.09,14.89ZM17.1,5.38a8.53,8.53,0,0,0-6.67-4.09v7.9Zm-.89,10.05A8.54,8.54,0,0,0,17.58,6.3l-6.7,3.84Z"/></g></g></svg> View Charts</div>

            </div>
            <h2 class="heading heading__2"><?=$user_name;?></h2>

			<div class="calcs"></div>

			<div class="linked_calcs"></div>

<div class="data-section chart">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>

            </div>
        </div>

    </div>
</div>



</div>

</div>

</div>

</div>
<?php
require_once(__ROOT__.'/global-scripts.php');
require_once('../page-sections/footer-elements.php');
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/modals/logout.php');
require_once(__ROOT__.'/modals/password.php');
require_once(__ROOT__.'/modals/time-out.php');
?>

   <script>

	   $(document).ready(function() {

		  $(".calcs").load("__calcs.php?fs_cc=<?=$client_code;?>");

		  <?php if ($linked_accounts != ''){
					$lnkarray = explode('|',$linked_accounts);
					$lnk_array = array_filter($lnkarray);

					foreach ($lnk_array as $lnk_client_code): ?>

		   				$(".linked_calcs").append( $('<div>').load("__calcs.php?fs_cc=<?=$lnk_client_code;?>") );

		   			 //$(".linked_calcs").load("__calcs.php?fs_cc=<?=$lnk_client_code;?>");

		   		<?php endforeach; // End For

				}  //  End If ?>

	});




		Chart.defaults.global.legend.display = false;

/* ##########################################       LINE CHART     ################################################## */

		<?php
		try {
		  // Connect and create the PDO object
		  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
		  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

			// Latest Date
			$monthago = Date("Y-m-d", strtotime("2020-01-23 -60 days"));

		//    Get the price data for Client Graph   ///

		  $query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE 'ISA' AND fs_client_code LIKE '$client_code' AND bl_live = 1 AND fs_transaction_date > '$monthago' ORDER BY fs_transaction_date ASC;";

		  $result = $conn->prepare($query);
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $data1 .= $row['fs_t_price']*$row['fs_shares'].',';
			  $labels1 .= "'".$row['fs_transaction_date']."',";
		  }

		}

		catch(PDOException $e) {
		  echo $e->getMessage();
		}
		?>

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(0, 0, 0, 1)'],
					borderWidth:1,
                    color: ['rgba(253, 0, 0, 0.95)'],
					label:'Performance Data',
					data:[<?=$data1;?>],
				}],
				labels: [<?=$labels1;?>]
			},

			options: { tooltips: {enabled: true}}
		});
    </script>
  </body>
</html>

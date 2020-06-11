<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*  
ini_set ("display_errors", "1");	error_reporting(E_ALL);

  */
$start_time = microtime(true);

$ac_id = $_GET['ac_id'];  $dl_data = $_GET['dl_data'];


if( $dl_data == 'dl'){
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=mailing_list.csv");
}

 $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
 $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

		  $query = "SELECT * FROM `tbl_accounts` where id = ".$ac_id." AND bl_live = 1;";

		  $result = $conn->prepare($query);
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $title = $row['ac_display_name'];
			  $clientCode = $row['ac_client_code'];
			  $designation = $row['ac_designation'];
			  $producttype = $row['ac_product_type'];
		  }

$isincode = 'GB0009346486';
$isincode2 = 'GB00BJQWRN41';
$isincode3 = 'GB00B1LB2Z79';

$thisday = $today;

$time = strtotime($today.' -1 year');
$lastyear = date("Y-m-d", $time);

if( $dl_data == 'dl'){
			echo ("Date,ISIN,Value,Shares,Fund Price,Value 2\n");
		}

$data1 = $data2 = $data3 = array();

for($a=0;$a<365;$a++){
	
	$time = strtotime($lastyear.'+'.$a.' day');
	$thedate = date("Y-m-d", $time);
	$weekday = date("N", $time);

	if($weekday<6){
		
		$query1 = "SELECT current_price FROM `tbl_fs_fund` where isin_code like '" . $isincode . "' AND correct_at <= '" . $thedate . "' ORDER BY correct_at desc LIMIT 1;";
		
		$result1 = $conn->prepare($query1);
	    $result1->execute();

	    while($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
		    $fundPrice1 = $row1['current_price'];
	    }
		
		$query2 = "SELECT SUM(fs_shares) AS value_sum FROM `tbl_fs_transactions` WHERE fs_client_code like '" . $clientCode . "' AND fs_designation LIKE '" . $designation . "' AND fs_product_type LIKE '" . $producttype . "' AND fs_isin_code LIKE '" . $isincode . "' AND fs_transaction_date <= '".$thedate."';";
		$result2 = $conn->prepare($query2);
		$result2->execute();

		while($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
			$sum_of_shares = $row2['value_sum'];
			
		}

		$product1 = $sum_of_shares*$fundPrice1;
		
		$product1 != '' ? $data1[$thedate] = $product1 : $data1[$thedate] = '0';

		if( $dl_data == 'dl'){
			echo ($thedate . ',' . $isincode . ',' . $data1[$thedate] . ',' . $sum_of_shares . ',' . $fundPrice1 . ',' . $product1 . "\n");
		}

		#################
		
		$query = "SELECT current_price FROM `tbl_fs_fund` where isin_code like '" . $isincode2 . "' AND correct_at <= '" . $thedate . "' ORDER BY correct_at desc LIMIT 1;";
		
		$result = $conn->prepare($query);
	    $result->execute();

	    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		    $fundPrice2 = $row['current_price'];
	    }
		
		$query = "SELECT SUM(fs_shares) AS value_sum FROM `tbl_fs_transactions` WHERE fs_client_code like '" . $clientCode . "' AND fs_designation LIKE '" . $designation . "' AND fs_product_type LIKE '" . $producttype . "' AND fs_isin_code LIKE '" . $isincode2 . "' AND fs_transaction_date <= '".$thedate."';";
		$result = $conn->prepare($query);
		$result->execute();

		$sum2 = 0;

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$sum2 = $row['value_sum'];
		}
		
		$product2 = $sum2*$fundPrice2;
		
		$product2 != '' ? $data2[$thedate] = $product2 : $data2[$thedate] = '0';
		
		if( $dl_data == 'dl'){
			echo ($thedate . ',' . $isincode2 . ',' . $data2[$thedate] . ',' . $sum2 . ',' . $fundPrice2 . ',' . $product2 . "\n");
		}
		
		#################
		
		$query = "SELECT current_price FROM `tbl_fs_fund` where isin_code like '" . $isincode3 . "' AND correct_at <= '" . $thedate . "' ORDER BY correct_at desc LIMIT 1;";
		
		$result = $conn->prepare($query);
	    $result->execute();

	    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		    $fundPrice3 = $row['current_price'];
	    }
		
		$query = "SELECT SUM(fs_shares) AS value_sum FROM `tbl_fs_transactions` WHERE fs_client_code like '" . $clientCode . "' AND fs_designation LIKE '" . $designation . "' AND fs_product_type LIKE '" . $producttype . "' AND fs_isin_code LIKE '" . $isincode3 . "' AND fs_transaction_date <= '".$thedate."';";
		$result = $conn->prepare($query);
		$result->execute();

		$sum3 = 0;

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$sum3 = $row['value_sum'];
		}
		
		$product3 = $sum3*$fundPrice3;
		
		$product3 != '' ? $data3[$thedate] = $product3 : $data3[$thedate] = '0';
		
		if( $dl_data == 'dl'){
			echo ($thedate . ',' . $isincode3 . ',' . $data3[$thedate] . ',' . $sum3 . ',' . $fundPrice3 . ',' . $product3 . "\n");
		}

	}
	
}

foreach ($data1 as $queryDate => $calc):

	$labels .= "'".$queryDate."',";
	$data_1 .= $calc.',';

endforeach;

foreach ($data2 as $queryDate => $calc2):

	$data_2 .= $calc2.',';

endforeach;

foreach ($data3 as $queryDate => $calc3):

	$data_3 .= $calc3.',';

endforeach;



$conn = null;        // Disconnect

if( $dl_data != 'dl'){
	
$time_elapsed_secs = microtime(true) - $start_time;
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/global-scripts.php');


?>


<div class="container">
	<div class="row">
		<div class="col-md-12">
			<canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>
		</div>
	</div>
	
	<div class="row">
		<p style="font-size:0.7em; color:#666;">Execution Time : <?=$time_elapsed_secs;?></p>
	</div>
</div>




   <script>

		Chart.defaults.global.legend.display = false;

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(0, 255, 0, 1)'],
					borderWidth:1,
                    color: ['rgba(253, 0, 0, 0.95)'],
					label:'<?=$isincode;?>',
					data:[<?=$data_1;?>],
				},{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(255, 0, 0, 1)'],
					borderWidth:1,
                    color: ['rgba(253, 0, 0, 0.95)'],
					label:'<?=$isincode2;?>',
					data:[<?=$data_2;?>],
				},{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(0, 0, 255, 1)'],
					borderWidth:1,
                    color: ['rgba(253, 0, 0, 0.95)'],
					label:'<?=$isincode3;?>',
					data:[<?=$data_3;?>],
				}],
				labels: [<?=$labels;?>]
			},

			options: { 
				tooltips: {
					enabled: true
				},
				legend: {
					display: true,
					labels: {
						fontColor: 'rgb(30, 30, 30)'
					}
				},
				title: {
					display: true,
					text: '<?=$title;?>'
				}
			}
		});
    </script>
<?php } ?>
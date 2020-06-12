<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");	error_reporting(E_ALL);

  */
$start_time = microtime(true);

$ac_id = $_GET['ac_id'];  $time_period = $_GET['t'];  $dl_data = $_GET['dl_data'];

$time_period > 366 ? $step = 7 : $step = 1;

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


		  //    Get the Funds
		  $query = "SELECT * FROM `tbl_funds` where bl_live = 1;";

		  $result = $conn->prepare($query);
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $isincode[] = $row['fu_isin'];
			  $f_name[$row['fu_isin']] = getfield('tbl_funds','fu_fund_name','fu_isin',$row['fu_isin']);

		  }






if( $dl_data == 'dl'){
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$clientCode.".csv");
}
$thisday = $today;

$time = strtotime($today.' -'.$time_period.' day');
$lastyear = date("Y-m-d", $time);

if( $dl_data == 'dl'){
			echo ($title."\n\n");
			echo ("Date,ISIN,Value,Shares,Fund Price\n");
		}

$data1 = $data2 = $data3 = $data4 = array();

for($a=0;$a<$time_period;$a+=$step){

	$time = strtotime($lastyear.'+'.$a.' day');
	$thedate = date("Y-m-d", $time);
	$weekday = date("N", $time);

	if($weekday<6){

		$cumulative = 0;
		foreach ($isincode as $isin):

			$query1 = "SELECT current_price FROM `tbl_fs_fund` where isin_code like '" . $isin . "' AND correct_at <= '" . $thedate . "' ORDER BY correct_at desc LIMIT 1;";

			$result1 = $conn->prepare($query1);
			$result1->execute();

			while($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
				$fundPrice1 = $row1['current_price'];
			}

			$query2 = "SELECT SUM(fs_shares) AS value_sum FROM `tbl_fs_transactions` WHERE fs_client_code like '" . $clientCode . "' AND fs_designation LIKE '" . $designation . "' AND fs_product_type LIKE '" . $producttype . "' AND fs_isin_code LIKE '" . $isin . "' AND fs_transaction_date <= '".$thedate."';";
			$result2 = $conn->prepare($query2);
			$result2->execute();

			while($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
				$sum_of_shares = $row2['value_sum'];

			}

			$product1 = $sum_of_shares*$fundPrice1;          $cumulative += $product1;

			$product1 != '' ? $data[$isin] .= round($product1,2).',' : $data[$isin] .= '0,';

			if( $dl_data == 'dl'){
				echo ($thedate . ',' . $isin . ',' . $product1 . ',' . $sum_of_shares . ',' . $fundPrice1 . "\n");
			}

		endforeach;

		$labels .= "'".$thedate."',";

		#################

		############################     AGGREGATION OF FUNDS    ################################

		$data_sum .= $cumulative . ',';

	}

}

$conn = null;        // Disconnect

if( $dl_data != 'dl'){

$time_elapsed_secs = microtime(true) - $start_time;
//define('__ROOT__', dirname(dirname(__FILE__)));
//require_once(__ROOT__.'/global-scripts.php');
?>


<div class="container account-chart-wrapper">
    <div class="row">
        <div class="col-md-12 controls">
            <h5 class="heading heading__5">Chart Period</h5>
            <a href="#?t=180&ac_id=<?=$ac_id;?>" class="button button__inline graphtime">6 Months</a>
            <a href="#?t=365&ac_id=<?=$ac_id;?>" class="button button__inline graphtime">1 Year</a>
            <a href="#?t=1095&ac_id=<?=$ac_id;?>" class="button button__inline graphtime">3 Years</a>
            <!--<a href="#?t=1825&ac_id=<?=$ac_id;?>" class="graphtime">5 Years</a>-->
        </div>
    </div>
	<div class="row">
		<div class="col-md-12">
			<canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<p style="font-size:0.7em; color:#AAA;">Execution Time : <?=$time_elapsed_secs;?></p>
		</div>
	</div>
</div>




   <script>

		Chart.defaults.global.legend.display = false;

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [
					{
						fill:'origin',
						lineTension:0,
						borderColor:['rgba(255, 255, 0, 1)'],
						backgroundColor:['rgba(255, 255, 0, 0.1)'],
						borderWidth:2,
						color: ['rgba(255, 255, 0, 0.95)'],
						label:'Total',
						pointBorderColor:['rgba(72, 72, 72, 0.1)'],
						pointHitRadius: 4,
						data:[<?=$data_sum;?>],
					},
					<?php foreach ($data as $isin => $value): ?>
					{
						fill:false,
						lineTension:0,
						borderColor:['rgba(100, 100, 100, 1)'],
						borderWidth:1,
						color: ['rgba(100, 100, 100, 0.95)'],
						label:'<?=$f_name[$isin];?>',
						hidden: true,
						data:[<?=$value;?>],
					},
					<?php endforeach; ?>
					],
				labels: [<?=$labels;?>]
			},

			options: {
				tooltips: {
					enabled: true
				},
				legend: {
					display: true,
					labels: {
						fontColor: 'rgb(255, 255, 255)'
					}
				},
				title: {
				},
				elements: {
                    point:{
                        radius: 0
                    }
                }
			}
		});

    </script>
<?php } ?>

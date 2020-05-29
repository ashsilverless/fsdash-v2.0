<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");	error_reporting(E_ALL);
*/

$client_code = $_GET['fs_cc'];

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

$total_inv_ammount = 0;
foreach ($products as $product):

	$funds = db_query("SELECT DISTINCT fs_isin_code FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND fs_product_type LIKE '".$product['fs_product_type']."';");

	foreach ($funds as $fund):

		$records = db_query("SELECT * FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE '".$product['fs_product_type']."' AND fs_isin_code LIKE '".$fund['fs_isin_code']."';");

		$total_shares = 0;

		for($count=0;$count<count($records);$count++){
			$total_shares += $records[$count]['fs_shares'];
			$total_inv_ammount += round($records[$count]['fs_shares'] * $records[$count]['fs_t_price'],2);
		}

		$tot_inv_ammount[$product['fs_product_type']] += $total_inv_ammount;

		$value_of_shares[$product['fs_product_type']] += round((get_current_price($fund['fs_isin_code']) * $total_shares),2);

		$total_product_shares[$product['fs_product_type']] += $total_shares;

	endforeach;

endforeach;

################################

//echo ('<b>Total Shares Held / Product Type</b><br>');

foreach ($total_product_shares as $tot_share => $shares_qty):

	$gain = number_format($value_of_shares[$tot_share] - $tot_inv_ammount[$tot_share],2);
	$gain_percent = number_format(100*($value_of_shares[$tot_share]/$tot_inv_ammount[$tot_share])-100,4);

	$total_quantity_of_shares_per_product[$tot_share] += $shares_qty;

	$ac_name = getField('tbl_fsusers','user_name','fs_client_code',$client_code);?>

	<?php
        $gross_array[$tot_share]['ac_name'] = $ac_name;
        $gross_array[$tot_share]['amount'] = $tot_inv_ammount[$tot_share];
        $gross_array[$tot_share]['value'] = $value_of_shares[$tot_share];
        $gross_array[$tot_share]['gain'] = $gain;
        $gross_array[$tot_share]['gainpercent'] = $gain_percent;

        /*echo ($ac_name.'-'.$tot_share.' : '.$shares_qty.' shares : Total Investment Amount : '.$tot_inv_ammount[$tot_share].' : Value of shares : '.$value_of_shares[$tot_share].' : Gain : '.$gain.' : Gain % : '.$gain_percent.'<br>');*/
        ?>
<?php endforeach;

################################

foreach ($products as $product):

	//echo('<br><b>'.$product['fs_product_type'].'</b><br>');

	$funds = db_query("SELECT DISTINCT fs_isin_code FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND fs_product_type LIKE '".$product['fs_product_type']."';");
    $pType = $product['fs_product_type'];
    $fundName = $product['fs_fund_name'];?>
<div class="data-table__account-wrapper">

    <div class="data-table__body">
        <div>
            <p class="heading heading__4"><?=$gross_array[$pType]['ac_name'];?></p>
        </div>
        <div>
            <p class="heading heading__4"><?=$gross_array[$pType]['amount'];?></p>
        </div>
        <div>
            <p class="heading heading__4"><?=$gross_array[$pType]['value'];?></p>
        </div>
        <div>
            <p class="heading heading__4"><?=$gross_array[$pType]['gain'];?></p>
        </div>
        <div>
            <p class="heading heading__4"><?=$gross_array[$pType]['gainpercent'];?></p>
        </div>
        <div>
            <div class="toggle button button__raised button__toggle"><i class="fas fa-caret-down arrow"></i></div>
        </div>
    </div>
    <div class="toggle-section">
        <div class="data-table__extended titles">
            <div>
                <h4 class="heading heading__5">Fund Name</h4>
            </div>
            <div class="split">
                <div><h4 class="heading heading__5">Holding</h4></div>
                <div><h4 class="heading heading__5">Invested</h4></div>
            </div>
            <div class="split">
                <div><h4 class="heading heading__5">Book Cost</h4></div>
                <div><h4 class="heading heading__5">Value</h4></div>
            </div>
            <div class="split">
                <div><h4 class="heading heading__5">Growth(£)</h4></div>
                <div><h4 class="heading heading__5">Growth(%)</h4></div>
            </div>
            <div>
                <h4 class="heading heading__5">Benchmark</h4>
            </div>
        </div>


	<?php $invested_in_fund = 0;
	foreach ($funds as $fund):

		//echo('ISIN Code : '.$fund['fs_isin_code']);
		//echo ('<br><table border="0" cellspacing="8" cellpadding="4">');

		//echo ('<tr><td>Holding</td><td>Invested</td><td>Book Cost</td><td>Value</td><td>Growth(£)</td><td>Growth(%)</td><td>Benchmark</td></tr>');

		$records = db_query("SELECT * FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE '".$product['fs_product_type']."' AND fs_isin_code LIKE '".$fund['fs_isin_code']."';");

		$currentvalue = $value = $cps = $totshares = $count = 0;    $average_cps = $total_shares = array();
		$book_cost = $growth = $growth_percent = $invested_in_fund = 0;

		for($count=0;$count<count($records);$count++){
			$totshares += $records[$count]['fs_shares'];

			$total_shares[$count] = $total_shares[$count-1] + $records[$count]['fs_shares'];

			$value = round($records[$count]['fs_shares'] * $records[$count]['fs_t_price'],2);


			//              ((E2*B2)+D3)/B3           (0 x 0) + 444  /  222  =  2
			//										  (2 x 222) + 40 /  232  =  2.09
			//										  (2.09 x 232) + 50 / 242 = 2.21
			//                      (previous acps * previous total shares)  +  share calc   /  current total shares

			if($records[$count]['fs_shares'] > 0){
				$average_cps[$count] = (($average_cps[$count-1] * $total_shares[$count-1])+$value)/$totshares;
			}else{
				$average_cps[$count] = $average_cps[$count-1];
			}

			$book_cost = round($average_cps[$count] * $total_shares[$count],2);

			$invested_in_fund += $records[$count]['fs_iam'];

			$currentvalue = round($totshares * get_current_price($fund['fs_isin_code'],$records[$count]['fs_transaction_date']),2);

			$growth = $currentvalue - $invested_in_fund;

            $growth_percent = round(($growth/$invested_in_fund) * 100,2);?>

		<?php
			/*echo ('<tr><td>'.round($totshares,4).'</td>');
			echo ('<td>'.$invested_in_fund.'</td>');
			echo ('<td>'.$book_cost.'</td>');
			echo ('<td>'.$currentvalue.' = '.$totshares.' x '.get_current_price($fund['fs_isin_code'],$records[$count]['fs_transaction_date']).'</td>');
			echo ('</td><td>&pound;'.$growth.' = '.$currentvalue.' - '.$invested_in_fund.'</td>');
			echo ('<td>'.$growth_percent.'</td>');
			echo ('<td>'.number_format(get_benchmark($fund['fs_isin_code']),2).'&percnt;</td></tr>');*/

		}?>


        <?php /* === Original Figures ***UNEDITED***
        echo ('<table border="0" cellspacing="4" cellpadding="4">');
        echo ('<tr><td>Holding</td><td>Invested</td><td>Book Cost</td><td>Value</td><td>Growth(£)</td><td>Growth(%)</td><td>Benchmark</td></tr>');
        echo ('<tr style="border-top:1px solid #DDD;border-bottom:1px solid #DDD;"><td>'.round($totshares,4).'</td>');
        echo ('<td>'.$invested_in_fund.'</td>');
        echo ('<td>'.$book_cost.'</td>');
        echo ('<td>'.$value.'</td>');
        echo ('</td><td>&pound;'.$growth.'</td>');
        echo ('<td>'.$growth_percent.'</td>');
        echo ('<td>'.number_format(get_benchmark($fund['fs_isin_code']),2).'&percnt;</td></tr>');
        echo ('</table>');*/?>

    <?php $last_good_price = get_current_price($fund['fs_isin_code']);
    $percentage_of_shares = round(($totshares / $total_quantity_of_shares_per_product[$product['fs_product_type']])*100,2);
$testfund = $fund['fs_fund_name'];
    /*echo ('<tr><td colspan="7"><b>Total Shares held  :  '.round($totshares,4).' at '.$last_good_price.' = &pound;'.round(($totshares*$last_good_price),2).' which is '.$percentage_of_shares.'% of total within product type.<br>Invested Amount : '.$invested_in_fund.'</b></td></tr>');
    echo ('</table><p>&nbsp;</p>');*/?>

            <div class="data-table__extended">
                <div><?=$fund_name;?></div>
                <div class="split">
                    <div><h4 class="heading heading__5"><?=$percentage_of_shares;?>%</h4></div>
                    <div><h4 class="heading heading__5"><?=$invested_in_fund;?></h4></div>
                </div>
                <div class="split">
                    <div><h4 class="heading heading__5"><?=$book_cost;?></h4></div>
                    <div><h4 class="heading heading__5"><?=$value;?></h4></div>
                </div>
                <div class="split">
                    <div><h4 class="heading heading__5"><?=$growth;?></h4></div>
                    <div><h4 class="heading heading__5"><?=$growth_percent;?>&percnt;</h4></div>
                </div>
                <div>
                    <h4 class="heading heading__5"><?=number_format(get_benchmark($fund['fs_isin_code']),2);?>&percnt;</h4>
                </div>
            </div>

	<?php endforeach;?>

        </div><!--toggle-->
    </div><!--body-->

<?php endforeach;?>


    <div class="data-table__foot">
        <div>
            <h3 class="heading heading__4">Totals</h3>
        </div>
        <div>
            <h3 class="heading heading__4">Invested</h3>
        </div>
        <div>
            <h3 class="heading heading__4">Value</h3>
        </div>
        <div>
            <h3 class="heading heading__4">Gain(£)</h3>
        </div>
        <div>
            <h3 class="heading heading__4">Gain(%)</h3>
        </div>
    </div>
    <div class="data-table__foot content">
        <div>
            <h3 class="heading heading__4"></h3>
        </div>
        <div>
            <h3 class="heading heading__4">=Total Invested Value</h3>
        </div>
        <div>
            <h3 class="heading heading__4">=Total Shares Value</h3>
        </div>
        <div>
            <h3 class="heading heading__4">= Total Gain in £</h3>
        </div>
        <div>
            <h3 class="heading heading__4">= Total Gain in %</h3>
        </div>
    </div>
</div><!--account wrapper-->

<?php //echo ($ac_name.'-'.$tot_share.' : '.$shares_qty.' shares : LOWER Total Investment Amount : '.$tot_inv_ammount[$tot_share].' : Value of shares : '.$value_of_shares[$tot_share].' : Gain : '.$gain.' : Gain % : '.$gain_percent.'<br>');?>

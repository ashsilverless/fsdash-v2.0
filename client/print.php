<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>
<div class="curtain"></div>

<div class="page-wrapper cover">
    <div class="left">
        <?php include(__ROOT__.'/client/images/fs-logo.php'); ?>
        <div></div>
    </div><!--left-->
    <div class="main">
        <div class="top-section">
            <h2 class="heading heading__3">
                <span>Advise</span>
                <span>Invest</span>
                <span>Manage</span>
            </h2>
        </div>
        <div class="main-section">
            <h1 class="heading heading__1">PORTFOLIO REPORT</h1>
            <h2 class="heading heading__2">Mr A Name<span>32.13.00</span>
        </div>
    </div><!--main-->
</div>

<!--==== END COVER PAGE=======-->

<?php
/*
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = 7161;
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
$testVar = 'test';
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the general products data for Client   ///


  $query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";
	debug($query);

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

    //    Get the funds   ///

  $query = "SELECT DISTINCT fs_isin_code FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $funds[] = $row;
  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<div class="page-wrapper">
    <div class="left">
        <?php include(__ROOT__.'/client/images/fs-logo.php'); ?>
        <div></div>
    </div><!--left-->
    <div class="main">
        <div class="top-section">
            <h2 class="heading heading__3">Daily Valuation Data</h2>
        </div>
        <div class="main-section">
            <p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p>

            <div class="data-section tables">

                <div class="data-table">
                    <div class="data-table__head">
                        <div>
                            <h3 class="heading heading__4">Account Name</h3>
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

                    <?php
                        // Connect and create the PDO object
                          $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                          $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
                          foreach ($products as $product):
                              $the_product = $product['fs_product_type'];
                              $inv_ammount = $value = $total_shares_qty = 0;
                              $shares = array(); $shares_per = array();  $fund_name = array();  $invested_in_fund = array();
                              $query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE '$the_product' AND fs_client_code LIKE '$client_code' AND bl_live = 1 ORDER BY fs_transaction_date ASC;";
                              debug($query);

                              $result = $conn->prepare($query);
                              $result->execute();
                              debug('Record Count = '+$result->rowCount());
                                // Parse returned data
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $account_name = $user_name." - ".$the_product;
                                    $inv_ammount += $row['fs_iam'];
                                    $isin = $row['fs_isin_code'];
                                    //$latest_price = get_current_price($row['fs_isin_code']);
                                    $shares[$isin] += $row['fs_shares'];
                                    $fund_name[$isin] = $row['fs_fund_name'];
                                    $invested_in_fund[$isin] += $row['fs_iam'];
                                    $cur = $row['fs_currency_code'];
                                }
                    debug(count($invested_in_fund));
                    foreach($shares as $isin => $shares_qty) {
                        $value += $shares_qty * get_current_price("$isin");
                        $total_shares_qty += $shares_qty;
                        $classname = str_replace(" ","",$account_name);
                      }
                    ?>
                    <div class="data-table__account-wrapper">
                        <div class="data-table__body">
                            <div>
                                <p class="heading heading__4"><?=$account_name;?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format($inv_ammount,2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format(($value),2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format($value - $inv_ammount,2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=number_format(100*($value/$inv_ammount)-100,4);?></p>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div><!--account wrapper-->
                    <?php endforeach; $conn = null; // Disconnect?>
                </div><!--data-table-->

            </div><!--data section-->
        </div>
    </div><!--main-->
</div>

<!--==== END VALUATION PAGE=======-->

<?php
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');
$confirmed_date = $row['confirmed_date']= date('d M Y');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id',$_SESSION['fs_client_user_id']));
//REMOVE NEXT LINE WHEN PUSHING
$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id','5'));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_assets` where $strategy > 0 AND bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			 $assetData[] =  $row;

        }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>

<div class="page-wrapper">
    <div class="left">
        <?php include(__ROOT__.'/client/images/fs-logo.php'); ?>
        <div></div>
    </div><!--left-->
    <div class="main">
        <div class="top-section">
            <h2 class="heading heading__3">Holdings & Asset Allocation</h2>
        </div>
        <div class="main-section">
            <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
            <div class="asset-wrapper">
                <div class="asset-wrapper__chart">

                    <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut" aria-labelledby="" role="img" style="transform:rotate(-90deg);">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#484848" role="presentation"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#414141" stroke-width="10" role="presentation"></circle>
                        <!--For each holding, create a segment like this
                        Params =
                        Stroke-dasharray: two figures.  The first is the value of the holding (ie, 30%); the second is the first value minus 100 (ie 30 - 100) therefore 70.

                        Stroke-dashoffset: This is the running sum of the value of the holding, expressed as a negative value to enable positioning.
                        -->
                        <?php foreach($assetData as $asset) {
                          $assetsData .= $asset[$strategy].',';
                          $assetsID .= $asset['id'].',';
                          $assetsName .= "'".$asset['fs_asset_name']."',";
                          $asset_color = "".$asset['asset_color']."";
                          $thisAsset = $asset[$strategy];
                          $assetBalance = 100 - $thisAsset;
                        ?>
<g>
                           <circle id="asset<?=$asset['id'];?>" class="donut-segment <?=$asset['id'];?> <?=$asset['fs_asset_name'];?> asset<?=$asset['id'];?>" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="<?= $asset_color;?>" stroke-width="10" stroke-dasharray="<?=$thisAsset;?> <?=$assetBalance;?>" stroke-dashoffset="-<?=$assetTotal;?>"></circle>
                           <text x="22" y="22" text-anchor="middle" alignment-baseline="middle" class="asset<?=$asset['id'];?>"><?=$thisAsset;?>%</text></g>
                           <?php $assetTotal = $thisAsset += $assetTotal;?>
                       <?php }?>
                    </svg>
                    <div class="key border-box">
                        <?php foreach($assetData as $asset) {
                          $assetsData .= $asset[$strategy].',';
                          $assetsID .= $asset['id'].',';
                          $assetsName .= "'".$asset['fs_asset_name']."',";
                          $asset_color = "".$asset['asset_color']."";
                          $thisAsset = $asset[$strategy];
                          $assetBalance = 100 - $thisAsset;
                        ?>
                        <div class="key__item">
                            <div class="color" style="background-color:<?= $asset_color;?>;"></div>
                            <h4 class="heading heading__4"><?=$asset['fs_asset_name'];?></h4>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <div class="asset-wrapper__table">
                    <div class="head">
                        <h4 class="heading heading__4">Fund</h4>
                        <h4 class="heading heading__4">Growth Rate</h4>
                    </div>
                    <?php foreach($assetData as $asset) {
                      $assetsData .= $asset[$strategy].',';
                      $assetsID .= $asset['id'].',';
                      $assetsName .= "'".$asset['fs_asset_name']."',";
                      $asset_color = "".$asset['asset_color']."";
                      $thisAsset = $asset[$strategy];
                      $assetBalance = 100 - $thisAsset;
                    ?>
                    <div id="asset<?=$asset['id'];?>" class="item asset<?=$asset['id'];?>" data-asset="asset<?=$asset['id'];?>">
                        <h4 class="heading heading__4"><?=$asset['fs_asset_name'];?></h4>
                        <h4 class="heading heading__4"><?=$asset[$strategy];?></h4>
                        <div class="toggle button button__raised button__toggle">
                            <i class="fas fa-caret-down arrow"></i>
                        </div>
                        <p><?=$asset['fs_asset_narrative'];?></p>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div><!--main-->
</div><!--page-wrapper-->

<!--==== END ASSETS PAGE=======-->

<?php

$user_id = 12694;
$client_code = 7161;
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');
$confirmed_date = $row['confirmed_date']= date('d M Y');
$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
     //    Get the general products data for Client   ///
  $query = "SELECT * FROM tbl_fsusers where id = '$user_id' AND bl_live = 1;";
	debug($query);
  $result = $conn->prepare($query);
  $result->execute();
  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];
	  $strategy = $row['strategy'];
  }

  switch ($strategy) {
    case 'Sensible':
        $strategy_str = 'fs_theme_sensible';
        break;
    case 'Steady':
        $strategy_str = 'fs_theme_steady';
        break;
    case 'Serious':
        $strategy_str = 'fs_theme_serious';
        break;
  }
  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>

<div class="page-wrapper">
    <div class="left">
        <?php include(__ROOT__.'/client/images/fs-logo.php'); ?>
        <div></div>
    </div><!--left-->
    <div class="main">
        <div class="top-section">
            <h2 class="heading heading__3">Current Investment Themes</h2>
        </div>
        <div class="main-section">
            <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>

            <div class="themes-table front">
            <?php
            try {
            // Connect and create the PDO object
            $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
            $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
            $query = "SELECT *  FROM `tbl_fs_themes` where $strategy_str = '1' AND bl_live = 1;";

            debug($query);
            $result = $conn->prepare($query);
            $result->execute();
                  // Parse returned data
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
                <div class="themes-table__item">
                    <img src="../icons_folder/<?= $row['fs_theme_icon'];?>">
                    <h3 class="heading heading__4"><?= $row['fs_theme_title'];?></h3>
                    <p><?= substr($row['fs_theme_narrative'],0,385);?>...</p>
                </div>
            <?php }
            $conn = null;        // Disconnect
            }
            catch(PDOException $e) {
            echo $e->getMessage();
            }?>
            </div>
            </div>

            </div>
        </div>
    </div><!--main-->
</div>

<!--==== END THEMES PAGE=======-->

<?php
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
$confirmed_date = $row['confirmed_date']= date('d M Y');
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the Peer Group Data   ///

  $query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '0' ;";
  $peer_data = $peer_colour = $peer_name = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour .= '"'.$row['fs_peer_color'].'",';
	  $peer_name .= '"'.$row['fs_peer_name'].'",';
	  //$peer_data .= "[ ".$row['fs_peer_return'].",".$row['fs_peer_volatility'].", '".$row['fs_peer_name']."', 'point { size: 4; fill-color: ".$row['fs_peer_color']."; }','".$row['fs_peer_volatility']."% Volatility'],";
  }


$query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '1' ;";
  $peer_data_line = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data_line .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour_line .= '"'.$row['fs_peer_color'].'",';
	  $peer_name_line .= '"'.$row['fs_peer_name'].'",';
  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>

<div class="page-wrapper">
    <div class="left">
        <?php include(__ROOT__.'/client/images/fs-logo.php'); ?>
        <div></div>
    </div><!--left-->
    <div class="main">
        <div class="top-section">
            <h2 class="heading heading__3">Peer Group Comparison</h2>
        </div>
        <div class="main-section">
            <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
            <div class="chart-wrapper">
                <div class="chart-wrapper__x-axis">
                    <?php //create x axis values
                    $sum = 0;
                    for($i = 1; $i<=11; $i++) {?>
                        <div class="x-axis-values" style="left:<?php echo $sum * 10;?>%;"><?php echo $sum;?></div>
                    <?php $sum = $sum + 1;
                    }?>
                </div>
                <div class="chart-wrapper__y-axis">
                    <?php //create y axis values
                    $sum = 10;
                    for($i=10; $i>=0; $i--){?>
                        <div class="y-axis-values" style="bottom:<?php echo $sum * 10;?>%;"><?php echo $sum;?></div>
                        <?php $sum = $sum - 1;
                        }?>
                </div>
                <div class="chart-wrapper__y-label">Annualised Return (%)</div>
                <div class="chart-wrapper__x-label">Annualised Volatility (%)</div>
                <div class="chart-wrapper__inner">
                    <?php //create chart inner
                    $sum = 0;
                    for($i = 1; $i<=11; $i++) {?>
                        <div class="x-axis" style="left:<?php echo $sum * 10;?>%;"></div>
                        <div class="y-axis" style="top:<?php echo $sum *10;?>%;"></div>
                    <?php $sum = $sum + 1;
                    }?>

                    <?php //create data points
                    try {
                      // Connect and create the PDO object
                      $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                      $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

                        $query = "SELECT *  FROM `tbl_fs_peers` where bl_live = 1;";

                        $result = $conn->prepare($query);
                        $result->execute();

                              // Parse returned data
                              while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>

                                <div class="data-point trendline<?= $row['fs_trend_line'];?>" style="bottom:<?= $row['fs_peer_volatility'] * 10;?>%; left:<?= $row['fs_peer_return'] * 10;?>%;"><?= $row['fs_peer_name'];?></div>

                              <?php }
                              $conn = null;        // Disconnect
                          }
                          catch(PDOException $e) {
                          echo $e->getMessage();
                    }?>

                    <svg id="trendline" width='100%' height='100%' viewBox="0 0 100 100" preserveAspectRatio="none">

                        <polyline points="
                        <?php //create trendline
                        try {
                          // Connect and create the PDO object
                          $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                          $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

                            $query = "SELECT *  FROM `tbl_fs_peers` where bl_live = 1;";

                            $result = $conn->prepare($query);
                            $result->execute();

                                  // Parse returned data
                                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    // first coord is multiplied by 10
                                    // second coord is multiplied by 100 and removed from 100
                                    //54,37 76,30
                                if($row['fs_trend_line'] == 1) {
                                    $trendRet = ($row['fs_peer_return'] * 10);
                                    $trendVol = 100 - ($row['fs_peer_volatility'] * 10);
                                    echo $trendRet. ',' .$trendVol. ' ';
                                }
                                }
                                  $conn = null;        // Disconnect
                              }
                              catch(PDOException $e) {
                              echo $e->getMessage();
                        }?>
                        "fill="none"/>
                    </svg>

                </div>
            </div><!--chart wrapper-->
        </div>
    </div><!--main-->
</div>

<!--==== END PEERS PAGE=======-->

<script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous"></script>
<!-- Print function run on page load-->
<script type="text/javascript" defer>

    $("html").delay(1000).queue(function(next) {
        window.print();
        //console.log('sssss');
    });
    window.onafterprint = function(){
        window.history.back();
    }
</script>

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
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

  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}

$strat_id = getField('tbl_fs_strategy_names','id','strat_name',$strategy);
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once(__ROOT__.'/page-sections/header-elements.php');
require_once(__ROOT__.'/page-sections/sidebar-elements.php');
?>
        <div class="col-md-9">
            <div class="border-box main-content">
                <div class="main-content__head">
                    <h1 class="heading heading__1">Current Investment Themes</h1>
                    <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
                </div>

				<div class="container">

                    <div class="recess-box">
                        <div class="themes-table front">
                	<?php
                	try {
                	  // Connect and create the PDO object
                	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
                		$query = "SELECT *  FROM `tbl_fs_theme_strats` where strat_id = '$strat_id' AND strat_val = '1' AND bl_live = 1;";

                		debug($query);
                		$result = $conn->prepare($query);
                		$result->execute();
                			  // Parse returned data
                			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$icon = getField('tbl_fs_themes','fs_theme_icon','id',$row['theme_id']);
								$title =  getField('tbl_fs_themes','fs_theme_title','id',$row['theme_id']);
								$narrative =  getField('tbl_fs_themes','fs_theme_narrative','id',$row['theme_id']);


							?>
                    		<div class="themes-table__item">
                    			<img src="../icons_folder/<?= $icon;?>">
                                <h3 class="heading heading__4"><?= $title;?></h3>
                    			<p><?= substr($narrative,0,385);?>...</p>
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
		    </div><!--9-->
		</div>
    </div>
</div>

<?php define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/global-scripts.php');
require_once(__ROOT__.'/modals/logout.php');
require_once(__ROOT__.'/modals/time-out.php');
require_once('../page-sections/footer-elements.php');?>

    <script>
      $(".toggler").click(function(e){
        e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});


Chart.defaults.global.legend.display = false;

/* ##########################################       LINE CHART     ################################################## */

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					borderColor:['rgba(255, 255, 255, 0.75)'],
					borderWidth:2,
					label:'Performance Data',
					data:[1.388,1.394,1.391,1.39,1.394,1.394,1.395,1.387,1.38,1.379,1.378,1.369,1.369,1.371,1.359,1.331,1.334,1.334,1.342,1.342,1.344,1.334,1.338,1.342,1.336,1.34,1.34,1.342,1.351,1.353],
				}],
				labels: ['2020-02-12','2020-02-11','2020-02-10','2020-02-09','2020-02-08','2020-02-07','2020-02-06','2020-02-05','2020-02-04','2020-02-03','2020-02-02','2020-02-01','2020-01-31','2020-01-30','2020-01-29','2020-01-28','2020-01-27','2020-01-26','2020-01-25','2020-01-24','2020-01-23','2020-01-22','2020-01-21','2020-01-20','2020-01-19','2020-01-18','2020-01-17','2020-01-16','2020-01-15','2020-01-14']
			},

			options: { tooltips: {enabled: true}}
		});

    </script>
  </body>
</html>

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");	error_reporting(E_ALL);
    */

$user_id = $_SESSION['fs_client_user_id'];
$client_code = $_SESSION['fs_client_featherstone_cc'];

$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_isin_code = "GB0009346486"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
$testVar = 'test';
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the user data for Client   ///


  $query = "SELECT * FROM tbl_fsusers where id LIKE '$user_id' AND bl_live = 1;";


  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];

  }

  //    Get the Client Accounts   ///

  $query = "SELECT * FROM `tbl_fs_client_accounts` where fs_client_id = '$user_id' AND ca_linked = '0' AND bl_live = 1 ORDER by ca_order_by DESC;;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $client_accounts[] = $row;
  }


	foreach ($client_accounts as $ca):

		  $query = "SELECT * FROM `tbl_accounts` where id = ".$ca['ac_account_id']." AND bl_live = 1;";

		  $result = $conn->prepare($query);
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $accounts[] = $row;
		  }

	endforeach;


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
                <p class="mb3">Data accurate as at <?= date('j M y',strtotime($last_date));?></p>
                <div class="button button__raised data-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.59 19.59"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,9.79A9.84,9.84,0,0,1,9.79,0a9.85,9.85,0,0,1,9.8,9.79,9.85,9.85,0,0,1-9.8,9.8A9.85,9.85,0,0,1,0,9.79Zm15.48,6.38L9.61,10.41a.7.7,0,0,1-.22-.56V1.28a8.53,8.53,0,1,0,6.09,14.89ZM17.1,5.38a8.53,8.53,0,0,0-6.67-4.09v7.9Zm-.89,10.05A8.54,8.54,0,0,0,17.58,6.3l-6.7,3.84Z"/></g></g></svg> View Charts</div>
            </div>


            <div class="data-section tables">

                <h2 class="heading heading__2">Accounts for <?=$user_name;?></h2>
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

			            <div class="calcs"></div>

                </div>
            </div>

            <div class="data-section tables">
                <h2 class="heading heading__2">Accounts Linked To <?=$user_name;?></h2>
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

			<div class="linked_calcs"></div>

        </div>
    </div>

<div class="data-section chart">
	<?php foreach ($accounts as $account): ?>
	<p><a href="#?ac_id=<?=$account['id'];?>" class="accountchart"><?=$account['ac_display_name'];?></a><p>
	<?php endforeach; ?>


    <div class="chartcontainer"></div>
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

		  $(".calcs").load("__calcs2.php?ca_lnk=0");
		  $(".linked_calcs").load("__calcs2.php?ca_lnk=1");

		$(document).on('click', '.accountchart', function(e) {
            e.preventDefault();
            var ac_id = getParameterByName('ac_id',$(this).attr('href'));
            $(".chartcontainer").load("chart.php?ac_id="+ac_id);
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

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$_GET['nu'] == '1' ? $flag = '2' : $flag = '1';

//    Get the graph data
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB0009346486' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB0009346486 .= $row['current_price'].',';
		$labels1 .= "'".$row['correct_at']."',";
	}

	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00B1LB2Z79' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB00B1LB2Z79 .= $row['current_price'].',';
		$labels2 .= "'".$row['correct_at']."',";
	}

	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00BJQWRN41' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB00BJQWRN41 .= $row['current_price'].',';
		$labels3 .= "'".$row['correct_at']."',";
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
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
require_once('page-sections/sidebar-elements.php');
?>
<!-- Upload script -->
	<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<div class="col-md-9">
    <div class="border-box main-content daily-data">

        <div class="row">
            <div class="col-7">
                <h2 class="heading heading__2">Upload Transaction File</h2>
                <div id="transfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="transcontainer"><a id="picktrans" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input type="text" id="trans_file" name="trans_file" readonly>
            </div>
            <div class="col-5">
                <h2 class="heading heading__2">Frequent Tasks</h2>
                <a href="" class="toggle button button__raised">Update Daily Prices</a>
                <a href="" class="toggle button button__raised">Edit Client</a>
                <a href="" class="toggle button button__raised">Create Client</a>
            </div>
        </div>

    </div>
</div>

		<!--   Upload TransFile  -->

			<!--<div id="result" class="col-md-12 mb-3" style="height:300px; max-height:300px; overflow-y: scroll;"><div id="data_info" class="col-md-12 text-center" style="height:300px; max-height:300px; overflow-y: scroll;"></div></div>-->

		<!-- / Upload Trans File -->

		<!--<div class="row">
              <div class="col-md-12">
                  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart1" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart2" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart3" height="400"></canvas>
              </div>
          </div>-->

		<!--<div id="assetdetails" class="col-md-12 mt-5"></div>-->
</div><!--row-->
</div><!--container-->

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		/* #########################    Trans File Upload and display    ####################### */
		$("#result").load("showdata.php?f=<?=$flag;?>");

		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'picktrans',
			container: document.getElementById('transcontainer'),
			url : 'upload.php',
			flash_swf_url : 'js/plupload/Moxie.swf',
			silverlight_xap_url : '.js/plupload/Moxie.xap',
			unique_names : true,
			filters : {
				max_file_size : '10mb',
				mime_types: [
					{title : "Data files", extensions : "txt,csv"}
				]
			},

			init: {
				PostInit: function() {
					document.getElementById('transfilelist').innerHTML = '';
				},

				FilesAdded: function(up, files) {
					uploader.start();
				},

				UploadProgress: function(up, file) {
					$('#data_info').html('<strong>Uploading & Parsing Datafile</strong><br>Please wait.....<br><br><img src="images/animated_progress.gif">');
				},

				FileUploaded: function(up, file, info) {
					var myData;
						try {
							myData = eval(info.response);
						} catch(err) {
							myData = eval('(' + info.response + ')');
						}
				   $('#data_info').html('');

					if(myData.error=="NULL"){
						$( "#trans_file" ).val(myData.result);
				   		$("#result").load("showdata.php");
					}else{
						$("#result").html('<h3 style="text-align:center; color:red;"><strong>Error : '+myData.error+'</strong></h3>');
					}
				},

				Error: function(up, err) {
					console.log("\nError #" + err.code + ": " + err.message);
				}
			}
		});
		uploader.init();
	/* ########################    / Trans File Upload and display    ####################### */

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		Chart.defaults.global.legend.display = false;

/* ##########################################       LINE CHART     ################################################## */

		var ctxline = document.getElementById('linechart1');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Growth Fund A Accumulation',
					data:[<?=mb_substr($GB0009346486, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels1,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
		});

		var ctxline = document.getElementById('linechart2');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 150, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund A  Accumulation',
					data:[<?=mb_substr($GB00B1LB2Z79, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels2,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
		});

		var ctxline = document.getElementById('linechart3');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(150, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund F Accumulation',
					data:[<?=mb_substr($GB00BJQWRN41, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels3,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
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

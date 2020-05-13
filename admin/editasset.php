<?PHP
ini_set ("display_errors", "1");	error_reporting(E_ALL);

include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$asset_id = $_GET['id'];

$asset_name = sanSlash($_POST['asset_name']);
$asset_narrative = sanSlash($_POST['asset_narrative']);

$old_strats = sanSlash($_POST['old_strats']);
$old_strat_ids = explode("|",$old_strats);

$new_strats = sanSlash($_POST['new_strats']);

$cat_new = sanSlash($_POST['cat_new']);
$cat_ids = explode("|",$_POST['cat_ids']);
$cat_id = $_POST['cat'];
$asset_color = sanSlash($_POST['asset_color']);

$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE `tbl_fs_assets` SET `fs_asset_name`='$asset_name', `fs_asset_narrative`='$asset_narrative', `confirmed_by`='$name', `confirmed_date`='$str_date', `cat_id`='$cat_id', `asset_color`='$asset_color' WHERE (`id`='$asset_id')";

    $conn->exec($sql);

	//   Now do the strategies   //

		foreach ($old_strat_ids as $sid):
			$val = $_POST['strat_'.$sid];

			debug("UPDATE `tbl_fs_asset_strat_vals` SET `strat_val` =  '$val' WHERE id = '$sid';");


			$sql_new_catid = "UPDATE `tbl_fs_asset_strat_vals` SET `strat_val` =  '$val' WHERE id = '$sid';";
			$conn->exec($sql_new_catid);
		endforeach;
		


	//   Now do the categories   //

	if($cat_new!=''){
		$sql_new_cat = "INSERT INTO `tbl_fs_categories` (`cat_name`, `confirmed_by`, `confirmed_date`) VALUES ('$cat_new', '$name', '$str_date')";
		$conn->exec($sql_new_cat);
		$lastCatId = $conn->lastInsertId();

		$sql_new_catid = "UPDATE `tbl_fs_assets` SET `cat_id` =  $lastCatId WHERE id = $asset_id;";
		$conn->exec($sql_new_catid);

	}

$conn = null;

header("location:assets.php");
?>

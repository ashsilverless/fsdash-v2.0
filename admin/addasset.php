<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$asset_name = sanSlash($_POST['asset_name']);
$asset_narrative = sanSlash($_POST['asset_narrative']);

$cat_new = sanSlash($_POST['cat_new']);
$cat_ids = explode("|",$_POST['cat_ids']);
$cat_id = $_POST['cat'];

$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fs_assets` (`fs_asset_name`, `fs_asset_narrative`, `confirmed_by`, `confirmed_date`, `cat_id`) VALUES ('$asset_name', '$asset_narrative', '$name', '$str_date','$cat_id')";

    $conn->exec($sql);
	$lastId = $conn->lastInsertId();


	//   Now do the strategies   //
	$stratString = '';
	$strategies =  getTable('tbl_fs_strategy_names');
	foreach ($strategies as $strategy):
		$stratID = $strategy['id'];
		$val = $_POST['strat_'.$stratID];
		
		$sql_strat = "INSERT INTO `tbl_fs_asset_strat_vals` (`asset_id`, `strat_id`, `strat_val`, `confirmed_by`, `confirmed_date`) VALUES ('$lastId', '$stratID', '$val', '$name', '$str_date')";
		$conn->exec($sql_strat);
		$laststratId = $conn->lastInsertId();
		
		$stratString .= '|'.$laststratId.'|';
	endforeach;

		$sql = "UPDATE `tbl_fs_assets` SET `fs_asset_strats`='$stratString' WHERE (`id`='$lastId')";

    	$conn->exec($sql);

	//   Now do the categories   //

	for($a=0;$a<count($cat_ids);$a++){
		$id = $cat_ids[$a];
		if($_POST['cat'.$id]!=''){
			$sql_cat = "INSERT INTO `tbl_fs_asset_cats` (`fs_asset_id`, `fs_cat_id`) VALUES ('$lastId', '$id')";
			$conn->exec($sql_cat);
		}
	}

	if($cat_new!=''){
		$sql_new_cat = "INSERT INTO `tbl_fs_categories` (`cat_name`, `confirmed_by`, `confirmed_date`) VALUES ('$cat_new', '$name', '$str_date')";
		$conn->exec($sql_new_cat);
		$lastCatId = $conn->lastInsertId();

		$sql_new_catid = "UPDATE `tbl_fs_assets` SET `cat_id` =  $lastCatId WHERE id = $lastId;";
		$conn->exec($sql_new_catid);
	}

$conn = null;

header("location:assets.php");
?>

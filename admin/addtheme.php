<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$theme_title = sanSlash($_POST['theme_title']);
$theme_narrative = sanSlash($_POST['theme_narrative']);
$icon_file = sanSlash($_POST['icon_file']);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fs_themes` (`fs_theme_title`, `fs_theme_narrative`, `fs_theme_icon`, `confirmed_by`, `confirmed_date`, `bl_live`) VALUES ('".$theme_title."', '$theme_narrative', '$icon_file', '".$name."', '".$str_date."', '1')";

    $conn->exec($sql);
	$lastId = $conn->lastInsertId();

	//   Now do the strategies   //
	$stratString = '';
	$strategies =  getTable('tbl_fs_strategy_names');
	foreach ($strategies as $strategy):
		$stratID = $strategy['id'];
		$val = $_POST['strat_'.$stratID];
		
		$sql_strat = "INSERT INTO `tbl_fs_theme_strats` (`theme_id`, `strat_id`, `strat_val`, `confirmed_by`, `confirmed_date`) VALUES ('$lastId', '$stratID', '$val', '$name', '$str_date')";
		$conn->exec($sql_strat);
		$laststratId = $conn->lastInsertId();
		
		$stratString .= '|'.$laststratId.'|';
	endforeach;

		$sql = "UPDATE `tbl_fs_themes` SET `fs_theme_strategy`='$stratString' WHERE (`id`='$lastId')";

    	$conn->exec($sql);

$conn = null;



header("location:themes.php");
?>
<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];
$theme_title = sanSlash($_POST['theme_title']);
$theme_narrative = sanSlash($_POST['theme_narrative']);
$icon_file = sanSlash($_POST['icon_file']);

$old_strats = sanSlash($_POST['old_strats']);
$old_strat_ids = explode("|",$old_strats);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_fs_themes` SET bl_live = 1, fs_theme_title = '$theme_title', fs_theme_narrative = '$theme_narrative', fs_theme_icon = '$icon_file', confirmed_by = '$name', confirmed_date = '$str_date' WHERE id LIKE '$id' ;";

    $conn->exec($sql);

	//   Now do the strategies   //

		foreach ($old_strat_ids as $sid):

			$_POST['strat_'.$sid] != "" ? $val = 1 : $val = 0;

			$sql_new_catid = "UPDATE `tbl_fs_theme_strats` SET `strat_val` =  '$val', confirmed_by = '$name', confirmed_date = '$str_date' WHERE id = '$sid';";
			$conn->exec($sql_new_catid);
		endforeach;

$conn = null;



header("location:themes.php");
?>
<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$secretish = $ga->createSecret();

$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$destruct_date = sanSlash($_POST['destruct_date']);
$user_name = $user_prefix.' '.$first_name.' '.$last_name;
$password = sanSlash($_POST['password']);
$client_email = sanSlash($_POST['client_email']);
$telephone = sanSlash($_POST['telephone']);
$strategy = sanSlash($_POST['strategy']);
$fs_client_code = onlyNum($_POST['fs_client_code']);
$fs_client_desc = sanSlash($_POST['fs_client_desc']);

$telephone = onlyNum($_POST['telephone']);


$name = $_SESSION['fs_admin_name'];


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fsusers` (`fs_client_code`, `user_type`, `user_prefix`, `first_name`, `last_name`, `user_name`, `password`, `email_address`, `telephone`, `strategy`, `agent_level`, `destruct_date`, `googlecode`, `fs_client_desc`) VALUES ('$fs_client_code', 'user', '$user_prefix', '$first_name', '$last_name', '$user_name', '$password', '$client_email', '$telephone', '$strategy', '0', '$destruct_date', '$secretish', '$fs_client_desc')";

    $conn->exec($sql);

	$user_id = $conn->lastInsertId();


$conn = null;



header("location:edit_client.php?id=".$user_id);

?>

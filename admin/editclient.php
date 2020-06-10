<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];

$strategy = sanSlash($_POST['strategy']);
$name = $_SESSION['fs_admin_name'];

//      Client Details
$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$user_name = sanSlash($_POST['user_name']);
$destruct_date = sanSlash($_POST['destruct_date']);
$client_email= sanSlash($_POST['client_email']);
$strategy= sanSlash($_POST['strategy']);
$fs_client_desc= sanSlash($_POST['fs_client_desc']);
$telephone= sanSlash($_POST['telephone']);




$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Update Client Deets

	$sql = "UPDATE `tbl_fsusers` SET `user_prefix`='$user_prefix', `first_name`='$first_name', `last_name`='$last_name', `user_name`='$user_name', `email_address`='$client_email', `telephone`='$telephone', `strategy`='$strategy', `bl_live`='1', `destruct_date`='$destruct_date', `fs_client_desc` = '$fs_client_desc' WHERE (`id`='$id')";

	$conn->exec($sql);



$conn = null;

header("location:clients.php");

?>

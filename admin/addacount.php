<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$ac_client_code = $_POST['ac_client_code'];
$ac_product_type = $_POST['ac_product_type'];
$ac_designation = sanSlash($_POST['ac_designation']);
$ac_display_name = sanSlash($_POST['ac_display_name']);

$fund_ids= $_POST['fund_ids'];

$user_prefix = sanSlash($_POST['user_prefix']);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_accounts` (`ac_client_code`, `ac_designation`, `ac_product_type`, `ac_display_name`, `ac_funds`, `created_by`, `created_date`) VALUES ('$ac_client_code', '$ac_designation', '$ac_product_type', '$ac_display_name', '$fund_ids', '$name', '$str_date')";

    $conn->exec($sql);

$conn = null;

header("location:accounts.php");

?>

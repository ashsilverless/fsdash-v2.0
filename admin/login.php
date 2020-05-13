<?PHP
session_start();
	##################      LIVE SERVER     ###########################

	$host = "46.32.229.204";
	$user = "FeatherStoneDashboard";
	$pass = "FSD>Login-1";
	$db	 = "featherstone_db";
	$charset = 'utf8mb4';

	##################     / LIVE SERVER     ##########################

    $my_t=getdate(date("U"));
    $str_date=$my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];

$username = $password = "";       $_SESSION['loggedin'] = FALSE;

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	die ('Please fill both the username and password field!');
}

$username = $_POST['username'];
$password = $_POST['password'];

try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM tbl_fsadmin WHERE user_name LIKE '$username' AND destruct_date > '$str_date' AND bl_live > 0; ");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  // Verification success!
			session_regenerate_id();
			$_SESSION['fs_admin_name'] = $row['first_name'].' '.$row['last_name'];

		  $_SESSION['secret'] = $row['verification_code'];
		  $_SESSION['email'] 	= $row['email_address'];

            $_SESSION['username'] = $row['user_name'];
            $_SESSION['phone'] = $row['telephone'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['company_id'] = $row['company_id'];
            $_SESSION['agent_level'] = $row['agent_level'];
			$_SESSION['id'] = session_id();
            $dbhash = $row['password_hash'];
            password_verify($password,$dbhash) ? $_SESSION['loggedin'] = TRUE : $_SESSION['loggedin'] = FALSE;
	  }

	  $conn = null;        // Disconnect

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}


if(!$_SESSION['loggedin']){
    header("location:back-office.php");
}else{

    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE tbl_fsadmin SET last_logged_in = '$str_date' WHERE id = ".$_SESSION['user_id']."; ";
        $conn->exec($sql);



    $_SESSION['last_logged_in'] = date('jS M Y',strtotime($str_date));
    $conn = null;
    header("location:confirmation.php");
}

?>

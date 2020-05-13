<p>Starting..................</p>
<?PHP
​ini_set ("display_errors", "1"); 	error_reporting(E_ALL);
	##################      LIVE SERVER     ###########################
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname	 = "featherstone_db";
	$charset = 'utf8mb4';
	##################     / LIVE SERVER     ##########################
​
	    $my_t=getdate(date("U"));
	    $str_date=$my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];
​
	    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
​
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $sql = "UPDATE tbl_fsadmin SET last_logged_in = '$str_date' WHERE id = 6; ";
	        $conn->exec($sql);
​
​
​
	    $_SESSION['last_logged_in'] = date('jS M Y',strtotime($str_date));
	    $conn = null;
​
	?>
<p>Finished</p>

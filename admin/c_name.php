<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
	if (isset($_REQUEST['query'])) {
		$query = $_REQUEST['query'];
		
		    $sqlquery = "SELECT * FROM `tbl_fsusers` where user_type LIKE 'user' AND bl_live = 1 AND user_name LIKE '%{$query}%';";

			$result = $conn->prepare($sqlquery);
			$result->execute();

			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$array[] = array (
						'label' => $row['user_name'],
						'value' => $row['user_name'],
					);
			}
			//RETURN JSON ARRAY
			echo json_encode ($array);

	}



  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}










?>
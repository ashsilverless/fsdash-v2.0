<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
	if (isset($_REQUEST['query'])) {
		$query = $_REQUEST['query'];
		
		    $sqlquery = "SELECT * FROM `tbl_accounts` where ac_client_code LIKE '%{$query}%' OR ac_display_name LIKE '%{$query}%' AND bl_live = 1;";

			$result = $conn->prepare($sqlquery);
			$result->execute();

			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$array[] = array (
						'label' => $row['id'],
						'value' => $row['ac_client_code'].' : '.$row['ac_display_name'],
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
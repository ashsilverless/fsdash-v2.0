<?php include('../connection.php');
	
$cid = $_GET['cid'];

	$username	= $connect->real_escape_string($_POST['username']);
	$password	= $connect->real_escape_string($_POST['password']);

	
	/* Check Username and Password */
	$query		= db_query("select * from tbl_fsusers where id='".$cid."' ");	

	$resuser = mysqli_num_rows($query);
	if($resuser = 1){
		$row = mysqli_fetch_array($query);
		session_regenerate_id();
		$_SESSION['fs_client_email'] 	= $row['email_address'];
		$_SESSION['fs_client_pass'] 	= $row['password'];
		$_SESSION['fs_client_secret'] = $row['googlecode'];

		$_SESSION['fs_client_name'] = $row['first_name'];
		$_SESSION['fs_client_username'] = $row['user_name'];
		$_SESSION['fs_client_phone'] = $row['telephone'];
		$_SESSION['fs_client_user_id'] = $row['id'];
		$_SESSION['fs_client_company_id'] = $row['company_id'];
		$_SESSION['fs_client_agent_level'] = $row['agent_level'];
		$_SESSION['fs_client_id'] = session_id();
		$_SESSION['fs_client_featherstone_cc'] = $row['fs_client_code'];
		$_SESSION['fs_client_featherstone_uid'] = $row['id'];
		
		$_SESSION['fs_client_googleCode']	= $code;
		$_SESSION['fs_client_loggedin'] = TRUE;
		
		header("location:../client/home.php");
		exit();
	}else{
		$strmsg="Invalid Username or Password";												
		exit();
	}
?>
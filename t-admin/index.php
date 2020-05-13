<?PHP

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tim">
    <title>Landing Page</title>

  <!-- Custom fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="../css/cp-admin.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body id="page-top">
    <!-- Header -->
	<header class="header" style="background:#FFF;">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="header_content d-flex flex-row align-items-center justify-content-start">
						<img src="images/fs_logo1.jpg" alt="" height="110"/>
					</div>
				</div>
			</div>
		</div>
	</header>
    
    
  <!-- Page Wrapper -->
  <div id="wrapper">


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Countries Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="col-10 offset-1 mt-5">
						<div class="home_content text-center">
							<div class="login">
								<h1 id="logname">Login</h1>
								<form action="login.php" method="post" name="login" id="login">
									<label for="username"id="user" >
										<i class="fa fa-user"></i>
									</label>
									<input type="text" name="username" placeholder="Username" id="username" required>
									<label for="password" id="pass">
										<i class="fa fa-lock"></i>
									</label>
									<input type="password" name="password" placeholder="Password" id="password" required>

                                    <input  id="go" type="submit" value="Login">
								</form>
							</div>
						</div>
					</div>
          </div>
            

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Silverless 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Custom scripts for all pages-->
  <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="../../c_p/js/bootstrap.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="../../c_p/js/dashboard.js"></script>
  <script src="../../c_p/js/cp-admin.js"></script>
    <script type="text/javascript">
        
       $(document).ready(function() { 
           
           $('#featherstone').click(function() {
            if($("#featherstone").is(':checked')){
                   console.log('checked');
                   $("#login").attr('action', 'featherstone/login.php');
                   $("#logname").html("Featherstone Login").css("color","gray");
                   $("#user, #pass, #code, #go").css("background-color","gray");
                   $("#development").prop( "checked", false );
                   $("#cdevelopment").prop( "checked", false );
               }else{
                   $("#login").attr('action', 'authenticate.php');
                   $("#logname").html("Login").css("color","black");
                   $("#user, #pass, #code, #go").css("background-color","#3274d6");
               }
            });
        
           $('#development').click(function() {
               if($("#development").is(':checked')){
                   $("#login").attr('action', 'development.php');
                   $("#logname").html("Development Login").css("color","red");
                   $("#user, #pass, #code, #go").css("background-color","red");
                   $("#cdevelopment").prop( "checked", false );
               }else{
                   $("#login").attr('action', 'authenticate.php');
                   $("#logname").html("Login").css("color","black");
                   $("#user, #pass, #code, #go").css("background-color","#3274d6");
               }
            });
           
           $('#cdevelopment').click(function() {
            if($("#cdevelopment").is(':checked')){
                   console.log('checked');
                   $("#login").attr('action', 'investment/development.php');
                   $("#logname").html("cURL Development Login").css("color","green");
                   $("#user, #pass, #code, #go").css("background-color","green");
                   $("#development").prop( "checked", false );
               }else{
                   $("#login").attr('action', 'authenticate.php');
                   $("#logname").html("Login").css("color","black");
                   $("#user, #pass, #code, #go").css("background-color","#3274d6");
               }
            });
           
           
        
           
           
           
           
           
        });
        
        
    </script>
</body>

</html>

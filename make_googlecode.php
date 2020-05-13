<?php include 'header.php';
include('connection.php');

$passcode	= $connect->real_escape_string($_POST["passcode"]);	
$csrf		= $connect->real_escape_string($_POST["csrf"]);

require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$secretish = $ga->createSecret();


if ($csrf == $_SESSION["token"]) {
	$googlecode = $connect->real_escape_string($_POST['googlecode']);
	$fname 		= $connect->real_escape_string($_POST['fname']);
	$lname 		= $connect->real_escape_string($_POST['lname']);
	$email 		= $connect->real_escape_string($_POST['username']);		
	$password	= $connect->real_escape_string($_POST['password']);
	
	$secret = $ga->createSecret();
	$qrCodeUrl 	= $ga->getQRCodeGoogleUrl($email, $secret,'www.featherstone.co.uk');

}




?>
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
            <div class="col-6 offset-3 login">
						<div class="text-center border-box login__inner">
								<h1 id="loginlogo" class="logo">
                                    <?php include 'client/images/fs-logo.php'; ?>
                                </h1>
								<form name="reg" action="make_googlecode.php" method="POST">
									<input type="hidden" name="csrf" 	 value="<?php print $_SESSION["token"]; ?>" >
									<input type="text" name="googlecode" value="<?php echo $secret; ?>" >
									<img src='<?php echo $qrCodeUrl; ?>'/>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
												<label>First name</label>
												<input type="text" name="fname" autocomplete="off" id="fname" value="<?php echo $fname; ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
												<label>last name</label>
												<input type="text" name="lname" id="lname" autocomplete="off" value="<?php echo $lname; ?>" required  />
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<label>Email Address</label>
										<input type="text" name="username" id="username" autocomplete="off" value="" required>
                                        
                                    </div>
                                    <div class="form-group">
										<label>Password</label>
										<input type="password" name="password" id="password" autocomplete="off" value="" required>
                                        
                                    </div>
                                    
                                    <div class="silverless-button">
                                        <input  id="go" type="submit" value="Create Account">
                                    </div>
									<div class="form-text">
                                        <span><?=$secretish;?></span>
                                    </div>
                                </form>

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

  <?php define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/footer.php');?>

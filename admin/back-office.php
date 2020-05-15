<?php include '../header.php';?>

  <div id="wrapper">

    <div id="content-wrapper" class="d-flex flex-column">

      <div id="content">

        <div class="container-fluid">

              <div class="row">
                <div class="col-6 offset-3 login">
                    <div class="text-center border-box login__inner">
                        <h1 id="loginlogo" class="logo">
                            <?php include '../client/images/fs-logo.php'; ?>
                        </h1>
                        <form action="login.php" method="post" name="login" id="login">
                            <label for="username" id="user" >User Name</label>
                            <input type="text" name="username" placeholder="Username" id="username" required class="mb1">
                            <label for="password" id="pass">Password</label>
                            <input type="password" name="password" placeholder="Password" id="password" required class="mb1">
                            <input  id="go" type="submit" value="Login">
                        </form>
                    </div>
                </div>
            </div>


        </div>

      </div>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Custom scripts for all pages-->
  <script src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
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

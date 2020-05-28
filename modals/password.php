<!--    Logged Out  -->
    <div class="modal fade fullscreen" id="password-change" tabindex="-1" role="dialog" aria-labelledby="LoggedOut" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">You should change your password</h5>
        </div>
        <div class="modal-body">


		<form action="editclient.php" method="post" id="editclient" name="editclient" class="settings">

                <div class="variable-details">
                    <h2 class="heading heading__2">Change Password</h2>
                    <label for="password" id="currentpasswordlabel" >Current Password</label>
                    <input type="password" id="password" name="password" value="" class="mb1">

                    <label for="newpassword" id="newpasswordlabel" >New Password</label>
                    <input type="password" id="newpassword" name="newpassword" value="" class="mb1">

                    <label for="confirmpassword" id="confirmpasswordlabel" >Confirm Password</label>
                    <input type="password" id="confirmpassword" name="confirmpassword" value="" class="mb1">
                </div>

                <div class="confirm-message">
                    <span id="message"></span>
                </div>b

                <input id="submit" type="submit" name="submit" value="Save Changes" />

            </form>




		</div>

      </div>
    </div>
  </div>
<script type="text/javascript">

		$('#newpassword, #confirmpassword').on('keyup', function() {
		  if ($('#newpassword').val() == $('#confirmpassword').val()) {
			$('#message').html('Matching').css('color', 'green');
			$('#submit').prop('disabled', false);
		  } else {
			$('#message').html('Not Matching').css('color', 'red');
			$('#submit').prop('disabled', true);
		  }
		});

    </script>

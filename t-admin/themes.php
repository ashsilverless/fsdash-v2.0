<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
<a href="#" class="button button__raised button__inline">Add New Theme</a>
<h1 class="heading heading__2">Themes</h1>
<div id="themedetails" class="col-md-12 mt-5"></div>
<div class="themes-table">
    <div class="themes-table__head">
        <h3 class="heading heading__4">Theme Name</h3>
        <h3 class="heading heading__4">Icon</h3>
        <h3 class="heading heading__4">Narrative</h3>
    </div>

    <div class="recess-box">

<?php
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_themes` where bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
    <div class="themes-table__item">
        <h3 class="heading heading__4"><?= $row['fs_theme_title'];?></h3>
        <img src="../icons_folder/<?= $row['fs_theme_icon'];?>">
        <p><?= substr($row['fs_theme_narrative'],0,385);?>...</p>
        <a href="#?id=<?=$row['id'];?>" class="button button__raised edittheme">Edit Theme</a>
        <a href="#" data-href="deletetheme.php?id=<?= $row['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="button button__raised button__danger">Delete Theme</a></td>
    </div>
<?php }
$conn = null;        // Disconnect
}
catch(PDOException $e) {
echo $e->getMessage();
}?>


    </div>
</div><!--themes table-->


</div><!--col-12-->

        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Themes</h1>
			<a href="#" class="addtheme btn btn-add"><i data-feather="plus-square"></i> Add Theme</a>

			<div class="table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
				      <td width="16%" bgcolor="#FFFFFF"><strong>Theme Name</strong></td>
					  <td width="40%" bgcolor="#FFFFFF"><strong>Narrative</strong></td>
					  <td width="12%" bgcolor="#FFFFFF"><strong>Actioned By</strong></td>
					  <td width="12%" bgcolor="#FFFFFF"><strong>Date</strong></td>
					  <td width="20%" bgcolor="#FFFFFF"></td>
				  </tr>
					<?php
					try {
					  // Connect and create the PDO object
					  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
					  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

						$query = "SELECT *  FROM `tbl_fs_themes` where bl_live = 1;";

					  	$result = $conn->prepare($query);
					  	$result->execute();

							  // Parse returned data
							  while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
								<tr>
								  <td><img src="../icons_folder/<?= $row['fs_theme_icon'];?>" style="margin-right:10px; max-width:40px;"><?= $row['fs_theme_title'];?></td>
								  <td><?= substr($row['fs_theme_narrative'],0,85);?>...</td>
								  <td><?= $row['confirmed_by'];?></td>
								  <td><?= date('j M y',strtotime($row['confirmed_date']));?></td>
								  <td><a href="#?id=<?=$row['id'];?>" class="edittheme btn" style="font-size:0.6em; font-weight:bold; margin-right:10px;">Edit Theme</a><a href="#" data-href="deletetheme.php?id=<?= $row['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.6em; font-weight:bold;">Delete Theme</a></td>
							    </tr>
							<?php }

					  $conn = null;        // Disconnect

					}

					catch(PDOException $e) {
					  echo $e->getMessage();
					}
					?>
			      </tbody>
				</table>
		  </div>





		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

		<div id="themedetails" class="col-md-12 mt-5"></div>

    </main>-->
      </div>
    </div>

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addtheme").click(function(e){
          e.preventDefault();
		  $("#themedetails").load("add_theme.php");
		});

		$(".edittheme").click(function(e){
          e.preventDefault();
		  var theme_id = getParameterByName('id',$(this).attr('href'));
			console.log(theme_id);
		  $("#themedetails").load("edit_theme.php?id="+theme_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});

	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    </script>
  </body>
</html>

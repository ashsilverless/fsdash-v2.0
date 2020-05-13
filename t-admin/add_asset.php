<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_categories` where bl_live = 1;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $cats[] = $row;
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}


?>


        
<form action="addasset.php" method="post" id="addtheme" name="addtheme">
			<div id="theme_details" class="col-md-6" style="float:left;">
				
					<h4>Details</h4>
					<p>Asset Name<br>
					<input type="text" id="asset_name" name="asset_name"></p>
					<p>Narrative<br>
			  <textarea name="asset_narrative" style="width:90%; min-height:240px;" id="asset_narrative"></textarea></p>
				<h5>Growth</h5>
				<div class="col-md-4" style="float:left;">
				<p>Steady<br>
					<input type="text" name="growth_steady" id="growth_steady" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
				</div>
				<div class="col-md-4" style="float:left;">
				<p>Sensible<br>
					<input type="text" name="growth_sensible" id="growth_sensible" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
				</div>
				<div class="col-md-4" style="float:left;">
				<p>Serious<br>
					<input type="text" name="growth_serious" id="growth_serious" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"></p>
				</div>
	
			</div>
	
			<div id="asset_categories" class="col-md-3" style="float:left;"><h4>Categories</h4><!--<a href="#" class="addasset"><i data-feather="plus-square"></i> Edit Categories</a>-->
				<?php $idString = ''; for($a=0;$a<count($cats);$a++){ $idString .= $cats[$a]['id'].'|';?>
					<label><input type="radio" name="cat" value="<?=$cats[$a]['id'];?>" id="cat">  <?=$cats[$a]['cat_name'];?></label><a href="#" data-href="deletecat.php?id=<?=$cats[$a]['id'];?>" data-toggle="modal" data-target="#confirm-catdelete" class="delcat"><i data-feather="trash-2"></i></a><br>
				<?php } ?>
				<p>Add Category<br>
				<input type="text" id="cat_new" name="cat_new"><input type="hidden" id="cat_ids" name="cat_ids" value="<?=substr($idString, 0, -1);?>"></p>
			</div>
	
	
			<div id="fund_actions" class="col-md-3" style="float:left;">
				<h5>Asset Actions</h5>
				<input type="submit" class="btn btn-grey" value="Add Asset">
			</div>
	
</form>			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

    <script>

	feather.replace();
		
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

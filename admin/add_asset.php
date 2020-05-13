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
<form action="addasset.php" method="post" id="addtheme" name="addtheme" class="asset-form">
    <div class="content">
        <h3 class="heading heading__2">Asset Details</h3>

        <div class="details">
            <label>Asset Name</label>
            <input type="text" id="asset_name" name="asset_name">
            <label>Narrative</label>
            <textarea name="asset_narrative" id="asset_narrative"></textarea>
            <label>Portfolio</label>
			<div class="row">
				<?php $stratHeadings =  getTable('tbl_fs_strategy_names');
				foreach ($stratHeadings as $strathead):
                    $portfolioChar = substr($strathead['strat_name'], -1);
				?>
					<div class="col-3">
						<label><?=$portfolioChar;?></label>
						<input type="text" name="strat_<?=$strathead['id'];?>" id="strat_<?=$strathead['id'];?>" class="calculator-input" onkeypress="return event.charCode >= 46 && event.charCode <= 57" size="5"  value="">
					</div>
				<?php endforeach; ?>


            </div><!--row-->

        </div><!--details-->

        <div class="categories">
            <label>Categories</label>
            <div class="inner">
                <?php $idString = ''; for($a=0;$a<count($cats);$a++){ $idString .= $cats[$a]['id'].'|';?>
                <div class="radio-item">
                    <input class="star-marker" type="radio" name="cat" value="<?=$cats[$a]['id'];?>" id="cat<?=$cats[$a]['id'];?>">
                    <?php define('__ROOT__', dirname(dirname(__FILE__)));
                    include(__ROOT__.'/admin/images/star.php'); ?>
                    <label for="cat<?=$cats[$a]['id'];?>"><?=$cats[$a]['cat_name'];?></label>
                </div>
                <a href="#" data-href="deletecat.php?id=<?=$cats[$a]['id'];?>" data-toggle="modal" data-target="#confirm-catdelete" class=" button button__delete elcat"><i data-feather="trash-2"></i></a><br>
                <?php } ?>
            </div><!--inner-->
            <label>Add Category</label>
            <input type="text" id="cat_new" name="cat_new"><input type="hidden" id="cat_ids" name="cat_ids" value="<?=substr($idString, 0, -1);?>">
            <a href="#" class="addasset button button__raised button__inline">Add Category</a>
        </div>
    </div>

    <div class="control">
            <h3 class="heading heading__2">Asset Actions</h3>
            <div id="fund_actions">
                <input type="submit" class="button button__raised" value="Save Changes">
            </div>
        </div>

</form>

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

<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$theme_id = $_GET['id'];

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_themes` where id = $theme_id;";

    $result = $conn->prepare($query); 
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {  
			  $theme_title = $row['fs_theme_title'];
			  $theme_narrative = $row['fs_theme_narrative'];
			  $theme_icon = '<img src="../icons_folder/'.$row['fs_theme_icon'].'" style="margin-right:10px; max-width:80px;">';
			  $theme_icon_file = $row['fs_theme_icon'];
			  $steady = $row['fs_theme_steady'];
			  $serious = $row['fs_theme_serious'];
			  $sensible = $row['fs_theme_sensible'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}
?>


        
<form action="edittheme.php?id=<?=$theme_id;?>" method="post" id="edittheme" name="edittheme">
			<div id="theme_details" class="col-md-6" style="float:left;">
				
					<h4>Details</h4>
					<p>Theme Title<br>
					<input type="text" id="theme_title" name="theme_title" value="<?=$theme_title;?>"></p>
					<p>Narrative<br>
			  <textarea name="theme_narrative" style="width:90%; min-height:240px;" id="theme_narrative"><?=$theme_narrative;?></textarea></p>
					<p>Theme Type<br>
					  <label>
						  <input name="theme_type_steady" type="checkbox" id="theme_type_steady" value="steady" <?php if($steady=='1'){?>checked="checked"<?php }?>>
						  Steady</label>
						<br>
						<label>
						  <input type="checkbox" name="theme_type_serious" value="serious" id="theme_type_serious" <?php if($serious=='1'){?>checked="checked"<?php }?>>
						  Serious</label>
						<br>
						<label>
						  <input type="checkbox" name="theme_type_sensible" value="sensible" id="theme_type_sensible" <?php if($sensible=='1'){?>checked="checked"<?php }?>>
						  Sensible</label>
				</p>
				
				
				
				
				
			</div>
			<div id="icon_upload" class="col-md-3" style="float:left;"><h4>Upload Icon</h4>		
				<div id="fundfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="fundcontainer"><a id="pickfund" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input name="icon_file" type="hidden" id="icon_file" value="<?=$theme_icon_file;?>"><div id="theme_icon"><?=$theme_icon;?></div>
			</div>
			<div id="fund_actions" class="col-md-3" style="float:left;">
				<h5>Theme Actions</h5>
				<p>Last edited on 14 Jan by James Barton</p>
				<input type="submit" class="btn btn-grey" value="Edit Theme">
			</div>
	
</form>			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

    <script>

	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
		
 // Fund File Upload
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfund',
	container: document.getElementById('fundcontainer'),
	url : 'iconupload.php',
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : '.js/plupload/Moxie.xap',
	unique_names : true,
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,jpeg,gif,svg"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('fundfilelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			for (var i in files) {
				$( "#fund_file" ).val(files[i].name); 
			}
			uploader.start();
		},

		UploadProgress: function(up, file) {
			//
		},
        
        FileUploaded: function(up, file, info) {
            var myData;
				try {
					myData = eval(info.response);
				} catch(err) {
					myData = eval('(' + info.response + ')');
				}

		   $( "#icon_file" ).val(myData.result); 
		   $( "#theme_icon" ).html('<img src="../icons_folder/'+myData.result+'" style="margin-right:10px; max-width:80px;">'); 
        },


		Error: function(up, err) {
			console.log("\nError #" + err.code + ": " + err.message);
		}
	}
});
		
uploader.init();
    
		
	 

    </script>
  </body>
</html>

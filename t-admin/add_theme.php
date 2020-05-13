<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>


        
<form action="addtheme.php" method="post" id="addtheme" name="addtheme">
			<div id="theme_details" class="col-md-6" style="float:left;">
				
					<h4>Details</h4>
					<p>Theme Title<br>
					<input type="text" id="theme_title" name="theme_title"></p>
					<p>Narrative<br>
			  <textarea name="theme_narrative" style="width:90%; min-height:240px;" id="theme_narrative"></textarea></p>
					<p>Theme Type<br>
					  <label>
						  <input name="theme_type_steady" type="checkbox" id="theme_type_steady" value="steady" checked="checked">
						  Steady</label>
						<br>
						<label>
						  <input name="theme_type_serious" type="checkbox" id="theme_type_serious" value="serious" checked="checked">
						  Serious</label>
						<br>
						<label>
						  <input name="theme_type_sensible" type="checkbox" id="theme_type_sensible" value="sensible" checked="checked">
						  Sensible</label>
				</p>
            </div>
			<div id="icon_upload" class="col-md-3" style="float:left;"><h4>Upload Icon</h4>		
				<div id="fundfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="fundcontainer"><a id="pickfund" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><div id="theme_icon"></div><input name="icon_file" type="hidden" id="icon_file">
			</div>
			<div id="fund_actions" class="col-md-3" style="float:left;">
				<h5>Theme Actions</h5>
				<p>Last edited on 14 Jan by James Barton</p>
				<input type="submit" class="btn btn-grey" value="Add Theme">
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

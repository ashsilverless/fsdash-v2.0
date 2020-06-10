<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$ac_id = $_GET['ac_id'];


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_accounts` SET bl_live = 0, created_by = '$name', created_date = '$str_date' WHERE id = '$ac_id' ;";

    $conn->exec($sql);

$conn = null;

//    Get the account and fund details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  	$query = "SELECT * FROM `tbl_accounts` where bl_live = 1 order by ac_client_code asc;";

  	$result = $conn->prepare($query);
  	$result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $accounts[] = $row;
  }


  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 0 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}

if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0&a_name='.$_GET['a_name'].'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&a_name='.$_GET['a_name'].'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&a_name='.$_GET['a_name'].'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10&a_name='.$_GET['a_name'].'">10</a>&nbsp;|&nbsp;<a href="?rpp=30&a_name='.$_GET['a_name'].'">30</a>&nbsp;|&nbsp;<a href="?rpp=50&a_name='.$_GET['a_name'].'">50</a>&nbsp;|&nbsp;<a href="?rpp=999&a_name='.$_GET['a_name'].'"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.'</div><div style="margin:auto; padding:5px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;">'.$ipp.'</div>';

echo ($rspaging);
foreach ($accounts as $ac):   ?>
	<p><a href="?ac_id=<?= $ac['id'];?>" class="delaccount" style="margin-right:10px;">X</a><?= $ac['ac_client_code']?> - <?= $ac['ac_display_name']?></p>
<?php endforeach; ?>
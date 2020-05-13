<?PHP

include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$shares = gimme_shares();

foreach($shares as $sedol => $shares_qty) {
    echo ($sedol.'   :   '.$shares_qty.'<br>');
}



?>

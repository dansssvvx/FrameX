<?php

include_once "../Conf/database.php";

session_start();

$username = $_SESSION['username'];

$stmt1 = $db->prepare( "INSERT INTO log (isi_log, tanggal_log, id_user) VALUES ( ?, NOW(), ?)");

if (!$_SESSION['is_admin']) {
    $role = "user";
} else{
    $role = "admin";
}
$isilog = $username . " (" . $role .") berhasil logout";
$stmt1->execute([$isilog, $_SESSION['user_id']]);

session_unset();
session_destroy(); 
header('Location: login.php');

exit;
?>
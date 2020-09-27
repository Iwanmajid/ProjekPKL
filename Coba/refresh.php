<?php
include 'koneksi.php';

$query = "UPDATE topic SET status='Unsubscribed' ";
$dewan1 = $db1->prepare($query);
$dewan1->bind_param("i", $id);
$dewan1->execute();

?>
<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mydb";
$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
	or die ('Error connecting to <b>'.$dbname.'</b>');


$client = $_POST["client"];
$topic = $_POST["topicPublish"];
$pesan = $_POST["msgSend"];

$sql = "INSERT INTO tbl_messages(clientID,topic,message) VALUES ('$client','$topic','$pesan')";
$tambah = mysqli_query($koneksi, $sql);

?>
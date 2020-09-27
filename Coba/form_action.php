<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mydb";
$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
	or die ('Error connecting to <b>'.$dbname.'</b>');


$id = $_POST["id"];
$nama_topic = $_POST["nama_topic"];
$status= 'Unsubscribed';

if ($id == null) {
	$sql = "insert into topic (nama_topic,status) values ('$nama_topic','$status')";
	$tambah = mysqli_query($koneksi, $sql);
} else {
	$sql = "UPDATE topic SET topic.nama_topic= '$nama_topic' WHERE id = '$id'";
	$tambah = mysqli_query($koneksi, $sql);
	
}
mysqli_close($koneksi);

?>
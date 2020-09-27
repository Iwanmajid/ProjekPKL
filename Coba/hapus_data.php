<?php
include 'koneksi.php';

$id = $_POST['id'];

$query2 = "SELECT * FROM topic WHERE id=? ORDER BY id DESC";
$dewan1 = $db1->prepare($query2);
$dewan1->bind_param("i", $id);
$dewan1->execute();

$res1 = $dewan1->get_result();
    while ($row = $res1->fetch_assoc()) {
        $h['id'] = $row["id"];
        $h['nama_topic'] = $row["nama_topic"];
        $h['status'] = $row["status"];
        
    }
    echo json_encode($h);
	
	
	if ($res1->num_rows > 0) {
		$query = "DELETE FROM topic WHERE id=?";
		$dewan1 = $db1->prepare($query);
		$dewan1->bind_param("i", $id);
		$dewan1->execute();

		$db1->close();	
	}
?>
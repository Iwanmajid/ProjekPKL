<?php
    include 'koneksi.php';
    $id = $_POST['id'];
    $query = "SELECT * FROM topic WHERE id=? ORDER BY id DESC";
    $data = $db1->prepare($query);
    $data->bind_param('i', $id);
    $data->execute();
    $res1 = $data->get_result();
    while ($row = $res1->fetch_assoc()) {
        $h['id'] = $row["id"];
        $h['nama_topic'] = $row["nama_topic"];
        $h['status'] = $row["status"];
        
    }
    echo json_encode($h);
?>
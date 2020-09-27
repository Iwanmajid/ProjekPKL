<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <td>No</td>
            <td>Nama Topic</td>
			<td>Status</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php
            include 'koneksi.php';
            $no = 1;
            $query = "SELECT * FROM topic ORDER BY id DESC";
            $data = $db1->prepare($query);
            $data->execute();
            $res1 = $data->get_result();

            if ($res1->num_rows > 0) {
                while ($row = $res1->fetch_assoc()) {
                    $id = $row['id'];
                    $nama_topic = $row['nama_topic'];
					$status = $row['status'];
                    
               
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $nama_topic; ?></td>
				<td><?php echo $status; ?></td>
               
                <td>
					<button id="<?php echo $id; ?>" class="btn btn-success btn-sm subscribe"> <i class="fa fa-edit" ></i> Subscribe </button>
                    <button id="<?php echo $id; ?>" class="btn btn-success btn-sm unsubscribe"> <i class="fa fa-trash"></i> Unsubscribe </button>
                    <button id="<?php echo $id; ?>" class="btn btn-danger btn-sm hapus_data"> <i class="fa fa-trash"></i> Hapus </button>
                </td>
            </tr>
        <?php } } else { ?> 
            <tr>
                <td colspan='7'>Tidak ada data ditemukan</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Publish -->
<?php
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$dbname = "mydb";
			$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
						or die ('Error connecting to <b>'.$dbname.'</b>');
			$perintah="select * from topic WHERE status='Subscribed' order by id DESC";
			$tampil = mysqli_query($koneksi, $perintah);
		?>
<br> 
<h2 align="left" >Menu Publish Topic</h2>
        <form method="post" class="form-PublishTopic" id="form-PublishTopic">  
        	<div class="row">
				<div class="form-groupPublishTopic">
					<div class="col-sm-12">
						<label>Nama Topic Untuk di Publish </label>
						<select name="topicPublish" id="topicPublish" class="form-control" required="true">
							<?php while($data=mysqli_fetch_array($tampil)){?>
							<option>
							<?php echo $data['nama_topic']?> </option>
							<?php } ?>
						</select>				  
					</div>
				</div>
			</div>
        </form>





<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    } );
    
    function reset(){
        document.getElementById("nama_topic").value = "";
    }
	
	function Cstatus(){
		return document.getElementById("status").value;
	}
	
	//subscribe  
	 $(".subscribe").click(function(){
        var id = $(this).attr('id');

		if (Cstatus()== "connected"){
			$.ajax({
				type: 'POST',
				url: "subscribe.php",
				data: {id:id},
				dataType:'json',
				success: function(response) {
					$('.data').load("data.php");
					//$('.publish').load("publish.php");
					topic = String(response.nama_topic);
					subscribe(topic);
					//client.subscribe(topic,subscribeOptions);
					alert("Subscribed from topic " + topic);	
				}
			});	
		}else{
			alert("Maaf, anda belum terhubung");
		}
		
    });
	

	//unsubscribe
	 $(".unsubscribe").click(function(){
        var id = $(this).attr('id');
		
		if (Cstatus()== "connected"){
			$.ajax({
				type: 'POST',
				url: "unsubscribe.php",
				data: {id:id},
				dataType:'json',
				success: function(response) {
					$('.data').load("data.php");
					//$('.publish').load("publish.php");
					topic = response.nama_topic; 
					unsubscribe(topic); 
					//client.unsubscribe(topic,unsubscribeOptions);
					alert("Unsubscribed from topic " + topic);
				}
			});
		}else{
			alert("Maaf, anda belum terhubung");
		}
        
    });
	
	//edit 
     $(".edit").click(function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: "get_data.php",
            data: {id:id},
            dataType:'json',
            success: function(response) {
                reset();
                document.getElementById("id").value = response.id;
                document.getElementById("nama_topic").value = response.nama_topic;                               
            }
        });
    });

	//hapus
     $(".hapus_data").click(function(){
        var id = $(this).attr('id');
		
		if (Cstatus()== "connected"){
			$.ajax({
				type: 'POST',
				url: "hapus_data.php",
				data: {id:id},
				dataType:'json',
				success: function(response) {
					stt = response.status;
					$('.data').load("data.php");
				
					topic = response.nama_topic; 
					if(stt== "Subscribed"){
						client.unsubscribe(topic);
						alert("Unsubscribed dan hapus topic " + topic);
						showStatus("Unsubscribe from topic " + topic);
					}else{
						alert("hapus topic " + topic);
					}
					
				}
			});
		}else{
			alert("Maaf, anda belum terhubung");
		}
        
    });
	
	 
</script>
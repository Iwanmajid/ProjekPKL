<?php
//Menggabungkan dengan file koneksi yang telah kita buat
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    
	<title>MQTT Web Client</title>
	<!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
	<!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
	<!-- MQTT --> 
	<script type="text/javascript" src="js/mqttws31.js"></script>
	<script type="text/javascript" src="js/myscripts.js"></script>
	
</head>
<body onload="<?php include_once 'refresh.php'; ?>">
	<nav class="navbar navbar-dark bg-primary">
	  <a class="navbar-brand" href="index.php" style="color: #fff;">
	    MQTT Web Client
	  </a>
	</nav>

	<div class="container">
		
		<!-- Untuk Koneksi -->
			<h2 align="left" >Koneksi</h2>
			<form method="post" class="form-koneksi" id="form-koneksi">
				<div class="row">
						<div class="col-sm-3">
							<div class="form-groupKoneksi">
								<label>Broker</label>
								<input type="hidden" name="status" id="status">
								<input type="text" name="broker" id="broker" class="form-control" required="true">
								<p class="text-danger" id="err_ip_broker"></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-groupKoneksi">
								<label>Port</label>
								<input type="text" name="port" id="port" class="form-control" required="true">
								<p class="text-danger" id="err_port"></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-groupKoneksi">
								<label>Client ID</label>
								<input type="text" name="clientID" id="clientID" class="form-control" required="true">
								<p class="text-danger" id="err_clientID"></p>
							</div>
						</div>
						
				</div>
					
				<div class="form-groupKoneksi">
					<button type="button" onclick="doConnect()" name="Connect" id="Connect" class="btn btn-primary">
						<i class="fa fa-save"></i> Connect
					</button>
					<button type="button" onclick="disconnect()" name="disConnect" id="disConnect" class="btn btn-primary">
						<i class="fa fa-save"></i> Disconnect
					</button>
				</div>
			</form>
		
		<!-- Untuk Tambah Topic -->
		<br>
		<h2 align="left" >Menu Tambah Topic</h2>
        <form method="post" class="form-tambahTopic" id="form-tambahTopic">  
        	<div class="row">
        		<div class="col-sm-3">
        			<div class="form-groupTambahTopic">
						<label>Nama Topic</label>
						<input type="hidden" name="id" id="id">
						<input type="text" name="nama_topic" id="nama_topic" class="form-control" required="true">
						<p class="text-danger" id="err_nama_topic"></p>
					</div>
        		</div>
			</div>

			<div class="form-groupTambahTopic">
				<button type="button" name="simpan" id="simpan" class="btn btn-primary">
					<i class="fa fa-save"></i> Simpan
				</button>
			</div>
        </form>
		
		<!-- Tempat Menampilkan data -->
		<br>
		<div class="data"></div>
	
		
		<!-- Untuk Publish Topic-->
		<form method="post" class="form-PublishTopic" id="form-PublishTopic">  
			<div class="form-groupPublishTopic">
				<label>Pesan</label>
				<input type="hidden" name="client" id="client">
				<textarea name="msgSend" id="msgSend" class="form-control" required="true"></textarea>
				<p class="text-danger" id="err_msgSend"></p>
			</div>
			<div class="form-groupPublishTopic">
					<button type="button" name="publish" id="publish" class="publish btn btn-primary" onclick="DIpublish()">
						<i class="fa fa-save"></i> Publish
					</button>
			</div>
		</form>
		
		<!-- Untuk Pesan Masuk-->
		<br> <br>
		<div class="row"> 
			<div class="col-sm-6 col-md-6 col-lg-6">
				<label><h3>Pesan Masuk</h3></label><br>
				<textarea rows="8" cols="75" id="msgReceived" placeholder="Pesan Masuk"></textarea>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-6">
				<label><h3>Logs</h3></label><br>
				<textarea rows="8" cols="75" id="logs" placeholder="Logs"></textarea><br>	
			</div>
		</div>
		
		
		
		
    </div>
	
	<br> <br>
	<hr>
    <div class="text-center">© <?php echo date('Y'); ?> Copyright :
	    <a href="https://localhost/"> IoT MQTT 1 </a>
	</div>

    <!-- Untuk Keperluan Demo Saya Menggunakan JQuery Online, Jika sobat menggunakan untuk keperluan developmen/production maka download JQuery pada situs resminya -->
    <!-- JQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- DataTable -->
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>

  	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		//publish
		function DIpublish(){
			var pub = $('.form-PublishTopic').serialize();
			var topic = document.getElementById("topicPublish").value;
			var pesan = document.getElementById("msgSend").value;
			
			if (pesan!="") {
				if (Cstatus()== "connected"){
				publish(topic);
				$.ajax({
						type: 'POST',
						url: "insert.php",
						data: pub,
						success: function() {}
					});
				}else{
					alert("Maaf, anda belum terhubung");
				}
			}
		}	

		$(document).ready(function(){
			$('.data').load("data.php");
	    });
		
	    $("#simpan").click(function(){
	        var data = $('.form-tambahTopic').serialize();
            var nama_topic = document.getElementById("nama_topic").value;
            

            if (nama_topic=="") {
            	document.getElementById("err_nama_topic").innerHTML = "Nama Topic Harus Diisi";
            } else {
            	document.getElementById("err_nama_topic").innerHTML = "";
            }
           

            if (nama_topic!="") {
				if (Cstatus()== "connected"){
					$.ajax({
						type: 'POST',
						url: "form_action.php",
						data: data,
						success: function() {
							$('.data').load("data.php");
							document.getElementById("id").value = "";
							document.getElementById("form-tambahTopic").reset();
						}
					});
				}else{
					alert("Maaf, anda belum terhubung");
				}
            	
            }
	        
	    });
	
	</script>
</body>
</html>
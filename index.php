<!DOCTYPE html>
<html lang="id">
	<head>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
		<!-- <link rel="icon" type="image/png" href="assets/image/icon.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="assets/image/apple-touch-icon-120x120-precomposed.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="assets/image/apple-touch-icon-152x152-precomposed.png" /> -->
    	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="Content-Type" content="Text/html; charset=utf-8" />
			<title></title>
			<meta name="title" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap" rel="stylesheet">
		<!-- <meta name="description" content="Sistem Akademik TK Satu Atap Kenanga,Tk Satu Atap Kenanga Adalah Sekolah Swasta Yang Berada Di Cilegon Banten,Ini Adalah Halaman Awal Untuk Sistem Akademik tk satu atap kenanga" />
		<meta name="keyword" content="tk satu atap kenanga,taman kanak-kanak satu atap kenanga,satu atap kenanga kindergarten,tk cilegon,tk satu atap kenanga cilegon,satu atap kenanga kindergarten,tk cilegon banten,tk satu atap kenanga cilegon banten" />
		<meta name="robots" content="index,follow" />
	 	<meta name="googlebot" content="index,follow" />
	 	<meta property="og:title" content="Login Page - Sistem Akademik TK Satu Atap Kenanga Cilegon">
		<meta property="og:description" content="Sistem Akademik TK Satu Atap Kenanga,Tk Satu Atap Kenanga Adalah Sekolah Swasta Yang Berada Di Cilegon Banten,Ini Adalah Halaman Awal Untuk Sistem Akademik tk satu atap kenanga" />
		<meta property="og:type" content="website">
		<link rel="canonical" href="https://satuatap-kenanga.com" />
	    <link rel="preconnect" href="https://fonts.gstatic.com" />
		<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" /> -->
	</head>
	<style type="text/css">
		html, body, main {
		  margin: 0;
		  height: 100%;
		  width: 100%;
		}
	</style>
	<body style="background-color: #fff;text-align: center; font-family: 'Poppins', sans-serif; height: 100%;">
		<main class="index" >
			
			<div style="display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;">
				<div style="margin: auto;"></div>
				<div style="margin: auto;">
					<h3 style="font-family: 'Poppins', sans-serif;font-weight: 500;">NAIVE BAYES CLASSIFIER</h3><br>
					<div>
						<form method="post" id="import_excel_form" enctype="multipart/form-data" >
							<input id="trainUp" style="background-color: #f5f8fa; color: #9699A8; border-radius: 10px; padding: 5px 20px;border: none;outline: none; width: 400px; height: 25px; font-size: 12px; cursor: pointer; font-family: 'Poppins', sans-serif;" type="text" name="training_path" placeholder="Pilih Data Training" onclick="document.getElementById('training_up').click();" onchange="change();">
							<!-- <input style="background-color: #009ef7; color: #fff; font-size: 12px; height: 35px; width: 100px;  border-radius: 10px; padding: 5px; border: none; cursor: pointer;" type="submit" name="training_up" value="Upload"> -->
							<input style="display: none;" type="file" id="training_up" name="import_excel" value="Upload">
							<button type="submit" id="btnfile" style="background-color: #009ef7; color: #fff; font-size: 12px; height: 35px; width: 100px; border-radius: 10px; padding: 5px; border: none; cursor: pointer; display: inline-flex; justify-content: space-evenly; text-align: center;" >
								<p style="margin-top: auto; margin-bottom: auto; font-family: 'Poppins', sans-serif;">Upload</p>

								<svg style="margin-top: auto; margin-bottom: auto;" width="25" height="25" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"> <path opacity="0.9" d="M10.2084 32.6667C6.73754 32.6667 4.08337 30.0125 4.08337 26.5417C4.08337 23.0709 6.73754 20.4167 10.2084 20.4167H10.4125C10.2084 19.8042 10.2084 18.9875 10.2084 18.375C10.2084 12.6584 14.7 8.16669 20.4167 8.16669C24.2959 8.16669 27.5625 10.2084 29.1959 13.2709C30.2167 12.6584 31.4417 12.25 32.6667 12.25C36.1375 12.25 38.7917 14.9042 38.7917 18.375C38.7917 19.1917 38.5875 19.8042 38.3834 20.4167C38.5875 20.4167 38.5875 20.4167 38.7917 20.4167C42.2625 20.4167 44.9167 23.0709 44.9167 26.5417C44.9167 30.0125 42.2625 32.6667 38.7917 32.6667H10.2084ZM16.3334 27.7667H32.6667L25.9292 21.0292C25.1125 20.2125 23.8875 20.2125 23.0709 21.0292L16.3334 27.7667Z" fill="white"/> <path d="M22.4584 27.7667V38.7917C22.4584 40.0167 23.275 40.8333 24.5 40.8333C25.725 40.8333 26.5417 40.0167 26.5417 38.7917V27.7667H22.4584Z" fill="white"/> </svg>
							</button>
						</form>
						<script type="text/javascript">
							/*document.getElementById("btnfile").click(function () {
							    document.getElementById("training_up").click();
							});*/
						</script>
					</div>
				</div>
				<div style="margin-top: auto; margin-bottom: 20px;">
					<p style="font-size: 12px; font-weight: 500;">Naive Bayes - Evaluasi Pegawai</p>
					<div style="font-size: 12px; font-weight: 500; display: flex; justify-content: space-evenly; ">
						<p>Muhammad Iman K</p>
						<p>Arya Dila Citra P</p>
						<p>Deyan Priatama A</p>
						<p>Tri Bagus Handika</p>
						<p>Mohamad Iksan B</p>
					</div>
				</div>
			</div>
			<span id="tbl_dt_train" style="display:block;"></span><br><br>
			
				<!-- <div style="border:1px solid black;width: fit-content;padding: 15px;margin: auto;border-radius: 10px;">
	                <h3 style="font-family: 'Poppins', sans-serif;font-weight: 500;">Jumlah dan probabilitas kelas pada data training</h3>
	                
	                <p style="text-align:left;">n(Ci) = n(PROMOSI) = 15 Kali<br>n(Ci) = n(MUTASI) = 12 Kali<br>n(Ci) = n(PHK) = 6 Kali<br>n(C) = n(RecordKelas) = 33 Kali<br><br>
	                Maka, P(Ci) = n(Ci) / n(C)
	            	</p>
	            	<b><h3 style="text-align:left;">P(PROMOSI) = n(PROMOSI) / n(RecordKelas) = 15/33</h3>
	            	<h3 style="text-align:left;">P(MUTASI) = n(MUTASI) / n(RecordKelas) = 12/33</h3>
	            	<h3 style="text-align:left;">P(PHK) = n(PHK) / n(RecordKelas) = 6/33</h3>
	            	</b>


	            </div> -->
		</main>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script type="text/javascript">
		/*function change(){
				if( document.getElementById('training_up').files.length === 0 ){
		    		console.log('no files selected');
				} else {
					$('#trainUp').val(document.getElementById('training_up').files.item(0).name);
				}
			}*/
		$(document).ready(function(){

		  $('#import_excel_form').on('submit', function(event){
		    event.preventDefault();
		    $.ajax({
		      url:"import.php",
		      method:"POST",
		      data:new FormData(this),
		      contentType:false,
		      cache:false,
		      processData:false,
		      beforeSend:function(){
		        $('#import').attr('disabled', 'disabled');
		        $('#import').val('Importing...');
		      },
		      success:function(data)
		      {
		        $('#tbl_dt_train').html(data);
		        $('#import_excel_form')[0].reset();
		        $('#import').attr('disabled', false);
		        $('#import').val('Import');
		      }
		    })
		  });
		});
			
		
	</script>
</html>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<link rel="icon" type="image/png" href="assets/images/thom.png" />
    	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="Content-Type" content="Text/html; charset=utf-8" />
			<title>Naive Bayes</title>
			<meta name="title" content="Naive Bayes" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap" rel="stylesheet">
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
			<div id="awal" style="display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;">
				<div style="margin: auto;"></div>
				<div style="margin: auto;">
					<h3 style="font-family: 'Poppins', sans-serif;font-weight: 500;">NAIVE BAYES CLASSIFIER</h3><br>
					<div>
						<form method="post" id="import_excel_dtr" enctype="multipart/form-data" >
							<input id="trainUp" style="background-color: #f5f8fa; color: #000; border-radius: 10px; padding: 5px 20px;border: none;outline: none; width: 400px; height: 25px; font-size: 12px; cursor: pointer; font-family: 'Poppins', sans-serif;" type="text" name="training_path" placeholder="Pilih Data Training" onclick="document.getElementById('training_up').click();"  readonly>
							<input style="display: none;" type="file" id="training_up" name="import_excel_dtr" value="Upload" onchange="changeTr();"><br><br>
							<input id="testUp" style="background-color: #f5f8fa; color: #000; border-radius: 10px; padding: 5px 20px;border: none;outline: none; width: 400px; height: 25px; font-size: 12px; cursor: pointer; font-family: 'Poppins', sans-serif;" type="text" name="testing_path" placeholder="Pilih Data Testing" onclick="document.getElementById('testing_up').click();"  readonly>
							<input style="display: none;" type="file" id="testing_up" name="import_excel_dts" value="Upload" onchange="changeTs();"><br><br>
							<button type="submit" id="btnfile" style="background-color: #009ef7; color: #fff; font-size: 12px; height: 35px; width: 100px; border-radius: 10px; padding: 5px; border: none; cursor: pointer; display: inline-flex; justify-content: space-evenly; text-align: center;" >
								<p style="margin-top: auto; margin-bottom: auto; font-family: 'Poppins', sans-serif;">Upload</p>
								<svg style="margin-top: auto; margin-bottom: auto;" width="25" height="25" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"> <path opacity="0.9" d="M10.2084 32.6667C6.73754 32.6667 4.08337 30.0125 4.08337 26.5417C4.08337 23.0709 6.73754 20.4167 10.2084 20.4167H10.4125C10.2084 19.8042 10.2084 18.9875 10.2084 18.375C10.2084 12.6584 14.7 8.16669 20.4167 8.16669C24.2959 8.16669 27.5625 10.2084 29.1959 13.2709C30.2167 12.6584 31.4417 12.25 32.6667 12.25C36.1375 12.25 38.7917 14.9042 38.7917 18.375C38.7917 19.1917 38.5875 19.8042 38.3834 20.4167C38.5875 20.4167 38.5875 20.4167 38.7917 20.4167C42.2625 20.4167 44.9167 23.0709 44.9167 26.5417C44.9167 30.0125 42.2625 32.6667 38.7917 32.6667H10.2084ZM16.3334 27.7667H32.6667L25.9292 21.0292C25.1125 20.2125 23.8875 20.2125 23.0709 21.0292L16.3334 27.7667Z" fill="white"/> <path d="M22.4584 27.7667V38.7917C22.4584 40.0167 23.275 40.8333 24.5 40.8333C25.725 40.8333 26.5417 40.0167 26.5417 38.7917V27.7667H22.4584Z" fill="white"/> </svg>
							</button>
						</form>
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
			<span id="tbl_dt_train" style="display: none;"></span>
			<span id="tbl_dt_test" style="display: none;"></span>
		</main>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script type="text/javascript">
		function changeTr(){
				if( document.getElementById('training_up').files.length !== 0 ){
		    		$('#trainUp').val(document.getElementById('training_up').files.item(0).name);
				}
			}
		function changeTs(){
				if( document.getElementById('testing_up').files.length !== 0 ){
		    		$('#testUp').val(document.getElementById('testing_up').files.item(0).name);
				}
			}
		$(document).ready(function(){

		  $('#import_excel_dtr').on('submit', function(event){
		    event.preventDefault();
		    $.ajax({
		      url:"import.php",
		      method:"POST",
		      data:new FormData(this),
		      contentType:false,
		      cache:false,
		      processData:false,
		      beforeSend:function(){
		      },
		      success:function(data)
		      {
		      	$('#tbl_dt_train').css("display", "block");
		        $('#tbl_dt_train').html(data);
		        $('#akhir').css("display", "flex");
		        $("html, body").animate({scrollTop: $('#awal').height()+50},1000);
		      }
		    })
		  });
		});
			
		
	</script>
</html>
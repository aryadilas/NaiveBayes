<?php


//include library phpspreadsheet
include 'vendor/autoload.php';

//kondisi jika user memasukan data training dan data testing
if(isset($_FILES["import_excel_dtr"]["name"]) != '' && isset($_FILES["import_excel_dts"]["name"]) != '') {
    //ekstensi yang diperbolehkan
    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["import_excel_dtr"]["name"]);
    $file_extension = end($file_array);

    if(in_array($file_extension, $allowed_extension)) {
        $file_name = time() . '.' . $file_extension;
        move_uploaded_file($_FILES['import_excel_dtr']['tmp_name'], $file_name);
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $spreadsheet = $reader->load($file_name);
        unlink($file_name);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $i = 0;
        $attrTitle = array();
        $kelas = array();
        //loop untuk setiap data dalam file data training
        foreach($data as $row) {
            if ($i == 0) {
                echo "<div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Data Training</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                    //looping kolom - kolom di baris pertama (heading tabel)
                                    for ($x=0; $x < count($row); $x++) { 
                                        echo "<th>".$row[$x]."</th>";
                                        $attrTitle[$x] = $row[$x];//variabel untuk menyimpan heading tabel
                                    }
                            echo "</tr>";
            } else {
                if (($i % 2) == 0) {
                    //jika baris genap, background akan menjadi abu - abu
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";
                } else {
                    //jika baris ganjil, background warna default (putih)
                    echo "<tr style=\"height: 35px;\">";
                }
                //Kolom No Baris
                echo "<td>".$i."</td>";
                //looping kolom kolom di baris selain baris data
                for ($y=0; $y < count($row); $y++) {
                    //menampilkan setiap kolom 
                    //pada kasus pegawai adalah
                    //| 1 | ALI TOPAN | JUNIOR | MUDA | BAIK | SANGAT BAIK | PROMOSI |
                    echo "<td>".$row[$y]."</td>";
                    if ($y == count($row)-1) {
                        //variabel array untuk menyimpan KELAS pada kolom terakhir (Hasil Evaluasi)
                        $kelas[count($kelas)] = $row[$y];
                    } else {
                        //variabel untuk memberikan penomoran atribut
                        $nmAttr = $y;
                        if (empty(${"attr$nmAttr"})) {
                            //jika variabel $attr dengan nomor $nmAttr kosong, maka akan mendeklarasikan
                            //variabel baru bernama $attr dengan nomor $nmAttr bertipe array
                            //contoh $attr1,$attr2,$attr3, dst
                            //variabel ini digunakan untuk menyimpan data pada setiap kolom atribut
                            ${"attr$nmAttr"} = array();    
                        }
                        //jika variabel telah di deklarasikan, maka simpan data pada kolom atribut
                        ${"attr$nmAttr"}[count(${"attr$nmAttr"})] = $row[$y];
                    }
                }
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";
        echo "<div style=\"border:1px solid black;width: fit-content;padding: 20px;margin: auto;\">
                <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Jumlah dan probabilitas kelas pada data training</h3> 
                <p style=\"text-align:left;\">";
                    //looping data apa saja yang ada di variabel kelas
                    //pada kasus pegawai berarti loop 3 kali 
                    //dengan macam kelas adalah PROMOSI,MUTASI,PHK
                    for($a=0;$a<count(array_unique($kelas));$a++){
                        //menampilkan n(Ci) atau jumlah kemunculan pada setiap kelas
                        //contoh pada kasus pegawai n(Ci) = n(PROMOSI) = 15 Kali
                        echo "n(Ci) = n(".array_values(array_unique($kelas))[$a].") = ".array_count_values($kelas)[array_values(array_unique($kelas))[$a]]." Kali<br>";
                    }
                    //menampilkan jumlah baris data pada data training
                    echo "n(C) = n(RecordKelas) = ".(count($data)-1)." Kali
                </p>
                <b>";
                //looping data apa saja yang ada di variabel kelas
                for($a=0;$a<count(array_unique($kelas));$a++){
                    //menampilkan probabilitas setiap kelas
                    //contoh pada kasus pegawai P(PROMOSI) = n(PROMOSI) / n(RecordKelas) = 15/33
                    echo "<h3 style=\"text-align:left;\">P(".array_values(array_unique($kelas))[$a].") = n(".array_values(array_unique($kelas))[$a].") / n(RecordKelas) = ".array_count_values($kelas)[array_values(array_unique($kelas))[$a]]."/".(count($data)-1)."</h3>";
                }
            echo "</b>    
            </div><br>
        ";
        echo "<div style=\"display: flex;justify-content: space-evenly;flex-direction: column;min-height: 100%;text-align:center;\">";
        //looping setiap heading table
        //$attrTitle = Atribut Title / Judul Atribut
        for ($a=0; $a < count($attrTitle); $a++) { 
            //kolom yang dianggap atribut adalah selain kolom 1 dan kolom terakhir
            //kolom 1 dianggap sebagai identitas, pada kasus pegawai adalah nama pegawai
            //kolom terakhir dianggap sebagai kelas
            //sehingga loop 6 kali,namun hanya 4 kali loop yang masuk kondisi ini
            //contoh pada kasus pegawai MASA KERJA,USIA,NILAI PELATIHAN,NILAI KERJA
            if ($a !== 0 && $a !== count($attrTitle)-1) {
                if ($a & 1) {
                    //untuk loop pertama akan menampilkan tag div untuk menaungi 2 buah tabel
                    echo "<div style=\"display: flex;justify-content: space-evenly;padding: 10px 0px;\">";    
                }
                echo "<style type=\"text/css\">.tbProbAC, .tbProbAC td, .tbProbAC th {border: 1px solid black;font-size: 12px;font-weight: 500;padding: 0px 30px;}</style>
                        <table class=\"tbProbAC\" style=\"border-collapse: collapse;max-width: 50%;\">
                            <tr><th>".$attrTitle[$a]."</th><th colspan=\"3\">KEMUNCULAN</th></tr>
                            <tr><th>P(Ai|Ci)</th><th>n(Ai)</th><th>n(Ai)/n(Ci)</th></tr>";
                            //looping data apa saja yang ada di ${"attr$a"}
                            //$at untuk penomoran looping Atribut
                            //contoh pada kasus pegawai 
                            //$attr1 = JUNIOR,SUPERVISOR,MANAGER
                            //$attr2 = MUDA,PARUBAYA,TUA
                            for ($at=0; $at < count(array_unique(${"attr$a"})); $at++) {
                                //looping data apa saja di variabel kelas
                                for ($kl=0; $kl < count(array_unique($kelas)); $kl++) {
                                    //variabel untuk menghitung kemunculan
                                    $muncul = 0;
                                    //looping seluruh data di ${"attr$a"} 
                                    //$co = Count / hitung
                                    //pada kasus pegawai akan di looping sebanyak 33 kali
                                    for ($co=0; $co < count(${"attr$a"}); $co++) { 
                                        //jika atribut pada value looping co sama dengan value lopping at
                                        //sebagai contoh 
                                        //Looping 1 JUNIOR = JUNIOR
                                        //Looping 2 SUPERVISOR = JUNIOR
                                        if (${"attr$a"}[$co] == array_values(array_unique(${"attr$a"}))[$at]) {
                                            //jika value kelas ke sekian sama dengan value looping kl
                                            //jika atribut JUNIOR dan kelas PROMOSI maka akan menambah kemunculan
                                            if ($kelas[$co] == array_values(array_unique($kelas))[$kl]) {
                                                $muncul++;
                                            }
                                        }
                                    }
                                    echo "<tr><td style='text-align:left;'>".array_values(array_unique(${"attr$a"}))[$at]." | ".array_values(array_unique($kelas))[$kl]."</td><td>".$muncul."</td><td>".$muncul."/".array_count_values($kelas)[array_values(array_unique($kelas))[$kl]]."</td></tr>";
                                    //variabel berisi judul atribut
                                    $atn = str_replace([' ','(',')'], '', $attrTitle[$a]);
                                    //variabel berisi atribut
                                    $ai = str_replace([' ','(',')'], '', array_values(array_unique(${"attr$a"}))[$at]);
                                    //variabel berisi kelas
                                    $ci = str_replace([' ','(',')'], '', array_values(array_unique($kelas))[$kl]);
                                    //membuat variabel untuk setiap probabilitas atribut terhadap kelas P(Ai|Ci)
                                    //contoh pada kasus pegawai
                                    //MASAKERJAdlmtahunJUNIORPROMOSI,MASAKERJAdlmtahunJUNIORMUTASI,
                                    //USIAdlmtahunMUDAPROMOSI,USIAdlmtahunMUDAMUTASI,
                                    //total ada 36 variabel
                                    ${"p$atn$ai$ci"} = $muncul."/".array_count_values($kelas)[array_values(array_unique($kelas))[$kl]];
                                }
                            }
                        echo "</table>";
                if (~$a & 1) {
                       echo "</div>";
                }
            }
        }
        echo "</div>";
        
    }





// ------------------------------ Perhitungan Data Training





    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["import_excel_dts"]["name"]);
    $file_extension = end($file_array);
    $laplace = false;
    if(in_array($file_extension, $allowed_extension)) {
        $file_name = time() . '.' . $file_extension;
        move_uploaded_file($_FILES["import_excel_dts"]['tmp_name'], $file_name);
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $spreadsheet = $reader->load($file_name);
        unlink($file_name);
        $dataT = $spreadsheet->getActiveSheet()->toArray();
        $i = 0;
        $frst_col = array();

        foreach($dataT as $row) {
            if ($i == 0) {
                echo "<br><br><div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Data Testing</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                    for ($x=0; $x < count($row); $x++) { 
                                        echo "<th>".$row[$x]."</th>";
                                        $attrTitle[$x] = $row[$x];
                                    }
                            echo "</tr>";
            } else {
                if (($i % 2) == 0) {
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";    
                } else {
                    echo "<tr style=\"height: 35px;\">";
                }
                echo "<td>".$i."</td>";
                for ($y=0; $y < count($row); $y++) { 
                    echo "<td>".$row[$y]."</td>";
                    if ($y == 0) {
                        $frst_col[count($frst_col)] = $row[$y];
                    }

                    
                    if ($y == count($row)-1) {
                        //$kelas[count($kelas)] = $row[$y];
                    } else {
                        $nmAttr = $y;
                        if (empty(${"attrTr$nmAttr"})) {
                            ${"attrTr$nmAttr"} = array();    
                        }
                        ${"attrTr$nmAttr"}[count(${"attrTr$nmAttr"})] = $row[$y];    
                    }
                }
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";
        //var_dump($attrKl1);
        //session_start();
        //var_dump($pMASAKERJAdlmtahunSUPERVISORPROMOSI);
        echo "
        <div style=\"width: fit-content;padding: 15px;margin: auto;border-radius: 10px;\">
                <style type=\"text/css\">.calcTesting, .calcTesting td, .calcTesting th {border: 1px solid black;font-size: 12px;font-weight: 500;padding: 0px 20px;}</style>
                <table class=\"calcTesting\" style=\"border: 1px solid black;border-collapse: collapse;min-width: 20%;\">";
                for ($i=0; $i < count($dataT)-1; $i++) { 
                    echo "<tr>
                        <td rowspan='".count(array_unique($kelas))."'>".$frst_col[$i]."</td>
                        <td style='text-align:left;'> P (";
                        $val = 0;
                            for ($x=0; $x < count($attrTitle); $x++) { 
                                if ($x !== 0 && $x !== count($attrTitle)-1) {
                                    if ($x == count($attrTitle)-2) {
                                        echo ${"attrTr$x"}[$i]." ";    
                                    } else {
                                        echo ${"attrTr$x"}[$i].", ";    
                                    }
                                }
                            }
                        echo " | ".array_values(array_unique($kelas))[0].") = </td>";
                        //${${"attrTr2"}[$i]}
                        echo "<td style='text-align:left;'>".array_count_values($kelas)[array_values(array_unique($kelas))[0]]."/".(count($data)-1)." * ";
                        $pa = intval(array_count_values($kelas)[array_values(array_unique($kelas))[0]])/intval((count($data)-1));
                        for ($x=0; $x < count($attrTitle); $x++) { 
                            if ($x !== 0 && $x !== count($attrTitle)-1) {
                                if ($x == count($attrTitle)-2) {
                                    $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[0]);


                                    for ($q = 0;$q < strlen(${"p$res"});$q++){
                                        if(substr(${"p$res"},$q,1) == '/'){
                                            $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                        }
                                    }

                                    //echo $pa;
                                    echo ${"p$res"};
                                } else {
                                    $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[0]);

                                    for ($q = 0;$q < strlen(${"p$res"});$q++){
                                        if(substr(${"p$res"},$q,1) == '/'){
                                            $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                        }
                                    }
                                    //echo $pa;
                                    echo ${"p$res"}." * ";
                                }
                            }
                        }
                        $pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[0]);
                        ${"p$pvar"} = $pa;
                        echo "</td>";
                    
                    echo "</tr>";
                    for ($a=1; $a < count(array_unique($kelas)); $a++) { 
                        $pa = intval(array_count_values($kelas)[array_values(array_unique($kelas))[$a]])/intval((count($data)-1));
                        echo "<tr>
                            <td style='text-align:left;'> P (";
                            for ($x=0; $x < count($attrTitle); $x++) { 
                                if ($x !== 0 && $x !== count($attrTitle)-1) {
                                    if ($x == count($attrTitle)-2) {
                                        echo ${"attrTr$x"}[$i]." ";    
                                    } else {
                                        echo ${"attrTr$x"}[$i].", ";    
                                    }

                                }
                            }
                        echo " | ".array_values(array_unique($kelas))[$a].") = </td>
                            <td style='text-align:left;'>".array_count_values($kelas)[array_values(array_unique($kelas))[$a]]."/".(count($data)-1)." * ";
                        for ($x=0; $x < count($attrTitle); $x++) { 
                            if ($x !== 0 && $x !== count($attrTitle)-1) {
                                if ($x == count($attrTitle)-2) {
                                    $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[$a]);
                                    for ($q = 0;$q < strlen(${"p$res"});$q++){
                                        if(substr(${"p$res"},$q,1) == '/'){
                                            $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                        }
                                    }
                                    echo ${"p$res"};
                                } else {
                                    $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[$a]);
                                    for ($q = 0;$q < strlen(${"p$res"});$q++){
                                        if(substr(${"p$res"},$q,1) == '/'){
                                            $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                        }
                                    }
                                    echo ${"p$res"}." * ";
                                }
                            }
                        }
                        $pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[$a]);
                        ${"p$pvar"} = $pa;
                        echo "</td>
                        </tr>";
                    }
                }
                
        echo "</table></div><br><br>
        ";



        /*$i = 0;
        foreach($dataT as $row) {
            if ($i == 0) {
                echo "<br><br><div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Data Testing</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                    for ($x=0; $x < count($row)-1; $x++) { 
                                        echo "<th>".$row[$x]."</th>";
                                        $attrTitle[$x] = $row[$x];
                                    }
                                    for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                                        echo "<th>P|".array_values(array_unique($kelas))[$x]."</th>";
                                    }
                            echo "</tr>";
            } else {
                if (($i % 2) == 0) {
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";    
                } else {
                    echo "<tr style=\"height: 35px;\">";
                }
                echo "<td>".$i."</td>";
                for ($y=0; $y < count($row)-1; $y++) { 
                    echo "<td>".$row[$y]."</td>";
                    
                }
                for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                        $v = $row[0].array_values(array_unique($kelas))[$x];
                        echo "<td>".round(${"p$v"},9)."</td>";
                        //echo "<td>".$pBUDIANOPROMOSI."</td>";
                    }
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";
*/












        $i = 0;
        foreach($dataT as $row) {
            if ($i == 0) {
                echo "<br><br><div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Hasil Perhitungan</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                    for ($x=0; $x < count($row)-1; $x++) { 
                                        echo "<th>".$row[$x]."</th>";
                                        $attrTitle[$x] = $row[$x];
                                    }
                                    for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                                        echo "<th>P|".array_values(array_unique($kelas))[$x]."</th>";
                                    }
                            echo "<th>".$row[count($row)-1]."</th>";
                            echo "</tr>";

            } else {
                if (($i % 2) == 0) {
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";    
                } else {
                    echo "<tr style=\"height: 35px;\">";
                }
                echo "<td>".$i."</td>";
                for ($y=0; $y < count($row)-1; $y++) { 
                    echo "<td>".$row[$y]."</td>";
                    
                }
                $hasil = 0;
                $max = 0;
                for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                        $v = str_replace([' ','(',')'], '',$row[0].array_values(array_unique($kelas))[$x]);
                        echo "<td>".round(${"p$v"},9)."</td>";
                        $max = max($max,round(${"p$v"},9));
                        if ($max == round(${"p$v"},9)) {
                            $hasil = array_values(array_unique($kelas))[$x];
                        }
                        //$hasil = ;
                        //echo "<td>".$pBUDIANOPROMOSI."</td>";
                }
                echo "<td>".$hasil."</td>";
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";













        

        for ($i=0; $i < count($dataT)-1; $i++) { 
            $val = 0;
            $pa = intval(array_count_values($kelas)[array_values(array_unique($kelas))[0]])/intval((count($data)-1));
            for ($x=0; $x < count($attrTitle); $x++) { 
                if ($x !== 0 && $x !== count($attrTitle)-1) {
                    if ($x == count($attrTitle)-2) {
                        $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[0]);
                        for ($q = 0;$q < strlen(${"p$res"});$q++){
                            if(substr(${"p$res"},$q,1) == '/'){
                                $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                            }
                        }
                    } else {
                        $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[0]);

                        for ($q = 0;$q < strlen(${"p$res"});$q++){
                            if(substr(${"p$res"},$q,1) == '/'){
                                $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                            }
                        }
                    }
                }   
            }
            $pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[0]);
            ${"p$pvar"} = $pa;

            //echo $pvar." ";
            //echo count(array_unique($kelas));
            for ($a=1; $a < count(array_unique($kelas)); $a++) { 

                $pa = intval(array_count_values($kelas)[array_values(array_unique($kelas))[$a]])/intval((count($data)-1));
                //echo array_values(array_unique($kelas))[$a]." ";

                for ($x=0; $x < count($attrTitle); $x++) { 
                    if ($x !== 0 && $x !== count($attrTitle)-1) {
                        if ($x == count($attrTitle)-2) {
                            $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[$a]);
                            for ($q = 0;$q < strlen(${"p$res"});$q++){
                                if(substr(${"p$res"},$q,1) == '/'){
                                    $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                }
                            }
                            //echo ${"p$res"};
                        } else {
                            $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[$a]);
                            for ($q = 0;$q < strlen(${"p$res"});$q++){
                                if(substr(${"p$res"},$q,1) == '/'){
                                    $pa = $pa * (intval(substr(${"p$res"},0,$q))/intval(substr(${"p$res"},$q+1,strlen(${"p$res"}))));
                                }
                            }
                            //echo ${"p$res"}." * ";
                        }
                    }
                }
                $pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[$a]);
                ${"p$pvar"} = $pa;
                
                
                if (${"p$pvar"} == 0) {
                    //echo $pRIANTOPHK;
                    echo "<p>Pada kasus <b>".$frst_col[$i]."</b>, dikarenakan ada nilai 0 sehingga menyebabkan ketidakakuratan perhitungan maka perlu diantisipasi dengan Metode Laplacian Correction</p><br>";
                    echo "<div style=\"width: fit-content;padding: 15px;margin: auto;border-radius: 10px;\">
                        <style type=\"text/css\">.calcTesting, .calcTesting td, .calcTesting th {border: 1px solid black;font-size: 12px;font-weight: 500;padding: 0px 20px;}</style>
                        <table class=\"calcTesting\" style=\"border: 1px solid black;border-collapse: collapse;min-width: 20%;\">
                    ";
                    echo "<tr>
                        <td></td>
                        <td>Sebelum</td>
                        <td>Sesudah</td>
                    </tr>";

                    $newRes = "";
                    $pa = intval(array_count_values($kelas)[array_values(array_unique($kelas))[$a]])/intval((count($data)-1));
                    //echo $pa." ";
                    for ($x=0; $x < count($attrTitle); $x++) { 
                        //echo ${"p$res"}." ";
                        if ($x !== 0 && $x !== count($attrTitle)-1) {
                            $res = str_replace([' ','(',')'], '', $attrTitle[$x].${"attrTr$x"}[$i].array_values(array_unique($kelas))[$a]);
                            //echo $res." ";
                            echo "<tr>
                                <td>".${"attrTr$x"}[$i]." | ".array_values(array_unique($kelas))[$a]."</td>
                                <td>".${"p$res"}."</td>";
                                for ($q = 0;$q < strlen(${"p$res"});$q++){
                                    if(substr(${"p$res"},$q,1) == '/'){
                                        
                                        ${"n$res"} = strval(intval(substr(${"p$res"}, 0, $q))+1)."/".strval(intval(substr(${"p$res"}, $q+1, strlen(${"p$res"}))) + (count($attrTitle)-2));
                                        //${"p$res"} = strval(intval(substr(${"p$res"}, 0, $q)))."/".strval(intval(substr(${"p$res"}, $q, strlen(${"p$res"}))));

                                        

                                    }
                                }
                                for ($q = 0;$q < strlen(${"n$res"});$q++){
                                    if(substr(${"n$res"},$q,1) == '/'){
                                        //$pa = $pa * (intval(substr(${"p$res"}, 0, $q))+1)/(intval(substr(${"p$res"}, $q+1, strlen(${"p$res"}))) + (count($attrTitle)-2));
                                        $pa = $pa * (intval(substr(${"n$res"},0,$q))/intval(substr(${"n$res"},$q+1,strlen(${"n$res"}))));
                                    }
                                }
                                //echo ${"n$res"}." ";
                            //echo ${"p$res"}." ";
                            //${"p$pvar"} = $pa;
                            echo "<td>".${"n$res"}."</td>
                            </tr>";
                            $newRes = $newRes." * ".${"n$res"};
                        }
                    }

                    //$pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[$a]);
                    //echo $pvar;
                    //${"p$pvar"} = $pa;

                    echo "</table></div><br>";
                    echo "<p>Sehingga perhitungannya menjadi ";
                    echo array_count_values($kelas)[array_values(array_unique($kelas))[$a]]."/".(count($data)-1).$newRes;
                    echo " = ".$pa."</p>";
                    $pvar = str_replace([' ','(',')'], '', $frst_col[$i].array_values(array_unique($kelas))[$a]);
                    ${"p$pvar"} = $pa;
                }
                //echo $pa."<br>";
                //echo 1++;
            }
        }
                
        //echo $pRIANTOPHK;









































        $i = 0;
        foreach($dataT as $row) {
            if ($i == 0) {
                echo "<br><br><div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Hasil Perhitungan</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                    for ($x=0; $x < count($row)-1; $x++) { 
                                        echo "<th>".$row[$x]."</th>";
                                        $attrTitle[$x] = $row[$x];
                                    }
                                    for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                                        echo "<th>P|".array_values(array_unique($kelas))[$x]."</th>";
                                    }
                            echo "<th>".$row[count($row)-1]."</th>";
                            echo "</tr>";

            } else {
                if (($i % 2) == 0) {
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";    
                } else {
                    echo "<tr style=\"height: 35px;\">";
                }
                echo "<td>".$i."</td>";
                for ($y=0; $y < count($row)-1; $y++) { 
                    echo "<td>".$row[$y]."</td>";
                    
                }
                $hasil = 0;
                $max = 0;
                for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                        $v = str_replace([' ','(',')'], '',$row[0].array_values(array_unique($kelas))[$x]);
                        echo "<td>".round(${"p$v"},9)."</td>";
                        $max = max($max,round(${"p$v"},9));
                        if ($max == round(${"p$v"},9)) {
                            $hasil = array_values(array_unique($kelas))[$x];
                        }
                        //$hasil = ;
                        //echo "<td>".$pBUDIANOPROMOSI."</td>";
                }
                echo "<td>".$hasil."</td>";
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";













        $i = 0;
        foreach($dataT as $row) {
            if ($i == 0) {
                echo "<br><br><div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 40%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Hasil Akhir</h3>
                            <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                                <tr style=\"height: 35px; \">
                                    <th>NO</th>";
                                echo "<th>".$row[0]."</th>";
                                echo "<th>".$row[count($row)-1]."</th>";
                            echo "</tr>";

            } else {
                if (($i % 2) == 0) {
                    echo "<tr style=\"height: 35px;background-color: #f5f8fa;\">";    
                } else {
                    echo "<tr style=\"height: 35px;\">";
                }
                echo "<td>".$i."</td>";
                echo "<td>".$row[0]."</td>";
                $hasil = 0;
                $max = 0;
                for ($x=0; $x < count(array_unique($kelas)); $x++) { 
                    $v = str_replace([' ','(',')'], '',$row[0].array_values(array_unique($kelas))[$x]);
                    $max = max($max,round(${"p$v"},9));
                    if ($max == round(${"p$v"},9)) {
                        $hasil = array_values(array_unique($kelas))[$x];
                    }
                }
                echo "<td>".$hasil."</td>";
                echo "</tr>";
            }
            $i++;
        }
        echo "</table>
            </div>
        </div><br>";























    }
















}

/*if(isset($_FILES["import_excel_dts"]["name"]) != '') {
    
}

function hitung($file){
    
}*/





?>
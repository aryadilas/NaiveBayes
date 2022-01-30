<?php

//import.php

include 'vendor/autoload.php';

//$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");


if(isset($_FILES["import_excel_dtr"]["name"]) != '') {
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
        foreach($data as $row) {
            if ($i == 0) {
                echo "<div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                        <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                            <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Data Training</h3>
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
                    
                    if ($y == count($row)-1) {
                        $kelas[count($kelas)] = $row[$y];
                    } else {
                        $nmAttr = $y;
                        if (empty(${"attr$nmAttr"})) {
                            ${"attr$nmAttr"} = array();    
                        }
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
        echo "
            <div style=\"border:1px solid black;width: fit-content;padding: 20px;margin: auto;\">
                <h3 style=\"font-family: 'Poppins', sans-serif;font-weight: 500;\">Jumlah dan probabilitas kelas pada data training</h3>
                
                <p style=\"text-align:left;\">";
                    for($a=0;$a<count(array_unique($kelas));$a++){
                        echo "n(Ci) = n(".array_values(array_unique($kelas))[$a].") = ".array_count_values($kelas)[array_values(array_unique($kelas))[$a]]." Kali<br>";
                    }
                    echo "n(C) = n(RecordKelas) = ".(count($data)-1)." Kali
                </p>
                <b>";
                for($a=0;$a<count(array_unique($kelas));$a++){
                    echo "<h3 style=\"text-align:left;\">P(".array_values(array_unique($kelas))[$a].") = n(".array_values(array_unique($kelas))[$a].") / n(RecordKelas) = ".array_count_values($kelas)[array_values(array_unique($kelas))[$a]]."/".(count($data)-1)."</h3>";
                }
            echo "</b>    
            </div><br>
        ";
        echo "<div style=\"display: flex;justify-content: space-evenly;flex-direction: column;min-height: 100%;text-align:center;\">";
        for ($a=0; $a < count($attrTitle); $a++) { 
            if ($a !== 0 && $a !== count($attrTitle)-1) {
                if ($a & 1) {
                    echo "<div style=\"display: flex;justify-content: space-evenly;padding: 10px 0px;\">";    
                }
                echo "<style type=\"text/css\">.tbProbAC, .tbProbAC td, .tbProbAC th {border: 1px solid black;font-size: 12px;font-weight: 500;padding: 0px 30px;}</style>
                        <table class=\"tbProbAC\" style=\"border-collapse: collapse;max-width: 50%;\">
                            <tr><th>".$attrTitle[$a]."</th><th colspan=\"3\">KEMUNCULAN</th></tr>
                            <tr><th>P(Ai|Ci)</th><th>n(Ai)</th><th>n(Ai)/n(Ci)</th></tr>";
                            for ($at=0; $at < count(array_unique(${"attr$a"})); $at++) { 
                                for ($kl=0; $kl < count(array_unique($kelas)); $kl++) {
                                    $muncul = 0;
                                    for ($co=0; $co < count(${"attr$a"}); $co++) { 
                                        if (${"attr$a"}[$co] == array_values(array_unique(${"attr$a"}))[$at]) {
                                            if ($kelas[$co] == array_values(array_unique($kelas))[$kl]) {
                                                $muncul++;   
                                            }
                                        }
                                    }
                                    echo "<tr><td style='text-align:left;'>".array_values(array_unique(${"attr$a"}))[$at]." | ".array_values(array_unique($kelas))[$kl]."</td><td>".$muncul."</td><td>".$muncul."/".array_count_values($kelas)[array_values(array_unique($kelas))[$kl]]."</td></tr>";
                                    $atn = str_replace([' ','(',')'], '', $attrTitle[$a]);
                                    $ai = str_replace([' ','(',')'], '', array_values(array_unique(${"attr$a"}))[$at]);
                                    $ci = str_replace([' ','(',')'], '', array_values(array_unique($kelas))[$kl]);
                                    //echo $ai."<br>"; 
                                    ${"p$atn$ai$ci"} = $muncul."/".array_count_values($kelas)[array_values(array_unique($kelas))[$kl]];
                                    //${"p$a$at$kl"} = $muncul."/".array_count_values($kelas)[array_values(array_unique($kelas))[$kl]];
                                    
                                }
                            }
                        echo "</table>";
                if ($a & 1) {
                      
                } else {
                    echo "</div>";
                }
            }
        }
        echo "</div>";

        /*echo "<div id=\"akhir\" style=\"height: 20%;justify-content: center;display: none;\">
                <form method=\"post\" id=\"import_excel_dts\" enctype=\"multipart/form-data\" style=\"margin: auto;\">
                    <input id=\"testUp\" style=\"background-color: #f5f8fa; color: #000; border-radius: 10px; padding: 5px 20px;border: none;outline: none; width: 400px; height: 25px; font-size: 12px; cursor: pointer; font-family: 'Poppins', sans-serif;\" type=\"text\" name=\"testing_path\" placeholder=\"Pilih Data Testing\" onclick=\"document.getElementById('testing_up').click();\"  readonly>
                    <!-- <input style=\"background-color: #009ef7; color: #fff; font-size: 12px; height: 35px; width: 100px;  border-radius: 10px; padding: 5px; border: none; cursor: pointer;\" type=\"submit\" name=\"training_up\" value=\"Upload\"> -->
                    <input style=\"display: none;\" type=\"file\" id=\"testing_up\" name=\"import_excel_dts\" value=\"Upload\" onchange=\"changeTs();\">
                    <button type=\"submit\" id=\"btnfile\" style=\"background-color: #009ef7; color: #fff; font-size: 12px; height: 35px; width: 100px; border-radius: 10px; padding: 5px; border: none; cursor: pointer; display: inline-flex; justify-content: space-evenly; text-align: center;\" >
                        <p style=\"margin-top: auto; margin-bottom: auto; font-family: 'Poppins', sans-serif;\">Upload</p>

                        <svg style=\"margin-top: auto; margin-bottom: auto;\" width=\"25\" height=\"25\" viewBox=\"0 0 49 49\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"> <path opacity=\"0.9\" d=\"M10.2084 32.6667C6.73754 32.6667 4.08337 30.0125 4.08337 26.5417C4.08337 23.0709 6.73754 20.4167 10.2084 20.4167H10.4125C10.2084 19.8042 10.2084 18.9875 10.2084 18.375C10.2084 12.6584 14.7 8.16669 20.4167 8.16669C24.2959 8.16669 27.5625 10.2084 29.1959 13.2709C30.2167 12.6584 31.4417 12.25 32.6667 12.25C36.1375 12.25 38.7917 14.9042 38.7917 18.375C38.7917 19.1917 38.5875 19.8042 38.3834 20.4167C38.5875 20.4167 38.5875 20.4167 38.7917 20.4167C42.2625 20.4167 44.9167 23.0709 44.9167 26.5417C44.9167 30.0125 42.2625 32.6667 38.7917 32.6667H10.2084ZM16.3334 27.7667H32.6667L25.9292 21.0292C25.1125 20.2125 23.8875 20.2125 23.0709 21.0292L16.3334 27.7667Z\" fill=\"white\"/> <path d=\"M22.4584 27.7667V38.7917C22.4584 40.0167 23.275 40.8333 24.5 40.8333C25.725 40.8333 26.5417 40.0167 26.5417 38.7917V27.7667H22.4584Z\" fill=\"white\"/> </svg>
                    </button>
                </form>
            </div>";*/
        
    }





// ------------------------------ Perhitungan Data Training





    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["import_excel_dts"]["name"]);
    $file_extension = end($file_array);

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



        $i = 0;
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

        //var_dump($attrTitle);
    }
















}

/*if(isset($_FILES["import_excel_dts"]["name"]) != '') {
    
}

function hitung($file){
    
}*/





?>
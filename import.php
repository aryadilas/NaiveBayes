<?php

//import.php

include 'vendor/autoload.php';

//$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");

if($_FILES["import_excel"]["name"] != '') {
    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["import_excel"]["name"]);
    $file_extension = end($file_array);

    if(in_array($file_extension, $allowed_extension)) {
        $file_name = time() . '.' . $file_extension;
        move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
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
    } else {
        
    }
} else {
   
}


?>
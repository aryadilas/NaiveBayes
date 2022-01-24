<?php

//import.php

include 'vendor/autoload.php';

//$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");

if($_FILES["import_excel"]["name"] != '')
{
 $allowed_extension = array('xls', 'csv', 'xlsx');
 $file_array = explode(".", $_FILES["import_excel"]["name"]);
 $file_extension = end($file_array);

 if(in_array($file_extension, $allowed_extension))
 {
  $file_name = time() . '.' . $file_extension;
  move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
  $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

  $spreadsheet = $reader->load($file_name);

  unlink($file_name);

  $data = $spreadsheet->getActiveSheet()->toArray();
  $i = 0;
  foreach($data as $row)
  {
   /*$insert_data = array(
    ':first_name'  => $row[0],
    ':last_name'  => $row[1],
    ':created_at'  => $row[2],
    ':updated_at'  => $row[3]
   );*/
   //echo $row[0]." ";
   if ($i == 0) {
       echo "<div style=\"display: flex; flex-direction: column; justify-content: space-evenly; height: 100%;\">
                
                <div style=\"width: 90%; margin: 5%;  border-radius: 15px; padding:10px;box-shadow: 0px 6px 19px -9px rgba(0,0,0,0.56);\">
                    <p style=\"margin-bottom: 50px;\">Data Training</p>
                    <table style=\"width: 100%;font-size: 12px; font-weight: 500;border-collapse: collapse;\">
                        <tr style=\"height: 35px; \">
                            <th>NO</th>";
                            for ($x=0; $x < count($row); $x++) { 
                                echo "<th>".$row[$x]."</th>";
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
        }
        echo "</tr>";
    }
    $i++;

   /*$query = "
   INSERT INTO sample_datas 
   (first_name, last_name, created_at, updated_at) 
   VALUES (:first_name, :last_name, :created_at, :updated_at)
   ";*/
   //echo "<pre>".var_dump($insert_data)."</pre>";
   //$statement = $connect->prepare($query);
   //$statement->execute($insert_data);
  }
  echo "</table>
                </div>
            </div>";
  //$message = '<div class="alert alert-success">Data Imported Successfully</div>';

 } else {
  //$message = '<div class="alert alert-danger">Only .xls .csv or .xlsx file allowed</div>';
 }
}
else
{
 //$message = '<div class="alert alert-danger">Please Select File</div>';
}

//echo $message;

?>
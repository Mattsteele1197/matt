<?php
//-----------------------PRE-REQUISITES-----------------------------
/*function libraryget(){
require("//matt/www/RedmileTest/libraries/popperjs.php");
require("//matt/www/RedmileTest/libraries/jquery.php");
require("//matt/www/RedmileTest/libraries/bootstrap.php");
require("//matt/www/RedmileTest/libraries/meekrodb.2.3.class.php");
require("//matt/www/RedmileTest/libraries/select2.php");

echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>';
} 
*/
//-----------------------GENERAL FUNCTIONS-------------------------

function dispatchAjaxMessage($message){
  $output = json_encode($message);
  $encodingErrorMsg = json_last_error_msg();
  if($encodingErrorMsg!=="No error"){
    //dolog($encodingErrorMsg, "base.php - dispatchAjaxMessage json_encode error");
    $output = json_encode(array_map("utf8_encode", $message ));
  }
  print $output;
  die;
}

function makeTable($report, $strongHighlightArray1=false, $weakHighlightArray2=false, $cashCols=false, $id=""){

  if(!(is_array($report))){
    return 'NOT AN ARRAY';
  } 
  
  if(!count($report)>0){
    return 'EMPTY ARRAY';
  }

  $firstItem = array_keys($report)[0];

  $table_headers = array_keys($report[$firstItem]);

  $table = "<table itemscope itemtype='Article' class='table table-striped table-bordered table-hover table-responsive table-condensed' id='$id'>
    <thead>
      <tr>
        ";
  
  foreach ($table_headers as $header) {
    $table.="     <th>$header</th>\n";
  }
  $table.="   </tr>
    </thead>
    <tbody>
  ";

  foreach ($report as $nr => $plotdata) {
    if($weakHighlightArray2 && in_array($nr, $weakHighlightArray2)){
      $table.="<tr class='success'>";
    } elseif ($strongHighlightArray1 && in_array($nr, $strongHighlightArray1)){
      $table.="<tr class='danger'>";
    }
    else {
      $table.="<tr class='hover'>";
    }
    foreach ($plotdata as $col=>$value) {
      if($cashCols&&in_array($col, $cashCols)){
        $val = utf8_encode(money_format('%n', $value));
        if($value < 0){
          $val = "<span style='color:#ff0500;'>$val</span>";
        }
        $table.="<td class='text-right'>$val</td>";
      } else {
        $table.="<td>$value</td>";
      }
    }
    $table.="</tr>";
  }
  $table.="</table>";
  return $table;
}

//------------------------$db->query-------------------------------

function getSubcontractors($db){
	return $db->query("SELECT * FROM Subcontractors ORDER BY SubcontractorName ASC");
}

function getDevelopments($db){
	return $db->query("SELECT idDevelopment, Name, InternalName FROM Development");
}

function getPlots($db){
    return $db->query("SELECT idDevelopment, PlotNr FROM Plot ORDER BY idDevelopment, PlotNr ASC"); 
}

function DevelopmentName($idDevelopment, $db){
return $db->query("SELECT Name FROM Development WHERE idDevelopment = $idDevelopment");
}

function getPlotsByidDevelopment($idDevelopment, $db){
    return $db->query("SELECT idPlot as id, PlotNr as `text` FROM Plot WHERE idDevelopment = %i ORDER BY idDevelopment, PlotNr ASC", $idDevelopment); 
}

function searchPlotsByidDevelopment($search, $idDevelopment, $db){
    return $db->query("SELECT idPlot as id, PlotNr as `text` FROM Plot WHERE idDevelopment = %i AND PlotNr LIKE %ss ORDER BY idDevelopment, PlotNr ASC", $idDevelopment, $search); 
}

function getOpCodes($db){
	return $db->query("SELECT * FROM OpCode ORDER BY idOpCode ASC");
}


//-----------------------VALIDATION--------------------------------

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validStrLen($str, $min, $max){
$len = strlen($str);

if($len < $min)
  return "Field Name is too short, minumum is $min characters ($max max).";

elseif ($len > $max)
  return "Field Name is too long, maximum is $max characters ($min min).";

return TRUE;
}

?>

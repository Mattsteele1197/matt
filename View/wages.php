<?php require("//matt/www/RedmileTest/db.php"); ?>
<?php require("//matt/www/RedmileTest/functions.php"); ?>

<!DOCTYPE html>
<html>
<head>

</head>

<link href="../libraries/css/bootstrap.min.css" rel="stylesheet" />
<link href="../libraries/css/select2.min.css" rel="stylesheet" />
<script src="../libraries/js/jquery.min.js"></script>
<script src="..//libraries/js/select2.min.js"></script>

<body>


<!------------------------------------------------------------------------------>
<!-- 1. Sub form -->
  <div id="insert">
  
    <script>
           
        function removeRowFromTable()
        {
          var tbl = document.getElementById('subtable');
          var lastRow = tbl.rows.length;
          if (lastRow > 2) tbl.deleteRow(lastRow - 1);
        }
          
        // Last updated 2006-02-21
        function addRowToTable(){
        let tbl = document.getElementById('subtable');
        let lastRow = tbl.rows.length;
        if(lastRow>=6){
          console.log('too many you plonker');
          return false;
        }

        // if there's no header row in the table, then iteration = lastRow + 1
        let iteration = lastRow;
        let row = tbl.insertRow(lastRow);
            
        // left cell
        let scNumberLeft = row.insertCell(0);
        let textNode = document.createTextNode(iteration);
        scNumberLeft.appendChild(textNode);
        
        // right cell
        let ScNameCell = row.insertCell(1);
        let ScNameInput = document.createElement('input');
        ScNameInput.type = 'text';
        ScNameInput.name = 'SC['+iteration+'][subName]';
        ScNameInput.id = 'SC['+iteration+'][subName]';
        ScNameInput.size = 40;
        ScNameCell.appendChild(ScNameInput);
        
        // select cell
        let ScValueCell = row.insertCell(2);
        let ScValueInput = document.createElement('input');
        ScValueInput.type = 'text';
        ScValueInput.name = 'SC['+iteration+'][subPay]';
        ScValueInput.id = 'SC['+iteration+'][subPay]';
        ScValueInput.size = 10;
        ScValueCell.appendChild(ScValueInput);
        }
             
    </script>
  </div>
<!---------------------------------- End --------------------------------------->



<!------------------------------------------------------------------------------>
<!-- 2. Script -->

  <?php
  $subcontractors = getSubcontractors($db);
  $developments = getDevelopments($db);
  $plots = getPlots($db);
  $opCodes = getOpCodes($db);


        $subcontractoroptions='';
          foreach ($subcontractors as $asubcontractor) {
             $subcontractoroptions.="<option value='{$asubcontractor['Code']}'>{$asubcontractor['SubcontractorName']}</option>";
        }

        $siteoptions='';
          foreach ($developments as $asite) {
             $siteoptions.="<option value='{$asite['idDevelopment']}'>{$asite['idDevelopment']}  {$asite['InternalName']}</option>";
        }

        $plotoptions='';
          foreach ($plots as $aplot) {
             $plotoptions.="<option value='{$aplot['PlotNr']}'>{$aplot['PlotNr']}</option>";
        }
        $opcodeoptions='';
          foreach ($opCodes as $opcode) {
             $opcodeoptions.="<option value='{$opcode['Operation']}'>{$opcode['Operation']}</option>";
        }
  ?>

        <script type="text/javascript">
        $(document).ready(function() {
          $("#subcontractorselect").select2();
        });
        </script>

        <script type="text/javascript">
        $(document).ready(function() {
          $("#siteselect").select2();
        });
        </script>

        <script type="text/javascript">
        $(document).ready(function() {
          $("#plotselect").select2();
        });      
        </script>

        <script type="text/javascript">
        $(document).ready(function() {
          $("#operationcodeselect").select2();
        });
        </script>
<!---------------------------------- End --------------------------------------->



<!------------------------------------------------------------------------------>
<!-- 3. Form -->
  <div align="center">
    <form id="wages" action="" method="POST"><br><br>
         <p>
           <input type="button" value="Add" onclick="addRowToTable();" />
           <input type="button" value="Remove" onclick="removeRowFromTable();" />
         </p>

          <table border="1" id="subtable">
            <div align="center"><tr><th colspan="3">Subcontractors</th></tr>
            <tr><td>
            1</td>
              <td><input type="text" name="SC[1][subName]" id="subName1" size="40"/></td>
              <td><input type="int" name="SC[1][subPay]" id="subPay1" size="10"></td>
            </tr>
          </table>
           <!--

           <select class="subcontractorselect" id="subcontractorselect" name="sub1" placeholder="Subcontractor" multiple="" style="width: 40%" required>
             <?= $subcontractoroptions ?>
          </select>
          <input type="int" id="pay1" style="width: 10%" name="pay1" required><br><br> -->


                      <a href="./View/addsubcontractor.php" target="_blank">Add New Subcontractor to Company</a><br><br>

            <select class="siteselect" id="siteselect" name="Site" placeholder="Site Name:" style="width: 50%" required>
             <?= $siteoptions ?>
          </select><br><br>
  <!--
          <select class="plotselect2" id="plotselect2" name="Plot" placeholder="Plot Number:" multiple="multiple" style="width:50%" required>
          </select><br><br>
  -->
          <br>Plots seperated by "," or "-" (1, 2, 3-5) = (1, 2, 3, 4, 5)
          <br><input type="text" name="plot" id="plot"><br><br>

          <select class="operationcodeselect" id="operationcodeselect" name="OpCode" placeholder="OpCode" style="width:50%"required>
             <?= $opcodeoptions ?>
          </select><br><br>

          <button class="button btn-lg" id="addsubcontractor" style="width:25%" align="center">Add to Form</button>

    </form>
  </div>
  <?php echo "<br><br>"; ?>
<!---------------------------------- End --------------------------------------->



<!------------------------------------------------------------------------------>
<!-- 4. Validation -->
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    $countSC = count($_POST["SC"]);
    echo $countSC;
  
  
  $plots = ($_POST["plot"]);
  
  
  $plots = preg_replace_callback('/(\d+)-(\d+)/', function($m) {
  return implode(',', range($m[1], $m[2]));
  }, $plots);
  #$plots = preg_replace('/\s+/', '', $plots);
  
  $site = ($_POST["Site"]);
  $DevelopmentName = DevelopmentName($site, $db);
  $DevelopmentName = implode("", $DevelopmentName[0]);
  
  
  foreach($_POST["SC"] as $value){
      $output["SC"][] = implode("", $value);
  }
  
  
  pre($output["SC"]);
  
  $output = array();
  #$output["SC"][1] = ($_POST["SC"][1]);
  #$output["SC"][2] = ($_POST["SC"][2]);
  #$output["SC"][3] = ($_POST["SC"][3]);
  #$output["SC"][4] = ($_POST["SC"][4]);
  #$output["SC"][5] = ($_POST["SC"][5]);
  $output["Site"] = $DevelopmentName;
  $output["Plots"] = $plots;
  
  echo "<br><br>";
  echo pre($output);

  }
  
  
  
  #$input = '3-5,6,9,11,23,14-18,42-50';
  # 
  #  $input = preg_replace_callback('/(\d+)-(\d+)/', function($m) {
  #  return implode(',', range($m[1], $m[2]));
  #}, $input);
  #echo pre($input);
  #
  #$inputsp = '3-            5, 6, 9, 11, 23, 14-18, 42-50';
  #  
  #$inputsp = preg_replace('/\s+/', '', $inputsp);
  #
  #  $inputsp = preg_replace_callback('/(\d+)-(\d+)/', function($m) {
  #  return implode(',', range($m[1], $m[2]));
  #}, $inputsp);
  #echo pre($inputsp);
  #
  
  
  #https://stackoverflow.com/  questions/7698664/converting-a-range-or-partial-array-in-the-form-3-6-or-3-6-12-into-an-arra
  
  ?>
<!---------------------------------- End --------------------------------------->



<!------------------------------------------------------------------------------>
<!-- 5. Tables -->
    <style>
    .subtable {
        border: 4px solid black;
        border-collapse: collapse;
        width: 100.25%;
    }
    </style>
    
    
    <table class="subtable">
        <tr>
             <th align="left"><b>Site - Plot</b></th>
             <th align="left"><b>Operation Code</b></th>
             <th align="left"><b>Value</b></th>
        </tr>
    </table>
    
    
    <?php
    echo '<div class="fulltable">';
    #if($subresponse){
    #
    #    echo <<< EOF
    #    <table class="table table-hover table-bordered table-dark" id="myTable" align="left"
    #    cellspacing="5" cellpadding="8">
    #    <thead id="theader">
    #    <tr>
    #
    #         <th align="left"><b>Site - Plot</b></th>
    #         <th align="left"><b>Operation Code</b></th>
    #         <th align="left"><b>Value</b></th>
    #    </tr>
    #    </thead>
    #EOF;
    #    foreach($subresponse as $row){
    #
    #        echo <<<EOF
    #    <tr>
    #        <td align="left"><b>{$row['SubcontractorName']}</b></td>
    #        <td align="left">{$row['Code']}</td>
    #        <td align="left">{$row['Address1']}</td>
    #        <td align="left">{$row['Address2']}</td>
    #        <td align="left">{$row['Address3']}</td>
    #        <td align="left">{$row['Town']}</td>
    #        <td align="left">{$row['County']}</td>
    #        <td align="center"><input type="radio" value={$row['id']} ></td>
    #    </tr>
    #EOF;
    #}
    #
    #echo '</table>' ;
    #echo '</div>';
    #}
    #?>
<!---------------------------------- End --------------------------------------->



<!------------------------------------------------------------------------------>
<!-- 6. Select 2 Javascript  -->
  <script type="text/javascript">
    
  function tryparse(data){
    try { 
          parsedData=JSON.parse(data);
        } catch(err) {
          console.log("raw data")
          console.log(data)
          var data={
            "status":"error",
            "message":err + "<br />Server returned:<br />" + data
          }
        //gritterStatus(data)
        throw new Error("Error Parsing Data:" + err);
        }
  
      if(parsedData.hasOwnProperty('serverError')){
          parsedData.status = "Server Error"
         // gritterStatus(parsedData)
      }
  
    return parsedData
  }
  
  $('#addsubcontractor').on('click', function(){
    console.log('got a click');// 1-6,98,9,7,65
    $.each($('#plotselect2').val(), function(){
      console.log($(this)[0]);
      $('#formdiv').append($(this)[0]+'<br />');
    })
  })
  
  $('body').on('click', '#testRandomButton', function(){
    console.log("----------------------------------------")
    data = {}
    data.name = 'GetPlotsByDevelopment'
  
    $.ajax({
      type: "POST",
      url: './PostHandler.php',
      data: data
    })
    .done(function(data){
      data=tryparse(data)
      console.log('data')
      console.log(data)
      //gritterStatus(data)
    })
  })
  
  
  $("#plotselect2").select2({
    ajax: {
      url: "../../Controller/PostHandler.php",
      dataType: 'json',
      type: 'POST',
      delay: 10,
      data: function (params) {
        return {
          name: 'GetPlotsByDevelopment',
          idDevelopment: $('#siteselect').val(),
          query: params.term, // search term
          page: params.page
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;
  
        return {
          results: data.payload,
          pagination: {
            more: (params.page * 30) < data.total_count
          }
        };
      },
      cache: true
    },
    placeholder: 'Select Plot Number',
    width: '50%',
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 1,
    templateResult: format,
    templateSelection: formatSelection
  });
  
  function format (Plot) {
    if (Plot.loading) {
      return Plot.text;
    }
  
   return Plot.text;
  }
  
  function formatSelection (Plot) {
    return Plot.full_name || Plot.text;
  }
  
  
  </script>
<!---------------------------------- End --------------------------------------->
</html>
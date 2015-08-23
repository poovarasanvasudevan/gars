<?php
$units = $_GET['unit'];

$weekDays = array(
    '1' => 'Monday',
    '2' => 'Tuesday',
    '3' => 'Wednesday',
    '4' => 'Thursday',
    '5' => 'Friday',
    '6' => 'Saturday',
    '7' => 'Sunday'
);
if ($units == 'Week') {
    echo "<div class='col-md-12 marginT10 border-top  '> ";
    echo "<h4>Occurance : </h4>";
    echo "<div class='col-md-10 padding0 marginT10'>";
    echo "<div class='col-md-4 padding5 paddingT5 text-right'><label>Day of</label></div>";
    echo "<div class='col-md-7 padding0'><select name='weekday' id='weekday' class='form-control' onchange='updateDescWeek(this.value)'>";
// 		for($i=1; $i<=sizeof($weekDays); $i++) {
    foreach ($weekDays as $weekkey => $weekvalue) {
        echo "<option value='" . $weekkey . "'>" . $weekvalue . "</option>";
    }
    echo '</select></div></div></div>';
    echo '</hr>';
    echo "<div class='col-md-12 marginT10'> ";
    echo "<div class='col-md-10 padding0 marginT10'>	";
    echo " <div class='col-md-4 padding5 paddingT5 text-right'><label>Description</label></div>";
    echo " <div class='col-md-7 padding0'>";
    echo "<textarea rows='4' cols='72' name='description' id='desctext' readonly style='background:#f1f1f1;' class='form-control'></textarea>
		</div>
				    
	</div>
</div>	";
} else if ($units == 'Month') {
    echo "<div class='col-md-12 marginT10'><h4>Occurance : </h4>";
    echo "	<div class='col-md-10 padding0 marginT10'><div class='col-md-4 padding5 paddingT5 text-right'><label>Date of the Month </label></div>
			<div class='col-md-7 padding0'><input type='number' min='1' max='30' name='monthDate' id='monthDate' class='form-control' onkeyup='updateDescMonth(this.value)'/></div></div></div>";

    echo '</hr>';
    echo "<div class='col-md-12 marginT10'> ";
    echo "<div class='col-md-10 padding0 marginT10'>	";
    echo " <div class='col-md-4 padding5 paddingT5 text-right'><label>Description</label></div>";
    echo " <div class='col-md-7 padding0'>";
    echo "<textarea rows='4' cols='72' name='description' id='desctext' readonly style='background:#f1f1f1;' class='form-control'></textarea>
		</div>
	
	</div>
</div>	";
} else {

}
?>
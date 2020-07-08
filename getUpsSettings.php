<?php
function
getUpsSettings() {
    $settingsFile = 'upsData/UPS_Alarm.conf';
    
    //Read the file line by line
	$lines = file($settingsFile);
    if($lines == NULL)
        echo "Unable to read the Settings";

    for($i=0; $i<count($lines); $i++) {
        echo $lines[$i];
/*
        $temp = explode("Temperature:", $lines[$i]);
        if(count($temp) > 1) {
            $values = explode(",", $temp[1]);
            array_push($x, $values[0]);
            array_push($y, $values[1]);
            //echo "<p>" . $temp[1] . "</p>";
        }
*/
    }
}
?>

<?php
function 
getTemperature() {
	//File to read
	$file = '/sys/devices/w1_bus_master1/28-000007200540/w1_slave';

	//Read the file line by line
	$lines = file($file);

	//Get the temp from second line 
	$temp = explode('=', $lines[1]);

	//Setup some nice formatting (i.e. 21,3)
	$temp = number_format((float)$temp[1] / 1000, 1, '.', '');
	return $temp;
}
?>

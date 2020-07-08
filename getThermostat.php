<?php
function 
getThermostat() {
    $gpioVal = shell_exec("./gpioget gpiochip0 23");
    return $gpioVal;
}
?>

<?php
function
getUpsMessages(&$x, &$y, &$threshold, &$username, &$mailserver, &$to, &$cc, &$cc1, &$message) {
    $settingsFile = "upsData/UPS-AlarmLog.txt";
    
    //Read the file line by line
	$lines = file($settingsFile);

    for($i=0; $i<count($lines); $i++) {
        //echo "<p>" . $lines[$i] . "</p>";
        $temp = explode("Temperature:", $lines[$i]);
        if(count($temp) > 1) {
            $values = explode(",", $temp[1]);
            array_push($x, $values[0]);
            array_push($y, $values[1]);
        }
        else {
            $temp = explode("Threshold:", $lines[$i]);
            if(count($temp) > 1) {
                $threshold = $temp[1];
                //echo "<p>" . $temp[1] . "</p>";
            }
            else {
                $temp = explode("Username:", $lines[$i]);
                if(count($temp) > 1) {
                    $username = $temp[1];
                }
                else {
                    $temp = explode("Mail Server:", $lines[$i]);
                    if(count($temp) > 1) {
                        $mailserver = $temp[1];
                    }
                    else {
                        $temp = explode("To:", $lines[$i]);
                        if(count($temp) > 1) {
                            $to = $temp[1];
                        }
                        else {
                            $temp = explode("Cc:", $lines[$i]);
                            if(count($temp) > 1) {
                                $cc = $temp[1];
                            }
                            else {
                                $temp = explode("Cc1:", $lines[$i]);
                                if(count($temp) > 1) {
                                    $cc1 = $temp[1];
                                }
                                else {
                                    $temp = explode("Message to Send:", $lines[$i]);
                                    if(count($temp) > 1) {
                                        $message = $temp[1];
                                        //echo "<p>.$message.</p>;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

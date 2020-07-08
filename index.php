<?php
    include "getUpsMessages.php";
    include "plot.php"
?>


<!DOCTYPE html>

<html>

    <head>
        <link rel="stylesheet" href="upsStyle.css">
        <title>UPS Alarm System</title>
        <meta charset="UTF-8">
        <meta name="description" content="UPS Temperature Alarm System">
        <meta name="author" content="Gabriele Salvato">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/JavaScript">
            function timeRefresh(timeoutPeriod) {
                setTimeout("location.reload(true);", timeoutPeriod);
            }
        </script>
    </head>

    <body onload="JavaScript:timeRefresh(5*60*1000);" >
        <center>
        <img src="cnrlogo.svg" alt="Consiglio Nazionale delle Ricerche" width="175" height="21"> <br>
        <img src="logoIPCF.jpg" alt="Istituto Processi Chimico Fisici - Messina" width="75" height="33"><br>

        
        <h5>UPS Temperature Alarm System</h5>

        <?php
            echo "<h6>Last update: ".date("H:i:s Y-m-d") . "</h6>";
            $x = $y = array();
            $username = $mailserver = "";
            $to = $cc = $cc1 = "";
            $message = "";
            $threshold = "";
            $temperature = "";
            getUpsMessages($x, $y, $threshold, $username, $mailserver, $to, $cc, $cc1, $message);
            if(count($y) > 0)
                $temperature = $y[count($y)-1];
            if((float)$temperature > (float)$threshold) {
                echo "ALARM! Current UPS-Room temperature is: ".$temperature."°C<br>";
                echo "Threshold value: ".$threshold."°C<br>";
            }
            else {
                echo "Current UPS-Room temperature: ".$temperature."°C<br>";
                echo "Threshold value: ".$threshold."°C<br>";
            }
            
            plot($x, $y);
        ?>
        <p>
        <button onclick="window.location.href='setup.php';">
            Setup
        </button>
        </p>
        </center>
    </body>

</html>


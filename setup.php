<?php
    include "getUpsMessages.php";
?>

<!DOCTYPE html>

<html>

    <head>
        <link rel="stylesheet" href="upsStyle.css">
        <title>UPS Alarm System</title>
        <meta charset="UTF-8">
        <meta name="description" content="UPS Temperature Alarm System">
        <meta name="author" content="Gabriele Salvato">
        <script type="text/JavaScript">
            function timeRefresh(timeoutPeriod) {
                setTimeout("location.reload(true);", timeoutPeriod);
            }
        </script>
    </head>

    <body>
        <center>
        <img src="cnrlogo.svg" alt="Consiglio Nazionale delle Ricerche" width="15%" height="15%"> <br>
        <img src="logoIPCF.jpg" alt="Istituto Processi Chimico Fisici - Messina" width="75" height="33"><br>

        
        <h5>UPS Temperature Alarm System</h5>
        <?php
            $x = $y = array();
            $username = $mailserver = "";
            $to = $cc = $cc1 = "";
            $message = "";
            $threshold = "";
            getUpsMessages($x, $y, $threshold, $username, $mailserver, $to, $cc, $cc1, $message);

            $token = md5(time());
            $fp = fopen('./tokens.txt', 'a');
            fwrite($fp, "$token\n");
            fclose($fp);
        ?>
        <div class="container">
            <form method="post" action="" >
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                <label for="username">From:</label>
                <input type="text" id="username" name="username" value="<?php echo $username ?>" style="text-align:right;" required>
                <label for="mailserver">@</label>
                <input type="text" id="mailserver" name="mailserver" value= "<?php echo $mailserver ?>" style="text-align:left;" required><br>
                <label for="to">To:</label>
                <input type="email" id="to" name="to" value= "<?php echo $to ?>" required><br>
                <label for="cc">Cc:</label>
                <input type="email" id="cc" name="cc" value= "<?php echo $cc ?>"><br>
                <label for="cc1">Cc:</label>
                <input type="email" id="cc1" name="cc1" value= "<?php echo $cc1 ?>"><br>
                <label for="message">Message:</label>
                <textarea name="message" rows="5" cols="40"><?php echo trim($message) ?> </textarea><br>
                <label for="threshold">Temperature Threshold:</label>
                <input type="text" id="threshold" name="threshold" value= "<?php echo $threshold ?>" style="width:15%;" required>
                <label for="threshold">[&deg;C]</label><br>
                <input type="submit" name= "submit" value="Submit">
            </form> 
        </div>
        <p>
            <button onclick="window.location.href='index.php';">
                Back
            </button>
        </p>

        <?php
/*
            $tokens = file("./tokens.txt");
            if(in_array("{$_POST["token"]}\n", $tokens)) {
                if(isset($_POST["submit"])) {
                    if(isset($_POST["to"])) {
                        $msg = "<p>".htmlentities($_POST["to"])."</p>";
                        if(isset($_POST["threshold"])) {
                            $msg = $msg."<p>".htmlentities($_POST["threshold"])."</p>";
                        }
                        $fp = fopen("./safer.txt", "w");
                        fwrite($fp, "$msg");
                        fclose($fp);
                    }
                }
                readfile("./safer.txt");
            }
*/
        ?>

        <?php
/*
            <label for="password">PW</label>
            <input type="password" id="password" name="password" value= "" required>
            <label for="passwordRe">Retype PW</label>
            <input type="password" id="passwordRe" name="passwordRe" value= "" required><br>
            error_reporting(E_ALL);
            // See the password_hash() example to see where this came from.
            $hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';

            //if (password_verify('rasmuslerdorf', $hash)) {
            if (password_verify($_POST[psw], $hash)) {
                echo 'Sorry: Not Yet Implemented';
            } else {
                echo 'Invalid password.';
            }
*/
        ?>
        </center>
        
    </body>

</html>


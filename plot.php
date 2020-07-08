<?php
function 
plot($x, $y) {

    $nPoints = min(count($x), count($y));
    if($nPoints == 0) {
        echo "<pre>"."No Temperature Data to Plot"."</pre>";
        return;
    }

    $fontSize = 2;
    $width = 480;
    $height= 360;
    
    $graph   = @imagecreatetruecolor($width, $height)
        or die('Cannot Initialize new GD image stream');

    $bkgndClr   = imagecolorallocate($graph,   0,   0,   0);
    $axesClr    = imagecolorallocate($graph,  64,  64, 255);
    $pointClr   = imagecolorallocate($graph, 255, 255,   0);
    $labelClr   = imagecolorallocate($graph, 255, 255, 255);
    
    imagefill($graph, 0, 0, $bkgndClr);

    $XMin = (float)$x[0];
    $XMax = (float)$x[0];
    $YMin = (float)$y[0];
    $YMax = (float)$y[0];
    
    foreach($x as $val) {
        if((float)$val < $XMin) { 
            $XMin = (float)$val; 
        }
        if((float)$val > $XMax) { 
            $XMax = (float)$val; 
        }
    }
    foreach($y as $val) {
        if((float)$val < $YMin) { 
            $YMin = (float)$val; 
        }
        if((float)$val > $YMax) { 
            $YMax = (float)$val; 
        }
    }
    $YMin = $YMin - 0.5;
    $YMax = $YMax + 0.5;
    
    $Pf_left   = imagefontwidth($fontSize)*8 + 2.0;
    $Pf_right  = $width - imagefontwidth($fontSize)*7 - 5.0;
    $Pf_bottom = 2.0 * imagefontheight($fontSize);
    $Pf_top    = $height - 3.0*imagefontheight($fontSize);

    XTicLin($graph, $axesClr, $labelClr, $fontSize, $XMin, $XMax,
            $Pf_left, $Pf_right, $Pf_bottom, $Pf_top);

    YTicLin($graph, $axesClr, $labelClr, $fontSize, $YMin, $YMax,
            $Pf_left, $Pf_right, $Pf_bottom, $Pf_top);

    imageline($graph,
              round($Pf_left),  round($Pf_bottom),
              round($Pf_right), round($Pf_bottom),
              $axesClr);
    imageline($graph,
              round($Pf_right), round($Pf_bottom),
              round($Pf_right), round($Pf_top),
              $axesClr);
    imageline($graph,
              round($Pf_right), round($Pf_top),
              round($Pf_left),  round($Pf_top),
              $axesClr);
    imageline($graph,
              round($Pf_left), round($Pf_top),
              round($Pf_left), round($Pf_bottom),
              $axesClr);

    $title = "Temperature [°C] vs Time [h]";
    $icx = strlen($title)*imagefontwidth(10)/2;
    imagettftext ($graph, 10, 0.0,
                  ($Pf_right-$Pf_left-$icx)/2 + $Pf_left,
                  1.25*imagefontheight(10), 
                  $labelClr, "/usr/share/fonts/truetype/freefont/FreeSans.ttf",
                  $title);

    $xfact = ($Pf_right-$Pf_left) / ($XMax-$XMin);
    $yfact = ($Pf_top-$Pf_bottom) / ($YMax-$YMin);
    
    for($i=0; $i<$nPoints; $i++) {
        if($x[$i] >= $XMin and $x[$i] <= $XMax and
           $y[$i] >= $YMin and $y[$i] <= $YMax)
        {
            $ix = round((((float)$x[$i]-$XMin)*$xfact) + $Pf_left);
            $iy = round(($Pf_top - ((float)$y[$i]-$YMin)*$yfact));
            imagefilledellipse($graph, $ix, $iy, 1, 1, $pointClr);
        }
    }

    imagepng($graph, "image.png", 9, -1);
    echo "<img src=\"image.png\" >";
    imagedestroy($graph);

}


function
XTicLin($graph, $axesClr, $labelClr, $fontSize,
        $AxXMin, $AxXMax,
        $Pf_left, $Pf_right, $Pf_top, $Pf_bottom)
{
    if ($AxXMax <= 0.0) {
        $xmax =-$AxXMin;
        $xmin =-$AxXMax;
        $isx  =-1;
    } else {
        $xmax = $AxXMax;
        $xmin = $AxXMin;
        $isx  = 1;
    }

    $dx = $xmax - $xmin;
    $b  = log10($dx);
    $ic = round($b) - 2;
    $dx = round(pow(10.0, ($b-$ic-1.0)));

    if     ($dx < 11.0) $dx =  10.0;
    else if($dx < 28.0) $dx =  20.0;
    else if($dx < 70.0) $dx =  50.0;
    else                $dx = 100.0;

    $dx    = $dx * pow(10.0, $ic);
    $xfact = ($Pf_right-$Pf_left) / ($xmax-$xmin);
    $dxx   = ($xmax+$dx) / $dx;
    $dxx   = floor($dxx) * $dx;
    $iy0   = intval($Pf_bottom + 0.5*imagefontheight($fontSize));
    $iesp  = floor(log10($dxx));

    if ($dxx > $xmax) $dxx = $dxx - $dx;
    
    do {
        if($isx == -1)
            $ix = intval($Pf_right-($dxx-$xmin) * $xfact);
        else
            $ix = intval(($dxx-$xmin) * $xfact + $Pf_left);
            
        $jy = intval($Pf_bottom + 5);// Perche' 5 ?
        
        imageline($graph,
                  $ix, round($Pf_top),
                  $ix, $jy,
                  $axesClr);

        $isig = 0;
        if($dxx == 0.0)
            $fmant= 0.0;
        else {
            $isig  = intval($dxx/abs($dxx));
            $dxx   = abs($dxx);
            $fmant = log10($dxx) - $iesp;
            $fmant = pow(10.0, $fmant)*10000.0 + 0.5;
            $fmant = floor($fmant)/10000.0;
            $fmant = $isig * $fmant;
        }

        if(($isx*$fmant) <= -10.0) {
            $Label = sprintf("%6.1f", $isx*$fmant);
        }
        else {
            $Label = sprintf("%6.2f", $isx*$fmant);
        }
        
        $ix0 = $ix - 3*imagefontwidth($fontSize);

        imagestring($graph, $fontSize,
                    $ix0,   $iy0, 
                    $Label, $labelClr);

        $dxx = $isig*$dxx - $dx;
    } while($dxx >= $xmin);
    if($iesp != 0) {
        imagestring($graph, $fontSize,
                    intval($Pf_right+2), intval($Pf_bottom-0.5*imagefontheight($fontSize)), 
                    "x10", $labelClr);
        $icx = 4*imagefontwidth($fontSize);
        $Label = sprintf("%d", $iesp);
        imagestring($graph, $fontSize,
                    intval($Pf_right+$icx),
                    intval($Pf_bottom-imagefontheight($fontSize)), 
                    $Label, $labelClr);
    }
}



function
YTicLin($graph, $axesClr, $labelClr, $fontSize,
        $AxYMin, $AxYMax,
        $Pf_left, $Pf_right, $Pf_top, $Pf_bottom)
{
    if ($AxYMax <= 0.0) {
        $ymax =-$AxYMin;
        $ymin =-$AxYMax;
        $isy  =-1;
    } else {
        $ymax = $AxYMax;
        $ymin = $AxYMin;
        $isy  = 1;
    }
    $dy  = $ymax - $ymin;
    $b   = log10($dy);
    $icc = round($b) - 2;
    $dy  = round(pow(10.0, ($b-$icc-1.0)));

    if($dy < 11.0)      $dy =  10.0;
    else if($dy < 28.0) $dy =  20.0;
    else if($dy < 70.0) $dy =  50.0;
    else                $dy = 100.0;

    $dy    = $dy * pow(10.0, $icc);
    $yfact = ($Pf_top-$Pf_bottom) / ($ymax-$ymin);
    $dyy   = ($ymax+$dy) / $dy;
    $dyy   = floor($dyy) * $dy;
    $iesp  = intval(floor(log10($dyy)))-1;

    if($dyy > $ymax) $dyy = $dyy - $dy;
    
    do {
        if($isy == -1)
            $iy = intval($Pf_top - ($dyy-$ymin) * $yfact);
        else
            $iy = intval(($dyy-$ymin) * $yfact + $Pf_bottom);
        $jx = intval($Pf_right);

        imageline($graph,
                  intval($Pf_left-5), $iy,
                  $jx,                $iy,
                  $axesClr);
        $isig = 0;
        if($dyy == 0.0)
            $fmant = 0.0;
        else{
            $isig  = intval($dyy/abs($dyy));
            $dyy   = abs($dyy);
            $fmant = log10($dyy) - $iesp;
            $fmant = pow(10.0, $fmant)*10000.0 + 0.5;
            $fmant = floor($fmant)/10000.0;
            $fmant = $isig * $fmant;
        }
        if(($isy*$fmant) <= -10.0) {
            $Label = sprintf("%6.2f", $isy*$fmant);
        }
        else {
            $Label = sprintf("%6.2f", $isy*$fmant);
        }
        
        $ix0 = intval($Pf_left - 6*imagefontwidth($fontSize) - 5);
        $iy0 = $iy - imagefontheight($fontSize)/2;
        
        imagestring($graph, $fontSize,
                    $ix0,   $iy0, 
                    $Label, $labelClr);

        $dyy = $isig*$dyy - $dy;
    } while ($dyy >= $ymin);

    if($iesp != 0) {
        imagestring($graph, $fontSize,
                    intval($Pf_left), intval($Pf_top-imagefontheight($fontSize)), 
                    "x10", $labelClr);
        $Label = sprintf("%d", $iesp);
        $icx = 4*imagefontwidth($fontSize);
        imagestring($graph, $fontSize,
                    intval($Pf_left+$icx), intval($Pf_top-1.5*imagefontheight($fontSize)), 
                    $Label, $labelClr);
    }
}
?>

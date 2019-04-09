<?php
function sumpixel($str){
		$strS=0;
		for ($i=0; $i<=strlen($str); $i++){
			$strS=$strS+imagefontwidth($str[$i]);	
		}
		return $strS;
	}
function drawPlot( $x, $y, $width, $height) {
	 // �������
	$MB=35;  // ������
	$ML=10;   // ����� 
	$M=40;    // ������� � ������ �������.
			// ��� ������, ��� ��� ��� ��� ������
	// ������ ������ �������
	$LW=imagefontwidth(2);

	// ���������� �������� � �������������� �����
	// ����� �� ��� Y.
	$county=10;
	$count=7;
	$text_width=sumpixel(max($y))+(sumpixel(max($y)))/5;

	// ���������� ����� ������� � ������ ������ �������� �� ��� Y
	$ML+=$text_width;

	// ��������� �������� ������� ������� (�� ������� �������� �
	// ��������)
	$RW=$width-$ML-$M;
	$RH=$height-$MB-$M;

	// ��������� ���������� ����
	$X0=$ML;
	$Y0=$height-$MB;
	$stepW=$RW/$count;
	$step=$RH/$county;
	$stepNum=max($y)/$county;
	$stepNumX=max($x)/$county;
	$im=imagecreate($width,$height);
	// ���� ���� (�����)
	$bg[0]=imagecolorallocate($im,255,255,255);
	// ���� ������ ����� ������� (������-�����)
	$bg[1]=imagecolorallocate($im,231,231,231);
	// ���� ����� ����� ������� (�����)
	$bg[2]=imagecolorallocate($im,212,212,212);
	// ���� ����� (�����, ������)
	$c=imagecolorallocate($im,184,184,184);
	// ���� ������ (�����-�����)
	$text=imagecolorallocate($im,136,136,136);
	// ����� ��� ����� ��������
	$bar[0]=imagecolorallocate($im,161,155,0);
	// ����� ������� ����� �������
	imagefilledrectangle($im, $X0, $Y0-$RH, $X0+$RW, $Y0, $bg[1]);
	imagerectangle($im, $X0, $Y0, $X0+$RW, $Y0-$RH, $c);
	imagestring($im,2, $X0,$Y0+imagefontheight(2)/2,'0',$text);
	imagestring($im,2, $X0+$RW+8,$Y0-$RH,'P,MPa',$text);
	if ($GLOBALS["chetchik"]==1)imagestring($im,2, $X0+$RW/2,$Y0+imagefontheight(2)/2+14,'k*10^-4',$text);
	if ($GLOBALS["chetchik"]==2)imagestring($im,2, $X0+$RW/2,$Y0+imagefontheight(2)/2+14,'(a/a0)*10^3',$text);
	if ($GLOBALS["chetchik"]==3)imagestring($im,2, $X0+$RW/2,$Y0+imagefontheight(2)/2+14,'k*10^4',$text);
	// ����� ����� �� ��� Y
for ($i=1;$i<=$county;$i++) {
    $yLine=$Y0-$step*$i;
	$str=$stepNum*$i;
    imagestring($im,2, $X0-sumpixel($str)-sumpixel($str)/5,$yLine-imagefontheight(2)/2,$str,$text);
    imageline($im,$X0-5,$yLine,$X0+$RW,$yLine,$c);
    }
	// ����� ����� �� ��� X
for ($i=1;$i<=$count-1;$i++) {
    $xLine=$X0+$stepW*$i;
	$str=$stepNumX*$i;
    imagestring($im,2, $xLine-15,$Y0+imagefontheight(2)/2,round($str,2),$text);                       
    imageline($im,$xLine,$Y0+5,$xLine,$Y0-$RH,$c);
    }	
	
  /* ����� ���������� �������� �� ������� ����� �� X � �� Y */
    $p_one_x = $RW / (max($x) - min($x));
    $p_one_y = $RH / (max($y) - min($y));
	
       /* ���������� ���������� �� ����� �������  � ������  */
    $p_x = array();
    $p_y = array();
    for ($i = 0; $i < count($x); $i++) {
      $p_x[$i] = round($X0 + ($x[$i]) * $p_one_x);
      $p_y[$i] = round($Y0 - ($y[$i]) * $p_one_y);
    }	

    for ($i = 1; $i < count($p_x); $i++) {
      imageLine($im, $p_x[$i - 1], $p_y[$i - 1], $p_x[$i], $p_y[$i], $bar[0]);
    }
	//$chetchik=1;
    imagePng($im, 'image'. $GLOBALS["chetchik"] .'.png');
    imageDestroy($im);
	$GLOBALS["chetchik"]=$GLOBALS["chetchik"]+1;
	
	
 }

	$T0=288;
	$P0= 0.3*pow(10,6);
	$a0=5*pow(10,-7);
	$Pd0=0.2*pow(10,6);
	$Ru=287;
	$Rd=189;
	$p10=pow(10,3);
	$sigma=75.5*pow(10,-3);
	$n0=pow(10,9);
	$shag=2*pow(10,-12);
	$shag2=pow(10,-7);
	$kn=pow(10,-6);
	$alfa0=4/3*3.14*pow($a0,3)*$n0;
	$Pu0=$P0+((2*$sigma)/$a0)-$Pd0;
	$pd0=$Pd0/($Rd*$T0);
	$pu0=$Pu0/($Ru*$T0);
	$k0=$pd0/($pd0+$pu0+$p10);

	$kd=$k0; 

	$chetchik=1;
	$x = array();
	$y = array();
	$n= intval(($k0-$kn)/$shag2)+960;//���������� �����
	 $kd=$k0-$shag;//������������ ������ ��������, �� ������ ��� ����
	// ������ ���� �� ���������� �����
	for ($d=0;$d<=$n;$d++){
		$a= pow((($p10*$T0*$Rd*($k0-$kd)*$Ru)-$Pu0*$alfa0)/(($Pd0*($kd/$k0))*$alfa0*$Ru),1/3)*$a0;
		
		if ($a>0){
			
			$P=2*$sigma*((1/$a0)-(1/$a))+$Pu0*(pow($a0/$a,3)-1)+$Pd0*(($kd/$k0)-1)+$P0;
		
			$x1[$d]= $kd*pow(10,4); // ������ � X ��� �D
			
			$x[$d]= ($a/$a0)/pow(10,3); // ������ � X, ���������� ��� ���� ��� ������� ������� ,� Y ��� ����� ���������.
			$y[$d]= $P/pow(10,6); // ������ � Y - P ��������
		
		}
		if ($a<(10*$a0)){  // ������ �������� � ������ ���
		$kd=$kd-$shag; 
		}
		else{
			$kd=$kd-$shag2;
		}

	}
	//$Fkd($t)=(-3/2)*$a*$Nu*$D/pow($ab0,3)*($kd-$ksigma)
	//echo $i;
	drawPlot( $x1, $y, 640, 480); // �������� �������
	drawPlot( $x, $y, 640, 480);
	// drawPlot( $x2, $y, 640, 480);
	echo '<div><img src="image1.png" width="640" height="480"><div/><br/>';
	echo '<div><img src="image2.png" width"640" height="480"><div/><br/>';
	//echo '<div><img src="image3.png" width"400" height="400"><div/>';
//  echo $k0;
	
 
?>
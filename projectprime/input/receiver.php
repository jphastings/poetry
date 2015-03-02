<?php
include('db.php');

// Year
if ($_REQUEST['year'] < 1890 or $_REQUEST['year'] > 2003) {
	header("Location: ./");
	exit("1");
}
// Month
if ($_REQUEST['month'] < 1 or $_REQUEST['month'] > 12) {
	header("Location: ./");
	exit("2");
}
// Day
if ($_REQUEST['day'] < 1 or $_REQUEST['day'] > 31) {
	header("Location: ./");
	exit("3");
}
// Ratings
for($n=1;$n<=4;$n++) {
	if ($_REQUEST['rating-'.$n] < 0 or $_REQUEST['rating-'.$n] > 1) {
		header("Location: ./");
		exit(4+$n);
	}
}
$secs_alive = date("U") - mktime(12,0,0,$_REQUEST['month'],$_REQUEST['day'],$_REQUEST['year']);
$a = (float)$_REQUEST['rating-1'];
$r = (float)$_REQUEST['rating-2'];
$g = (float)$_REQUEST['rating-3'];
$b = (float)$_REQUEST['rating-4'];

$sql = "INSERT INTO `projectprime` (`secsalive`,`time_completed`,`chapter1`,`chapter2`,`chapter3`,`chapter4`,`comment`) VALUES ($secs_alive,UNIX_TIMESTAMP(NOW()),$a,$r,$g,$b,'".$_REQUEST['comment']."');";
mysql_query($sql);
$id = mysql_insert_id();
$colours = array(round(255 * $r),round(255 * $g),round(255 * $b));

setcookie("ProjectPrimeParticipant[colour]",join($colours,","));
setcookie("ProjectPrimeParticipant[alpha]",(string)$a);
setcookie("ProjectPrimeParticipant[number]",(string)$id);
echo json_encode(array('number'=>$id,'colour'=>$colours,'alpha'=>$a));

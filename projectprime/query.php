<?php
include('db.php');

// Will give a randomly chosen opinion (with id) or a requested ID's details if asked.
if (isset($_REQUEST['n']) and is_numeric($_REQUEST['n'])) {
	$sql = "SELECT `userid`,`chapter1`,`chapter2`,`chapter3`,`chapter4`,`comment` FROM `projectprime` WHERE `id` = {$_REQUEST['n']} LIMIT 1";
	$res = mysql_query($sql);
	
	if (mysql_num_rows($res) < 1) {
		header("HTTP/1.1 404 Not found");
		exit();
	}
} else {
	$sql = "SELECT `userid`,`chapter1`,`chapter2`,`chapter3`,`chapter4`,`comment`,`secsalive`,`time_completed` FROM `projectprime` WHERE 1 ORDER BY rand() LIMIT 1";
	$res = mysql_query($sql);
}

$data = mysql_fetch_array($res);


echo json_encode(array(
	"number" => (int)$data[0],
	"colour" => array((float)$data[2],(float)$data[3],(float)$data[4]),
	"alpha"  => (float)$data[1],
	"comment"=> trim($data[5]),
	"years" => round($data[6] / 31557600,5),
	"completed" => date("F jS",$data[7])
));

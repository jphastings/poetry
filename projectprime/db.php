<?php
if (!@mysql_connect("kedakai.co.uk","kedakaic_pprime","3tgvD@@k2%NX")) {
	die("Technical Difficulties! Please come back soon.");
}
if (!@mysql_select_db("kedakaic_miniProjects")) {
	die("Can't connect to database");
}

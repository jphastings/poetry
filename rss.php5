<?php
date_default_timezone_set('GMT');
$dir = "poems/";
$poems = array();
$i=0;
if (!isset($_GET['others']) and !isset($_GET['author'])) {
	$_GET['author'] = "Cy Densham";
}
if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		$path = pathinfo($file);
        $path['filename'] = substr($path['basename'],0,-5);
		if ($path['extension'] == "poem") {
			$poem = file_get_contents("poems/{$path['basename']}");
			$pos = strpos($poem,"\n");
			list($title,$author,$align,$date) = explode(" - ",substr($poem,0,$pos));
			$date = strtotime(trim($date,"\n\r)("));
			$poem = substr($poem,$pos+1);
			$pre = false;
			if (stristr($align,"x")) {
				$pre = true;
				$align = str_replace("x","|",$align);
			}
			switch(trim($align)) {
				case "__|":
					$align = "right";
					break;
				case "_|_":
					$align = "center";
					break;
				case "|__":
				default:
					$align = "left";
			}
			
			if (empty($_GET['author']) or $_GET['author'] == $author) {
				$poems[($date > 0 ? $date + $i : filemtime("poems/{$path['basename']}")+$i)] = array("title"=>$title,"author"=>$author,"poem"=>$poem,"align"=>$align,"link"=>$path['filename'],'pre'=>$pre);
				$i++;
			}
		}
	}
	closedir($handle);
}

krsort($poems);

// Create RSS

header("Content-type: text/xml; charset=utf-8");

$feed = simplexml_load_string("<?xml version=\"1.0\" encoding=\"utf-8\"?><rss version=\"2.0\"/>");

$channel = $feed->addChild("channel");
if (empty($_GET['author'])) {
	$channel->addChild("title","Poetry from kedakai");
} else {
	$channel->addChild("title","Poetry by ".$_GET['author']);
}
	$channel->addChild("description","Poems and short stories selected by the minds at kedakai.co.uk");
$channel->addChild("link","http://poetry.kedakai.co.uk");
$channel->addChild("pubDate",date("r"));
$channel->addChild("generator","Kedakai");

foreach($poems as $date=>$poem) {
	$item = $channel->addChild("item");
	$item->addChild("title",$poem['title'].((empty($_GET['author'])) ? " [{$poem['author']}]" : ''));
	$item->addChild("author",$poem['author']);
	$item->addChild("description",(($poem['align'] == "center") ? "<center>" : "").(($poem['pre']) ? "<pre>".str_replace("&","&amp;",$poem['poem'])."</pre>" : nl2br(str_replace("&","&amp;",$poem['poem']))).(($poem['align'] == "center") ? "</center>" : "")."<br/><br/><a href=\"http://poetry.kedakai.co.uk/poem:{$poem['link']}/?comments\">Comments</a>");
	$item->addChild("link","http://poetry.kedakai.co.uk/poem:{$poem['link']}/");
	$item->addChild("pubDate",date("r",$date));
	$guid = $item->addChild("guid","http://poetry.kedakai.co.uk/poem:{$poem['link']}/");
	//$guid->addAttribute("isPermalink","true");
}

echo $feed->asXML();
?>

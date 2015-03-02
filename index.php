<?php
date_default_timezone_set("UTC");
if (!isset($_GET['others']) and !isset($_GET['author'])) {
	$_GET['author'] = "Densham";
}
$page = array('body align' => "left");
$notice = true;
$comments = false;
if (file_exists("poems/{$_GET['poem']}.poem")) {
	$comments = true;
	$notice = false;
	$poem = file_get_contents("poems/{$_GET['poem']}.poem");
	$pos = strpos($poem,"\n");
	list($title,$author,$align,$date) = explode(" - ",substr($poem,0,$pos));
	$date = strtotime(trim($date,"\n\r)("));
	$date = (($date > 0) ? $date : filemtime("poems/{$_GET['poem']}.poem"))*1000;
	
	$poem = substr($poem,$pos+1);
	$externalurl = false;
	if (preg_match("/rel=\"external\ piece\"\ href=\"(.+)\"/",$poem,$out)) {
		$externalurl = $out[1];
	}
	$pre = false;
	if (stristr($align,"x")) {
		$pre = true;
		$align = str_replace("x","|",$align);
	}
	switch(trim($align)) {
		case "__|":
			$page['body align'] = "right";
			break;
		case "_|_":
			$page['body align'] = "center";
			break;
		case "|__":
		default:
			$page['body align'] = "left";
	}
	$name = explode(" ",$author);
	$page['title'] = "$title [$author]";
	$page['heading'] = "<strong>$title</strong><br/><a style=\"font-size:small;font-style:italic;\" href=\"/authors/".end($name)."/\">$author</a>";
	$page['body'] = nl2br($poem);
	if ($pre) {
		$page['body'] = "<pre>{$page['body']}</pre>";
	}
	$page['extra'] = "<div id=\"title\"><br/><a href=\"".($author == "Cy Densham" ? "/authors/Densham/" : "/")."\" style=\"font-size:small;\">back</a></div>\n\n<!-- ".date("r",filemtime("poems/{$_GET['poem']}.poem"))." -->\n\n";
} else {
	$date = date('U')*1000;
	$_GET['author'] = trim($_GET['author'],"/");
	$dir = "poems/";
	$poems = array();
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			$path = pathinfo($file);
            $path['filename'] = substr($path['basename'],0,-5);
			if ($path['extension'] == "poem") {
				$poem = file_get_contents("poems/{$path['basename']}");
				$pos = strpos($poem,"\n");
				list($title,$author) = explode(" - ",substr($poem,0,$pos));
				$name = explode(" ",$author);
				if (strtolower(end($name)) == strtolower($_GET['author']) && !$oneauthor) {
					$oneauthor = $author;
				}
				$poems[$author][$title] = $path['filename'];
			}
		}
		closedir($handle);
	}
	ksort($poems);
	
	$page['title'] = "Selected Poetry";
	if (!empty($oneauthor)) {
		$author = $oneauthor;
		$page['title'] .= " by $author";
		$poems = array($author => $poems[$author]);
	}
	$page['heading'] = "<strong>{$page['title']}</strong>";
	
	$page['body'] = "<ul style=\"list-style:none;\">";
	foreach ($poems as $author=>$a_poems) {
		$page['body'] .= "<li>".(($author == "Cy Densham" and empty($oneauthor)) ? "<a href=\"http://jphastings.tumblr.com\" style=\"color:#000;\" taget=\"_new\" title=\"This is my own work, under my exciting pen name.\">Cy Densham</a> <small style=\"color:#999;font-size:11px;\">(me!)</small>" : $author )."<ul>";

        ksort($a_poems);
		foreach($a_poems as $title=>$poemfile) {
			$page['body'] .= "<li><a href=\"/poems/$poemfile\">$title</a></li>";
		}
		
		$page['body'] .= "</ul></li>";
	}
	$page['body'] .= "</ul>";
	if (!empty($oneauthor)) {
		$page['body'] .= "<div id=\"title\"><a href=\"/others/\" style=\"font-size:small;\">other authors</a></div>";
	}
}
?>
<?="<"?>?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="title" content="<?=$page['title']?>" />
	<meta name="description" content="Poetry<?=((!empty($author)) ? " by {$author}" : "")?>" />
	<?php if ($externalurl != false) { ?>
	<meta http-equiv="REFRESH" content="0; url=<?=$externalurl?>">
	<?php } ?>
	<link rel="alternate" type="application/rss+xml" title="Poetry Feed" href="/feed<?=($author == "Cy Densham" ?'?author=Cy%20Densham' :'/')?>" />
    <title><?=$page['title']?></title>
    <style>
	
    body {
        margin: 20px 0 45px 0;
        width:100%;
    }
    
    #poem {
        max-width:400px;
        margin-left:auto;
        margin-right:auto;
        font-family:georgia;
		text-align:<?=$page['body align']?>;
		font-size:14px;
		line-height:1.25;
        padding: 0 25px 0 25px;
    }

	pre {
		line-height:0.5;
		font-family:monaco, courier, monospace;
	}

    #title {
		font-family:trebuchet ms;
        text-align:center;
        margin-bottom:15px;
		font-size:24px;
    }

	ul {
		font-family: tahoma;
	}
	
	ul ul {
		margin-bottom:10px;
	}
	
	a {
		text-decoration: none;
		color: #22ac94;
	}
	
	a:hover {
		text-decoration: underline;
	}
	
	#licence {
		position:fixed;
		bottom:2px;
		text-align:center;
		width:100%;
	}
	
	#licence a {
		padding:4px;
		background-color:#fff;
		border:1px solid #f8f8f8;
	}
	
	#notice {
		text-align:center;
		font-size:11px;
		font-style:italic;
		font-family:tahoma;
		color:#999;
		<?=(($notice) ? "" : "display:none;")?>
	}
	
	#flattr {
		position:fixed;
		bottom:0;
		right:0;
	}
	
	#tictoc {
		opacity:0.2;
		display:block;
	}
	
	#tictoc:hover {
		opacity: 1.0;
	}
	
	#tictoc img {
		position: absolute;
		left:1px;
		bottom:1px;
		width:22px;
		height:21px;
		border:0;
	}
    </style>
<?php if ($author == "Cy Densham") { ?>
<!--	<script>
	var flattr_uid = '1171';
	var flattr_tle = '<?=$page['title']?>';
	var flattr_dsc = 'A poem by Cy Densham';
	var flattr_cat = 'text';
	var flattr_tag = 'poetry';
	var flattr_url = 'http://poetry.byJP.me/poems/<?=$_GET['poem']?>';
	</script>-->
<?php } ?>
</head>
<body>
<div id="poem">
	<div id="title"><?=$page['heading']?></div>
	<?=$page['body']?>
	<?=$page['extra']?>
</div>
<?php if ($author == "Cy Densham") { ?>
	<div id="notice">These works are available under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons licence</a>.</div>
	<div id="licence"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/" title="Licenced inder Create Commons Attribution, Noncommercial, Share Alike 3.0"><img alt="Creative Commons" style="border-width:0" src="/cc.png" /> <img alt="Attribution" style="border-width:0" src="/by.png" /> <img alt="Non-Commercial" style="border-width:0" src="/nc.png" /> <img alt="Share-Alike" style="border-width:0" src="/sa.png" /></a></div>
	<a href="http://tictoc.byJP.me/#<?=$date?>" id="tictoc" target="time" title="<?=date('r',$date/1000)?>"><img src="http://tictoc.byJP.me/<?=$date?>.png"/></a>
<!--	<div id="flattr">
		<script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>
	</div>-->
<?php } else { ?>
	<div id="notice">This is just a selection of poetry, if you like what you read, please support the authors by buying their works.</div>
<?php } ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8873620-1");
pageTracker._trackPageview();
} catch(err) {}
</script>
</body>
</html>

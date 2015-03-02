<?php
include('db.php');
$sql = "SELECT `userid`,`chapter1`,`chapter2`,`chapter3`,`chapter4`,`comment` FROM `projectprime` WHERE `blush` = 0 AND `comment` != '' ORDER BY rand()";
$res = mysql_query($sql);
$data = mysql_fetch_assoc($res);
$sql = "SELECT * FROM `projectprime` WHERE 1";
$res = mysql_query($sql);
$num = mysql_num_rows($res);
$c = array(round(255 * $data['chapter2']),round(255 * $data['chapter3']),round(255 * $data['chapter4']));
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Project Prime</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-language" content="en" />
		<meta name="title" content="Project Prime" />
		<meta name="description" content=" A collaborative art project, based on your opinions of my poetry - Cy Densham." />
		<link rel="stylesheet" rev="stylesheet" href="quizical.css" media="screen">
		<?php if (!isset($_COOKIE['ProjectPrimeParticipant'])) { ?>
		<script type="text/javascript" src="mootools.js"></script>
		<script src="quizical.js"></script>
		<?php } ?>
		<style>
		#sofar {
			border-color:rgb(<?=join($c,",")?>);
		}
		</style>
	</head>
	<body>
		<div id="content">
			<h1>Project Prime</h1>
			<?php if (!isset($_COOKIE['ProjectPrimeParticipant'])) { ?>
			<form id="questions" action="receiver.php" method="post">
				<p>Hello &ndash; I'm <a href="http://twitter.com/CyDensham" target="twitter" title="My infrequent tweets">Cy Densham</a> and I'm trying to create some collaborative art, would you join in?</p>
				
				<h2>About Me</h2>
				<p>I'm trying to build a piece of art based on <em>your</em> opinions of my writing. I'd like you to answer five questions for me; one of them is about you, the other four are about your opinions of a piece of my work.</p>
				
				<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { ?>
				<p class="ie"><strong>You appear to be using Internet Explorer</strong><br/>Unfortunately I just don't have the time to make this page function fully within IE's picky tastes. I can't recommend strongly enough that you move to a more compliant browser, why not try <a href="http://getfirefox.com" target="newbrowser">Firefox</a>, <a href="http://www.google.co.uk/chrome" target="newbrowser">Chrome</a>, <a href="http://www.apple.com/safari/download/" target="newbrowser">Safari</a> or <a href="http://www.opera.com/download/" target="newbrowser">Opera</a>?</p>
				<?php } ?>
				<p>Right! Here we go - please be <strong>honest</strong> it'll make for a more interesting end result!</p>
				<h2>About You</h2>
				<p class="important">Your information is private and anonymous. Even I won't know who you are!</p>
		
				<h3>When were you born?</h3>
				<p class="why">A part of the piece will be determined by the number of seconds its been since you were born.</p>
				<div class="input">
					<label name="year">y</label> <input name="year" id="year" style="width:4em" onchange="check_year()" onkeyup="check_year()"/>
					<label name="month">m</label>
					<select name="month" id="month" onchange="check_month()">
						<option value="0" default="true" style="background-color:#ffe0e0;"></option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">Movember</option><option value="12">December</option>
					</select>
					<label name="day">d</label> <input name="day" id="day" style="width:2em" onchange="check_day()" onkeyup="check_day()"/>
				</div>
		
				<h2>About The Haiku</h2>
				<p class="important">So now you need to go ahead and <a href="http://poetry.kedakai.co.uk/poem:chapteri/" target="poem" title="Starting with Chapter I">read all four haiku</a>.</p>
				<p class="why">Hopefully we'll see people from different age groups enjoying different haiku to different extents. This will be the main body of the collaborative piece!</p>
			
				<h3><a href="http://poetry.kedakai.co.uk/poem:chapteri/" target="poem" title="Chapter I">Chapter I</a></h3>
				<p class="why">Use the slider to choose whether you liked it, or didn't. There's no percentage, or stars &ndash; just put the slider where you think it belongs.</p>
				<div class="input" style="padding:1em 0;"><div id="1-slide" class="slider"><div class="knob"></div></div></div>
				<input type="hidden" id="1-value" name="rating-1" />
			
				<h3><a href="http://poetry.kedakai.co.uk/poem:chapterii/" target="poem" title="Chapter II">Chapter II</a></h3>
				<p class="why">What do you think of the second chapter? Better or worse?</p>
				<div class="input" style="padding:1em 0;"><div id="2-slide" class="slider"><div class="knob"></div></div></div>
				<input type="hidden" id="2-value" name="rating-2" />
				
				<h3><a href="http://poetry.kedakai.co.uk/poem:chapteriii/" target="poem" title="Chapter III">Chapter III</a></h3>
				<div class="input" style="padding:1em 0;"><div id="3-slide" class="slider"><div class="knob"></div></div></div>
				<input type="hidden" id="3-value" name="rating-3" />
			
				<h3><a href="http://poetry.kedakai.co.uk/poem:chapteriv/" target="poem" title="Chapter IV">Chapter IV</a></h3>
				<p class="why">Conclude your part in this collaborative piece - what did you think of Chapter IV?</p>
				<div class="input" style="padding:1em 0;"><div id="4-slide" class="slider"><div class="knob"></div></div></div>
				<input type="hidden" id="4-value" name="rating-4" />
			
				<h2>About Time!</h2>
				<p>Almost there &ndash; all you need to do is add a (public, though anonymous) comment, if you'd like, and click below when you're ready. Please only complete this questionnaire once!</p>
				
				<h3>Any thoughts?</h3>
				<p class="why">Say something to future viewers of our work, anything! You could even write a haiku :P</p>
				
				<div class="input"><input name="comment" id="comment" style="position:relative;width:92%;color:#666;font-size:1.0em;padding:0.5em 2em 0.5em 0.5em" maxlength="80" onkeyup="counter()"><div id="counter">80</div></input></div>
				
				<div class="input"><input type="submit" id="submit" value="Please Complete the Questions!" disabled="true"/></div>
			</form>
			<div id="thanks">
			<?php } else { ?>
				<style>
				#clue {
					border-color:rgb(<?=$_COOKIE['ProjectPrimeParticipant']['colour']?>);
				}
				</style>
			<div id="thanks2">
			<?php } ?>
			
				<h2>Thanking You</h2>
				<p>Thank you! Your opinions have been saved and we're one step closer to making something rather funky.</p>
				<p>If you'd like to be emailed when the piece is ready then please send an email to <a href="mailto:projectprime@poetry.kedakai.co.uk"><strong>projectprime</strong>@<em>poetry.kedakai</em>.co.uk</a>.</p>
				<p id="clue">You are number <strong id="number"><?=$_COOKIE['ProjectPrimeParticipant']['number']?></strong></p>
				<p class="why">Remember this number if you'd like to be able to find your contribution to the piece later!</p>
				
				<p>If you enjoyed this, please consider spreading the word (you can use the facebook button at the top right) - the more the merrier!</p>
				<p>If all this interactivity and poetry has you ready for more, you may enjoy <a href="http://poetry.kedakai.co.uk/helix" target="poem" title="Helix: puzzle or poem?">helix</a> &ndash; Can you figure out how to read it?</p>
				<p>Be seeing you,<br/><em>Cyrus R. Densham</em></p>
				<br/>
			</div>
		</div>
		<div id="share">
			<a name="fb_share" type="box_count" share_url="http://poetry.kedakai.co.uk/projectprime/" href="http://www.facebook.com/sharer.php">Share</a>
			<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
		</div>
		<div id="sofarcont">
			<span id="sofar">
				<strong><?=$data['userid']?></strong>/<?=$num?> <em class="quote"><?=$data['comment']?></em>
			</span>
		</div>
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try{
		var pageTracker = _gat._getTracker("UA-8873620-1");
		<?php if (!isset($_COOKIE['ProjectPrimeParticipant'])) { ?>
		pageTracker._trackPageview('unanswered');
		<?php } else { ?>
		pageTracker._trackPageview('answered');
		<?php } ?>
		} catch(err) {}
		</script>
	</body>
</html>
		

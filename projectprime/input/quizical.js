// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults

var checks = {
	'year':false,
	'month':false,
	'day':false,
	'slider1':false,
	'slider2':false,
	'slider3':false,
	'slider4':false
}

var ready = false;

function check_day() {
	el = $('day')
	checks['day'] = (el.value > 0 & el.value <= 31)
	el.setStyle('background-color',((checks['day']) ? [250,250,255] : [255,224,224]))
	check_done();
}

function check_month() {
	el = $('month')
	checks['month'] = (el.value > 0 & el.value <= 12)
	el.setStyle('background-color',((checks['month']) ? [250,250,255] : [255,224,224]))
	check_done();
}

function check_year() {
	el = $('year')
	checks['year'] = (el.value > 1890 & el.value <= 2003)
	el.setStyle('background-color',((checks['year']) ? [250,250,255] : [255,224,224]))
	check_done();
}

function check_done() {
	ready = true;
	for (var el in checks) {
		ready = ready & checks[el]
	}
	if (ready) {
		$('submit').disabled = false
		$('submit').value = "I'm done!"
		$('submit').setStyle('background-color',[250,250,255])
	} else {
		$('submit').disabled = true
		$('submit').value = "Please complete the questions!"
		$('submit').setStyle('background-color',[255,224,224])
	}
}

function counter() {
	$('counter').set('text',80 - $('comment').value.length)
	$('counter').setStyle('color',[(Math.round(($('comment').value.length - 40)/5)*32 - 1),0,0])
}

function setSlider(n,value) {
	$(n+'-value').value = value/1000;
	var r = (1000-value)/500;
	if (r > 1) { r = 1; }
	var g = value/500;
	if (g > 1) { g = 1; }
	var b = value/500;
	if (b > 1) { b = 2 - b; }
	$(n+'-slide').setStyle('background-color', [Math.round(r*55 + 200),Math.round(g*55 + 200),Math.round(b*55 + 200)]);
	
	el = $$('#'+n+'-slide .knob')[0]
	if (value < 500) {
		el.set('text',':(')
	} else if (value > 500) {
		el.set('text',':)')
	} else {
		el.set('text','')
	}
}

window.addEvent('domready', function(){
	new Fx.Slide('thanks').hide()
	check_year();
	check_month();
	check_day();
	counter();
	
	window.addEvent('keydown',function(event) {
		if (event.key == 'enter') {
			event.stop();
		}
	})
	
	$('questions').addEvent('submit', function(e) {
		e.stop();
		$('submit').disabled = true
		$('submit').value = "sending..."
		this.set('send', {
			onComplete: function(response) { 
				res = JSON.decode(response)
				$('clue').setStyle('border-color',res['colour'])
				$('number').set('text',res['number'])
				new Fx.Scroll(document.body).toTop().chain(
					function(){
						new Fx.Slide('questions').slideOut()
						new Fx.Slide('thanks').slideIn();
					}
				);
				pageTracker._trackPageview('quizanswered');
			}
		});
		this.send();
	});
	
	new Slider($('1-slide'), $('1-slide').getElement('.knob'), {
		steps: 1000,
		range: [0,1000],
		onChange: function(value){setSlider(1,value);checks['slider1'] = (value != 500);check_done();}
	}).set(500)
	
	new Slider($('2-slide'), $('2-slide').getElement('.knob'), {
		steps: 1000,
		range: [0,1000],
		onChange: function(value){setSlider(2,value);checks['slider2'] = (value != 500);check_done();}
	}).set(500)
	
	new Slider($('3-slide'), $('3-slide').getElement('.knob'), {
		steps: 1000,
		range: [0,1000],
		onChange: function(value){setSlider(3,value);checks['slider3'] = (value != 500);check_done();}
	}).set(500)
	
	new Slider($('4-slide'), $('4-slide').getElement('.knob'), {
		steps: 1000,
		range: [0,1000],
		onChange: function(value){setSlider(4,value);checks['slider4'] = (value != 500);check_done();}
	}).set(500)
})
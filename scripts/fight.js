function fight(result) {
	let health_hero = health1;
	let health_enemy = health2;
	let i = 1;

	function getMinDmg(opponent){
		let min_dmg;

		if(opponent == 'hero'){
			min_dmg = result[1];
			for(let i = 1; i <= result.length; i+=2){
				if(result[i] < min_dmg) min_dmg = result[i];
			}
		}
		else if(opponent == 'monster'){
			min_dmg = result[2];
			for(let i = 2; i <= result.length; i+=2){
				if(result[i] < min_dmg) min_dmg = result[i];
			}
		}

		return min_dmg;
	}

	alert(getMinDmg('monster'));

	$("#exit").fadeOut();

	function finish(winner) {
		if (result[0]) {
			$('#won_gold').html(result['gold']);
			$('#won_exp').html(result['exp']);
			$('[data-remodal-id=modal_won]').remodal().open();
		}
		else {
			$('[data-remodal-id=modal_defeated]').remodal().open();
		}
		$("#exit").fadeIn();
	}

	function slashHero(weapon) {

		$("#" + weapon).position({
			my: "center center",
			at: "center center",
			of: "#monster"
		});

		setTimeout(function() {
			$("#" + weapon).css("visibility", "visible");
			$("#" + weapon).css("opacity", "1");
			$("#" + weapon).position({
				my: "bottom bottom",
				at: "center center",
				of: ".main__window",
				using: function(css, calc) {
					$(this).animate(css, 300, "easeInSine");
				}
			});
			$("#" + weapon).css('transform', 'rotate(-120deg)');
		}, 10);


		setTimeout(function() {
			$("#" + weapon).position({
				my: "center center",
				at: "center center",
				of: "#hero",
				using: function(css, calc) {
					$(this).animate(css, 300, "easeOutCubic");
				}
			});;
		}, 300);

		setTimeout(function() {
			$("#" + weapon).css("opacity", "0");
			resolveParticules('hero');
		}, 400);

		setTimeout(function() {
			$("#" + weapon).css("visibility", "hidden");
			$("#" + weapon).css('transform', 'rotate(-90deg)');
		}, 1000);
	}

	function slashEnemy(weapon) {

		$("#" + weapon).position({
			my: "center center",
			at: "center center",
			of: "#hero"
		});

		setTimeout(function() {
			$("#" + weapon).css("visibility", "visible");
			$("#" + weapon).css("opacity", "1");
			$("#" + weapon).position({
				my: "bottom bottom",
				at: "center center",
				of: ".main__window",
				using: function(css, calc) {
					$(this).animate(css, 300, "easeInSine");
				}
			});
			$("#" + weapon).css('transform', 'rotate(30deg)');
		}, 10);


		setTimeout(function() {
			$("#" + weapon).position({
				my: "center center",
				at: "center center",
				of: "#monster",
				using: function(css, calc) {
					$(this).animate(css, 300, "easeOutCubic");
				}
			});;
		}, 300);

		setTimeout(function() {
			$("#" + weapon).css("opacity", "0");
			resolveParticules('monster');
		}, 400);

		setTimeout(function() {
			$("#" + weapon).css("visibility", "hidden");
			$("#" + weapon).css('transform', 'rotate(-60deg)');
		}, 1000);
	}

	function hitEnemy() {
		slashEnemy("weapon-1");

		health_enemy -= result[i];
		let min_dmg = getMinDmg('hero');

		if(result[i] > 0){
			if((min_dmg * 2) <= result[i] * 2){
				setTimeout(function(){$("#monster").effect("highlight", { color: "#bb2200" }, 500);}, 400);
			}
			$("#progress-enemy").val(health_enemy);
			$("#progress-enemy").attr('data-value', health_enemy + " / " + health2);
			$('.fireworks_monster').fadeIn();
			setTimeout(function() {
				$('.fireworks_monster').fadeOut();
			}, 1000);
		}
		else {
		$('.fireworks_monster').fadeOut();
		setTimeout(function() {
			$('.fireworks_monster').fadeIn();
		}, 1000);
		$("#monster img").effect("drop", {direction: 'right'}, 400, function(){
			$("#monster img").fadeIn("fast");
		});
		}
	}

	function hitHero() {
		slashHero("weapon-2");

		health_hero -= result[i];
		let min_dmg = getMinDmg('monster');

		if(result[i] > 0){
			if((min_dmg * 2) <= result[i]){
				setTimeout(function(){$("#hero").effect("highlight", { color: "#bb2200" }, 500);}, 400);
			}
			$("#progress-hero").val(health_hero);
			$("#progress-hero").attr('data-value', health_hero + " / " + health1);
			$('.fireworks_hero').fadeIn();
			setTimeout(function() {
				$('.fireworks_hero').fadeOut();
			}, 1000);
		}
		else {
		$('.fireworks_hero').fadeOut();
		setTimeout(function() {
			$('.fireworks_hero').fadeIn();
		}, 1000);
		$("#hero img").effect("drop", 400, function(){
			$("#hero img").fadeIn("fast");
		});
		}
	}

	function hitting1() {
		setTimeout(function() {
			hitEnemy();
			i++;
			if (health_enemy > 0 && health_hero > 0) hitting2();
			else setTimeout(function(){finish();}, 1000);
		}, 1500) //czas rundy
	}

	function hitting2() {
		setTimeout(function() {
			hitHero();
			i++;
			if (health_enemy > 0 && health_hero > 0) hitting1();
			else setTimeout(function(){finish();}, 1000);
		}, 1500) //czas rundy
	}

	hitting1();
}

function progressInit() {
	$("#progress-hero").attr("max", health1);
	$("#progress-hero").val(health1);
	$("#progress-hero").attr('data-value', health1 + " / " + health1);

	$("#progress-enemy").attr("max", health2);
	$("#progress-enemy").val(health2);
	$("#progress-enemy").attr('data-value', health2 + " / " + health2);

	$(".fight__block").css("justify-content", "space-around");
}

$("#start").on("click", function() {
	const object = $(this);
	$.post({
        url : 'fight',
				dataType: 'json',
    })
    .done(result => {
			console.log(result);
			progressInit();
			object.fadeOut("fast");
			$(".fight__block__stats").fadeOut("slow");
			$(".fight__block progress").fadeIn("slow");

			fight(result);
    });
});

window.human = false;

var canvasHero = document.querySelector('.fireworks_hero');
var canvasMonster = document.querySelector('.fireworks_monster');
var ctx = canvasHero.getContext('2d');
var numberOfParticules = 30;
var pointerX = 0;
var pointerY = 0;
var colors = ['#FF0000', '#FFaa00', '#aa1100', '#FBF38C'];

function setCanvasSizeHero() {
  canvasHero.width = $("#hero").width() + 150;
  canvasHero.height = $("#hero").height() + 150;
  canvasHero.style.width = $("#hero").width() + 150 + 'px';
  canvasHero.style.height = $("#hero").height() + 150 + 'px';
  canvasHero.getContext('2d').scale(2, 2);
}

function setCanvasSizeMonster() {
  canvasMonster.width = $("#hero").width() + 150;
  canvasMonster.height = $("#hero").height() + 150;
  canvasMonster.style.width = $("#hero").width() + 150 + 'px';
  canvasMonster.style.height = $("#hero").height() + 150 + 'px';
  canvasMonster.getContext('2d').scale(2, 2);
}

function setParticuleDirection(p) {
  var angle = anime.random(0, 360) * Math.PI / 180;
  var value = anime.random(30, 200);
  var radius = [-1, 1][anime.random(0, 1)] * value;
  return {
    x: p.x + radius * Math.cos(angle),
    y: p.y + radius * Math.sin(angle)
  }
}

function createParticule(x,y) {
  var p = {};
  p.x = x;
  p.y = y;
  p.color = colors[anime.random(0, colors.length - 1)];
  p.radius = anime.random(3, 12);
  p.endPos = setParticuleDirection(p);
  p.draw = function() {
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
    ctx.fillStyle = p.color;
    ctx.fill();
  }
  return p;
}

function createCircle(x,y) {
  var p = {};
  p.x = x;
  p.y = y;
  p.color = '#FFF';
  p.radius = 0.05;
  p.alpha = .2;
  p.lineWidth = 2;
  p.draw = function() {
    ctx.globalAlpha = p.alpha;
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
    ctx.lineWidth = p.lineWidth;
    ctx.strokeStyle = p.color;
    ctx.stroke();
    ctx.globalAlpha = 1;
  }
  return p;
}

function renderParticule(anim) {
  for (var i = 0; i < anim.animatables.length; i++) {
    anim.animatables[i].target.draw();
  }
}

function animateParticules(x, y) {
  var circle = createCircle(x, y);
  var particules = [];
  for (var i = 0; i < numberOfParticules; i++) {
    particules.push(createParticule(x, y));
  }
  anime.timeline().add({
    targets: particules,
    x: function(p) { return p.endPos.x; },
    y: function(p) { return p.endPos.y; },
    radius: 0.5,
    duration: anime.random(1000, 1500),
    easing: 'easeOutExpo',
    update: renderParticule
  })
    .add({
    targets: circle,
    radius: anime.random(80, 300),
    lineWidth: 0,
    alpha: {
      value: 0,
      easing: 'linear',
      duration: anime.random(600, 800),
    },
    duration: anime.random(1200, 1800),
    easing: 'easeOutExpo',
    update: renderParticule,
    offset: 0
  });
}

var render = anime({
  duration: Infinity,
  update: function() {
    ctx.clearRect(0, 0, canvasHero.width, canvasHero.height);
  }
});

function resolveParticules(object){

	if(object == 'hero') ctx = canvasHero.getContext('2d');
	else ctx = canvasMonster.getContext('2d');

	object = $('#' + object);
  window.human = true;
  render.play();
	x = (object.width() + 150) / 4;
	y = (object.height() + 150) / 4;
  animateParticules(x, y);
}

var centerX = window.innerWidth / 2;
var centerY = window.innerHeight / 2;

function autoClick() {
  if (window.human) return;
  animateParticules(
    anime.random(centerX-50, centerX+50),
    anime.random(centerY-50, centerY+50)
  );
  anime({duration: 200}).finished;
}

autoClick();
setCanvasSizeHero();
setCanvasSizeMonster();
window.addEventListener('resize', setCanvasSizeHero, false);
window.addEventListener('resize', setCanvasSizeMonster, false);

function fight(result) {
	let health_hero = health1;
	let health_enemy = health2;
	let i = 1;

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
		}, 400);

		setTimeout(function() {
			$("#" + weapon).css("visibility", "hidden");
			$("#" + weapon).css('transform', 'rotate(-60deg)');
		}, 1000);
	}

	function hitEnemy() {
		slashEnemy("weapon-1");

		health_enemy -= result[i];

		if(result[i] > 0){
			$("#progress-enemy").val(health_enemy);
			$("#progress-enemy").attr('data-value', health_enemy + " / " + health2);
		}
		else {
		$("#monster img").effect("drop", {direction: 'right'}, 400, function(){
			$("#monster img").fadeIn("fast");
		});
		}
	}

	function hitHero() {
		slashHero("weapon-2");

		health_hero -= result[i];

		if(result[i] > 0){
			$("#progress-hero").val(health_hero);
			$("#progress-hero").attr('data-value', health_hero + " / " + health1);
		}
		else {
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
			else finish();
		}, 1500) //czas rundy
	}

	function hitting2() {
		setTimeout(function() {
			hitHero();
			i++;
			if (health_enemy > 0 && health_hero > 0) hitting1();
			else finish();
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

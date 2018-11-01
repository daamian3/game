function getMoney(){
	$.post({
				url : 'get_money',
				dataType : 'json',
		})
		.done(result => {
				$('#hero-gold').html(result['gold']);
				$('#hero-silver').html(result['silver']);
				$('#hero-bronze').html(result['bronze']);
				if(result['gold'] == false) $('#hero-img-gold').fadeOut();
				if(result['silver'] == false) $('#hero-img-silver').fadeOut();
		});
}

function getStats(){
	$.post({
				url : 'get_stats',
				dataType : 'json',
		})
		.done(result => {
				$('#hero__health').html(result['health']);
				$('#hero__defense').html(result['defense']);
				$('#hero__attack').html(result['attack_min'] + '-' + result['attack_max']);
				$('#hero__critical').html(result['critical'] + '%');
				$('#hero__miss').html(result['miss'] + '%');
				$('#hero__vitality').html(result['vitality']);
				$('#hero__strength').html(result['strength']);
				$('#hero__intelligence').html(result['intelligence']);
				$('#hero__agility').html(result['agility']);
				$('#hero__luck').html(result['luck']);
		});
}

function getAttribCost(id){
	$.post({
				url : 'attrib_cost',
				data: { attrib: id },
				dataType : 'json',
		})
		.done(result => {
			if(!result['silver']) result['silver'] = '0';
			if(!result['gold']) result['gold'] = '0';

			$('#attributes-bronze-' + id).html(result['bronze']);
			$('#attributes-silver-' + id).html(result['silver']);
			$('#attributes-gold-' + id).html(result['gold']);
		});
}

$(document).ready(function() {

	$("#hero__tabs").tabs({ //utworzenie zakladek
		show: {
			effect: "fade",
			duration: "fast",
		}
	});

	$('.hero__items__block img').on('dragstart', function(event) { event.preventDefault(); });

	let noty;

	$("body").on( "click", ".hero__attributes__button", function( event ) {
		const id = this.id;
		$.post({
	        url : 'attrib_up',
					data: { attrib: id },
	    })
	    .done(result => {
				if(result){
					$('#hero__' + id).html(result);
					$('#hero__' + id).animateCss('pulse');
					$('.hero__gold').animateCss('pulse');
					getMoney();
					getStats();
					getAttribCost(id);
				}
				else{
					noty = new Noty({
					    type: 'warning',
					    layout: 'topRight',
					    text: 'Nie posiadasz wystarczające ilości złota!',
							timeout: 3000,
					}).show();
				}
	    });
	});

	$("body").on( "click", "#hero__inventory .hero__items__block img", function( event ) {
			const id = $(this).parent().attr('id').slice(5);

			$.post({
						url : 'hero_equip',
						data: { item: id },
				})
				.done(result => {
					if(result){
						$("#hero__tab1").load("hero__stats");
						$("#hero__tab2").load("hero__eq");
						$("#hero__tab3").load("hero__attrib");
					}
					else{
						noty = new Noty({
								type: 'error',
								layout: 'topRight',
								text: 'Nie udało się, przepraszamy!',
								timeout: 3000,
						}).show();
					}
					console.log(result);
				});
		});

	$("body").on( "click", ".equiped", function( event ) {
		const type = this.id;

		$.post({
	        url : 'hero_unEquip',
					data: { item: type },
	    })
	    .done(result => {
				if(result){
					$("#hero__tab1").load("hero__stats");
					$("#hero__tab2").load("hero__eq");
					$("#hero__tab3").load("hero__attrib");
				}
				else{
					noty = new Noty({
					    type: 'error',
					    layout: 'topRight',
					    text: 'Nie udało się, przepraszamy!',
							timeout: 3000,
					}).show();
				}
	    });
	});
});

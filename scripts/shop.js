var items = 0;

$("body").on( "click", ".shop__items__buy", function( event ) {
  const identify = $(this).val();

  $.post({
				url : 'buy_item',
        data: {id: identify},
        dataType : 'json',
		})
		.done(result => {
      if(result[0])
        new Noty({
        			type: 'warning',
        			layout: 'topRight',
        			text: result[0],
        			timeout: 2000,
        }).show();
      if(result[1])
        new Noty({
        			type: 'warning',
        			layout: 'topRight',
        			text: result[1],
        			timeout: 2000,
        }).show();
      if(!result[0] && !result[1]){
        new Noty({
        			type: 'success',
        			layout: 'topRight',
        			text: 'Przedmiot został zakupiony.',
        			timeout: 2000,
        }).show();
        $('.shop__items__block[data-id='+identify+']').fadeOut();
        items++;
        if(items >= 4) $('.shop__reload').delay(500).fadeIn('slow');
        $(".shop__flex").load('shop__bag');
        getMoney();
      }
    });
});

$("body").on( "click", ".shop__bag__block", function( event ) {
  const identify = $(this).attr('id').slice(5);
  const object = $(this);

  $.post({
				url : 'sell_item',
        data: {id: identify},
        dataType : 'json',
		})
		.done(result => {
        new Noty({
        			type: 'success',
        			layout: 'topRight',
        			text: 'Przedmiot został sprzedany.',
        			timeout: 2000,
        }).show();
        object.fadeOut();
    });

    getMoney();
});

function getMoney(){
	$.post({
				url : 'get_money',
				dataType : 'json',
		})
		.done(result => {
				$('#shop-gold').html(result['gold']);
				$('#shop-silver').html(result['silver']);
				$('#shop-bronze').html(result['bronze']);
				if(result['gold'] == false) $('#shop-img-gold').fadeOut();
        else $('#shop-img-gold').fadeIn();
				if(result['silver'] == false) $('#shop-img-silver').fadeOut();
        else $('#shop-img-silver').fadeIn();
		});
}

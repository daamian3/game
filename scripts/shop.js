$("button").on("click", function(){
  const identify = $(this).val();;
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
        			type: 'warning',
        			layout: 'topRight',
        			text: 'Przedmiot został zakupiony.',
        			timeout: 2000,
        }).show();
        $('.shop__items__block[data-id='+identify+']').fadeOut();
      }
    });
});

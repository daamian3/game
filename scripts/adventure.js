var a = false;

function show_info(id){
  const object = id.slice(-1);

  $('#adventureWelcome').fadeOut(200);
  $('.adventure__info__block').fadeOut(200);

  $('#adventure__info-' + object).delay(400).show("drop", {direction: "up"}, 500);
}

$(document).ready(function() {

  $('.adventure__panel__block').on('click', function(){ show_info(this.id); });

});

$('.adventure_start').on('click', function(){
  $('#adventure__list').submit();
});

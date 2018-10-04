const menu = $(".main__menu");

$(window).on("resize", function() {
		if($(document).width() > 768) menu.css("display", "block");
		else if(menu.css('display') != 'none' && (window.innerHeight > window.innerWidth)) menu.css("display", "block");
		else if($(document).width() < 768 && menu.css('display') != 'none') $("#open").click();
});

function menu_slide(direction){
	if(direction == "up"){
		menu.fadeOut({ duration: 350, queue: false }).slideUp(350);
		return false;
	}
	else if(direction == "down"){
		menu.fadeIn({ duration: 350, queue: false }).css('display', 'none').slideDown(350);
		return true;
	}
}

$("#open").on("click", function(){
	if(menu.css('display') == 'none'){
		menu_slide("down");
		$(this).addClass("exit");
	}
	else{
		$(this).removeClass("exit");
		menu_slide("up");
	}

});

$(".main__menu__button").on("click", function(){
  if($(document).width() < 768)	$("#open").click();
});

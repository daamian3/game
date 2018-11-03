$('.login__forgot_password-link').on("click", function() {
	$(".login").first().fadeOut('500');

  $(".login").eq(1).hide().delay(500).fadeIn();
});

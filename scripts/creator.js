function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}

document.querySelector('#creator__tabs').onkeypress = checkEnter;

function checkPassword(){
    let haslo = document.getElementsByClassName("register__password");
    let vhaslo = document.getElementsByClassName("register__password-check");
    haslo = haslo[0];
    vhaslo = vhaslo[0];

    function check(){
      if(haslo.value===0) vhaslo.classList.remove('bad');
      if(haslo.value!==vhaslo.value && haslo.value!==0){
        vhaslo.classList.remove('good');
        vhaslo.classList.add('bad');
      }
      else if(haslo.value!==0){
          vhaslo.classList.remove('bad');
          vhaslo.classList.add('good');
      }
    }

    vhaslo.addEventListener("keyup", function(){
      check();
    }, false);

    haslo.addEventListener("keyup", function(){
      check();
    }, false);

    const calculateComplexity = function (password) {

        let complexity = 0;

        const regExps = [
            /[a-z]/,
            /[A-Z]/,
            /[0-9]/,
            /.{8}/,
            /[!-//:-@[-`{-Ăż]/
        ];

        regExps.forEach(function (regexp) {

            if (regexp.test(password)) {
                complexity++;
            }
        });

        return {
            value: complexity,
            max: regExps.length
        };
    };

    const checkPasswordStregth = function (password) {

        const progress = document.querySelector('.register__progress'),
            complexity = calculateComplexity(this.value);

        progress.value = complexity.value;
        progress.max = complexity.max;

    };

    haslo.addEventListener('keyup', checkPasswordStregth);
}

function checkReg(){

    const inputs = $('.register input');
    const submit = $('#submit__reg');

    function check(inputs){
        return inputs[0].value == "" ||
            inputs[1].value == "" ||
            inputs[2].value == "" ||
            inputs[3].value == "" ||
            !inputs[4].checked ||
            inputs[2].value != inputs[3].value;
    }

    if(check(inputs)){
        submit.addClass("disabled");
        submit.prop('disabled', true);
    }
    else{
        submit.removeClass("disabled");
        submit.prop('disabled', false);
    }

    $(".register").on("keyup, click", function(){
        if(check(inputs)){
            submit.addClass("disabled");
            submit.prop('disabled', true);
        }
        else{
            submit.removeClass("disabled");
            submit.prop('disabled', false);
        }
    });
}

function creator__slider(pm, id__slider, id__handle) {
	var val = id__slider.slider("value");

	if (pm == true && points > 0 && val != 100) { //dodaj

		id__slider.slider('value', val + 10);
		val = id__slider.slider("value");
		id__handle.text(val / 10);
		points--;

		$("#creator__points").html(points);
	} else if (pm == false && points < 10 && val != 0) { //odejmij

		id__slider.slider('value', val - 10);
		val = id__slider.slider("value");
		id__handle.text(val / 10);
		points++;

		$("#creator__points").html(points);
	}
}

function attributes_count(){
	var strength = $("#strength__slider").slider("value") / 10;
	var intelligence = $("#intelligence__slider").slider("value") / 10;
	var agility = $("#agility__slider").slider("value") / 10;
	var vitality = $("#vitality__slider").slider("value") / 10;

	$('input[name="strength"]').val(strength);
	$('input[name="intelligence"]').val(intelligence);
	$('input[name="agility"]').val(agility);
	$('input[name="vitality"]').val(vitality);
}

let register;

$(document).ready(function() {
	//$("#przyciemnienie").css("display", "block");
	//$("#loading").css("display", "block");

  let i = 0;

  const display = setInterval(function(){
    $("#display__time").val(i);
    i++;
    if(i>3) clearInterval(display);
  }, 1000);

	$("#creator__tabs").tabs({ //utworzenie zakladek
		show: {
			effect: "fade",
			duration: "slow",
		}
	});

	$("#strength__slider, #intelligence__slider, #agility__slider, #vitality__slider").slider({ //utworzenie sliderow

		create: function() {
			$(this).children().text($(this).slider("value"));
		},
		slide: function(event, ui) {
			return false;
		}
	});

	checkPassword();
  checkReg();

  register = $('[data-remodal-id=register]').remodal();
});

$("#creator__tabs").on("submit", function(){
	attributes_count();
});

let klasa = false;
let rasa = false;

$("#submit-hero").on("click", function(){
  const name = $("#name").val();
  if(name){
    $.post({
  				url : 'check_hero',
          data: {name: name},
  		})
  		.done(result => {
        if(result == 1){
          new Noty({
        			type: 'warning',
        			layout: 'topRight',
        			text: 'Nazwa postaci jest już zajęta!',
        			timeout: 2000,
        	}).show();

          $(".creator__input-name").animateCss('bounce');
        }
        else if(klasa == true && rasa == true && $('#creator__points').html() == '0') register.open();
        else new Noty({
      			type: 'warning',
      			layout: 'topRight',
      			text: 'Wróć, aby dokończyć tworzenie postaci.',
      			timeout: 2000,
      	}).show();
  		});
  }
});

$("#submit__reg").on("click", function(){
  var login = $('.register input[name="login"]').val();
  var haslo = $('.register input[name="haslo"]').val();
  var vhaslo = $('.register input[name="vhaslo"]').val();
  var email = $('.register input[name="email"]').val();
  var displaytime = $('.register input[name="displaytime"]').val();

  $('#creator__tab4 input[name="login"]').val(login);
  $('#creator__tab4 input[name="haslo"]').val(haslo);
  $('#creator__tab4 input[name="vhaslo"]').val(vhaslo);
  $('#creator__tab4 input[name="email"]').val(email);
  $('#creator__tab4 input[name="displaytime"]').val(displaytime);

  $.post({
        url : 'check_reg',
        data: {
          username: login,
          email: email,
        },
    })
    .done(result => {
      if(result == 0) $('#creator__tabs').submit();
      else new Noty({
          type: 'warning',
          layout: 'topRight',
          text: 'Nazwa użytkownika lub email jest już zajęta!',
          timeout: 2000,
      }).show();
    });
});

$('input[name="class"]').on("click", function(){
	klasa = true;
});

$('input[name="race"]').on("click", function(){
	rasa = true;
});

$("#nextTab").on("click", function(){
	const tab1 = $('#ui-id-1').parent();
	const tab2 = $('#ui-id-2').parent();
	const tab3 = $('#ui-id-3').parent();

	if(tab1.attr('aria-selected') == 'true'){
		if(klasa == true) $('#ui-id-2').click();
		else $('.creator__class-image').animateCss('bounce');
	}
	else if(tab2.attr('aria-selected')  == 'true'){
		if(rasa == true) $('#ui-id-3').click();
		else $('.creator__race__block').animateCss('bounce');
	}
	else{
		if($('#creator__points').html() == 0) $('#ui-id-4').click();
		else $('#creator__points').animateCss('bounce');
	}
});

//Obsługa sliderow w kreatorze

var points = 10;

$("#strength__plus, #intelligence__plus, #agility__plus, #vitality__plus").on("click", function() { //wcisniecie plusa
	var id__slider = $(this).prev();
	var id__handle = $(this).prev().children();

	creator__slider(true, id__slider, id__handle);
});

$("#strength__minus, #intelligence__minus, #agility__minus, #vitality__minus").on("click", function() { //wcisniecie minusa
	var id__slider = $(this).next();
	var id__handle = $(this).next().children();

	creator__slider(false, id__slider, id__handle);
});

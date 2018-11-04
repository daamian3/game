function checkPassword(){
  const haslo = document.getElementById("inputPassword");
  const vhaslo = document.getElementById("inputPasswordCheck");
  const submit = document.getElementById("forgot");

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

    if(haslo.value == vhaslo.value){
      submit.disabled = false;
      submit.classList.remove('disabled');
    }
    else{
      submit.disabled = true;
      submit.classList.add('disabled');
    }
  }

  vhaslo.addEventListener("keyup", function(){
    check();
  }, false);

  haslo.addEventListener("keyup", function(){
    check();
  }, false);
}

$(document).ready(function() {
  checkPassword();
});

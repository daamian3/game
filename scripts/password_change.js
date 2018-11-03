function checkPassword(){
  let haslo = document.getElementById("inputPassword");
  let vhaslo = document.getElementById("inputPasswordCheck");

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
}

$(document).ready(function() {
  checkPassword();
});

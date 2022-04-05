function afficherMenu() {
    var menu = document.getElementById("MenuPopUp");
    menu.classList.toggle("show");
  }
  function afficherRecherche() {
    var recherche = document.getElementById("RecherchePopUp");
    recherche.classList.toggle("show");
  }

var estOuvert = false 
var idDivCourrant = 0;
function afficherMenuItem(item) {
  var idItem = item[0];
  /* Call fonction pour afficher info */
  if(!estOuvert){
    var itemCliquer = document.getElementById(idItem);
    var div = document.createElement('div');
    div.classList.add('testItem');
    div.setAttribute("id", "itemPopUp");
    // Bouton - et +
    var bouttonPlus = document.createElement('button');
    bouttonPlus.setAttribute("aria-label","Plus");
    bouttonPlus.setAttribute('onclick', "ModifierNbItemChoisie('augmenter')");
    var bouttonMoin = document.createElement('button');
    bouttonMoin.setAttribute("aria-label","Minus");
    bouttonMoin.setAttribute('onclick', "ModifierNbItemChoisie('reduire')");
    // Input pour le nb d'item
    var inputNombre = document.createElement("input");
    inputNombre.type = "number";
    inputNombre.setAttribute("aria-label","Alternative");
    inputNombre.style = "width: 80px";
    inputNombre.readOnly = "true";
    inputNombre.id = "nbItemChoisie";
    inputNombre.value = 1;
    // Boutton Acheter
    var bouttonAcheter = document.createElement("button");
    bouttonAcheter.setAttribute("aria-label","Normal");
    bouttonAcheter.textContent = "Ajouter au panier";
  
    div.appendChild(bouttonPlus);
    div.appendChild(inputNombre);
    div.appendChild(bouttonMoin);
    div.appendChild(bouttonAcheter);
    itemCliquer.appendChild(div);
    lastId = idItem;
    estOuvert = true;
    var itemPop = document.getElementById("itemPopUp");
    itemPop.classList.toggle("show");
    idDivCourrant = idItem;
  }
  else if(idDivCourrant!=idItem){
    var itemPop = document.getElementById("itemPopUp");
    itemPop.classList.toggle("show");
    var divASupp = document.getElementById("itemPopUp");
    divASupp.parentNode.removeChild(divASupp);
    estOuvert = false;
    idDivCourrant = idItem;
  }
}
function ModifierNbItemChoisie(option){
  var inputNbItemChoisie = document.getElementById("nbItemChoisie");
  if(option == "reduire" && parseInt(inputNbItemChoisie.value)>1){
    inputNbItemChoisie.value = inputNbItemChoisie.value-1;
  }
  else if(option == "augmenter"){
    var nbInput = parseInt(inputNbItemChoisie.value);
    inputNbItemChoisie.value = nbInput + 1;
  }
}
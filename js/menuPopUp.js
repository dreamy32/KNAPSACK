function afficherMenu() {
    var menu = document.getElementById("MenuPopUp");
    menu.classList.toggle("show");
}
function afficherRecherche() {
  var recherche = document.getElementById("RecherchePopUp");
  recherche.classList.toggle("show");
}

var rechercheOuvert = false;

  function afficherRecherche() {
    if (!rechercheOuvert)
    {
    var recherche = document.getElementById("RecherchePopUp");
    recherche.classList.toggle("show");
    rechercheOuvert = true;
    }
  }

  function hideRecherche() {
    if (rechercheOuvert)
    {
    var recherche = document.getElementById("RecherchePopUp");
    recherche.classList.toggle("show");
    rechercheOuvert = false;
    }
  }

var estOuvert = false 
var dernierIdDiv = 0;
function afficherMenuItem(idItem) {
  if(dernierIdDiv != idItem && estOuvert){
    var itemACache = document.getElementById(("itempPopUp" + dernierIdDiv));
    itemACache.classList.toggle("show");
    estOuvert = false;
  }
  else if(!estOuvert){
    var item = document.getElementById(("itempPopUp" + idItem));
    item.classList.toggle("show");
    dernierIdDiv = idItem;
    estOuvert = true;
  }
}
function fermerMenuItem(){
  var itemACache = document.getElementById(("itempPopUp" + dernierIdDiv));
  itemACache.classList.toggle("show");
  estOuvert = false;
}
var commentaireOuvert = false
function afficherFormCommentaire() {
  var eval = document.getElementById("formAjoutEval");
  eval.classList.toggle("show");
  if(!(commentaireOuvert)){
    var bouttonAjout = document.getElementById("ajoutEvalButton");
    bouttonAjout.ariaLabel = 'Minus';
    commentaireOuvert = true;
  }
  else{
    var bouttonAjout = document.getElementById("ajoutEvalButton");
    bouttonAjout.ariaLabel = 'Plus';
    commentaireOuvert = false;
  }
  document.getElementById("ajoutEvalButton").contentWindow.location.reload(true);
}


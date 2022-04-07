function afficherMenu() {
    var menu = document.getElementById("MenuPopUp");
    menu.classList.toggle("show");
  }
function afficherRecherche() {
  var recherche = document.getElementById("RecherchePopUp");
  recherche.classList.toggle("show");
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
    /*
    var topItem = document.getElementById(idItem).offsetTop;
    topItem = topItem - 150;
    */
    item.style.top = (topItem + "vh");
    item.classList.toggle("show");
    dernierIdDiv = idItem;
    estOuvert = true;
  }
}
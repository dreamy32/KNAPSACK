function afficherMenu() {
  var menu = document.getElementById("MenuPopUp");
  menu.classList.toggle("show");
}

var estOuvert = false;
function afficherMenuItem(idItem) {
  if (!estOuvert) {
    var itemCliquer = document.getElementById(idItem);
    var div = document.createElement("div");
    div.classList.add("testItem");
    div.setAttribute("id", "itemPopUp");
    var bouttonPlus = document.createElement("button");
    bouttonPlus.setAttribute("aria-label", "Plus");
    var bouttonMoin = document.createElement("button");
    bouttonMoin.setAttribute("aria-label", "Minus");
    div.appendChild(bouttonMoin);
    div.appendChild(bouttonPlus);
    itemCliquer.appendChild(div);
    lastId = idItem;
    estOuvert = true;
    var itemPop = document.getElementById("itemPopUp");
    itemPop.classList.toggle("show");
  } else {
    var itemPop = document.getElementById("itemPopUp");
    itemPop.classList.toggle("show");
    var divASupp = document.getElementById("itemPopUp");
    divASupp.parentNode.removeChild(divASupp);
    estOuvert = false;
  }
}

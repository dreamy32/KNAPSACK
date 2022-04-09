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

function trier()
{
    var url = "";
    var count = 0;

    document.getElementsByName("tri").forEach(checkbox => {
        if (checkbox.checked)
        {
          if (count != 0)
          {
            url += "," + checkbox.value;
            
          }
          else
          {
            url = "?tri=";
            url += checkbox.value;
          }
          count++;
        }
    });  
  
    if (document.getElementById("nbEtoiles").value != 0)
    {
    if (count == "")
    {
      url = "?nbEtoiles=" + document.getElementById("nbEtoiles").value;
    }
    else
    {
      url += "&" + "nbEtoiles=" + document.getElementById("nbEtoiles").value;
    }
  }

  $countType = 0;

document.getElementsByName("type").forEach(checkbox => {
  if (checkbox.checked)
  {
    $countType++;

    if ($countType == 1)
    {
  if (url == "")
  {
    url = "?type=" + checkbox.value;
  }
  else
  {
    url += "&" + "type=" + checkbox.value;
  }
}
else
{
  url += "-" + checkbox.value;
}
}});

    
  if (url == "")
  {
    window.history.pushState({}, document.title, "/" + "PhpBuilding");
    location.reload();
  }
  else
  {
    window.location.href = url;
  }
}
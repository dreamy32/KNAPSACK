$().ready(() => {
  //
  let itemTypesCheckboxes = $("*[name=type]");
  let searchTypesCheckboxes = $("*[name=search-box]");
  let resetCheckbox = $("#reset");
  let orderButton = $("#order-button");
  let ascOrDesc = (orderButton.val() === 'true');
  //
  resetCheckbox.click(() =>
    Reset([itemTypesCheckboxes, searchTypesCheckboxes])
  );
  //Croissant-Décroissant
  //Initialiser
  orderButton.text(ascOrDesc ? "Décroissant" : "Croissant");
  orderButton.click(() => {
    ascOrDesc = !ascOrDesc;
    orderButton.text(ascOrDesc ? "Décroissant" : "Croissant");
    orderButton.val(ascOrDesc);
  });
  
});

//Réinitialiser
const Reset = (checkboxesArr) => {
  ResetCheckboxes(checkboxesArr);
};
const ResetCheckboxes = (checkboxesArr) => {
  for (const checkboxes of checkboxesArr) {
    for (const checkbox of checkboxes) {
      if (checkbox.checked) checkbox.checked = false;
    }
    
  }
};

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


  if (url != "")
  {
    if (document.getElementById("order-button").value === "false")
    {
      url += "&ordre=" + "ASC";
    }
    else
    {
      url += "&ordre=" + "DESC";
    }
  }
  else
  {
    if (document.getElementById("order-button").value === "false")
    {
      url += "?ordre=" + "ASC";
    }
    else
    {
      url += "?ordre=" + "DESC";
    }
  }

    
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

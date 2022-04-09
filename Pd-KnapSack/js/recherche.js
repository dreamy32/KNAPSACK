$().ready(() => {
  //
  let itemTypesCheckboxes = $("*[name=type]");
  let searchTypesCheckboxes = $("*[name=search-box]");
  let resetCheckbox = $("#reset");
  let orderButton = $("#order-button");
  let ascOrDesc = orderButton.val();
  console.log(ascOrDesc);
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

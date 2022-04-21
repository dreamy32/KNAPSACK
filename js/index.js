ChangerInformation = (idItem) => {
  let infoNomItem = $("#infoNom");
  let infoNbItem = $("#infoNbItem");
  let infoImageItem = $("#infoImageItem");
  let infoPrixItem = $("#infoPrixItem");
  let infoPoidsItem = $("#infoPoidsItem");
  let infoDescriptionItem = $("#infoDescriptionItem");
  //
  infoNomItem.innerHTML = idItem[1].toUpperCase();
  infoNbItem.value = idItem[2];
  infoImageItem.src = `items_images/${idItem[0]}.png`;
  infoPrixItem.innerHTML = `Prix: ${idItem[4]} $`;
  infoPoidsItem.innerHTML = `Poids: ${idItem[5]} lb`;
  infoDescriptionItem.innerHTML = idItem[6];
  //
  afficherMenuItem(idItem[0]);
};
ReduireNbItemChoisie = (idItem) => {
  let inputNbItemChoisie = $(`#nbItemChoisie${idItem}`);
  if (inputNbItemChoisie.val() > 1)
    inputNbItemChoisie.val(inputNbItemChoisie.val() - 1);
};

AugmenterNbItemChoisie = (idItem) => {
  let inputNbItemChoisie = $(`#nbItemChoisie${idItem}`);
  let maxNbItem = $("#infoNbItem");
  if (maxNbItem.val() > inputNbItemChoisie.val()) {
    let nbInput = parseInt(inputNbItemChoisie.val());
    inputNbItemChoisie.val(nbInput + 1);
  }
};

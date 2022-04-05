$().ready(() => {
  let checkboxes = $("*[name=type]");
  let resetCheckbox = $("#reset");
  resetCheckbox.click(() => Reset(checkboxes));
});

const Reset = (checkboxes) => {
  for (const checkbox of checkboxes) {
      if(checkbox.checked)
        checkbox.checked = false;
  }
  //   checkboxes[0].checked = false;
};

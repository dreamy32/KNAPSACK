$().ready(() => {
  setDelayOnSubmit(1500);
});

const setDelayOnSubmit = (milliseconds) => {
  let submit = false;
  $("form").submit((e) => {
    setTimeout(() => {
      submit = true;
      $("form").submit();
    }, milliseconds);
    if (!submit) e.preventDefault();
  });
};

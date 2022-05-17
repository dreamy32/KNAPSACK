$().ready(() => {
  setDelayOnSubmit(5000);
});

const setDelayOnSubmit = (milliseconds) => {
  let submit = false;
  $("#answer").submit((e) => {
    setTimeout(() => {
      submit = true;
      $("#answer").submit();
      document.querySelector("#answer-a").classList.add("vert");
      document.querySelector("#answer-b, #answer-c, #answer-d").classList.add("rouge");
    }, milliseconds);
    if (!submit) e.preventDefault();
  });
};

$().ready(() => {
  let audioElement = document.createElement("audio");
  audioElement.setAttribute("src", "./audio/minecraft-click.mp3");
  $("body").append(audioElement);
  audioElement.load();
  PlaySound(audioElement);
});

const PlaySound = (audioElement) => {
  $("button, input[type='checkbox'").click(() => audioElement.play());
  // $("input[type='submit'").click(() => audioElement.play());
};

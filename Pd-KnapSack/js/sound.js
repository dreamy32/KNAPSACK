$().ready(() => {
  let audioElement = document.createElement("audio");
  audioElement.setAttribute(
    "src",
    "./audio/minecraft-click.mp3"
  );
  $("body").append(audioElement);
  PlaySound(audioElement);
});

const PlaySound = (audioElement) => {
  $("button").click(() => audioElement.play());
};

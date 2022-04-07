$().ready(() => {
  DefaultClick();
  ItemFrameClick();
  ResetClick();
});

const DefaultClick = () => {
  let audioElement = document.createElement("audio");
  audioElement.setAttribute("src", "./audio/minecraft-click.mp3");
  $("body").append(audioElement);
  audioElement.load();
  //
  $("button, input[type='checkbox']")
    .not(":input[aria-label=Item-Frame]")
    .click(() => audioElement.play());
};

const ItemFrameClick = () => {
  const nbAudioFiles = 4;
  const audioFiles = [];

  for (let i = 0; i < nbAudioFiles; i++) {
    let audioElement = document.createElement("audio");
    audioFiles.push(audioElement);
    audioElement.setAttribute("src", `./audio/item_frame/add_item${i + 1}.ogg`);
    $("body").append(audioElement);
  }
  //
  $("input[aria-label='Item-Frame']")
    .not("#reset")
    .click(() => audioFiles[Math.floor(Math.random() * nbAudioFiles)].play());
};

const ResetClick = () => {
  const nbAudioFiles = 3;
  const audioFiles = [];

  for (let i = 0; i < nbAudioFiles; i++) {
    let audioElement = document.createElement("audio");
    audioFiles.push(audioElement);
    audioElement.setAttribute("src", `./audio/item_frame/break${i + 1}.ogg`);
    $("body").append(audioElement);
  }
  $("#reset").click(() =>
    audioFiles[Math.floor(Math.random() * nbAudioFiles)].play()
  );
};

const ShopItemClick = () => {
  const nbAudioFiles = 3;
  const audioFiles = [];

  for (let i = 0; i < nbAudioFiles; i++) {
    let audioElement = document.createElement("audio");
    audioFiles.push(audioElement);
    audioElement.setAttribute("src", `./audio/villager/haggle${i + 1}.ogg`);
    $("body").append(audioElement);
  }
  $(".shop-item").click(() =>
    audioFiles[Math.floor(Math.random() * nbAudioFiles)].play()
  );
}

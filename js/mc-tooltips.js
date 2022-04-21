//source: https://codepen.io/devbobcorn/pen/wvzxxLv

var minetip = document.getElementById("minetip-tooltip");

$(".minetext").mouseover((event) => {
  minetip.innerHTML = event.target.dataset.mctitle;
  minetip.style.display = "block";
});

$(".minetext").mouseout(() => {
  minetip.style.display = "none";
});

$(".minetext").mousemove((event) => {
  pos (minetip, 5, -30, event);
});

var pos = (o, x, y, event) => {
    var posX = 0, posY = 0;
    var e = event || window.event;
    if (e.pageX || e.pageY) {
        posX = e.pageX;
        posY = e.pageY;
    } else if (e.clientX || e.clientY) {
        posX = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
        posY = event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
    }
    o.style.position = "absolute";
    o.style.top = (posY + y) + "px";
    o.style.left = (posX + x) + "px";
}

minetip.style.display = "none";
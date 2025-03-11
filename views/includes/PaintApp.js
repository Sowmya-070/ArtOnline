const canvas = document.querySelector("canvas"),
  toolBtns = document.querySelectorAll(".tool"),
  fillColor = document.querySelector("#fill-color"),
  sizeSlider = document.querySelector("#size-slider"),
  colorBtns = document.querySelectorAll(".colors .option"),
  colorPicker = document.querySelector("#color-picker"),
  clearCanvas = document.querySelector(".clear-canvas"),
  saveImg = document.querySelector(".save-img"),
  ctx = canvas.getContext("2d");

//Global Variables With Default Value !
let prevMouseX,
  prevMouseY,
  snapshot,
  isDrawing = false,
  selectedTool = "brush",
  brushWidth = 5,
  selectedColor = "#000";

const setCanvasBackground = () => {
  //Setting Whole Canvas Background To White ~ So The Download Img Background Will Be White !
  ctx.fillStyle = "#fff";
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  ctx.fillStyle = selectedColor; //Setting fillStyle Back To The selectedColor ~ It Will Be The Brush Color !
};

window.addEventListener("load", () => {
  //Setting Canvas Width & Height ~ offsetWidth & Height Return Viewable Width & Height Of An Element !
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
  setCanvasBackground();
});

const drawRect = (e) => {
  //If fillColor Is Not Checked Draw A Rect With Border Else Draw Rect With Background !
  if (!fillColor.checked) {
    //Creating Circle According To The Mouse Pointer !
    return ctx.strokeRect(
      e.offsetX,
      e.offsetY,
      prevMouseX - e.offsetX,
      prevMouseY - e.offsetY
    );
  }
  ctx.fillRect(
    e.offsetX,
    e.offsetY,
    prevMouseX - e.offsetX,
    prevMouseY - e.offsetY
  );
};

const drawCircle = (e) => {
  ctx.beginPath(); //Creating New Path To Draw Circle !
  //Getting Radius For Circle According To The mouse Pointer !
  let radius = Math.sqrt(
    Math.pow(prevMouseX - e.offsetX, 2) + Math.pow(prevMouseY - e.offsetY, 2)
  );
  ctx.arc(prevMouseX, prevMouseY, radius, 0, 2 * Math.PI); //Creating Circle According To The Mouse Pointer !
  fillColor.checked ? ctx.fill() : ctx.stroke(); //If fillColor Is Checked Fill Circle Else Draw Border Circle !
};

const drawTriangle = (e) => {
  ctx.beginPath(); //Creating New Path To Draw Circle !
  ctx.moveTo(prevMouseX, prevMouseY); //Moving Triangle To The Mouse Pointer !
  ctx.lineTo(e.offsetX, e.offsetY); //Creating First Line According To The Mouse Pointer !
  ctx.lineTo(prevMouseX * 2 - e.offsetX, e.offsetY); //Creating Bottom Line Of Triangle !
  ctx.closePath(); //Close Path Of A Triangle So The Third Line Draw Automatically !
  fillColor.checked ? ctx.fill() : ctx.stroke(); //If fillColor Is Checked Fill Triangle Else Draw Border Triangle !
};

const startDraw = (e) => {
  isDrawing = true;
  prevMouseX = e.offsetX; //Passing Current MouseX Position As prevMouseX Value !
  prevMouseY = e.offsetY; //Passing Current MouseY Position As prevMouseY Value !
  ctx.beginPath(); //Creating New Path To Draw !
  ctx.lineWidth = brushWidth; //Passing brushSize As Line Width !
  ctx.strokeStyle = selectedColor; //Passing selectedColor As Stroke Style !
  ctx.fillStyle = selectedColor; //Passing selectedColor As Fill Style !
  //Copying Canvas Data & Passing As Snapshot Value ~ This Avoids Dragging The Image !
  snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);
};

const drawing = (e) => {
  if (!isDrawing) return; //If isDrawing Is False Return From Here !
  ctx.putImageData(snapshot, 0, 0); //Adding Copied Canvas Data On Ti This Canvas !

  if (selectedTool === "brush" || selectedTool === "eraser") {
    //If Selected Tool Is Eraser Then Set strokeStyle To White !
    //To paint White Color On To The Existing Canvas Content Else Set The Stroke Color To Selected Color !
    ctx.strokeStyle = selectedTool === "eraser" ? "#fff" : selectedColor;
    ctx.lineTo(e.offsetX, e.offsetY); //Creating Line According To The Mouse Pointer !
    ctx.stroke(); //Drawing & Filling Line With Color !
  } else if (selectedTool === "rectangle") {
    drawRect(e);
  } else if (selectedTool === "circle") {
    drawCircle(e);
  } else {
    drawTriangle(e);
  }
};

toolBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    //Adding Click Event To All Tool Option !
    //Removing Active Class From The Previous Option And Adding On Current Clicked Option !
    document.querySelector(".options .active").classList.remove("active");
    btn.classList.add("active");
    selectedTool = btn.id;
    console.log(selectedTool);
  });
});

sizeSlider.addEventListener("change", () => (brushWidth = sizeSlider.value)); //Passing Slider Value As brushSize !

colorBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    //Adding Click Event To All Color Button !
    //Removing Active Class From The Previous Option And Adding On Current Clicked Option !
    document.querySelector(".options .selected").classList.remove("selected");
    btn.classList.add("selected");
    //Passing Selected btn Background Color As selectedColor value !
    selectedColor = window
      .getComputedStyle(btn)
      .getPropertyValue("background-color");
  });
});

colorPicker.addEventListener("change", () => {
  //Passing Picked Color Value From Color Picker To Last Color btn Background !
  colorPicker.parentElement.style.background = colorPicker.value;
  colorPicker.parentElement.click();
});

clearCanvas.addEventListener("click", () => {
  ctx.clearRect(0, 0, canvas.width, canvas.height); //Clearing Whole Canvas !
  setCanvasBackground();
});

saveImg.addEventListener("click", () => {
  const canvasData = canvas.toDataURL("image/png", 1.0).replace(/^data:image\/png;base64,/, "");
  
  fetch("../controllers/save_art.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ image: canvasData })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert(`✅ Artwork saved successfully!`);
      } else {
          alert("❌ Error: " + data.message);
      }
  })
  .catch(error => console.error("Save Error:", error));
});




canvas.addEventListener("mousedown", startDraw);
canvas.addEventListener("mousemove", drawing);
canvas.addEventListener("mouseup", () => (isDrawing = false));

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

// const setCanvasBackground = () => {
//   //Setting Whole Canvas Background To White ~ So The Download Img Background Will Be White !
//   ctx.fillStyle = "#fff";
//   ctx.fillRect(0, 0, canvas.width, canvas.height);
//   ctx.fillStyle = selectedColor; //Setting fillStyle Back To The selectedColor ~ It Will Be The Brush Color !
// };

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
  if (!isDrawing) return; 
  ctx.putImageData(snapshot, 0, 0);

  if (selectedTool === "brush" || selectedTool === "eraser") {
    ctx.strokeStyle = selectedTool === "eraser" ? "#fff" : selectedColor;
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
  } else if (selectedTool === "rectangle") {
    drawRect(e);
  } else if (selectedTool === "circle") {
    drawCircle(e);
  } else if (selectedTool === "triangle") {
    drawTriangle(e);
  } else if (selectedTool === "text") { 
    addText(e); // Call the addText function
  }
};

toolBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
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
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  setCanvasBackground(); 
  selectedTool = null; 
  selectedColor = "#000000"; 
  brushWidth = 5; 
  document.querySelector("#colorPicker").value = selectedColor; 
  document.querySelector("#brushSize").value = brushWidth; 
  selectedImage = null; 
  document.querySelector("#imageInput").value = ""; 
});


saveImg.addEventListener("click", () => {
  const canvasData = canvas.toDataURL("image/png", 1.0).replace(/^data:image\/png;base64,/, "");
  console.log(canvasData);
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

// textevt = document.querySelector(".add-text");

const addText = (e) => {
  let text = prompt("Enter your text:");

  // If the user clicks "Cancel" or enters an empty string, exit the function
  if (text === null || text.trim() === "") return;

  ctx.font = `${brushWidth * 2}px Arial`; // Set font size based on brush width
  ctx.fillStyle = selectedColor; // Set text color
  ctx.fillText(text, e.offsetX, e.offsetY); // Draw text at the clicked position
};

canvas.addEventListener("click", (e) => {
  if (selectedTool === "text") {
    addText(e);
  }
});



const uploadImageInput = document.querySelector("#upload-image");


let img = new Image();
let imgX = 50, imgY = 50; // Default position
let imgWidth = 200, imgHeight = 150; // Default size
let isDragging = false, isResizing = false;
let dragStartX, dragStartY;

// Handle Image Upload
uploadImageInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// Draw Image on Canvas
img.onload = () => {
    drawCanvas();
};

// Handle Mouse Events for Drag & Resize
canvas.addEventListener("mousedown", (e) => {
    const mouseX = e.offsetX;
    const mouseY = e.offsetY;

    // Check if clicking the resize handle (bottom-right corner)
    if (mouseX >= imgX + imgWidth - 10 && mouseX <= imgX + imgWidth &&
        mouseY >= imgY + imgHeight - 10 && mouseY <= imgY + imgHeight) {
        isResizing = true;
    }
    // Check if clicking inside the image to drag
    else if (mouseX >= imgX && mouseX <= imgX + imgWidth &&
             mouseY >= imgY && mouseY <= imgY + imgHeight) {
        isDragging = true;
        dragStartX = mouseX - imgX;
        dragStartY = mouseY - imgY;
    }
});

canvas.addEventListener("mousemove", (e) => {
    const mouseX = e.offsetX;
    const mouseY = e.offsetY;

    if (isDragging) {
        imgX = mouseX - dragStartX;
        imgY = mouseY - dragStartY;
        drawCanvas();
    } else if (isResizing) {
        imgWidth = Math.max(30, mouseX - imgX);
        imgHeight = Math.max(30, mouseY - imgY);
        drawCanvas();
    }
});

canvas.addEventListener("mouseup", () => {
    isDragging = false;
    isResizing = false;
});

function drawCanvas() {
  // Preserve the background color before clearing the canvas
  ctx.fillStyle = bgColorPicker.value; // Use the selected background color
  ctx.fillRect(0, 0, canvas.width, canvas.height); // Redraw background

  // Draw the image at its position
  ctx.drawImage(img, imgX, imgY, imgWidth, imgHeight);

  // // Draw Resize Handle (small red square at bottom-right corner)
  // ctx.fillStyle = "red";
  // ctx.fillRect(imgX + imgWidth - 10, imgY + imgHeight - 10, 10, 10);
}



const bgColorPicker = document.getElementById("bgColorPicker");

// Function to set the canvas background color
const setCanvasBackground = (color = "#ffffff") => {
  ctx.fillStyle = color;
  ctx.fillRect(0, 0, canvas.width, canvas.height);
};

// Event listener to change background color
bgColorPicker.addEventListener("input", () => {
  setCanvasBackground(bgColorPicker.value);
});

// Modify the clearCanvas function to reset everything
clearCanvas.addEventListener("click", () => {
  ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear canvas
  setCanvasBackground(bgColorPicker.value); // Apply selected background color

  // Reset all selections and options
  selectedTool = null;
  selectedColor = "#000000"; // Reset drawing color to black
  brushWidth = 5;
  document.querySelector("#colorPicker").value = selectedColor;
  document.querySelector("#brushSize").value = brushWidth;

  // Reset image selection (if applicable)
  selectedImage = null;
  document.querySelector("#imageInput").value = ""; 
});

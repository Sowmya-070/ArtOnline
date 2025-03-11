<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
include '../controllers/marketplace.php';

// Fetch username for unique file naming
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT name FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
$username = strtolower(str_replace(' ', '_', $user['name']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/includes/PaintApp.css">
    <title>Art Studio</title>

    <style>
      /* 🌟 Reset Defaults */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        background-color: #f7f5eb; /* Light Background */
        font-family: Arial, sans-serif;
      }

      /* 🎨 Navbar Styling */
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #6a0dad; /* Purple Navbar */
    padding: 15px 20px;
    color: white;
    position: fixed; /* FIXED at the top */
    top: 0; /* Sticks to the top */
    left: 0;
    width: 100%;
    z-index: 1000; /* Ensures it stays above other content */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* 🖼 Fix Page Layout (Move Content Down) */
.container {
    margin-top: 70px; /* Push content below the fixed navbar */
}

.navbar img {
    width: 40px;
    height: 40px;
}

.navbar h1 {
    flex-grow: 1;
    text-align: center;
    font-size: 24px;
    margin: 0;
}

.navbar button {
    background-color: white;
    color: #6a0dad;
    border: none;
    padding: 8px 15px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

.navbar button:hover {
    background-color: #e0c3fc;
}


      /* 🖌️ Tools Board */
      .tools-board {
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin: 20px;
      }

      /* 🎭 Canvas Styling */
      .drawing-board {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
      }

      canvas {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }
    </style>
</head>
<body>

<!-- 🟣 Navbar -->
<nav class="navbar">
    <img src="../views/includes/Images/Paint.png" alt="logo">
    <h1>ART STUDIO</h1>
    <button onclick="goBack()">🔙 Back</button>
</nav>

<div class="container">
    <section class="tools-board">
        <div class="row">
          <label class="title">Shapes</label>
          <ul class="options">
            <li class="option tool" id="rectangle">
              <img src="../views/includes/Images/rectangle.svg" alt="" />
              <span>Rectangle</span>
            </li>
            <li class="option tool" id="circle">
              <img src="../views/includes/Images/circle.svg" alt="" />
              <span>Circle</span>
            </li>
            <li class="option tool" id="triangle">
              <img src="../views/includes/Images/triangle.svg" alt="" />
              <span>Triangle</span>
            </li>
            <li class="option">
              <input type="checkbox" id="fill-color" />
              <label for="fill-color">Fill Color</label>
            </li>
          </ul>
        </div>

        <div class="row">
          <label class="title">Options</label>
          <ul class="options">
            <li class="option active tool" id="brush">
              <img src="../views/includes/Images/brush.svg" alt="" />
              <span>Brush</span>
            </li>
            <li class="option tool" id="eraser">
              <img src="../views/includes/Images/eraser.svg" alt="" />
              <span>Eraser</span>
            </li>
            <li class="option">
              <input type="range" id="size-slider" min="1" max="30" value="5" />
            </li>
          </ul>
        </div>

        <div class="row colors">
          <label class="title">Colors</label>
          <ul class="options">
            <li class="option"></li>
            <li class="option selected"></li>
            <li class="option"></li>
            <li class="option"></li>
            <li class="option">
              <input type="color" id="color-picker" value="#4a98f7" />
            </li>
          </ul>
        </div>

        <div class="row buttons">
          <button class="clear-canvas">Clear Canvas</button>
          <button class="save-img">Save As Image</button>
        </div>
      </section>

      <section class="drawing-board">
        <canvas width="800" height="500"></canvas>
      </section>
</div>

<script>
  function goBack() {
    window.location.href = "../views/dashboard.php"; // Redirect to the dashboard page
  }
</script>

<script src="../views/includes/PaintApp.js"></script>

</body>
</html>

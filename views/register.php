<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-main v1">
    <div class="bg-overlay bg-primary"></div>
    <div class="auth-wrapper">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">
                    <img src="../assets/images/favicon.svg" alt="" class="img-fluid mb-4 img-logo">
                    <h4 class="mb-3 f-w-400">Register</h4>
                    <form method="post" action="../controllers/auth.php">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i data-feather="user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="User Name" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i data-feather="mail"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i data-feather="lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text"><i data-feather="briefcase"></i></span>
                            <select name="role" class="form-control" required>
                                <option value="artist">Artist</option>
                                <option value="buyer">Buyer</option>
                            </select>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary btn-block mb-4">Register</button>
                    </form>
                    <p class="mb-2">Already have an account? <a href="login.php" class="f-w-400">Login</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/fonts/custom-font.js"></script>
    <script src="../assets/js/pcoded.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>
</body>
</html>

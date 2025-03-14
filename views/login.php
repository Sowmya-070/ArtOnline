<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/lrstyle.css">
</head>
<body class="auth-main v1">
    
    <div class="auth-wrapper">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">
                    <img src="../assets/images/favicon.svg" alt="" class="img-fluid mb-4 img-logo">
                    <h4 class="mb-3 f-w-400">Login</h4>
                    <form method="post" action="../controllers/auth.php">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i data-feather="user"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <!-- <div class="input-group mb-4">
                            <span class="input-group-text"><i data-feather="lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div> -->
                        <div class="input-group mb-4">
    <span class="input-group-text"><i data-feather="lock"></i></span>
    <input type="password" id="password" name="password" class="form-control"
           placeholder="Password" required
           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
           title="Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.">
</div>
<small class="text-danger" id="passwordError" style="display:none;">Password must contain at least one uppercase, one lowercase, one number, one special character, and be at least 8 characters long.</small>


                        <div class="mb-3 mt-2">
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                <label class="form-check-label" for="flexCheckChecked">Remember me</label>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block mb-4">Login</button>
                    </form>
                    <p class="mb-2">Don’t have an account? <a href="register.php" class="f-w-400">Register</a></p>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>
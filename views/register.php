<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/lrstyle.css">
</head>
<body class="auth-main v1">
  
    <div class="auth-wrapper">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">
                    <img src="../assets/images/favicon.svg" alt="" class="img-fluid mb-4 img-logo">
                    <h4 class="mb-3 f-w-400">Register</h4>
                    <form method="post" action="../controllers/auth.php">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i data-feather="user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i data-feather="mail"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="input-group mb-4">
    <span class="input-group-text"><i data-feather="lock"></i></span>
    <input type="password" id="password" name="password" class="form-control"
           placeholder="Password" required
           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
           title="Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.">
</div>
<small class="text-danger" id="passwordError" style="display:none;">Password must contain at least one uppercase, one lowercase, one number, one special character, and be at least 8 characters long.</small>

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
  

</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const passwordError = document.getElementById("passwordError");

    passwordInput.addEventListener("input", function () {
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (!passwordPattern.test(passwordInput.value)) {
            passwordError.style.display = "block";
        } else {
            passwordError.style.display = "none";
        }
    });
});
</script>

</html>
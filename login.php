<?php
session_start();
include 'config.php';

$error_message = '';

// Google OAuth configuration (replace with your actual Google OAuth credentials)
$google_client_id = 'YOUR_GOOGLE_CLIENT_ID';
$google_redirect_uri = 'http://yourdomain.com/google-login.php'; // Redirect URI after Google login

// Apple OAuth configuration (replace with your actual Apple OAuth credentials)
$apple_client_id = 'YOUR_APPLE_CLIENT_ID';
$apple_redirect_uri = 'http://yourdomain.com/apple-login.php'; // Redirect URI after Apple login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    // $sql="SELECT id FROM users WHERE username='{$user['username']}'"
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'admin') {
                header("Location: admin.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "No user found with that email";
    }
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 100px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
            border-radius: 10px;
            padding: 30px;
            background-color: #fff;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #007bff;
            border: 1px solid #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-google {
            background-color: #ea4335;
            border-color: #ea4335;
        }
        .btn-google:hover {
            background-color: #d63027;
            border-color: #d63027;
        }
        .btn-apple {
            background-color: black;
            border-color: black;
            color: white;
        }
        .btn-apple:hover {
            background-color: #333;
            border-color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2 class="card-title">Login</h2>
        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="text-center mt-3">
            <p>Or login with:</p>
            <!-- Google login button -->
            <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=<?php echo $google_client_id; ?>&redirect_uri=<?php echo $google_redirect_uri; ?>&scope=email%20profile" class="btn btn-google">Google</a>
            <!-- Apple login button -->
            <a href="https://appleid.apple.com/auth/authorize?response_type=code&client_id=<?php echo $apple_client_id; ?>&redirect_uri=<?php echo $apple_redirect_uri; ?>&scope=name%20email" class="btn btn-apple">Apple</a>
            <p><a href="index.php" class="btn btn-go-home">Go Home</a></p>
        </div>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
</div>
</body>
</html>
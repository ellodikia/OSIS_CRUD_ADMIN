<?php
if (isset($_POST['admin']) && isset($_POST['password'])) {
    $admin = $_POST['admin'];
    $password = $_POST['password'];

    if ($admin == "admin123" && $password == "osis123") {
        header("Location: index_admin.php");
        exit;
    } else {
        echo "<p style='color: red; text-align: center;'>Username atau password salah!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        .login label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .login input[type="text"],
        .login input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login input[type="submit"] {
            width: 100%;
            background-color: #800000;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login input[type="submit"]:hover {
            background-color: #a00000;
        }
    </style>
</head>
<body>
    <div class="login">
        <form action="" method="post">
            <label for="admin">Admin</label>
            <input type="text" name="admin" id="admin" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>

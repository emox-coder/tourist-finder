<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Tourist Attraction Finder</title>
    <link rel="stylesheet" href="../assets/css/landing-page.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .login-logo {
            margin-bottom: 30px;
        }

        .login-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .login-container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .login-container p {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: 'Roboto', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            font-family: 'Roboto', sans-serif;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .back-home {
            margin-top: 20px;
        }

        .back-home a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .back-home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="../assets/img/logo.png" alt="TAF Logo">
        </div>
        <h1>Admin Login</h1>
        <p>Enter your credentials to access the admin dashboard</p>

        <div class="error-message" id="errorMessage">
            Invalid email or password
        </div>

        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="admin@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="login-btn">Sign In</button>
        </form>

        <div class="back-home">
            <a href="../pages/landing-page.php">← Back to Website</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');

            try {
                const response = await fetch('../Backend/app/Controllers/LoginController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const text = await response.text();
                
                // Remove BOM and clean up the response
                const cleanText = text.replace(/^\uFEFF/, '').trim();
                
                let result;
                try {
                    result = JSON.parse(cleanText);
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    console.error('Response text:', cleanText);
                    throw new Error('Invalid server response: ' + cleanText.substring(0, 100));
                }

                if (result.success) {
                    window.location.href = 'top-destinations.php';
                } else {
                    errorMessage.textContent = result.message || 'Invalid email or password';
                    errorMessage.classList.add('show');
                }
            } catch (error) {
                console.error('Login error:', error);
                errorMessage.textContent = 'Connection error: ' + error.message + '. Please try again.';
                errorMessage.classList.add('show');
            }
        });
    </script>
</body>
</html>
 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Student Record</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #fff7f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-card {
    background: #ffffff;
    padding: 30px;
    width: 400px;
    height: 250px;
    border: 2px solid #ff8c00; 
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #ff8c00;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ffb366; 
    border-radius: 5px;
    outline: none;
}

.form-control:focus {
    border-color: #ff8c00;
}

.btn {
    width: 100%;
    padding: 10px;
    background: #ff8c00;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background: #e67600;
}

#error-msg {
    color: #ff4d4d;
    text-align: center;
    margin-bottom: 15px;
    display: none;
}
</style>

</head>
<body>

<div class="login-card">
    <h2>Student Management Login</h2>
    
    <div id="error-msg"></div>

    <form id="loginForm">
        <div class="form-group">
            <input id="username" type="text" class="form-control" placeholder="Username" autocomplete="off" required>
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control" placeholder="Password" required>
        </div>
        
        <button type="submit" class="btn">Login</button>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorMsg = document.getElementById('error-msg');
    errorMsg.style.display = 'none';

    if (!username || !password) {
        errorMsg.textContent = 'Please enter both username and password.';
        errorMsg.style.display = 'block';
        return;
    }

    fetch('api/login_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({
            username: username,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.user_type === 'admin') {
                window.location.href = 'admin_dashboard.php';
            } else {
                window.location.href = 'student_dashboard.php';
            }
        } else {
            errorMsg.textContent = data.message || 'Invalid username or password.';
            errorMsg.style.display = 'block';
        }
    })
    .catch(err => {
        errorMsg.textContent = 'Unable to connect. Please try again.';
        errorMsg.style.display = 'block';
        console.error('Login error:', err);
    });
});
</script>

</body>
</html>
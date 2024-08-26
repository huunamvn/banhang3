<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    background: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    text-align: center;
}

.error-content {
    background: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 120px;
    margin: 0;
    color: #e53935;
}

.error-message {
    font-size: 20px;
    color: #333;
}

.btn {
    text-decoration: none;
    background-color: #e53935;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    display: inline-block;
    margin-top: 20px;
    font-size: 16px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #c62828;
}

</style>
<body>
    <div class="container">
        <div class="error-content">
            <h1>403</h1>
            <p class="error-message">Forbidden: You don’t have permission to access this page.</p>
            <a href="/" class="btn">Back to Homepage</a>
        </div>
    </div>
</body>
</html>

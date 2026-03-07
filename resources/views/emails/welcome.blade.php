<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Pharmacy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            background: #f9faf9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #14b8a6;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Welcome to Our Pharmacy!</h1>
        <p>Thank you for registering with us.</p>
    </div>
    
    <div class="content">
        <h3>Hello {{ $content }}</h3>
        <p>Your account has been successfully created in our pharmacy management system.</p>
        <p>You can now log in to manage your prescriptions and orders.</p>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Pharmacy Management System. All rights reserved.</p>
    </div>
</body>
</html>

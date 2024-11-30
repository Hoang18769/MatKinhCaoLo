<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Notification</title>
    <style>
        /* CSS cho email */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .header {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            margin-top: 20px;
        }
        .discount-code {
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .expiration-date {
            font-style: italic;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Đăng ký tài khoản thành công</h2>
        </div>
        <div class="content">
            <p>Xin chào <h1> {{ $account->name_account }}</h1></p>
            <p>Chúc mừng! Bạn đã đăng ký tài khoản thành công.</p>
            <p>Cảm ơn bạn đã tham gia!</p>
        </div>
        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>

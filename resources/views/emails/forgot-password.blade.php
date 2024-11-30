<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu</title>
    <style>
        /* Thêm CSS để làm đẹp email */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Khôi phục mật khẩu</h2>
        <p>Xin chào <b>{{ $name_account }}</b></p>
        <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu khôi phục mật khẩu từ tài khoản của bạn.</p>
        <p>Vui lòng nhấn vào đường dẫn bên dưới để khôi phục mật khẩu của bạn:</p>
        <p>
            <a style="background: rgb(5, 185, 125)221, 221);color:white;padding:5px;border-radius:5px;margin-top:5px;margin-bottom:5px;"
            href="https://frontend.matkinhcaolo.io.vn/reset">Khôi phục mật khẩu</a></p>
        <p><b style="color: red">*Lưu ý:</b> Nếu không phải bạn yêu cầu, vui lòng bỏ qua email này.</p>
    </div>
</body>
</html>


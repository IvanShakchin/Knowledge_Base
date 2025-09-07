<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступ к помощнику вайб-кодера / Vibe coder's assistant</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            margin: 0;
            padding: 10px;
        }
        .login-container {
            /*width: 100%;*/
            max-width: 400px;
            margin: 50px auto;
            background: white; 
            padding: 20px;
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 { 
            text-align: center; 
            font-size: 20px;
        }
        .login-form input {
            width: 100%; 
            padding: 14px; 
            margin: 12px 0;
            box-sizing: border-box; 
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .login-form button {
            width: 100%; 
            padding: 14px; 
            background: #4CAF50;
            color: white; 
            border: none; 
            cursor: pointer;
            font-size: 16px;
            min-height: 44px;
        }
        .error { 
            color: red; 
            text-align: center;
            font-size: 16px;
            padding: 8px 0;
        }
        
        /* Десктопная версия */
        @media (min-width: 768px) {
            .login-container {
                margin: 100px auto;
                padding: 30px;
            }
            .login-container h2 { 
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Доступ к помощнику вайб-кодера <br> Vibe coder's assistant</h2>
       
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form class="login-form" method="POST" action="{{ route('vca.index') }}">
            @csrf
            <input type="password" name="password" placeholder="Введите пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>

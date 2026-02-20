<!DOCTYPE html>
<html>

<head>

    <title>Result | Laravel 12 Doorman</title>

    <style>
        body {

            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            height: 100vh;

        }

        .container {

            width: 450px;
            margin: 120px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;

        }

        h2 {

            margin-bottom: 20px;
            color: #333;

        }

        .message {

            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            word-wrap: break-word;

        }

        .success {

            background: #d4edda;
            color: #155724;

        }

        .error {

            background: #f8d7da;
            color: #721c24;

        }

        .info {

            background: #d1ecf1;
            color: #0c5460;

        }

        .btn {

            display: inline-block;
            padding: 12px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;

        }

        .btn:hover {

            background: #5a67d8;
            transform: scale(1.05);

        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Result</h2>

        @php

            $class = 'info';

            if (str_contains(strtolower($message), 'success') || str_contains(strtolower($message), 'generated')) {
                $class = 'success';
            }

            if (str_contains(strtolower($message), 'error')) {
                $class = 'error';
            }

        @endphp


        <div class="message {{ $class }}">
            {{ $message }}
        </div>


        <a href="/" class="btn">
            Back to Dashboard
        </a>

    </div>

</body>

</html>
<!DOCTYPE html>
<html>

<head>

    <title>Redeem Invite | Laravel 12 Doorman</title>

    <style>
        body {

            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            height: 100vh;

        }

        .container {

            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);

        }

        h2 {

            text-align: center;
            margin-bottom: 20px;
            color: #333;

        }

        label {

            font-weight: bold;
            display: block;
            margin-top: 10px;

        }

        input {

            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;

        }

        input:focus {

            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);

        }

        button {

            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;

        }

        button:hover {

            background: #218838;

        }

        .back {

            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;

        }

        .back:hover {

            text-decoration: underline;

        }

        .success {

            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;

        }

        .error {

            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;

        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Redeem Invite Code</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif


        <form method="POST" action="/redeem">

            @csrf

            <label>Email</label>

            <input type="email" name="email" placeholder="Enter your email" required>


            <label>Invite Code</label>

            <input type="text" name="code" placeholder="Enter invite code" required>


            <button type="submit">
                Redeem Invite
            </button>

        </form>

        <a href="/" class="back">← Back to Dashboard</a>

    </div>

</body>

</html>
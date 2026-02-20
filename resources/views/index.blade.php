<!DOCTYPE html>
<html>

<head>

    <title>Laravel 12 Doorman</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .container {

            width: 500px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);

            text-align: center;
        }

        h2 {

            margin-bottom: 30px;
            color: #333;

        }

        .btn {

            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;

            text-decoration: none;
            color: white;

            border-radius: 5px;

            font-weight: bold;

            transition: 0.3s;

        }

        .btn-single {
            background: #28a745;
        }

        .btn-multiple {
            background: #007bff;
        }

        .btn-expiry {
            background: #ffc107;
            color: black;
        }

        .btn-email {
            background: #17a2b8;
        }

        .btn-redeem {
            background: #dc3545;
        }

        .btn:hover {

            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

        }

        .footer {

            margin-top: 20px;
            font-size: 14px;
            color: gray;

        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Laravel 12 Doorman Dashboard</h2>

        <a href="/generate-single" class="btn btn-single">
            Generate Single Invite
        </a>

        <a href="/generate-multiple" class="btn btn-multiple">
            Generate Multiple Invites
        </a>

        <a href="/generate-expiry" class="btn btn-expiry">
            Generate Expiry Invite
        </a>

        <a href="/generate-email" class="btn btn-email">
            Generate Email Invite
        </a>

        <a href="/redeem" class="btn btn-redeem">
            Redeem Invite
        </a>

        <div class="footer">

            Laravel 12 Doorman Example

        </div>

    </div>

</body>

</html>
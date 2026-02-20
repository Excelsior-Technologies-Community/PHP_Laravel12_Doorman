# PHP_Laravel12_Doorman


## Project Description

PHP_Laravel12_Doorman is a Laravel 12 application that demonstrates how to implement an invite-code system using the Doorman package.

The system allows administrators to generate invite codes with usage limits, expiration dates, and email restrictions. Users can redeem invite codes, and the application validates them against the database.

This project follows Laravel’s MVC architecture and integrates Doorman’s official features such as invite generation, validation, redemption, and database storage.


## Features

• Generate single invite code

• Generate multiple invite codes

• Generate expiry invite code

• Generate email-specific invite code

• Redeem invite code

• Database storage

• Error handling

• Laravel 12 compatible


## Technologies Used

• PHP 8+

• Laravel 12

• MySQL

• Doorman Package

• Blade Template Engine

• Laravel MVC Architecture

• Composer




---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Doorman "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Doorman

```

#### Explanation:

This step installs a fresh Laravel 12 application using Composer and creates the project directory with all required Laravel core files.





## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_doorman
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_doorman

```

#### Explanation:

This step connects the Laravel application to the MySQL database so invite codes and other data can be stored and retrieved.




## STEP 3: Install Doorman Package

### Run command:

```
composer require "clarkeash/doorman:^10.0"

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

This installs the Doorman package and creates necessary database tables including the invites table.




## STEP 4: Publish Config

### Run:

```
php artisan vendor:publish --tag=doorman-config

```

### Now file created:

```
config/doorman.php

```

### Code: config/doorman.php:

```
<?php

return [

    'invite_table_name' => 'invites',

];

```


#### Explanation:

This creates the config/doorman.php file where you can configure invite table name and Doorman settings.




## STEP 5: Publish Language Files

### Run: 

```
php artisan vendor:publish --tag=doorman-translations

```

### Folder created:

```
resources/lang/vendor/doorman/en/messages.php

```


#### Explanation:

This publishes language files used by Doorman to display messages like invalid invite or expired invite.




## STEP 6: Create Controller

### Command:

```
php artisan make:controller DoormanController

```

### File: app/Http/Controllers/DoormanController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doorman;
use Carbon\Carbon;
use Clarkeash\Doorman\Exceptions\DoormanException;

class DoormanController extends Controller
{

    // Show Home Page
    public function index()
    {
        return view('index');
    }


    // Generate single invite
    public function generateSingle()
    {
        $invite = Doorman::generate()->once();

        return view('result', [
            'message' => 'Single Invite Code Generated: ' . $invite->code
        ]);
    }


    // Generate multiple invites
    public function generateMultiple()
    {
        $invites = Doorman::generate()
                    ->times(5)
                    ->make();

        $codes = [];

        foreach($invites as $invite)
        {
            $codes[] = $invite->code;
        }

        return view('result', [
            'message' => 'Multiple Invite Codes: ' . implode(', ', $codes)
        ]);
    }


    // Generate invite with expiry
    public function generateExpiry()
    {
        $invite = Doorman::generate()
                    ->expiresIn(7)
                    ->once();

        return view('result', [
            'message' => 'Invite Code with 7 days expiry: ' . $invite->code
        ]);
    }


    // Generate invite with email
    public function generateEmail()
    {
        $invite = Doorman::generate()
                    ->for('test@gmail.com')
                    ->once();

        return view('result', [
            'message' => 'Invite Code for test@gmail.com: ' . $invite->code
        ]);
    }


    // Show redeem form
    public function redeemForm()
    {
        return view('redeem');
    }


    // Redeem invite
    public function redeem(Request $request)
    {

        try {

            Doorman::redeem(
                $request->code,
                $request->email
            );

            return view('result', [
                'message' => 'Invite Redeemed Successfully'
            ]);

        } catch (DoormanException $e) {

            return view('result', [
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

}

```

#### Explanation:

This creates the controller that handles invite generation, invite redemption, and application logic.





## STEP 7: Create Routes

### File: routes/web.php

#### Code:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoormanController;

Route::get('/', [DoormanController::class, 'index']);

Route::get('/generate-single', [DoormanController::class, 'generateSingle']);

Route::get('/generate-multiple', [DoormanController::class, 'generateMultiple']);

Route::get('/generate-expiry', [DoormanController::class, 'generateExpiry']);

Route::get('/generate-email', [DoormanController::class, 'generateEmail']);

Route::get('/redeem', [DoormanController::class, 'redeemForm']);

Route::post('/redeem', [DoormanController::class, 'redeem']);

```


#### Explanation:

Routes connect browser URLs to controller functions, allowing users to generate and redeem invite codes.




## STEP 8: . Create Views File


### resources/views/index.blade.php

```
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
            box-shadow: 0 0 20px rgba(0,0,0,0.2);

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
            box-shadow: 0 0 10px rgba(0,0,0,0.3);

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

```



### resources/views/redeem.blade.php

```
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
            box-shadow: 0 0 20px rgba(0,0,0,0.2);

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
            box-shadow: 0 0 5px rgba(102,126,234,0.5);

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

```


### resources/views/result.blade.php

```
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
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
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

        if(str_contains(strtolower($message), 'success') || str_contains(strtolower($message), 'generated'))
        {
            $class = 'success';
        }

        if(str_contains(strtolower($message), 'error'))
        {
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

```

#### Explanation:

These Blade view files provide the user interface for generating invites, redeeming invites, and displaying results.




## STEP 9: Run Server

### Run: 

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000

```

#### Explanation:

This starts the Laravel development server and allows you to access the application in your browser.





## Application Output:

### Dashboard allows:


<img width="1900" height="959" alt="Screenshot 2026-02-20 172226" src="https://github.com/user-attachments/assets/285f5113-c947-41ee-bea5-31a39bed9d0b" />


### Generate Single Invite Code:


<img width="1907" height="949" alt="Screenshot 2026-02-20 172242" src="https://github.com/user-attachments/assets/591cfeea-6c36-454d-9327-a8a101b29607" />


### Generate Multiple Invite Codes:  


<img width="1902" height="948" alt="Screenshot 2026-02-20 172258" src="https://github.com/user-attachments/assets/8f79de60-b349-46c0-a87e-11f05bdea70f" />


### Generate Expiry Invite Code:


<img width="1890" height="932" alt="Screenshot 2026-02-20 172318" src="https://github.com/user-attachments/assets/8cafcb92-cb3e-4bfb-a560-1838e266c29c" />


### Generate Email-specific Invite Code:


<img width="1890" height="958" alt="Screenshot 2026-02-20 172333" src="https://github.com/user-attachments/assets/739c90c3-6da7-4b52-9d3c-499522d4e4db" />


### Redeem Invite Cod:


<img width="1891" height="963" alt="Screenshot 2026-02-20 172413" src="https://github.com/user-attachments/assets/919996b8-0f5b-4a95-b87b-d838ba17930a" />

 <img width="1884" height="950" alt="Screenshot 2026-02-20 172505" src="https://github.com/user-attachments/assets/99131a5d-e611-4c62-9a23-8b9e4bcbfad0" />



---

# Project Folder Structure:

```
PHP_Laravel12_Doorman/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── DoormanController.php
│   │   │
│   │   ├── Middleware/
│   │   │
│   │   └── Kernel.php
│   │
│   ├── Models/
│   │   └── User.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   └── app.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   ├── doorman.php
│   └── services.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── xxxx_xx_xx_xxxxxx_create_invites_table.php
│   │
│   └── seeders/
│
├── public/
│   ├── index.php
│   └── .htaccess
│
├── resources/
│   ├── views/
│   │   ├── index.blade.php
│   │   ├── redeem.blade.php
│   │   └── result.blade.php
│   │
│   ├── lang/
│   │   └── vendor/
│   │       └── doorman/
│   │           └── en/
│   │               └── messages.php
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── composer.lock

```

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Employee Added</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            text-align: center;
            color: #333333;
        }
        .content h2 {
            color: #007bff;
            margin-top: 0;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
        }
        .name {
            font-size: 20px;
            font-weight: bold;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Exciting News for {{ $company->name }}!</h1>
        </div>
        <div class="content">
            <h2>A New Employee Has Joined the Team</h2>
            <p>
                We would like to inform you that a new employee has been added to the system and is officially joining our team.
            </p>
            <p class="name">
                {{ $employee->first_name }} {{ $employee->last_name }}
            </p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Team Incubation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            color: #374151;
        }
        .error-card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 450px;
            width: 100%;
        }
        .error-code {
            font-size: 72px;
            font-weight: 800;
            color: #ea580c; /* Coral/orange warning */
            margin: 0;
            line-height: 1;
        }
        h1 {
            font-size: 24px;
            margin: 20px 0 10px;
            color: #111827;
        }
        p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn {
            display: inline-block;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn-primary {
            background-color: #0d9488;
        }
        .btn-primary:hover {
            background-color: #0f766e;
        }
        .btn-secondary {
            background-color: #4b5563;
        }
        .btn-secondary:hover {
            background-color: #374151;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">403</div>
        <h1>Access Denied</h1>
        <p>You do not have the database-driven permissions required to access this area. If you believe this is an error, please contact your administrator.</p>
        <div class="btn-container">
            <a href="/dashboard" class="btn btn-primary">Go to Dashboard</a>
            <a href="/logout" class="btn btn-secondary">Sign Out</a>
        </div>
    </div>
</body>
</html>

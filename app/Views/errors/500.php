<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Server Error - Team Incubation</title>
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
            color: #dc2626; /* Red error */
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
        .btn {
            display: inline-block;
            background-color: #0d9488;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0f766e;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">500</div>
        <h1>Internal Server Error</h1>
        <p>An unexpected error occurred on our server. The issue has been logged, and our team is looking into it. Please try again later.</p>
        <?php if (isset($exception)): ?>
            <div style="background: #fee2e2; border: 1px solid #ef4444; padding: 10px; text-align: left; font-size: 12px; font-family: monospace; overflow-wrap: break-word; margin-bottom: 20px;">
                <strong>Error:</strong> <?= htmlspecialchars($exception->getMessage()) ?><br>
                <strong>File:</strong> <?= htmlspecialchars($exception->getFile()) ?> : <?= $exception->getLine() ?>
            </div>
        <?php endif; ?>
        <a href="/" class="btn">Return to Homepage</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Team Incubation') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-base: #f5fafd; /* Ice white background */
            --bg-surface: rgba(255, 255, 255, 0.85); /* Glassmorphic white */
            --bg-card: #ffffff; /* Pure white */
            --primary: #26b5d1; /* Turquoise primary */
            --primary-hover: #1da3bd;
            --secondary: #0ea5e9; /* Sky blue secondary */
            --text-main: #1e293b; /* Slate-800 */
            --text-muted: #577399; /* Steel blue muted text */
            --border-glow: rgba(38, 181, 209, 0.12); /* Soft turquoise borders */
            --border-active: rgba(38, 181, 209, 0.35);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Pastel Color Palette */
            --pastel-blue: #e0f2fe;
            --pastel-teal: #e6fcff;
            --pastel-green: #e6fcf5;
            --pastel-pink: #fff5f7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: 
                radial-gradient(at 0% 0%, rgba(38, 181, 209, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.05) 0px, transparent 50%);
        }
    </style>
</head>
<body>
    <?= $content ?>
</body>
</html>

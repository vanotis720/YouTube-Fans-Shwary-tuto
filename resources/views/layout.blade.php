<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offrez un Sourire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Space+Grotesk:wght@400;500;600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Space Grotesk', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, rgba(255, 234, 210, 0.65), transparent 45%),
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.45), transparent 35%),
                #0f172a;
        }

        .title-font {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="min-h-screen text-slate-100">
    @yield('content')
    <footer
        class="border-t border-white/10 bg-slate-950/60 py-10 text-center text-xs uppercase tracking-[0.3em] text-slate-400">
        Créé avec gratitude et néons ✶</footer>
</body>

</html>

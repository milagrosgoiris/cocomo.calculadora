@vite(['resources/css/app.css','resources/js/app.js'])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora COCOMO</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <main class="min-h-screen p-8">
        @livewire('cocomo-calculator')
    </main>

    @livewireScripts
</body>
</html>

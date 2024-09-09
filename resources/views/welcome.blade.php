<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... -->
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="app">
        <app></app>
    </div>
    <!-- ... -->
</body>
</html>

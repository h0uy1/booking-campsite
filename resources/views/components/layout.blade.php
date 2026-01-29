<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campsite Supply Tracker</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- Sidebar -->
<aside class="w-64 bg-white border-r border-gray-200 px-4 py-6 space-y-6">
    <h1 class="text-xl font-bold text-green-600">Campsite Supply Tracker</h1>

    <nav class="space-y-3 text-gray-600">
        <a href="#" class="block font-medium text-green-600">Dashboard</a>
        <a href="#" class="block hover:text-green-600">Inventory List</a>
        <a href="#" class="block hover:text-green-600">Add / Receive Stock</a>
    </nav>

    <div class="pt-10 space-y-3 text-gray-500">
        <a href="#" class="block hover:text-red-500">Settings</a>
        <a href="#" class="block hover:text-red-500">Logout</a>
    </div>
</aside>

<!-- Main -->
<div class="flex-1 flex flex-col">

<!-- Top Bar -->
<header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
    <div class="flex items-center bg-gray-100 space-x-4 rounded-lg px-4 py-2 w-80 hover:ring ">
        <img src="https://img.icons8.com/?size=100&id=HNqs6ySvgu0d&format=png&color=000000" alt="" class="w-4 h-4">
        <input 
                type="text"
                placeholder="Search..."
                class="focus:outline-none"
        >
    </div>
    

    <div class="flex items-center space-x-4">
        <img src="https://img.icons8.com/?size=100&id=13717&format=png&color=000000" alt="" class="rounded-full w-8 h-8">
        <img src="https://img.icons8.com/?size=100&id=23309&format=png&color=000000" class="rounded-full w-10 h-10 object-cover bg-black" />
    </div>
</header>

<!-- Content -->
    <main class="p-6 space-y-6">
        {{ $slot }}
    </main>
</div>

</body>
</html>

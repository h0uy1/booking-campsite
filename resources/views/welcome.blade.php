
<x-layout>
    <h2 class="text-2xl font-semibold">Dashboard</h2>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-5 rounded-xl shadow-sm">
        <p class="text-gray-500 text-sm">Total Items</p>
        <h3 class="text-3xl font-bold">150</h3>
        <p class="text-xs text-gray-400">All items currently tracked</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm">
        <p class="text-gray-500 text-sm">Items in Stock</p>
        <h3 class="text-3xl font-bold">135</h3>
        <p class="text-xs text-gray-400">Currently available</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm">
        <p class="text-gray-500 text-sm">Low Stock Items</p>
        <h3 class="text-3xl font-bold text-red-500">15</h3>
        <p class="text-xs text-gray-400">Need replacement</p>
    </div>

    </div>

    <!-- Middle -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Low Stock -->
    <div class="bg-white p-5 rounded-xl shadow-sm col-span-2">
        <h3 class="font-semibold mb-4">Low Stock Alerts</h3>

        <ul class="space-y-3 text-sm">
            <li class="flex justify-between">
                <span>Lantern Fuel Canisters</span>
                <span class="text-red-500">5 units</span>
            </li>
            <li class="flex justify-between">
                <span>Tent Stakes (Set of 10)</span>
                <span class="text-red-500">12 units</span>
            </li>
            <li class="flex justify-between">
                <span>First Aid Kit</span>
                <span class="text-red-500">1 unit</span>
            </li>
            <li class="flex justify-between">
                <span>Headlamp Batteries</span>
                <span class="text-red-500">20 units</span>
            </li>
        </ul>
    </div>

    <!-- Actions -->
    <div class="bg-white p-5 rounded-xl shadow-sm space-y-4">
        <h3 class="font-semibold">Quick Actions</h3>

        <button class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
            + Add New Stock
        </button>

        <button class="w-full border py-2 rounded-lg hover:bg-gray-100">
            View All Inventory
        </button>
    </div>

    </div>

    <!-- Recent Activity -->
    <div class="bg-white p-5 rounded-xl shadow-sm">
    <h3 class="font-semibold mb-4">Recent Activity</h3>

    <ul class="space-y-2 text-sm text-gray-600">
        <li>Added 10 units of Camp Chairs — 2 hours ago</li>
        <li>Updated 5 units of Headlamps — 4 hours ago</li>
        <li>Removed 2 units of Sleeping Bags — 1 day ago</li>
        <li>Received 20 units of Propane Tanks — 2 days ago</li>
        <li>Adjusted stock for Portable Grills — 3 days ago</li>
    </ul>
</x-layout>
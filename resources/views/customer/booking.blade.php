<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Session</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blue: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        orange: {
                            500: '#f97316',
                            600: '#ea580c'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Book a Photoshoot Session</h1>
            <div class="text-orange-500 font-bold bg-orange-100 px-3 py-1 rounded-lg">Beta</div>
        </div>
        
        <form action="/bookings" method="POST">
            @csrf
            <!-- Photographer Selection -->
            <div class="mb-4">
                <label for="photographer" class="text-gray-700 font-medium">Select Photographer</label>
                <select id="photographer" name="photographer_id" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <option value="">Choose Photographer</option>
                    <option value="1">Alex Johnson</option>
                    <option value="2">Jane Smith</option>
                </select>
            </div>

            <!-- Date Selection -->
            <div class="mb-4">
                <label for="date" class="text-gray-700 font-medium">Select Date</label>
                <input type="date" id="date" name="date" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Time Selection -->
            <div class="mb-4">
                <label for="time" class="text-gray-700 font-medium">Select Time</label>
                <input type="time" id="time" name="time" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Package Selection -->
            <div class="mb-4">
                <label for="package" class="text-gray-700 font-medium">Select Package</label>
                <select id="package" name="package" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <option value="">Choose Package</option>
                    <option value="basic">Basic Session - Rp 500.000</option>
                    <option value="premium">Premium Session - Rp 1.200.000</option>
                    <option value="wedding">Wedding Package - Rp 3.500.000</option>
                </select>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="text-gray-700 font-medium">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter location details" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-700 text-white py-3 px-4 rounded-lg hover:bg-blue-800 transition-colors font-medium">
                    Booking Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 flex justify-center space-x-4 text-sm text-gray-600">
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">1,200+</span>
                <span>Fotografer Aktif</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">24+</span>
                <span>Kota di Indonesia</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">15K+</span>
                <span>Momen Terabadikan</span>
            </div>
        </div>
    </div>
</body>
</html>
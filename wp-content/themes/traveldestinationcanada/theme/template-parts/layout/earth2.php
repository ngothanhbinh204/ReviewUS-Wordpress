<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Interactive Canada Globe - Explore Canadian Territories</title>
    <meta name="description"
        content="Explore Canada's provinces and territories with our interactive 3D globe. Discover landmarks, cities, and natural wonders across the Great White North.">

    <!-- Mapbox GL JS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css' rel='stylesheet' />

    <!-- Preload critical fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white overflow-hidden font-sans">
    <div id="app" class="relative w-full h-screen">
        <!-- Loading Screen -->
        <div id="loading"
            class="absolute inset-0 bg-gradient-to-br from-gray-900 to-gray-800 flex items-center justify-center z-50">
            <div class="text-center">
                <div class="loading-spinner rounded-full h-32 w-32 border-b-4 border-green-500 mb-6 mx-auto"></div>
                <h2 class="text-2xl font-bold mb-2">Loading Interactive Globe...</h2>
                <p class="text-gray-400">Preparing your journey through Canada</p>
            </div>
        </div>

        <!-- Map Container -->
        <div id="map" class="w-full h-full"></div>

        <!-- Territory Info Sidebar -->
        <div id="territory-sidebar"
            class="absolute left-6 top-1/2 transform -translate-y-1/2 w-80 bg-white text-gray-900 rounded-xl shadow-2xl p-6 opacity-0 pointer-events-none transition-all duration-500 z-40 max-h-[80vh] overflow-y-auto">
            <div id="territory-image" class="w-full h-48 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                <img id="territory-img" src="" alt=""
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
            <h2 id="territory-name" class="text-2xl font-bold text-green-700 mb-3"></h2>
            <p id="territory-description" class="text-gray-700 leading-relaxed mb-6 text-sm"></p>

            <div class="flex space-x-3">
                <button id="discover-btn"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                    Discover More
                </button>
                <button id="close-sidebar"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- Main Info Panel -->
        <div id="main-info"
            class="absolute right-6 top-1/2 transform -translate-y-1/2 w-96 bg-white text-gray-900 rounded-xl shadow-2xl p-8 transition-all duration-500 z-30">
            <div class="mb-4">
                <span class="text-sm text-gray-500 font-medium">Canada, naturally.</span>
            </div>
            <h1 class="text-4xl font-bold text-red-600 mb-2">Once-in-a-</h1>
            <h1 class="text-4xl font-bold text-red-600 mb-6">lifetime happens all the time.</h1>
            <p class="text-gray-700 leading-relaxed mb-6 text-sm">
                Whether it's postcard-worthy views at every pitstop or warm-hearted folks always eager to offer a
                helping hand,
                the exceptional is just a part of everyday life in Canada. Wandering through cities can sometimes mean
                pausing
                your commute for a four-legged passerby. Sitting down to dinner often comes with an epic backdrop. And
                if the
                northern lights start stirring overhead? In the North, that's just a regular Tuesday. Extraordinary
                experiences
                come naturally here.
            </p>
            <button class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                Learn more
            </button>
        </div>

        <!-- Controls -->
        <div class="absolute top-6 left-6 z-40 space-y-3">
            <button id="reset-view" class="control-button flex items-center space-x-2">
                <span>🌍</span>
                <span>Reset View</span>
            </button>
            <button id="toggle-3d" class="control-button flex items-center space-x-2">
                <span>🎛️</span>
                <span>Toggle 3D</span>
            </button>
        </div>

        <!-- Legend -->
        <div
            class="absolute bottom-6 left-6 z-40 bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-4 text-gray-900 text-sm">
            <h3 class="font-bold mb-2">Legend</h3>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                    <span>Provincial Capitals</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                    <span>Major Cities</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-purple-600 rounded-full"></div>
                    <span>Landmarks</span>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div
            class="absolute top-6 right-6 z-40 bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-4 text-gray-900 text-sm max-w-xs">
            <h3 class="font-bold mb-2">How to Explore</h3>
            <ul class="space-y-1 text-xs">
                <li>• Hover over territories to see info</li>
                <li>• Click territories to zoom in</li>
                <li>• Click landmarks for details</li>
                <li>• Use controls to navigate</li>
            </ul>
        </div>
    </div>

    <script type="module" src="/main.js"></script>
</body>

</html>

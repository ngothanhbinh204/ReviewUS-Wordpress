/**
 * USA Interactive Map Controller
 * Multi-site architecture with permanent sidebar and slide-in info panel
 */

class USAInteractiveMap {
    constructor(containerId, config) {
        this.containerId = containerId;
        this.config = config;
        this.map = null;
        this.destinations = [];
        this.featuredDestinations = [];
        this.selectedDestination = null;
        this.markers = [];

        this.init();
    }

    async init() {
        try {
            await this.loadDestinations();
            await this.loadFeaturedDestinations();
            this.initializeMap();
            this.setupEventListeners();
            this.renderFeaturedSidebar();
            this.hideLoading();
        } catch (error) {
            console.error('Map initialization error:', error);
            this.showError('Failed to load map. Please check your Mapbox token.');
        }
    }

    async loadDestinations() {
        const response = await fetch(`${this.config.apiUrl}/destinations`);
        if (!response.ok) throw new Error('Failed to fetch destinations');
        this.destinations = await response.json();
    }

    async loadFeaturedDestinations() {
        const response = await fetch(`${this.config.apiUrl}/featured`);
        if (!response.ok) throw new Error('Failed to fetch featured destinations');
        this.featuredDestinations = await response.json();
    }

    initializeMap() {
        mapboxgl.accessToken = this.config.mapboxToken;

        const mapElement = document.getElementById(this.containerId);

        // ===================================================
        // VỊ TRÍ CAMERA VÀ GÓC NHÌN MẶC ĐỊNH
        // ===================================================
        const initialZoom = parseFloat(mapElement.dataset.initialZoom) || 4;
        // Tọa độ trung tâm USA (Kansas)
        const centerLat = parseFloat(mapElement.dataset.centerLat) || 39.8283;
        const centerLng = parseFloat(mapElement.dataset.centerLng) || -98.5795;
        // Góc nghiêng (pitch): 45 độ để nhìn 3D
        // Góc xoay (bearing): 0 độ (hướng Bắc)
        // ===================================================

        this.map = new mapboxgl.Map({
            container: this.containerId,
            // style: 'mapbox://styles/mapbox/dark-v11',
			style: 'mapbox://styles/ngothanhbinh2004/cmcsgjw4500hu01s7d3czhtv2',
            center: [centerLng, centerLat],    // [-98.5795, 39.8283]
            zoom: initialZoom,                  // 4
            pitch: 10,                          // Góc nghiêng 45°
            bearing: 0,                         // Hướng Bắc
            antialias: true,
            scrollZoom: false                   // TẮT scroll zoom mặc định
        });

        // Chỉ cho phép zoom khi giữ Ctrl + scroll
        this.map.on('wheel', (e) => {
            if (e.originalEvent.ctrlKey) {
                // Cho phép zoom khi giữ Ctrl
                e.originalEvent.preventDefault();
                this.map.scrollZoom.enable();
            } else {
                // Ngăn zoom, cho phép scroll trang bình thường
                this.map.scrollZoom.disable();
            }
        });

        this.map.on('load', () => {
            this.addWorldBoundaries();          // Add world countries (faded)
            this.addUSAHighlight();             // Highlight USA territories
            this.addDestinationLayers();        // Add state polygons
            this.addCityMarkers();              // Add city markers
            this.add3DTerrain();                // Add 3D terrain
            this.setupGlobeInteractions();      // Setup click handlers on globe
        });
    }

    /**
     * Add world boundaries with faded appearance (non-USA regions)
     */
    addWorldBoundaries() {
        // Add world country boundaries (built-in Mapbox source)
        this.map.addLayer({
            'id': 'world-countries-faded',
            'type': 'fill',
            'source': {
                'type': 'vector',
                'url': 'mapbox://mapbox.country-boundaries-v1'
            },
            'source-layer': 'country_boundaries',
            'filter': ['!=', ['get', 'iso_3166_1'], 'US'], // All countries except USA
            'paint': {
                'fill-color': '#2d3748',        // Dark gray
                'fill-opacity': 0.3             // Semi-transparent (faded)
            }
        });

        // World country outlines
        this.map.addLayer({
            'id': 'world-countries-outline',
            'type': 'line',
            'source': {
                'type': 'vector',
                'url': 'mapbox://mapbox.country-boundaries-v1'
            },
            'source-layer': 'country_boundaries',
            'paint': {
                'line-color': '#1a202c',
                'line-width': 1,
                'line-opacity': 0.5
            }
        });
    }

    /**
     * Highlight USA with solid gray color on the globe
     */
    addUSAHighlight() {
        // USA territory fill (solid gray)
        this.map.addLayer({
            'id': 'usa-highlight-fill',
            'type': 'fill',
            'source': {
                'type': 'vector',
                'url': 'mapbox://mapbox.country-boundaries-v1'
            },
            'source-layer': 'country_boundaries',
            'filter': ['==', ['get', 'iso_3166_1'], 'US'], // Only USA
            'paint': {
                'fill-color': '#4a5568',        // Solid gray
                'fill-opacity': 0.8             // Prominent
            }
        });

        // USA outline (prominent white border)
        this.map.addLayer({
            'id': 'usa-highlight-outline',
            'type': 'line',
            'source': {
                'type': 'vector',
                'url': 'mapbox://mapbox.country-boundaries-v1'
            },
            'source-layer': 'country_boundaries',
            'filter': ['==', ['get', 'iso_3166_1'], 'US'],
            'paint': {
                'line-color': '#ffffff',
                'line-width': 2,
                'line-opacity': 1
            }
        });
    }

    /**
     * Setup click interactions on globe territories
     * Modular design for easy addition of hover, tooltip, etc.
     */
    setupGlobeInteractions() {
        // Click on USA territory to focus camera
        this.map.on('click', 'usa-highlight-fill', (e) => {
            this.handleUSATerritoryClick(e);
        });

        // Hover effect on USA territory
        this.map.on('mouseenter', 'usa-highlight-fill', () => {
            this.handleUSATerritoryHover(true);
        });

        this.map.on('mouseleave', 'usa-highlight-fill', () => {
            this.handleUSATerritoryHover(false);
        });

        // Click on individual state/region polygons
        this.map.on('click', 'territory-fills', (e) => {
            if (e.features.length > 0) {
                const destinationId = e.features[0].properties.id;
                this.handleTerritoryClick(destinationId);
            }
        });
    }

    /**
     * Handle click on USA territory (country-level)
     * Modular method for easy customization
     */
    handleUSATerritoryClick(event) {
        // Focus camera on USA
        this.map.flyTo({
            center: [-98.5795, 39.8283],
            zoom: 4,
            pitch: 45,
            bearing: 0,
            duration: 2000,
            essential: true
        });

        // Optional: Show USA overview panel
        // this.showUSAOverview();
    }

    /**
     * Handle hover on USA territory
     * Modular method for future tooltip/highlight enhancements
     */
    handleUSATerritoryHover(isHovering) {
        this.map.getCanvas().style.cursor = isHovering ? 'pointer' : '';

        // Change USA highlight color on hover
        this.map.setPaintProperty(
            'usa-highlight-fill',
            'fill-opacity',
            isHovering ? 1 : 0.8
        );

        // Future: Add tooltip showing "Click to explore USA"
        // if (isHovering) {
        //     this.showTooltip('Click to explore USA', event.lngLat);
        // }
    }

    /**
     * Handle click on individual state/region territory
     * Modular method separate from country-level interaction
     */
    handleTerritoryClick(destinationId) {
        this.selectDestination(destinationId);
    }

    add3DTerrain() {
        this.map.addSource('mapbox-dem', {
            'type': 'raster-dem',
            'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
            'tileSize': 512,
            'maxzoom': 14
        });

        this.map.setTerrain({ 'source': 'mapbox-dem', 'exaggeration': 1.5 });

        this.map.addLayer({
            'id': 'sky',
            'type': 'sky',
            'paint': {
                'sky-type': 'atmosphere',
                'sky-atmosphere-sun': [0.0, 0.0],
                'sky-atmosphere-sun-intensity': 15
            }
        });
    }

    /**
     * Thêm City Markers (dấu chấm tròn) cho các city bên trong State
     */
    addCityMarkers() {
        // Lọc các destination level = 'city' có coordinates
        const cities = this.destinations.filter(d =>
            d.destination_level === 'city' && d.coordinates
        );

        if (cities.length === 0) return;

        // Tạo GeoJSON source cho city markers
        this.map.addSource('city-markers', {
            'type': 'geojson',
            'data': {
                'type': 'FeatureCollection',
                'features': cities.map(city => ({
                    'type': 'Feature',
                    'properties': {
                        'id': city.id,
                        'title': city.title,
                        'parent': city.parent_destination
                    },
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [city.coordinates.lng, city.coordinates.lat]
                    }
                }))
            }
        });

        // Layer: Vòng tròn bên ngoài (viền trắng)
        this.map.addLayer({
            'id': 'city-markers-outer',
            'type': 'circle',
            'source': 'city-markers',
            'paint': {
                'circle-radius': 8,
                'circle-color': '#ffffff',
                'circle-opacity': 1
            }
        });

        // Layer: Vòng tròn bên trong (màu đỏ)
        this.map.addLayer({
            'id': 'city-markers-inner',
            'type': 'circle',
            'source': 'city-markers',
            'paint': {
                'circle-radius': 6,
                'circle-color': '#dc2626',
                'circle-opacity': 1
            }
        });

        // Layer: Label tên city
        this.map.addLayer({
            'id': 'city-labels',
            'type': 'symbol',
            'source': 'city-markers',
            'layout': {
                'text-field': ['get', 'title'],
                'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
                'text-size': 11,
                'text-offset': [0, 1.5],
                'text-anchor': 'top'
            },
            'paint': {
                'text-color': '#dc2626',
                'text-halo-color': '#ffffff',
                'text-halo-width': 2
            }
        });

        // Hover effect cho city markers
        this.map.on('mouseenter', 'city-markers-inner', () => {
            this.map.getCanvas().style.cursor = 'pointer';
        });

        this.map.on('mouseleave', 'city-markers-inner', () => {
            this.map.getCanvas().style.cursor = '';
        });

        // Click city marker để xem chi tiết
        this.map.on('click', 'city-markers-inner', (e) => {
            if (e.features.length > 0) {
                const cityId = e.features[0].properties.id;
                this.selectDestination(cityId);
            }
        });
    }

    addDestinationLayers() {
        // Chỉ hiển thị State/Region (có GeoJSON polygon)
        const destinationsWithGeoJSON = this.destinations.filter(d =>
            d.geojson && (d.destination_level === 'state' || d.destination_level === 'region')
        );

        if (destinationsWithGeoJSON.length === 0) return;

        // Add source for all territories (States/Regions)
        this.map.addSource('territories', {
            'type': 'geojson',
            'data': {
                'type': 'FeatureCollection',
                'features': destinationsWithGeoJSON.map(dest => ({
                    'type': 'Feature',
                    'properties': {
                        'id': dest.id,
                        'title': dest.title,
                        'color': dest.map_color || '#dc2626',
                        'level': dest.destination_level
                    },
                    'geometry': dest.geojson
                }))
            }
        });

        // Fill layer - Màu nền cho từng State/Region
        // Note: These appear on TOP of the gray USA highlight
        this.map.addLayer({
            'id': 'territory-fills',
            'type': 'fill',
            'source': 'territories',
            'paint': {
                'fill-color': ['get', 'color'],  // Lấy màu từ ACF field
                'fill-opacity': [
                    'case',
                    ['boolean', ['feature-state', 'hover'], false],
                    0.9,    // Opacity khi hover: very prominent
                    0.7     // Opacity mặc định: stand out from gray USA
                ]
            }
        });

        // Outline layer - Ranh giới giữa các State/Region
        this.map.addLayer({
            'id': 'territory-outlines',
            'type': 'line',
            'source': 'territories',
            'paint': {
                'line-color': '#ffffff',    // Viền trắng
                'line-width': 3,            // Độ dày viền rõ ràng
                'line-opacity': 0.8
            }
        });

        // Labels layer
        this.map.addLayer({
            'id': 'territory-labels',
            'type': 'symbol',
            'source': 'territories',
            'layout': {
                'text-field': ['get', 'title'],
                'text-size': 14,
                'text-transform': 'uppercase'
            },
            'paint': {
                'text-color': '#1f2937',
                'text-halo-color': '#ffffff',
                'text-halo-width': 2
            }
        });

        // Hover effect
        let hoveredStateId = null;

        this.map.on('mousemove', 'territory-fills', (e) => {
            if (e.features.length > 0) {
                if (hoveredStateId !== null) {
                    this.map.setFeatureState(
                        { source: 'territories', id: hoveredStateId },
                        { hover: false }
                    );
                }
                hoveredStateId = e.features[0].id;
                this.map.setFeatureState(
                    { source: 'territories', id: hoveredStateId },
                    { hover: true }
                );
                this.map.getCanvas().style.cursor = 'pointer';
            }
        });

        this.map.on('mouseleave', 'territory-fills', () => {
            if (hoveredStateId !== null) {
                this.map.setFeatureState(
                    { source: 'territories', id: hoveredStateId },
                    { hover: false }
                );
            }
            hoveredStateId = null;
            this.map.getCanvas().style.cursor = '';
        });

        // Note: Click handler moved to setupGlobeInteractions() for modularity
    }

    async selectDestination(destinationId, fromSidebar = false) {
        try {
            const response = await fetch(`${this.config.apiUrl}/destination/${destinationId}`);
            if (!response.ok) throw new Error('Failed to fetch destination');

            const destination = await response.json();
            this.selectedDestination = destination;

            // Zoom to destination
            if (destination.coordinates) {
                this.map.flyTo({
                    center: [destination.coordinates.lng, destination.coordinates.lat],
                    zoom: destination.zoom_level || 6,
                    pitch: 60,
                    bearing: 0,
                    duration: 2000,
                    essential: true
                });
            }

            // Add POI markers
            this.clearMarkers();
            if (destination.points_of_interest && destination.points_of_interest.length > 0) {
                destination.points_of_interest.forEach(poi => {
                    this.addPOIMarker(poi);
                });
            }

            // Show info panel on left
            this.showInfoPanel(destination);

            // Hide featured sidebar on right when territory selected
            if (fromSidebar) {
                this.hideFeaturedSidebar();
            }

        } catch (error) {
            console.error('Error selecting destination:', error);
        }
    }

    addPOIMarker(poi) {
        if (!poi.coordinates) return;

        const el = document.createElement('div');
        el.className = 'map-poi-marker';
        el.innerHTML = `
            <div class="map-poi-marker-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        `;

        const popup = new mapboxgl.Popup({ offset: 25 }).setHTML(`
            <div class="map-poi-popup">
                ${poi.image ? `<img src="${poi.image.url}" alt="${poi.name}" />` : ''}
                <h4>${poi.name}</h4>
                <p>${poi.description || ''}</p>
            </div>
        `);

        const marker = new mapboxgl.Marker(el)
            .setLngLat([poi.coordinates.lng, poi.coordinates.lat])
            .setPopup(popup)
            .addTo(this.map);

        this.markers.push(marker);
    }

    clearMarkers() {
        this.markers.forEach(marker => marker.remove());
        this.markers = [];
    }

    renderFeaturedSidebar() {
        const listContainer = document.getElementById('map-featured-list');
        if (!listContainer) return;

        if (this.featuredDestinations.length === 0) {
            listContainer.innerHTML = '<p class="text-sm text-gray-500 p-4">No featured destinations yet.</p>';
            return;
        }

        listContainer.innerHTML = this.featuredDestinations.map(dest => `
            <div class="map-featured-item" data-destination-id="${dest.id}">
                ${dest.thumbnail ? `
                    <div class="map-featured-item-image">
                        <img src="${dest.thumbnail}" alt="${dest.title}" />
                    </div>
                ` : ''}
                <div class="map-featured-item-content">
                    <h4 class="map-featured-item-title">${dest.title}</h4>
                    <p class="map-featured-item-excerpt">${dest.excerpt || ''}</p>
                    <span class="map-featured-item-level">${this.formatLevel(dest.destination_level)}</span>
                </div>
            </div>
        `).join('');

        // Add click handlers
        listContainer.querySelectorAll('.map-featured-item').forEach(item => {
            item.addEventListener('click', () => {
                const destinationId = parseInt(item.dataset.destinationId);
                this.selectDestination(destinationId, true);
            });
        });
    }

    formatLevel(level) {
        const labels = {
            'country': 'Country',
            'region': 'Region',
            'state': 'State',
            'city': 'City',
            'attraction': 'Attraction'
        };
        return labels[level] || level;
    }

    showInfoPanel(destination) {
        const panel = document.getElementById('map-info-panel');
        if (!panel) return;

        // Populate content
        const img = panel.querySelector('.map-info-panel-image img');
        if (img && destination.thumbnail) {
            img.src = destination.thumbnail;
            img.alt = destination.title;
        }

        const title = panel.querySelector('.map-info-panel-body h2');
        if (title) title.textContent = destination.title;

        const excerpt = panel.querySelector('.map-info-panel-body > div');
        if (excerpt) excerpt.innerHTML = destination.excerpt || destination.content || '';

        const link = panel.querySelector('.map-info-panel-actions a');
        if (link) link.href = destination.url;

        // Render POIs
        const poisContainer = panel.querySelector('.poi-list');
        if (poisContainer && destination.points_of_interest) {
            poisContainer.innerHTML = destination.points_of_interest.map(poi => `
                <div class="poi-item">
                    <div class="poi-item-icon">📍</div>
                    <div>
                        <div class="font-semibold">${poi.name}</div>
                        <div class="text-sm text-gray-600">${poi.description || ''}</div>
                    </div>
                </div>
            `).join('');
        }

        // Show panel
        panel.classList.remove('hidden');
        requestAnimationFrame(() => {
            panel.classList.add('active');
        });
    }

    hideInfoPanel() {
        const panel = document.getElementById('map-info-panel');
        if (!panel) return;

        panel.classList.remove('active');
        setTimeout(() => {
            panel.classList.add('hidden');
        }, 300);

        this.clearMarkers();
        this.resetView();
        this.showFeaturedSidebar();
    }

    hideFeaturedSidebar() {
        const sidebar = document.getElementById('map-featured-sidebar');
        if (sidebar) {
            sidebar.classList.add('hidden');
        }
    }

    showFeaturedSidebar() {
        const sidebar = document.getElementById('map-featured-sidebar');
        if (sidebar) {
            sidebar.classList.remove('hidden');
        }
    }

    resetView() {
        const mapElement = document.getElementById(this.containerId);
        const initialZoom = parseFloat(mapElement.dataset.initialZoom) || 4;
        const centerLat = parseFloat(mapElement.dataset.centerLat) || 39.8283;
        const centerLng = parseFloat(mapElement.dataset.centerLng) || -98.5795;

        this.map.flyTo({
            center: [centerLng, centerLat],
            zoom: initialZoom,
            pitch: 45,
            bearing: 0,
            duration: 2000,
            essential: true
        });
    }

    setupEventListeners() {
        // Close button
        const closeBtn = document.querySelector('.map-info-panel-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.hideInfoPanel();
            });
        }

        // Mobile selector
        const mobileSelect = document.getElementById('map-territory-select');
        if (mobileSelect) {
            this.destinations.forEach(dest => {
                const option = document.createElement('option');
                option.value = dest.id;
                option.textContent = dest.title;
                mobileSelect.appendChild(option);
            });

            mobileSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    this.selectDestination(parseInt(e.target.value));
                }
            });
        }

        // Reset view button
        const resetBtn = document.getElementById('map-reset-view');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.hideInfoPanel();
            });
        }
    }

    hideLoading() {
        const loading = document.getElementById('map-loading');
        if (loading) {
            loading.style.display = 'none';
        }
    }

    showError(message) {
        const loading = document.getElementById('map-loading');
        if (loading) {
            loading.innerHTML = `
                <div class="text-red-600">
                    <p class="font-semibold">${message}</p>
                </div>
            `;
        }
    }
}

// Initialize map when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const mapContainer = document.getElementById('usa-map');
    if (mapContainer && typeof USAMapConfig !== 'undefined') {
        new USAInteractiveMap('usa-map', USAMapConfig);
    }
});

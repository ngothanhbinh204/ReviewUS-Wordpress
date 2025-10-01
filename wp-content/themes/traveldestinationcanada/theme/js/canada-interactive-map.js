/**
 * Canada Interactive Map Component
 * Using Mapbox GL JS
 */

class CanadaInteractiveMap {
    constructor(config) {
        this.config = config;
        this.map = null;
        this.destinations = [];
        this.selectedDestination = null;
        this.markers = [];

        this.init();
    }

    async init() {
        // Check for Mapbox token
        if (!this.config.mapboxToken) {
            console.error('Mapbox token not configured');
            this.showError('Map configuration error. Please contact administrator.');
            return;
        }

        // Set Mapbox token
        mapboxgl.accessToken = this.config.mapboxToken;

        // Fetch destinations data
        await this.loadDestinations();

        // Initialize map
        this.initializeMap();

        // Setup event listeners
        this.setupEventListeners();

        // Hide loading
        this.hideLoading();
    }

    async loadDestinations() {
        try {
            const response = await fetch(`${this.config.apiUrl}/destinations`);
            this.destinations = await response.json();

            // Populate mobile selector
            this.populateMobileSelector();
        } catch (error) {
            console.error('Error loading destinations:', error);
            this.showError('Failed to load map data');
        }
    }

    initializeMap() {
        const mapElement = document.getElementById('canada-map');
        const initialZoom = parseFloat(mapElement.dataset.initialZoom) || 3.5;
        const centerLat = parseFloat(mapElement.dataset.centerLat) || 56.1304;
        const centerLng = parseFloat(mapElement.dataset.centerLng) || -106.3468;

        this.map = new mapboxgl.Map({
            container: 'canada-map',
            style: 'mapbox://styles/mapbox/light-v11',
            center: [centerLng, centerLat],
            zoom: initialZoom,
            pitch: 45, // 3D tilt
            bearing: 0,
            antialias: true
        });

        // Add navigation controls
        this.map.addControl(new mapboxgl.NavigationControl(), 'top-right');

        // Add fullscreen control
        this.map.addControl(new mapboxgl.FullscreenControl(), 'top-right');

        // Wait for map to load
        this.map.on('load', () => {
            this.addDestinationLayers();
        });
    }

    addDestinationLayers() {
        // Add a source for all destinations
        this.destinations.forEach((dest, index) => {
            if (!dest.geojson || !dest.geojson.coordinates) return;

            const sourceId = `destination-${dest.id}`;

            // Add source
            this.map.addSource(sourceId, {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    properties: {
                        id: dest.id,
                        title: dest.title,
                        color: dest.map_color
                    },
                    geometry: dest.geojson
                }
            });

            // Add fill layer
            this.map.addLayer({
                id: `${sourceId}-fill`,
                type: 'fill',
                source: sourceId,
                paint: {
                    'fill-color': dest.map_color,
                    'fill-opacity': 0.3
                }
            });

            // Add border layer
            this.map.addLayer({
                id: `${sourceId}-border`,
                type: 'line',
                source: sourceId,
                paint: {
                    'line-color': dest.map_color,
                    'line-width': 2
                }
            });

            // Add hover effect
            this.map.on('mouseenter', `${sourceId}-fill`, () => {
                this.map.getCanvas().style.cursor = 'pointer';
                this.map.setPaintProperty(`${sourceId}-fill`, 'fill-opacity', 0.6);
            });

            this.map.on('mouseleave', `${sourceId}-fill`, () => {
                this.map.getCanvas().style.cursor = '';
                if (this.selectedDestination?.id !== dest.id) {
                    this.map.setPaintProperty(`${sourceId}-fill`, 'fill-opacity', 0.3);
                }
            });

            // Add click handler
            this.map.on('click', `${sourceId}-fill`, () => {
                this.selectDestination(dest.id);
            });
        });
    }

    async selectDestination(destinationId) {
        try {
            // Fetch full destination data
            const response = await fetch(`${this.config.apiUrl}/destination/${destinationId}`);
            const destination = await response.json();

            this.selectedDestination = destination;

            // Fly to destination
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

            // Highlight selected territory
            this.highlightTerritory(destinationId);

            // Show info panel
            this.showInfoPanel(destination);

            // Add POI markers
            this.addPOIMarkers(destination.points_of_interest);

        } catch (error) {
            console.error('Error selecting destination:', error);
        }
    }

    highlightTerritory(destinationId) {
        // Reset all territories
        this.destinations.forEach(dest => {
            const layerId = `destination-${dest.id}-fill`;
            if (this.map.getLayer(layerId)) {
                this.map.setPaintProperty(layerId, 'fill-opacity', 0.3);
            }
        });

        // Highlight selected
        const selectedLayerId = `destination-${destinationId}-fill`;
        if (this.map.getLayer(selectedLayerId)) {
            this.map.setPaintProperty(selectedLayerId, 'fill-opacity', 0.7);
        }
    }

    addPOIMarkers(pois) {
        // Clear existing markers
        this.markers.forEach(marker => marker.remove());
        this.markers = [];

        if (!pois || pois.length === 0) return;

        pois.forEach(poi => {
            // Create custom marker element
            const el = document.createElement('div');
            el.className = 'map-poi-marker';
            el.innerHTML = `
                <div class="map-poi-marker-dot"></div>
                <div class="map-poi-marker-label">${poi.name}</div>
            `;

            // Create popup
            const popup = new mapboxgl.Popup({
                offset: 25,
                closeButton: false,
                className: 'map-poi-popup'
            }).setHTML(`
                <div class="map-poi-popup-content">
                    ${poi.image ? `<img src="${poi.image.url}" alt="${poi.name}" />` : ''}
                    <h4>${poi.name}</h4>
                    ${poi.description ? `<p>${poi.description}</p>` : ''}
                </div>
            `);

            // Create marker
            const marker = new mapboxgl.Marker(el)
                .setLngLat([poi.coordinates.lng, poi.coordinates.lat])
                .setPopup(popup)
                .addTo(this.map);

            this.markers.push(marker);
        });
    }

    showInfoPanel(destination) {
        const panel = document.getElementById('map-info-panel');
        const image = panel.querySelector('.map-info-panel-image img');
        const title = panel.querySelector('.map-info-panel-body h2');
        const description = panel.querySelector('.map-info-panel-body .text-gray-600');
        const link = panel.querySelector('.map-info-panel-actions a');
        const poisList = panel.querySelector('.poi-list');

        // Set content
        if (destination.thumbnail) {
            image.src = destination.thumbnail;
            image.alt = destination.title;
            panel.querySelector('.map-info-panel-image').classList.remove('hidden');
        } else {
            panel.querySelector('.map-info-panel-image').classList.add('hidden');
        }

        title.textContent = destination.title;
        description.innerHTML = destination.excerpt;
        link.href = destination.url;

        // Add POIs to list
        poisList.innerHTML = '';
        if (destination.points_of_interest && destination.points_of_interest.length > 0) {
            destination.points_of_interest.forEach(poi => {
                const poiItem = document.createElement('div');
                poiItem.className = 'poi-item flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer';
                poiItem.innerHTML = `
                    <div class="poi-icon w-2 h-2 rounded-full bg-primary"></div>
                    <span class="text-sm text-gray-700">${poi.name}</span>
                `;

                // Click to show on map
                poiItem.addEventListener('click', () => {
                    this.map.flyTo({
                        center: [poi.coordinates.lng, poi.coordinates.lat],
                        zoom: 10,
                        duration: 1000
                    });

                    // Find and open popup
                    const marker = this.markers.find(m =>
                        m.getLngLat().lng === poi.coordinates.lng &&
                        m.getLngLat().lat === poi.coordinates.lat
                    );
                    if (marker) marker.togglePopup();
                });

                poisList.appendChild(poiItem);
            });
            panel.querySelector('.map-info-panel-pois').classList.remove('hidden');
        } else {
            panel.querySelector('.map-info-panel-pois').classList.add('hidden');
        }

        // Show panel with animation
        panel.classList.remove('hidden');
        setTimeout(() => panel.classList.add('active'), 10);
    }

    hideInfoPanel() {
        const panel = document.getElementById('map-info-panel');
        panel.classList.remove('active');
        setTimeout(() => panel.classList.add('hidden'), 300);

        // Clear markers
        this.markers.forEach(marker => marker.remove());
        this.markers = [];

        // Reset view
        this.resetView();
    }

    resetView() {
        const mapElement = document.getElementById('canada-map');
        const initialZoom = parseFloat(mapElement.dataset.initialZoom) || 3.5;
        const centerLat = parseFloat(mapElement.dataset.centerLat) || 56.1304;
        const centerLng = parseFloat(mapElement.dataset.centerLng) || -106.3468;

        this.map.flyTo({
            center: [centerLng, centerLat],
            zoom: initialZoom,
            pitch: 45,
            bearing: 0,
            duration: 2000
        });

        // Reset territory highlights
        this.destinations.forEach(dest => {
            const layerId = `destination-${dest.id}-fill`;
            if (this.map.getLayer(layerId)) {
                this.map.setPaintProperty(layerId, 'fill-opacity', 0.3);
            }
        });

        this.selectedDestination = null;
    }

    populateMobileSelector() {
        const select = document.getElementById('map-territory-select');
        if (!select) return;

        this.destinations.forEach(dest => {
            const option = document.createElement('option');
            option.value = dest.id;
            option.textContent = dest.title;
            select.appendChild(option);
        });
    }

    setupEventListeners() {
        // Close info panel
        const closeBtn = document.querySelector('.map-info-panel-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hideInfoPanel());
        }

        // Mobile selector
        const mobileSelect = document.getElementById('map-territory-select');
        if (mobileSelect) {
            mobileSelect.addEventListener('change', (e) => {
                const destId = parseInt(e.target.value);
                if (destId) {
                    this.selectDestination(destId);
                } else {
                    this.hideInfoPanel();
                }
            });
        }

        // Close panel on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('map-info-panel').classList.contains('hidden')) {
                this.hideInfoPanel();
            }
        });
    }

    showLoading() {
        const loading = document.getElementById('map-loading');
        if (loading) loading.classList.remove('hidden');
    }

    hideLoading() {
        const loading = document.getElementById('map-loading');
        if (loading) loading.classList.add('hidden');
    }

    showError(message) {
        const mapElement = document.getElementById('canada-map');
        mapElement.innerHTML = `
            <div class="map-error flex items-center justify-center h-full">
                <div class="text-center text-red-600">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-semibold">${message}</p>
                </div>
            </div>
        `;
        this.hideLoading();
    }
}

// Initialize map when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('canada-map');
    if (mapContainer && typeof mapboxgl !== 'undefined') {
        window.canadaMap = new CanadaInteractiveMap(CanadaMapConfig);
    }
});

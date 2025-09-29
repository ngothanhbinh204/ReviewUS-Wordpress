import mapboxgl from 'mapbox-gl';
import { canadaLandmarksData } from '../data/canada-territories.js';

export class EnhancedMapboxController {
	constructor() {
		// Use a public access token - you should replace this with your own
		mapboxgl.accessToken =
			'pk.eyJ1IjoibmdvdGhhbmhiaW5oMjAwNCIsImEiOiJjbWMzN3pyNzkwMmNzMmlxeDY0Z295a3o2In0.N9r67mt54P8n6b91AyV-4w';

		this.map = null;
		this.selectedTerritory = null;
		this.isZoomedIn = false;
		this.landmarkLabels = [];
		this.currentPopup = null;
		this.hoveredTerritory = null;

		// Canada territory data with proper names
		this.canadaTerritories = [
			'British Columbia',
			'Alberta',
			'Saskatchewan',
			'Manitoba',
			'Ontario',
			'Quebec',
			'New Brunswick',
			'Nova Scotia',
			'Prince Edward Island',
			'Newfoundland and Labrador',
			'Yukon',
			'Northwest Territories',
			'Nunavut',
		];

		this.territoryInfo = {
			'British Columbia': {
				description:
					'British Columbia offers stunning Pacific coastlines, towering mountains, and vibrant cities like Vancouver and Victoria. From the Rocky Mountains to the Pacific Ocean, BC is a paradise for outdoor enthusiasts.',
				image: 'https://images.pexels.com/photos/417173/pexels-photo-417173.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Victoria',
				population: '5.2 million',
			},
			Alberta: {
				description:
					"In Alberta, spectacular doesn't need a spotlight. The Rockies tend to steal the show with jagged peaks, turquoise lakes, and ancient glaciers that make you want to go further, stay longer, and breathe deeper.",
				image: 'https://images.pexels.com/photos/417074/pexels-photo-417074.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Edmonton',
				population: '4.4 million',
			},
			Saskatchewan: {
				description:
					'Saskatchewan offers endless prairie landscapes, vibrant cities, and rich cultural heritage. From the bustling streets of Saskatoon to the serene beauty of Prince Albert National Park.',
				image: 'https://images.pexels.com/photos/158251/forest-the-sun-morning-tucholskie-158251.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Regina',
				population: '1.2 million',
			},
			Manitoba: {
				description:
					"Manitoba combines urban sophistication with wilderness adventure. Winnipeg's cultural scene thrives alongside pristine northern lakes and polar bear watching in Churchill.",
				image: 'https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Winnipeg',
				population: '1.4 million',
			},
			Ontario: {
				description:
					"Ontario is home to Canada's capital Ottawa and largest city Toronto, plus the magnificent Niagara Falls and pristine Muskoka region. A province of incredible diversity and opportunity.",
				image: 'https://images.pexels.com/photos/1535162/pexels-photo-1535162.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Toronto',
				population: '14.8 million',
			},
			Quebec: {
				description:
					'Quebec offers rich French culture, historic Quebec City, cosmopolitan Montreal, and stunning natural landscapes. Experience European charm in North America.',
				image: 'https://images.pexels.com/photos/1731660/pexels-photo-1731660.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Quebec City',
				population: '8.6 million',
			},
			'New Brunswick': {
				description:
					"New Brunswick offers the world's highest tides in the Bay of Fundy, charming coastal communities, and rich Acadian culture. A maritime province full of natural wonders.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Fredericton',
				population: '780,000',
			},
			'Nova Scotia': {
				description:
					'Nova Scotia is surrounded by ocean on three sides, offering spectacular coastlines, historic Halifax, and the famous Cabot Trail. Maritime hospitality at its finest.',
				image: 'https://images.pexels.com/photos/1450082/pexels-photo-1450082.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Halifax',
				population: '980,000',
			},
			'Prince Edward Island': {
				description:
					"Prince Edward Island is famous for red sand beaches, Anne of Green Gables, and the freshest seafood. Canada's smallest province with the biggest heart.",
				image: 'https://images.pexels.com/photos/1591447/pexels-photo-1591447.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Charlottetown',
				population: '160,000',
			},
			'Newfoundland and Labrador': {
				description:
					'Newfoundland and Labrador offers rugged coastlines, icebergs, whales, and the warmest people in Canada. Experience the edge of North America.',
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: "St. John's",
				population: '520,000',
			},
			Yukon: {
				description:
					"Yukon offers the midnight sun, northern lights, and pristine wilderness. Home to Canada's highest peak and endless adventure opportunities.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Whitehorse',
				population: '42,000',
			},
			'Northwest Territories': {
				description:
					'Northwest Territories offers vast wilderness, diamond mines, and indigenous culture. Experience the true Canadian North.',
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Yellowknife',
				population: '45,000',
			},
			Nunavut: {
				description:
					"Nunavut is Canada's newest territory, offering Arctic wildlife, Inuit culture, and the most remote wilderness experiences on Earth.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Iqaluit',
				population: '39,000',
			},
		};

		this.init();
	}

	init() {
		this.createMap();
		this.setupEventListeners();

		// Hide loading screen after map loads
		this.map.on('load', () => {
			this.loadCanadaData();
			this.hideLoadingScreen();
		});

		// Handle map errors
		this.map.on('error', (e) => {
			console.error('Map error:', e);
			this.showErrorScreen();
		});
	}

	createMap() {
		this.map = new mapboxgl.Map({
			container: 'map',
			style: 'mapbox://styles/mapbox/dark-v11',
			projection: 'globe', // Enable globe projection
			center: [-106.0, 56.0], // Center on Canada
			zoom: 2.5,
			pitch: 0, // Start flat, will animate to 3D
			bearing: 0,
			antialias: true,
			fadeDuration: 300,
		});

		// Add atmosphere for globe effect
		this.map.on('style.load', () => {
			this.map.setFog({
				color: 'rgb(186, 210, 235)', // Lower atmosphere
				'high-color': 'rgb(36, 92, 223)', // Upper atmosphere
				'horizon-blend': 0.02, // Atmosphere thickness
				'space-color': 'rgb(11, 11, 25)', // Background color
				'star-intensity': 0.6, // Background star brightness
			});
		});

		// Add navigation controls
		this.map.addControl(
			new mapboxgl.NavigationControl({
				visualizePitch: true,
			}),
			'bottom-right'
		);

		// Add fullscreen control
		this.map.addControl(new mapboxgl.FullscreenControl(), 'bottom-right');

		// Animate to 3D view after initial load
		setTimeout(() => {
			this.map.easeTo({
				pitch: 45,
				duration: 2000,
			});
		}, 1000);
	}

	loadCanadaData() {
		// Use Mapbox's built-in administrative boundaries
		// Add Canada provinces/territories layer using Mapbox boundaries
		this.map.addLayer({
			id: 'canada-admin-fills',
			type: 'fill',
			source: {
				type: 'vector',
				url: 'mapbox://mapbox.boundaries-adm1-v3',
			},
			'source-layer': 'boundaries_admin_1',
			filter: [
				'all',
				['==', 'iso_3166_1', 'CA'], // Canada only
				['in', 'name', ...this.canadaTerritories], // Only specified territories
			],
			paint: {
				'fill-color': '#059669',
				'fill-opacity': 0.4,
			},
		});

		// Add hover effect layer
		this.map.addLayer({
			id: 'canada-admin-hover',
			type: 'fill',
			source: {
				type: 'vector',
				url: 'mapbox://mapbox.boundaries-adm1-v3',
			},
			'source-layer': 'boundaries_admin_1',
			filter: [
				'all',
				['==', 'iso_3166_1', 'CA'],
				['==', 'name', ''], // Initially empty
			],
			paint: {
				'fill-color': '#10b981',
				'fill-opacity': 0.6,
			},
		});

		// Add territory borders using Mapbox boundaries
		this.map.addLayer({
			id: 'canada-admin-borders',
			type: 'line',
			source: {
				type: 'vector',
				url: 'mapbox://mapbox.boundaries-adm1-v3',
			},
			'source-layer': 'boundaries_admin_1',
			filter: [
				'all',
				['==', 'iso_3166_1', 'CA'],
				['in', 'name', ...this.canadaTerritories],
			],
			paint: {
				'line-color': '#ffffff',
				'line-width': [
					'interpolate',
					['linear'],
					['zoom'],
					2,
					1,
					6,
					2,
					10,
					3,
				],
				'line-opacity': 0.8,
			},
		});

		// Load landmarks
		this.loadLandmarks();
		this.setupTerritoryInteractions();
	}

	loadLandmarks() {
		// Add landmarks source
		this.map.addSource('landmarks', {
			type: 'geojson',
			data: canadaLandmarksData,
		});

		// Add landmark points
		this.map.addLayer({
			id: 'landmark-points',
			type: 'circle',
			source: 'landmarks',
			paint: {
				'circle-radius': [
					'interpolate',
					['linear'],
					['zoom'],
					2,
					3,
					6,
					6,
					10,
					10,
				],
				'circle-color': [
					'case',
					['==', ['get', 'type'], 'capital'],
					'#DC2626', // Red for capitals
					['==', ['get', 'type'], 'city'],
					'#F59E0B', // Orange for major cities
					'#8B5CF6', // Purple for landmarks
				],
				'circle-stroke-color': '#ffffff',
				'circle-stroke-width': 2,
				'circle-opacity': 0.9,
			},
		});

		// Add landmark labels (initially hidden)
		this.map.addLayer({
			id: 'landmark-labels',
			type: 'symbol',
			source: 'landmarks',
			layout: {
				'text-field': ['get', 'name'],
				'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
				'text-offset': [0, 1.5],
				'text-anchor': 'top',
				'text-size': [
					'interpolate',
					['linear'],
					['zoom'],
					6,
					10,
					10,
					14,
				],
			},
			paint: {
				'text-color': '#ffffff',
				'text-halo-color': '#000000',
				'text-halo-width': 2,
				'text-opacity': 0, // Initially hidden
			},
		});

		this.setupLandmarkInteractions();
	}

	setupTerritoryInteractions() {
		// Territory hover effects
		this.map.on('mouseenter', 'canada-admin-fills', (e) => {
			if (!this.isZoomedIn) {
				this.map.getCanvas().style.cursor = 'pointer';

				const territoryName = e.features[0].properties.name;
				this.hoveredTerritory = territoryName;

				// Update hover layer
				this.map.setFilter('canada-admin-hover', [
					'all',
					['==', 'iso_3166_1', 'CA'],
					['==', 'name', territoryName],
				]);

				// Show territory info tooltip
				this.showTerritoryTooltip(e.features[0], e.lngLat);
			}
		});

		this.map.on('mouseleave', 'canada-admin-fills', () => {
			this.map.getCanvas().style.cursor = '';
			this.hoveredTerritory = null;
			this.map.setFilter('canada-admin-hover', [
				'all',
				['==', 'iso_3166_1', 'CA'],
				['==', 'name', ''],
			]);

			// Hide tooltip
			if (this.currentPopup) {
				this.currentPopup.remove();
				this.currentPopup = null;
			}
		});

		// Territory click handler
		this.map.on('click', 'canada-admin-fills', (e) => {
			const territory = e.features[0];

			if (!this.isZoomedIn) {
				this.selectTerritory(territory);
			}
		});
	}

	setupLandmarkInteractions() {
		this.map.on('mouseenter', 'landmark-points', () => {
			this.map.getCanvas().style.cursor = 'pointer';
		});

		this.map.on('mouseleave', 'landmark-points', () => {
			this.map.getCanvas().style.cursor = '';
		});

		this.map.on('click', 'landmark-points', (e) => {
			const landmark = e.features[0];
			this.showLandmarkDetails(landmark, e.lngLat);
		});
	}

	showTerritoryTooltip(territory, lngLat) {
		if (this.currentPopup) {
			this.currentPopup.remove();
		}

		const territoryName = territory.properties.name;
		const info = this.territoryInfo[territoryName];

		if (!info) return;

		const tooltipContent = `
      <div class="territory-tooltip">
        <h4 class="font-bold text-lg">${territoryName}</h4>
        <p class="text-sm text-gray-600">Capital: ${info.capital}</p>
        <p class="text-sm text-gray-600">Population: ${info.population}</p>
        <p class="text-xs text-gray-500 mt-1">Click to explore</p>
      </div>
    `;

		this.currentPopup = new mapboxgl.Popup({
			closeButton: false,
			closeOnClick: false,
			className: 'territory-tooltip-popup',
		})
			.setLngLat(lngLat)
			.setHTML(tooltipContent)
			.addTo(this.map);
	}

	selectTerritory(territory) {
		const territoryName = territory.properties.name;
		this.selectedTerritory = territoryName;
		this.isZoomedIn = true;

		// Get territory bounds from the feature
		const bounds = new mapboxgl.LngLatBounds();

		// For vector tiles, we need to use the feature's geometry
		if (territory.geometry && territory.geometry.coordinates) {
			const coords = territory.geometry.coordinates;
			if (territory.geometry.type === 'Polygon') {
				coords[0].forEach((coord) => bounds.extend(coord));
			} else if (territory.geometry.type === 'MultiPolygon') {
				coords.forEach((polygon) => {
					polygon[0].forEach((coord) => bounds.extend(coord));
				});
			}
		}

		// Fit map to territory bounds
		this.map.fitBounds(bounds, {
			padding: 100,
			duration: 1500,
			pitch: 60,
			bearing: 0,
		});

		// Update territory styles to highlight selected
		this.map.setFilter('canada-admin-fills', [
			'all',
			['==', 'iso_3166_1', 'CA'],
			['==', 'name', territoryName],
		]);

		this.map.setPaintProperty(
			'canada-admin-fills',
			'fill-color',
			'#059669'
		);
		this.map.setPaintProperty('canada-admin-fills', 'fill-opacity', 0.4);

		// Show landmark labels for selected territory
		this.showLandmarkLabels(territoryName);

		// Show territory sidebar
		this.showTerritorySidebar(territoryName);

		// Hide main info panel
		const mainInfo = document.getElementById('main-info');
		if (mainInfo) {
			mainInfo.style.opacity = '0';
			mainInfo.style.pointerEvents = 'none';
		}

		// Hide tooltip
		if (this.currentPopup) {
			this.currentPopup.remove();
			this.currentPopup = null;
		}
	}

	showLandmarkLabels(territoryName) {
		// Filter landmarks for the selected territory
		this.map.setFilter('landmark-labels', [
			'all',
			['==', ['get', 'province'], territoryName],
		]);

		// Animate labels appearing
		this.map.setPaintProperty('landmark-labels', 'text-opacity', 1);
	}

	hideLandmarkLabels() {
		this.map.setPaintProperty('landmark-labels', 'text-opacity', 0);
		setTimeout(() => {
			this.map.setFilter('landmark-labels', ['==', 'province', '']);
		}, 300);
	}

	showTerritorySidebar(territoryName) {
		const sidebar = document.getElementById('territory-sidebar');
		const name = document.getElementById('territory-name');
		const description = document.getElementById('territory-description');
		const image = document.getElementById('territory-img');

		const info = this.territoryInfo[territoryName];

		if (sidebar && name && description && image && info) {
			name.textContent = territoryName;
			description.textContent = info.description;
			image.src = info.image;
			image.alt = territoryName;

			sidebar.style.opacity = '1';
			sidebar.style.pointerEvents = 'auto';
			sidebar.style.transform = 'translateY(-50%) translateX(0)';
		}
	}

	showLandmarkDetails(landmark, lngLat) {
		if (this.currentPopup) {
			this.currentPopup.remove();
		}

		const props = landmark.properties;
		const popupContent = `
      <div class="landmark-details">
        <h3 class="font-bold text-lg mb-2">${props.name}</h3>
        <p class="text-sm text-gray-600 mb-1">${props.description}</p>
        <div class="flex justify-between text-xs text-gray-500 mt-2">
          <span>Type: ${props.type}</span>
          <span>Pop: ${props.population}</span>
        </div>
      </div>
    `;

		this.currentPopup = new mapboxgl.Popup({
			closeButton: true,
			closeOnClick: false,
			className: 'landmark-details-popup',
		})
			.setLngLat(lngLat)
			.setHTML(popupContent)
			.addTo(this.map);
	}

	resetView() {
		this.isZoomedIn = false;
		this.selectedTerritory = null;

		// Reset map view
		this.map.flyTo({
			center: [-106.0, 56.0],
			zoom: 2.5,
			pitch: 45,
			bearing: 0,
			duration: 1500,
		});

		// Reset territory filter to show all
		this.map.setFilter('canada-admin-fills', [
			'all',
			['==', 'iso_3166_1', 'CA'],
			['in', 'name', ...this.canadaTerritories],
		]);

		// Hide landmark labels
		this.hideLandmarkLabels();

		// Hide territory sidebar
		const sidebar = document.getElementById('territory-sidebar');
		if (sidebar) {
			sidebar.style.opacity = '0';
			sidebar.style.pointerEvents = 'none';
		}

		// Show main info panel
		const mainInfo = document.getElementById('main-info');
		if (mainInfo) {
			mainInfo.style.opacity = '1';
			mainInfo.style.pointerEvents = 'auto';
		}

		// Close popup if exists
		if (this.currentPopup) {
			this.currentPopup.remove();
			this.currentPopup = null;
		}
	}

	toggle3D() {
		const currentPitch = this.map.getPitch();
		const newPitch = currentPitch > 30 ? 0 : 60;

		this.map.easeTo({
			pitch: newPitch,
			duration: 1000,
		});
	}

	hideLoadingScreen() {
		const loading = document.getElementById('loading');
		if (loading) {
			loading.style.opacity = '0';
			setTimeout(() => {
				loading.style.display = 'none';
			}, 500);
		}
	}

	showErrorScreen() {
		const loading = document.getElementById('loading');
		if (loading) {
			loading.innerHTML = `
        <div class="text-center">
          <div class="text-red-500 text-6xl mb-4">⚠️</div>
          <p class="text-xl mb-4">Failed to load the interactive map</p>
          <p class="text-sm text-gray-400 mb-4">This might be due to missing Mapbox access token</p>
          <button onclick="window.location.reload()" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
            Refresh Page
          </button>
        </div>
      `;
		}
	}

	setupEventListeners() {
		// Reset view button
		const resetBtn = document.getElementById('reset-view');
		if (resetBtn) {
			resetBtn.addEventListener('click', () => this.resetView());
		}

		// Toggle 3D button
		const toggle3DBtn = document.getElementById('toggle-3d');
		if (toggle3DBtn) {
			toggle3DBtn.addEventListener('click', () => this.toggle3D());
		}

		// Close sidebar button
		const closeSidebarBtn = document.getElementById('close-sidebar');
		if (closeSidebarBtn) {
			closeSidebarBtn.addEventListener('click', () => this.resetView());
		}

		// Discover button
		const discoverBtn = document.getElementById('discover-btn');
		if (discoverBtn) {
			discoverBtn.addEventListener('click', () => {
				alert(`Discovering more about ${this.selectedTerritory}!`);
			});
		}
	}
}

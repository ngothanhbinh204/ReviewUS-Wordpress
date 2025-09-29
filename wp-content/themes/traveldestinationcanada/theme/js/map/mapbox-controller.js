import mapboxgl from 'mapbox-gl';
import {
	canadaProvincesData,
	landmarksData,
} from '../data/canada-provinces.js';

export class MapboxController {
	constructor() {
		// Use a public access token for demo purposes
		// In production, you should use your own token
		mapboxgl.accessToken =
			'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

		this.map = null;
		this.selectedProvince = null;
		this.isZoomedIn = false;
		this.landmarkMarkers = [];
		this.currentPopup = null;

		this.init();
	}

	init() {
		this.createMap();
		this.setupEventListeners();

		// Hide loading screen after map loads
		this.map.on('load', () => {
			this.loadCanadaData();
			document.getElementById('loading').style.opacity = '0';
			setTimeout(() => {
				document.getElementById('loading').style.display = 'none';
			}, 500);
		});
	}

	createMap() {
		this.map = new mapboxgl.Map({
			container: 'map',
			style: 'mapbox://styles/mapbox/dark-v11',
			center: [-106.0, 56.0], // Center on Canada
			zoom: 3.5,
			pitch: 45, // 3D tilt
			bearing: 0,
			antialias: true,
			fadeDuration: 300,
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
	}

	loadCanadaData() {
		// Add province boundaries
		this.map.addSource('provinces', {
			type: 'geojson',
			data: canadaProvincesData,
		});

		// Add province fill layer
		this.map.addLayer({
			id: 'province-fills',
			type: 'fill',
			source: 'provinces',
			paint: {
				'fill-color': [
					'case',
					['get', 'active'],
					'#059669', // Active provinces in green
					'#6b7280', // Inactive provinces in gray
				],
				'fill-opacity': ['case', ['get', 'active'], 0.3, 0.6],
			},
		});

		// Add province border layer
		this.map.addLayer({
			id: 'province-borders',
			type: 'line',
			source: 'provinces',
			paint: {
				'line-color': '#ffffff',
				'line-width': 2,
				'line-opacity': 0.8,
			},
		});

		// Add province hover effect
		this.map.addLayer({
			id: 'province-hover',
			type: 'fill',
			source: 'provinces',
			paint: {
				'fill-color': '#047857',
				'fill-opacity': 0.5,
			},
			filter: ['==', 'name', ''],
		});

		// Load landmarks
		this.loadLandmarks();
		this.setupProvinceInteractions();
	}

	loadLandmarks() {
		// Add landmarks source
		this.map.addSource('landmarks', {
			type: 'geojson',
			data: landmarksData,
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
					3,
					4,
					8,
					8,
					12,
					12,
				],
				'circle-color': '#DC2626',
				'circle-stroke-color': '#ffffff',
				'circle-stroke-width': 2,
				'circle-opacity': 0.9,
			},
		});

		// Add landmark interactions
		this.setupLandmarkInteractions();
	}

	setupProvinceInteractions() {
		// Change cursor on hover
		this.map.on('mouseenter', 'province-fills', (e) => {
			if (e.features[0].properties.active && !this.isZoomedIn) {
				this.map.getCanvas().style.cursor = 'pointer';

				// Update hover layer
				this.map.setFilter('province-hover', [
					'==',
					'name',
					e.features[0].properties.name,
				]);
			}
		});

		this.map.on('mouseleave', 'province-fills', () => {
			this.map.getCanvas().style.cursor = '';
			this.map.setFilter('province-hover', ['==', 'name', '']);
		});

		// Handle province clicks
		this.map.on('click', 'province-fills', (e) => {
			const province = e.features[0];

			if (province.properties.active && !this.isZoomedIn) {
				this.selectProvince(province);
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
			this.showLandmarkPopup(landmark, e.lngLat);
		});
	}

	selectProvince(province) {
		this.selectedProvince = province.properties.name;
		this.isZoomedIn = true;

		// Calculate bounds for the province
		const bounds = new mapboxgl.LngLatBounds();

		if (province.geometry.type === 'Polygon') {
			province.geometry.coordinates[0].forEach((coord) => {
				bounds.extend(coord);
			});
		}

		// Fit map to province bounds
		this.map.fitBounds(bounds, {
			padding: 100,
			duration: 1500,
			pitch: 60,
			bearing: 0,
		});

		// Update province styles to disable non-selected ones
		this.map.setPaintProperty('province-fills', 'fill-color', [
			'case',
			['==', ['get', 'name'], this.selectedProvince],
			'#059669',
			'#9ca3af',
		]);

		this.map.setPaintProperty('province-fills', 'fill-opacity', [
			'case',
			['==', ['get', 'name'], this.selectedProvince],
			0.3,
			0.4,
		]);

		// Show territory sidebar
		this.showTerritorySidebar(province.properties);

		// Hide main info panel
		document.getElementById('main-info').style.opacity = '0';
		document.getElementById('main-info').style.pointerEvents = 'none';
	}

	showTerritorySidebar(provinceData) {
		const sidebar = document.getElementById('territory-sidebar');
		const name = document.getElementById('territory-name');
		const description = document.getElementById('territory-description');
		const image = document.getElementById('territory-img');

		name.textContent = provinceData.name;
		description.textContent = provinceData.description;
		image.src = provinceData.image;
		image.alt = provinceData.name;

		sidebar.style.opacity = '1';
		sidebar.style.pointerEvents = 'auto';
		sidebar.style.transform = 'translateY(-50%) translateX(0)';
	}

	showLandmarkPopup(landmark, lngLat) {
		// Close existing popup
		if (this.currentPopup) {
			this.currentPopup.remove();
		}

		const popupContent = `
      <div class="landmark-popup">
        <h3>${landmark.properties.name}</h3>
        <p>${landmark.properties.description}</p>
      </div>
    `;

		this.currentPopup = new mapboxgl.Popup({
			closeButton: true,
			closeOnClick: false,
			className: 'landmark-popup-container',
		})
			.setLngLat(lngLat)
			.setHTML(popupContent)
			.addTo(this.map);
	}

	resetView() {
		this.isZoomedIn = false;
		this.selectedProvince = null;

		// Reset map view
		this.map.flyTo({
			center: [-106.0, 56.0],
			zoom: 3.5,
			pitch: 45,
			bearing: 0,
			duration: 1500,
		});

		// Reset province styles
		this.map.setPaintProperty('province-fills', 'fill-color', [
			'case',
			['get', 'active'],
			'#059669',
			'#6b7280',
		]);

		this.map.setPaintProperty('province-fills', 'fill-opacity', [
			'case',
			['get', 'active'],
			0.3,
			0.6,
		]);

		// Hide territory sidebar
		const sidebar = document.getElementById('territory-sidebar');
		sidebar.style.opacity = '0';
		sidebar.style.pointerEvents = 'none';

		// Show main info panel
		document.getElementById('main-info').style.opacity = '1';
		document.getElementById('main-info').style.pointerEvents = 'auto';

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

	setupEventListeners() {
		// Reset view button
		document.getElementById('reset-view').addEventListener('click', () => {
			this.resetView();
		});

		// Toggle 3D button
		document.getElementById('toggle-3d').addEventListener('click', () => {
			this.toggle3D();
		});

		// Close sidebar button
		document
			.getElementById('close-sidebar')
			.addEventListener('click', () => {
				this.resetView();
			});

		// Discover button
		document
			.getElementById('discover-btn')
			.addEventListener('click', () => {
				alert(`Discovering more about ${this.selectedProvince}!`);
			});
	}
}

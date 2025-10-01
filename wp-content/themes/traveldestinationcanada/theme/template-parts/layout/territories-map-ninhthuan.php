 <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js"></script>
 <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet" />
 <style>
 	* {
 		margin: 0;
 		padding: 0;
 		box-sizing: border-box;
 	}

 	body {
 		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 		/* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
 		/* height: 100vh; */
 		/* overflow: hidden; */
 	}

 	.container {
 		display: flex;
 		height: 100vh;
 		position: relative;
 	}

 	.sidebar {
 		width: 350px;
 		background: rgba(255, 255, 255, 0.95);
 		backdrop-filter: blur(10px);
 		padding: 20px;
 		overflow-y: auto;
 		box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
 		z-index: 1000;
 	}

 	.sidebar h1 {
 		color: #2d3748;
 		margin-bottom: 20px;
 		font-size: 24px;
 		text-align: center;
 		border-bottom: 3px solid #e53e3e;
 		padding-bottom: 10px;
 	}

 	.info-card {
 		background: white;
 		border-radius: 12px;
 		padding: 20px;
 		margin-bottom: 20px;
 		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
 		border-left: 4px solid #e53e3e;
 	}

 	.info-card h3 {
 		color: #2d3748;
 		margin-bottom: 10px;
 		font-size: 18px;
 	}

 	.info-card p {
 		color: #4a5568;
 		line-height: 1.6;
 		margin-bottom: 8px;
 	}

 	.landmarks-list {
 		margin-top: 15px;
 	}

 	.landmark-item {
 		background: #f7fafc;
 		padding: 8px 12px;
 		margin: 5px 0;
 		border-radius: 6px;
 		border-left: 3px solid #38b2ac;
 		font-size: 14px;
 		color: #2d3748;
 	}

 	#map {
 		flex: 1;
 		position: relative;
 	}

 	.mapboxgl-popup {
 		max-width: 300px;
 	}

 	.mapboxgl-popup-content {
 		border-radius: 12px;
 		padding: 20px;
 		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
 	}

 	.popup-title {
 		font-size: 18px;
 		font-weight: bold;
 		color: #2d3748;
 		margin-bottom: 10px;
 		border-bottom: 2px solid #e53e3e;
 		padding-bottom: 5px;
 	}

 	.popup-description {
 		color: #4a5568;
 		line-height: 1.5;
 		margin-bottom: 15px;
 	}

 	.popup-landmarks {
 		background: #f7fafc;
 		padding: 10px;
 		border-radius: 8px;
 		border-left: 4px solid #38b2ac;
 	}

 	.popup-landmarks h4 {
 		color: #2d3748;
 		margin-bottom: 8px;
 		font-size: 14px;
 	}

 	.popup-landmarks ul {
 		list-style: none;
 		padding: 0;
 	}

 	.popup-landmarks li {
 		color: #4a5568;
 		font-size: 13px;
 		margin-bottom: 4px;
 		padding-left: 15px;
 		position: relative;
 	}

 	.popup-landmarks li:before {
 		content: "•";
 		color: #38b2ac;
 		position: absolute;
 		left: 0;
 	}

 	.controls {
 		position: absolute;
 		top: 20px;
 		right: 20px;
 		z-index: 1000;
 		display: flex;
 		flex-direction: column;
 		gap: 10px;
 	}

 	.control-btn {
 		background: white;
 		border: none;
 		padding: 12px;
 		border-radius: 8px;
 		cursor: pointer;
 		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
 		transition: all 0.3s ease;
 		font-size: 14px;
 		color: #2d3748;
 	}

 	.control-btn:hover {
 		transform: translateY(-2px);
 		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
 		background: #e53e3e;
 		color: white;
 	}

 	@media (max-width: 768px) {
 		.container {
 			flex-direction: column;
 		}

 		.sidebar {
 			width: 100%;
 			height: 200px;
 			order: 2;
 		}

 		#map {
 			height: calc(100vh - 200px);
 			order: 1;
 		}
 	}
 </style>
 <div class="container">
 	<div class="sidebar">
 		<h1>🌏 Khám phá Ninh Thuận</h1>

 		<div class="info-card">
 			<h3>🏛️ Tỉnh Ninh Thuận</h3>
 			<p><strong>Diện tích:</strong> 3,358.2 km²</p>
 			<p><strong>Dân số:</strong> ~590,000 người</p>
 			<p><strong>Thủ phủ:</strong> Phan Rang - Tháp Chàm</p>
 			<p>Ninh Thuận là tỉnh ven biển miền Nam Trung Bộ, nổi tiếng với văn hóa Chăm độc đáo, tháp cổ và những bãi
 				biển tuyệt đẹp.</p>
 		</div>

 		<div class="info-card" id="selected-info">
 			<h3>📍 Thông tin khu vực</h3>
 			<p>Nhấp vào các khu vực trên bản đồ để xem thông tin chi tiết về địa danh và điểm tham quan.</p>
 		</div>
 	</div>

 	<div id="map"></div>

 	<div class="controls">
 		<button class="control-btn" onclick="resetView()">🏠 Về tổng quan</button>
 		<button class="control-btn" onclick="toggleStyle()">🗺️ Đổi kiểu bản đồ</button>
 	</div>
 </div>
 <script>
 	// Thay thế YOUR_MAPBOX_ACCESS_TOKEN bằng token thực của bạn
 	mapboxgl.accessToken =
 		'pk.eyJ1IjoibmdvdGhhbmhiaW5oMjAwNCIsImEiOiJjbWMzN3pyNzkwMmNzMmlxeDY0Z295a3o2In0.N9r67mt54P8n6b91AyV-4w';

 	// Dữ liệu các khu vực và địa danh trong Ninh Thuận
 	const regions = {
 		"phan-rang-thap-cham": {
 			name: "Phan Rang - Tháp Chàm",
 			center: [108.9892, 11.5659],
 			description: "Thành phố trung tâm của tỉnh Ninh Thuận, là trung tâm chính trị, kinh tế, văn hóa của tỉnh.",
 			bounds: [
 				[108.85, 11.45],
 				[109.15, 11.45],
 				[109.15, 11.48],
 				[109.20, 11.50],
 				[109.25, 11.55],
 				[109.20, 11.65],
 				[109.10, 11.70],
 				[108.95, 11.68],
 				[108.85, 11.60],
 				[108.80, 11.52],
 				[108.85, 11.45]
 			],
 			subRegions: [{
 				name: "Phường Đài Sơn",
 				coords: [108.95, 11.56],
 				population: "12,000"
 			}, {
 				name: "Phường Đạo Long",
 				coords: [109.00, 11.58],
 				population: "15,000"
 			}, {
 				name: "Phường Tấn Tài",
 				coords: [108.98, 11.54],
 				population: "10,500"
 			}, {
 				name: "Phường Thanh Sơn",
 				coords: [109.02, 11.60],
 				population: "8,200"
 			}],
 			landmarks: [{
 				name: "Tháp Po Klong Garai",
 				coords: [108.9789, 11.5889],
 				type: "historical"
 			}, {
 				name: "Tháp Po Ro Me",
 				coords: [108.9678, 11.5234],
 				type: "historical"
 			}, {
 				name: "Chợ Phan Rang",
 				coords: [108.9892, 11.5659],
 				type: "market"
 			}, {
 				name: "Bãi biển Ninh Chữ",
 				coords: [109.0156, 11.5789],
 				type: "beach"
 			}, {
 				name: "Núi Chúa",
 				coords: [109.1234, 11.6789],
 				type: "mountain"
 			}]
 		},
 		"ninh-hai": {
 			name: "Ninh Hải",
 			center: [108.8567, 11.2345],
 			description: "Huyện ven biển với nhiều bãi biển đẹp và làng chài truyền thống.",
 			bounds: [
 				[108.65, 11.10],
 				[109.05, 11.08],
 				[109.10, 11.15],
 				[109.12, 11.25],
 				[109.08, 11.35],
 				[108.95, 11.38],
 				[108.80, 11.35],
 				[108.70, 11.28],
 				[108.65, 11.20],
 				[108.62, 11.15],
 				[108.65, 11.10]
 			],
 			subRegions: [{
 				name: "Thị trấn Khánh Hải",
 				coords: [108.88, 11.25],
 				population: "8,500"
 			}, {
 				name: "Xã Vĩnh Hy",
 				coords: [109.05, 11.18],
 				population: "3,200"
 			}, {
 				name: "Xã Tri Hải",
 				coords: [108.82, 11.30],
 				population: "4,100"
 			}, {
 				name: "Xã Thanh Hải",
 				coords: [108.75, 11.22],
 				population: "2,800"
 			}],
 			landmarks: [{
 				name: "Bãi biển Ca Na",
 				coords: [108.8234, 11.2156],
 				type: "beach"
 			}, {
 				name: "Mũi Dinh",
 				coords: [108.8456, 11.1234],
 				type: "cape"
 			}, {
 				name: "Làng chài Tri Thủy",
 				coords: [108.8678, 11.2678],
 				type: "village"
 			}]
 		},
 		// ... (other regions: ninh-son, thuan-bac, thuan-nam, bac-ai remain the same as in your original code)
 	};

 	let currentStyle = 'mapbox://styles/mapbox/satellite-streets-v12';
 	let map;

 	// Khởi tạo bản đồ
 	function initMap() {
 		map = new mapboxgl.Map({
 			container: 'map',
 			style: currentStyle,
 			center: [108.9892, 11.5659], // Tâm Ninh Thuận
 			zoom: 9,
 			pitch: 45,
 			bearing: 0,
 			scrollZoom: false // Tắt scroll zoom mặc định
 		});

 		map.on('wheel', function(e) {
 			if (e.originalEvent.ctrlKey) {
 				map.scrollZoom.enable();
 			} else {
 				map.scrollZoom.disable();
 			}
 		});

 		map.on('load', function() {
 			addRegionsToMap();
 			addLandmarksToMap();
 			setupMapInteractions();

 			let lastZoomLevel = 9;
 			map.on('zoom', function() {
 				const zoom = map.getZoom();
 				if ((zoom >= 10 && lastZoomLevel < 10) || (zoom < 10 && lastZoomLevel >= 10)) {
 					Object.keys(regions).forEach(regionId => {
 						const region = regions[regionId];
 						if (region.subRegions) {
 							region.subRegions.forEach((_, index) => {
 								const subRegionId = `${regionId}-sub-${index}`;
 								if (map.getLayer(`sub-region-label-${subRegionId}`)) {
 									map.setLayoutProperty(
 										`sub-region-label-${subRegionId}`,
 										'visibility',
 										zoom >= 10 ? 'visible' : 'none'
 									);
 								}
 							});
 						}
 					});
 					lastZoomLevel = zoom;
 				}
 			});
 		});
 	}

 	// Thêm các khu vực lên bản đồ
 	function addRegionsToMap() {
 		Object.keys(regions).forEach(regionId => {
 			const region = regions[regionId];
 			const coordinates = [region.bounds];

 			map.addSource(`region-${regionId}`, {
 				type: 'geojson',
 				data: {
 					type: 'Feature',
 					properties: {
 						id: regionId,
 						name: region.name
 					},
 					geometry: {
 						type: 'Polygon',
 						coordinates
 					}
 				}
 			});

 			map.addLayer({
 				id: `region-fill-${regionId}`,
 				type: 'fill',
 				source: `region-${regionId}`,
 				paint: {
 					'fill-color': [
 						'case',
 						['==', ['get', 'id'], 'phan-rang-thap-cham'], '#e53e3e',
 						['==', ['get', 'id'], 'ninh-hai'], '#3182ce',
 						['==', ['get', 'id'], 'ninh-son'], '#38a169',
 						['==', ['get', 'id'], 'thuan-bac'], '#d69e2e',
 						['==', ['get', 'id'], 'thuan-nam'], '#805ad5',
 						['==', ['get', 'id'], 'bac-ai'], '#dd6b20',
 						'#e53e3e'
 					],
 					'fill-opacity': 0.3
 				}
 			});

 			map.addLayer({
 				id: `region-border-${regionId}`,
 				type: 'line',
 				source: `region-${regionId}`,
 				paint: {
 					'line-color': [
 						'case',
 						['==', ['get', 'id'], 'phan-rang-thap-cham'], '#e53e3e',
 						['==', ['get', 'id'], 'ninh-hai'], '#3182ce',
 						['==', ['get', 'id'], 'ninh-son'], '#38a169',
 						['==', ['get', 'id'], 'thuan-bac'], '#d69e2e',
 						['==', ['get', 'id'], 'thuan-nam'], '#805ad5',
 						['==', ['get', 'id'], 'bac-ai'], '#dd6b20',
 						'#e53e3e'
 					],
 					'line-width': 3,
 					'line-opacity': 0.8
 				}
 			});

 			map.addLayer({
 				id: `region-label-${regionId}`,
 				type: 'symbol',
 				source: `region-${regionId}`,
 				layout: {
 					'text-field': region.name,
 					'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
 					'text-size': 16,
 					'text-anchor': 'center'
 				},
 				paint: {
 					'text-color': '#2d3748',
 					'text-halo-color': '#ffffff',
 					'text-halo-width': 3
 				}
 			});

 			addSubRegions(regionId, region);
 		});
 	}

 	// Thêm các vùng con như chấm tròn
 	function addSubRegions(regionId, region) {
 		region.subRegions.forEach((subRegion, index) => {
 			const subRegionId = `${regionId}-sub-${index}`;

 			map.addSource(subRegionId, {
 				type: 'geojson',
 				data: {
 					type: 'Feature',
 					properties: {
 						name: subRegion.name,
 						population: subRegion.population,
 						regionName: region.name
 					},
 					geometry: {
 						type: 'Point',
 						coordinates: subRegion.coords
 					}
 				}
 			});

 			map.addLayer({
 				id: `sub-region-circle-${subRegionId}`,
 				type: 'circle',
 				source: subRegionId,
 				paint: {
 					'circle-radius': ['interpolate', ['linear'],
 						['zoom'], 8, 6, 12, 12, 16, 20
 					],
 					'circle-color': [
 						'case',
 						['==', regionId, 'phan-rang-thap-cham'], '#e53e3e',
 						['==', regionId, 'ninh-hai'], '#3182ce',
 						['==', regionId, 'ninh-son'], '#38a169',
 						['==', regionId, 'thuan-bac'], '#d69e2e',
 						['==', regionId, 'thuan-nam'], '#805ad5',
 						['==', regionId, 'bac-ai'], '#dd6b20',
 						'#e53e3e'
 					],
 					'circle-opacity': 0.8,
 					'circle-stroke-width': 2,
 					'circle-stroke-color': '#ffffff'
 				}
 			});

 			map.addLayer({
 				id: `sub-region-label-${subRegionId}`,
 				type: 'symbol',
 				source: subRegionId,
 				layout: {
 					'text-field': ['get', 'name'],
 					'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
 					'text-size': 11,
 					'text-anchor': 'top',
 					'text-offset': [0, 1.5],
 					'text-optional': true
 				},
 				paint: {
 					'text-color': '#2d3748',
 					'text-halo-color': '#ffffff',
 					'text-halo-width': 2
 				},
 				minzoom: 10
 			});
 		});
 	}

 	// Thêm các địa danh lên bản đồ
 	function addLandmarksToMap() {
 		const landmarkIcons = {
 			historical: 'historical',
 			beach: 'beach',
 			mountain: 'mountain',
 			waterfall: 'waterfall',
 			village: 'village',
 			market: 'market',
 			craft: 'craft',
 			farm: 'farm',
 			lake: 'lake',
 			cape: 'cape',
 			forest: 'forest'
 		};

 		const iconPromises = Object.keys(landmarkIcons).map(type =>
 			new Promise((resolve, reject) => {
 				map.loadImage(`/path/to/icons/${type}.png`, (error, image) => {
 					if (error) {
 						console.error(`Failed to load icon for ${type}:`, error);
 						reject(error);
 					} else {
 						map.addImage(type, image);
 						resolve();
 					}
 				});
 			})
 		);

 		Promise.all(iconPromises).then(() => {
 			Object.keys(regions).forEach(regionId => {
 				const region = regions[regionId];
 				region.landmarks.forEach((landmark, index) => {
 					const landmarkId = `${regionId}-landmark-${index}`;
 					map.addSource(landmarkId, {
 						type: 'geojson',
 						data: {
 							type: 'Feature',
 							properties: {
 								name: landmark.name,
 								type: landmark.type,
 								region: region.name,
 								icon: landmarkIcons[landmark.type] || 'default'
 							},
 							geometry: {
 								type: 'Point',
 								coordinates: landmark.coords
 							}
 						}
 					});
 					map.addLayer({
 						id: `landmark-${landmarkId}`,
 						type: 'symbol',
 						source: landmarkId,
 						layout: {
 							'icon-image': ['get', 'icon'],
 							'icon-size': 0.05,
 							'icon-anchor': 'bottom',
 							'text-field': ['get', 'name'],
 							'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
 							'text-size': 12,
 							'text-anchor': 'top',
 							'text-offset': [0, 0.5]
 						},
 						paint: {
 							'text-color': '#2d3748',
 							'text-halo-color': '#ffffff',
 							'text-halo-width': 2
 						}
 					});
 				});
 			});
 		}).catch(error => console.error('Error loading landmark icons:', error));
 	}

 	// Thiết lập tương tác với bản đồ
 	function setupMapInteractions() {
 		Object.keys(regions).forEach(regionId => {
 			const region = regions[regionId];
 			map.on('click', `region-fill-${regionId}`, function(e) {
 				zoomToRegion(regionId);
 				updateSidebar(regionId);
 				showLandmarkLabels(regionId);
 			});
 			map.on('mouseenter', `region-fill-${regionId}`, () => {
 				map.getCanvas().style.cursor = 'pointer';
 				map.setPaintProperty(`region-fill-${regionId}`, 'fill-opacity', 0.6);
 			});
 			map.on('mouseleave', `region-fill-${regionId}`, () => {
 				map.getCanvas().style.cursor = '';
 				map.setPaintProperty(`region-fill-${regionId}`, 'fill-opacity', 0.3);
 			});
 			region.subRegions.forEach((subRegion, index) => {
 				const subRegionId = `${regionId}-sub-${index}`;
 				map.on('click', `sub-region-circle-${subRegionId}`, function(e) {
 					showSubRegionPopup(subRegion, region, e.lngLat);
 				});
 				map.on('mouseenter', `sub-region-circle-${subRegionId}`, () => {
 					map.getCanvas().style.cursor = 'pointer';
 					map.setPaintProperty(`sub-region-circle-${subRegionId}`, 'circle-radius', [
 						'interpolate', ['linear'],
 						['zoom'], 8, 8, 12, 16, 16, 24
 					]);
 				});
 				map.on('mouseleave', `sub-region-circle-${subRegionId}`, () => {
 					map.getCanvas().style.cursor = '';
 					map.setPaintProperty(`sub-region-circle-${subRegionId}`, 'circle-radius', [
 						'interpolate', ['linear'],
 						['zoom'], 8, 6, 12, 12, 16, 20
 					]);
 				});
 			});
 		});
 	}

 	// Hiển thị label cho các địa danh
 	function showLandmarkLabels(regionId) {
 		const region = regions[regionId];
 		region.landmarks.forEach((_, index) => {
 			const landmarkId = `landmark-label-${regionId}-${index}`;
 			if (map.getLayer(landmarkId)) map.removeLayer(landmarkId);
 			if (map.getSource(landmarkId)) map.removeSource(landmarkId);
 		});
 		region.landmarks.forEach((landmark, index) => {
 			const landmarkId = `landmark-label-${regionId}-${index}`;
 			map.addSource(landmarkId, {
 				type: 'geojson',
 				data: {
 					type: 'Feature',
 					geometry: {
 						type: 'Point',
 						coordinates: landmark.coords
 					},
 					properties: {
 						name: landmark.name
 					}
 				}
 			});
 			map.addLayer({
 				id: landmarkId,
 				type: 'symbol',
 				source: landmarkId,
 				layout: {
 					'text-field': ['get', 'name'],
 					'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
 					'text-size': 12,
 					'text-anchor': 'top',
 					'text-offset': [0, 0.6],
 					'text-allow-overlap': true
 				},
 				paint: {
 					'text-color': '#ffffff',
 					'text-halo-color': '#2d3748',
 					'text-halo-width': 1,
 					'text-halo-blur': 0.5
 				}
 			});
 		});
 	}

 	// Cập nhật thông tin sidebar
 	function updateSidebar(regionId) {
 		const region = regions[regionId];
 		const infoCard = document.getElementById('selected-info');
 		const landmarksList = region.landmarks.map(landmark => `<div class="landmark-item">${landmark.name}</div>`).join(
 			'');
 		infoCard.innerHTML = `
            <h3>📍 ${region.name}</h3>
            <p>${region.description}</p>
            <div class="landmarks-list">
                <strong>Địa danh nổi bật:</strong>
                ${landmarksList}
            </div>
        `;
 	}

 	// Reset view về tổng quan
 	function resetView() {
 		map.flyTo({
 			center: [108.9892, 11.5659],
 			zoom: 9,
 			pitch: 45,
 			bearing: 0
 		});
 		document.getElementById('selected-info').innerHTML = `
            <h3>📍 Thông tin khu vực</h3>
            <p>Nhấp vào các khu vực trên bản đồ để xem thông tin chi tiết về địa danh và điểm tham quan.</p>
        `;
 		Object.keys(regions).forEach(regionId => {
 			regions[regionId].landmarks.forEach((_, index) => {
 				const landmarkId = `landmark-label-${regionId}-${index}`;
 				if (map.getLayer(landmarkId)) map.removeLayer(landmarkId);
 				if (map.getSource(landmarkId)) map.removeSource(landmarkId);
 			});
 		});
 	}

 	// Hiển thị popup cho vùng con
 	function showSubRegionPopup(subRegion, region, lngLat) {
 		const popupContent = `
            <div class="popup-title">${subRegion.name}</div>
            <div class="popup-description">
                <p>Thuộc: ${region.name}</p>
                <p>Dân số: ${subRegion.population}</p>
            </div>
        `;
 		new mapboxgl.Popup({
 				closeOnClick: true
 			})
 			.setLngLat(lngLat)
 			.setHTML(popupContent)
 			.addTo(map);
 	}

 	// Đổi kiểu bản đồ
 	function toggleStyle() {
 		const styles = [
 			'mapbox://styles/mapbox/satellite-streets-v12',
 			'mapbox://styles/mapbox/streets-v12',
 			'mapbox://styles/mapbox/outdoors-v12',
 			'mapbox://styles/mapbox/light-v11'
 		];
 		const currentIndex = styles.indexOf(currentStyle);
 		currentStyle = styles[(currentIndex + 1) % styles.length];
 		map.setStyle(currentStyle);
 		map.once('styledata', () => {
 			addRegionsToMap();
 			addLandmarksToMap();
 			setupMapInteractions();
 		});
 	}

 	// Zoom đến khu vực
 	function zoomToRegion(regionId) {
 		const region = regions[regionId];
 		const bounds = region.bounds;
 		let minLng = bounds[0][0],
 			maxLng = bounds[0][0];
 		let minLat = bounds[0][1],
 			maxLat = bounds[0][1];
 		bounds.forEach(coord => {
 			minLng = Math.min(minLng, coord[0]);
 			maxLng = Math.max(maxLng, coord[0]);
 			minLat = Math.min(minLat, coord[1]);
 			maxLat = Math.max(maxLat, coord[1]);
 		});
 		map.fitBounds(
 			[
 				[minLng, minLat],
 				[maxLng, maxLat]
 			], {
 				padding: {
 					top: 100,
 					bottom: 100,
 					left: 400,
 					right: 100
 				},
 				maxZoom: 12,
 				duration: 1000,
 				pitch: 45,
 				bearing: 0
 			}
 		);
 	}

 	initMap();
 </script>

 <style>
 	#selected-info {
 		position: absolute;
 		left: 10px;
 		top: 10px;
 		width: 300px;
 		background: white;
 		padding: 15px;
 		border-radius: 8px;
 		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
 		z-index: 1;
 	}

 	.landmarks-list {
 		margin-top: 10px;
 	}

 	.landmark-item {
 		padding: 5px 0;
 		font-size: 14px;
 		color: #2d3748;
 	}

 	.popup-title {
 		font-size: 16px;
 		font-weight: bold;
 		color: #2d3748;
 		margin-bottom: 8px;
 	}

 	.popup-description {
 		font-size: 14px;
 		color: #4a5568;
 	}

 	.popup-landmarks {
 		margin-top: 8px;
 	}

 	.popup-landmarks ul {
 		margin: 0;
 		padding-left: 20px;
 		font-size: 13px;
 	}
 </style>
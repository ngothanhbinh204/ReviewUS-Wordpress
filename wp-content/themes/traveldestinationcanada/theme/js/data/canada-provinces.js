// Simplified GeoJSON data for Canadian provinces
export const canadaProvincesData = {
	type: 'FeatureCollection',
	features: [
		{
			type: 'Feature',
			properties: {
				name: 'Alberta',
				code: 'AB',
				active: true,
				description:
					"In Alberta, spectacular doesn't need a spotlight. The Rockies tend to steal the show (and fair enough)—those jagged peaks, turquoise lakes, and ancient glaciers are the kind of raw beauty that makes you want to go further, stay longer, and breathe deeper.",
				image: 'https://images.pexels.com/photos/417074/pexels-photo-417074.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-120.0, 60.0],
						[-110.0, 60.0],
						[-110.0, 49.0],
						[-120.0, 49.0],
						[-120.0, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Saskatchewan',
				code: 'SK',
				active: true,
				description:
					'Saskatchewan offers endless prairie landscapes, vibrant cities, and rich cultural heritage. From the bustling streets of Saskatoon to the serene beauty of Prince Albert National Park.',
				image: 'https://images.pexels.com/photos/158251/forest-the-sun-morning-tucholskie-158251.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-110.0, 60.0],
						[-102.0, 60.0],
						[-102.0, 49.0],
						[-110.0, 49.0],
						[-110.0, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Manitoba',
				code: 'MB',
				active: true,
				description:
					"Manitoba combines urban sophistication with wilderness adventure. Winnipeg's cultural scene thrives alongside pristine northern lakes and polar bear watching in Churchill.",
				image: 'https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-102.0, 60.0],
						[-95.0, 60.0],
						[-95.0, 49.0],
						[-102.0, 49.0],
						[-102.0, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'British Columbia',
				code: 'BC',
				active: false,
				description:
					'British Columbia offers stunning Pacific coastlines, towering mountains, and vibrant cities like Vancouver and Victoria.',
				image: 'https://images.pexels.com/photos/417173/pexels-photo-417173.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-139.0, 60.0],
						[-120.0, 60.0],
						[-120.0, 48.5],
						[-139.0, 48.5],
						[-139.0, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Ontario',
				code: 'ON',
				active: false,
				description:
					"Ontario is home to Canada's capital Ottawa and largest city Toronto, plus the magnificent Niagara Falls and pristine Muskoka region.",
				image: 'https://images.pexels.com/photos/1535162/pexels-photo-1535162.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-95.0, 56.9],
						[-74.0, 56.9],
						[-74.0, 41.9],
						[-95.0, 41.9],
						[-95.0, 56.9],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Quebec',
				code: 'QC',
				active: false,
				description:
					'Quebec offers rich French culture, historic Quebec City, cosmopolitan Montreal, and stunning natural landscapes throughout the province.',
				image: 'https://images.pexels.com/photos/1731660/pexels-photo-1731660.jpeg?auto=compress&cs=tinysrgb&w=800',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-79.0, 62.0],
						[-57.0, 62.0],
						[-57.0, 45.0],
						[-79.0, 45.0],
						[-79.0, 62.0],
					],
				],
			},
		},
	],
};

// Landmark data for each province
export const landmarksData = {
	type: 'FeatureCollection',
	features: [
		// Alberta landmarks
		{
			type: 'Feature',
			properties: {
				name: 'Calgary',
				province: 'Alberta',
				description:
					"Alberta's largest city, home to the famous Calgary Stampede.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-114.0, 51.0],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Edmonton',
				province: 'Alberta',
				description: "Alberta's capital city and cultural hub.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-113.5, 53.5],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Banff',
				province: 'Alberta',
				description:
					'World-famous national park in the Canadian Rockies.',
				type: 'landmark',
			},
			geometry: {
				type: 'Point',
				coordinates: [-115.6, 51.2],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Jasper',
				province: 'Alberta',
				description:
					'Stunning national park known for its pristine wilderness.',
				type: 'landmark',
			},
			geometry: {
				type: 'Point',
				coordinates: [-118.1, 52.9],
			},
		},
		// Saskatchewan landmarks
		{
			type: 'Feature',
			properties: {
				name: 'Regina',
				province: 'Saskatchewan',
				description: "Saskatchewan's capital city.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-104.6, 50.4],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Saskatoon',
				province: 'Saskatchewan',
				description:
					"The 'City of Bridges' on the South Saskatchewan River.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-106.7, 52.1],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Prince Albert National Park',
				province: 'Saskatchewan',
				description: 'Beautiful national park with lakes and forests.',
				type: 'landmark',
			},
			geometry: {
				type: 'Point',
				coordinates: [-106.2, 53.9],
			},
		},
		// Manitoba landmarks
		{
			type: 'Feature',
			properties: {
				name: 'Winnipeg',
				province: 'Manitoba',
				description: "Manitoba's capital and largest city.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-97.1, 49.9],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Churchill',
				province: 'Manitoba',
				description:
					'Famous for polar bear watching and northern lights.',
				type: 'landmark',
			},
			geometry: {
				type: 'Point',
				coordinates: [-94.2, 58.8],
			},
		},
		// Other provinces (limited landmarks since they're disabled)
		{
			type: 'Feature',
			properties: {
				name: 'Vancouver',
				province: 'British Columbia',
				description: 'Major Pacific coast city.',
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-123.1, 49.3],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Toronto',
				province: 'Ontario',
				description: "Canada's largest city.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-79.4, 43.7],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Montreal',
				province: 'Quebec',
				description: "Quebec's largest city.",
				type: 'city',
			},
			geometry: {
				type: 'Point',
				coordinates: [-73.6, 45.5],
			},
		},
	],
};

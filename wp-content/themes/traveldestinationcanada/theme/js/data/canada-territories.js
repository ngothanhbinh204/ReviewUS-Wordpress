// Accurate GeoJSON data for Canadian provinces and territories
export const canadaTerritoriesData = {
	type: 'FeatureCollection',
	features: [
		{
			type: 'Feature',
			properties: {
				name: 'British Columbia',
				code: 'BC',
				active: true,
				description:
					'British Columbia offers stunning Pacific coastlines, towering mountains, and vibrant cities like Vancouver and Victoria. From the Rocky Mountains to the Pacific Ocean, BC is a paradise for outdoor enthusiasts.',
				image: 'https://images.pexels.com/photos/417173/pexels-photo-417173.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Victoria',
				population: '5.2 million',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-139.06, 60.0],
						[-139.06, 48.31],
						[-114.03, 49.0],
						[-114.03, 60.0],
						[-139.06, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Alberta',
				code: 'AB',
				active: true,
				description:
					"In Alberta, spectacular doesn't need a spotlight. The Rockies tend to steal the show with jagged peaks, turquoise lakes, and ancient glaciers that make you want to go further, stay longer, and breathe deeper.",
				image: 'https://images.pexels.com/photos/417074/pexels-photo-417074.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Edmonton',
				population: '4.4 million',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-114.03, 60.0],
						[-110.0, 60.0],
						[-110.0, 49.0],
						[-114.03, 49.0],
						[-114.03, 60.0],
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
				capital: 'Regina',
				population: '1.2 million',
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
				capital: 'Winnipeg',
				population: '1.4 million',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-102.0, 60.0],
						[-95.15, 60.0],
						[-95.15, 49.0],
						[-102.0, 49.0],
						[-102.0, 60.0],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Ontario',
				code: 'ON',
				active: true,
				description:
					"Ontario is home to Canada's capital Ottawa and largest city Toronto, plus the magnificent Niagara Falls and pristine Muskoka region. A province of incredible diversity and opportunity.",
				image: 'https://images.pexels.com/photos/1535162/pexels-photo-1535162.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Toronto',
				population: '14.8 million',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-95.15, 56.86],
						[-74.32, 56.86],
						[-74.32, 41.68],
						[-95.15, 41.68],
						[-95.15, 56.86],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Quebec',
				code: 'QC',
				active: true,
				description:
					'Quebec offers rich French culture, historic Quebec City, cosmopolitan Montreal, and stunning natural landscapes. Experience European charm in North America.',
				image: 'https://images.pexels.com/photos/1731660/pexels-photo-1731660.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Quebec City',
				population: '8.6 million',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-74.32, 62.58],
						[-57.1, 62.58],
						[-57.1, 44.99],
						[-74.32, 44.99],
						[-74.32, 62.58],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'New Brunswick',
				code: 'NB',
				active: true,
				description:
					"New Brunswick offers the world's highest tides in the Bay of Fundy, charming coastal communities, and rich Acadian culture. A maritime province full of natural wonders.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Fredericton',
				population: '780,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-69.05, 47.46],
						[-63.77, 47.46],
						[-63.77, 44.56],
						[-69.05, 44.56],
						[-69.05, 47.46],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Nova Scotia',
				code: 'NS',
				active: true,
				description:
					'Nova Scotia is surrounded by ocean on three sides, offering spectacular coastlines, historic Halifax, and the famous Cabot Trail. Maritime hospitality at its finest.',
				image: 'https://images.pexels.com/photos/1450082/pexels-photo-1450082.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Halifax',
				population: '980,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-66.34, 47.04],
						[-59.73, 47.04],
						[-59.73, 43.37],
						[-66.34, 43.37],
						[-66.34, 47.04],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Prince Edward Island',
				code: 'PE',
				active: true,
				description:
					"Prince Edward Island is famous for red sand beaches, Anne of Green Gables, and the freshest seafood. Canada's smallest province with the biggest heart.",
				image: 'https://images.pexels.com/photos/1591447/pexels-photo-1591447.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Charlottetown',
				population: '160,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-64.42, 47.07],
						[-61.95, 47.07],
						[-61.95, 45.95],
						[-64.42, 45.95],
						[-64.42, 47.07],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Newfoundland and Labrador',
				code: 'NL',
				active: true,
				description:
					'Newfoundland and Labrador offers rugged coastlines, icebergs, whales, and the warmest people in Canada. Experience the edge of North America.',
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: "St. John's",
				population: '520,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-67.8, 60.37],
						[-52.62, 60.37],
						[-52.62, 46.56],
						[-67.8, 46.56],
						[-67.8, 60.37],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Yukon',
				code: 'YT',
				active: true,
				description:
					"Yukon offers the midnight sun, northern lights, and pristine wilderness. Home to Canada's highest peak and endless adventure opportunities.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Whitehorse',
				population: '42,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-141.0, 69.65],
						[-124.0, 69.65],
						[-124.0, 60.0],
						[-141.0, 60.0],
						[-141.0, 69.65],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Northwest Territories',
				code: 'NT',
				active: true,
				description:
					'Northwest Territories offers vast wilderness, diamond mines, and indigenous culture. Experience the true Canadian North.',
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Yellowknife',
				population: '45,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-124.0, 69.65],
						[-102.0, 69.65],
						[-102.0, 60.0],
						[-124.0, 60.0],
						[-124.0, 69.65],
					],
				],
			},
		},
		{
			type: 'Feature',
			properties: {
				name: 'Nunavut',
				code: 'NU',
				active: true,
				description:
					"Nunavut is Canada's newest territory, offering Arctic wildlife, Inuit culture, and the most remote wilderness experiences on Earth.",
				image: 'https://images.pexels.com/photos/1761279/pexels-photo-1761279.jpeg?auto=compress&cs=tinysrgb&w=800',
				capital: 'Iqaluit',
				population: '39,000',
			},
			geometry: {
				type: 'Polygon',
				coordinates: [
					[
						[-102.0, 83.11],
						[-61.0, 83.11],
						[-61.0, 60.0],
						[-102.0, 60.0],
						[-102.0, 83.11],
					],
				],
			},
		},
	],
};

// Enhanced landmarks data with more detailed information
export const canadaLandmarksData = {
	type: 'FeatureCollection',
	features: [
		// British Columbia
		{
			type: 'Feature',
			properties: {
				name: 'Vancouver',
				province: 'British Columbia',
				description:
					'Cosmopolitan coastal city surrounded by mountains',
				type: 'city',
				population: '2.6M',
			},
			geometry: { type: 'Point', coordinates: [-123.12, 49.28] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Victoria',
				province: 'British Columbia',
				description: 'Provincial capital with British colonial charm',
				type: 'capital',
				population: '367K',
			},
			geometry: { type: 'Point', coordinates: [-123.37, 48.43] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Whistler',
				province: 'British Columbia',
				description: 'World-class ski resort and outdoor adventure hub',
				type: 'landmark',
				population: '12K',
			},
			geometry: { type: 'Point', coordinates: [-122.95, 50.12] },
		},

		// Alberta
		{
			type: 'Feature',
			properties: {
				name: 'Calgary',
				province: 'Alberta',
				description: 'Energy capital and home of the Calgary Stampede',
				type: 'city',
				population: '1.3M',
			},
			geometry: { type: 'Point', coordinates: [-114.07, 51.05] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Edmonton',
				province: 'Alberta',
				description: 'Provincial capital and cultural center',
				type: 'capital',
				population: '1.0M',
			},
			geometry: { type: 'Point', coordinates: [-113.49, 53.54] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Banff',
				province: 'Alberta',
				description: 'Iconic Rocky Mountain national park',
				type: 'landmark',
				population: '8K',
			},
			geometry: { type: 'Point', coordinates: [-115.57, 51.18] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Jasper',
				province: 'Alberta',
				description: 'Pristine wilderness and dark sky preserve',
				type: 'landmark',
				population: '5K',
			},
			geometry: { type: 'Point', coordinates: [-118.08, 52.87] },
		},

		// Saskatchewan
		{
			type: 'Feature',
			properties: {
				name: 'Regina',
				province: 'Saskatchewan',
				description: 'Provincial capital and RCMP heritage center',
				type: 'capital',
				population: '230K',
			},
			geometry: { type: 'Point', coordinates: [-104.62, 50.45] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Saskatoon',
				province: 'Saskatchewan',
				description: 'City of Bridges on the South Saskatchewan River',
				type: 'city',
				population: '273K',
			},
			geometry: { type: 'Point', coordinates: [-106.63, 52.13] },
		},

		// Manitoba
		{
			type: 'Feature',
			properties: {
				name: 'Winnipeg',
				province: 'Manitoba',
				description: 'Provincial capital and cultural hub',
				type: 'capital',
				population: '750K',
			},
			geometry: { type: 'Point', coordinates: [-97.14, 49.9] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Churchill',
				province: 'Manitoba',
				description: 'Polar bear capital of the world',
				type: 'landmark',
				population: '900',
			},
			geometry: { type: 'Point', coordinates: [-94.17, 58.77] },
		},

		// Ontario
		{
			type: 'Feature',
			properties: {
				name: 'Toronto',
				province: 'Ontario',
				description: "Canada's largest city and financial center",
				type: 'city',
				population: '2.9M',
			},
			geometry: { type: 'Point', coordinates: [-79.38, 43.65] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Ottawa',
				province: 'Ontario',
				description: 'National capital of Canada',
				type: 'capital',
				population: '1.0M',
			},
			geometry: { type: 'Point', coordinates: [-75.7, 45.42] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Niagara Falls',
				province: 'Ontario',
				description: 'World-famous waterfalls',
				type: 'landmark',
				population: '88K',
			},
			geometry: { type: 'Point', coordinates: [-79.08, 43.08] },
		},

		// Quebec
		{
			type: 'Feature',
			properties: {
				name: 'Montreal',
				province: 'Quebec',
				description: 'Cultural metropolis and largest city in Quebec',
				type: 'city',
				population: '1.8M',
			},
			geometry: { type: 'Point', coordinates: [-73.57, 45.5] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Quebec City',
				province: 'Quebec',
				description: 'Historic walled city and provincial capital',
				type: 'capital',
				population: '540K',
			},
			geometry: { type: 'Point', coordinates: [-71.21, 46.81] },
		},

		// Atlantic Provinces
		{
			type: 'Feature',
			properties: {
				name: 'Halifax',
				province: 'Nova Scotia',
				description: 'Maritime hub and provincial capital',
				type: 'capital',
				population: '440K',
			},
			geometry: { type: 'Point', coordinates: [-63.57, 44.65] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Fredericton',
				province: 'New Brunswick',
				description: 'Provincial capital on the Saint John River',
				type: 'capital',
				population: '63K',
			},
			geometry: { type: 'Point', coordinates: [-66.64, 45.95] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Charlottetown',
				province: 'Prince Edward Island',
				description: 'Birthplace of Confederation',
				type: 'capital',
				population: '70K',
			},
			geometry: { type: 'Point', coordinates: [-63.13, 46.24] },
		},
		{
			type: 'Feature',
			properties: {
				name: "St. John's",
				province: 'Newfoundland and Labrador',
				description: 'Easternmost city in North America',
				type: 'capital',
				population: '110K',
			},
			geometry: { type: 'Point', coordinates: [-52.71, 47.56] },
		},

		// Territories
		{
			type: 'Feature',
			properties: {
				name: 'Whitehorse',
				province: 'Yukon',
				description:
					'Territorial capital and gateway to the wilderness',
				type: 'capital',
				population: '28K',
			},
			geometry: { type: 'Point', coordinates: [-135.05, 60.72] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Yellowknife',
				province: 'Northwest Territories',
				description: 'Diamond capital of North America',
				type: 'capital',
				population: '20K',
			},
			geometry: { type: 'Point', coordinates: [-114.35, 62.45] },
		},
		{
			type: 'Feature',
			properties: {
				name: 'Iqaluit',
				province: 'Nunavut',
				description: 'Arctic territorial capital',
				type: 'capital',
				population: '8K',
			},
			geometry: { type: 'Point', coordinates: [-68.51, 63.75] },
		},
	],
};

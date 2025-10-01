# Canada Interactive Map - Setup Guide

## 🗺️ Overview

Interactive 3D map component using Mapbox GL JS that displays Canadian territories with detailed information panels and points of interest.

## 📋 Features Implemented

### ✅ Core Features:
- **3D Interactive Map**: Mapbox GL JS with 45° pitch
- **Territory Boundaries**: GeoJSON-based territory rendering
- **Click Events**: Territory selection with smooth camera transitions
- **Info Panel**: Slide-in panel with destination details
- **Points of Interest**: Custom markers with popups
- **Mobile Support**: Responsive design with dropdown selector
- **REST API**: WordPress endpoints for data fetching

### 🎨 Visual Features:
- Hover effects on territories
- Smooth camera animations (2s fly-to transitions)
- Custom POI markers with labels
- Loading states
- Error handling
- Mobile-first responsive design

## 🔧 Setup Instructions

### 1. Get Mapbox Access Token

1. Go to [Mapbox Account](https://account.mapbox.com/)
2. Sign up or log in
3. Create a new token with these scopes:
   - `styles:read`
   - `styles:tiles`
   - `fonts:read`
   - `datasets:read`
4. Copy the token

### 2. Configure WordPress

1. Go to **Settings > Mapbox** in WordPress admin
2. Paste your Mapbox Access Token
3. Save settings

### 3. Setup Destination Data

#### A. Add GeoJSON Boundary

For each destination (province/territory), you need GeoJSON data:

**Where to get GeoJSON:**
- [GeoJSON.io](https://geojson.io/) - Draw or find boundaries
- [Natural Earth Data](https://www.naturalearthdata.com/)
- [Canada Open Data](https://open.canada.ca/)

**Format:**
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-140.0, 60.0],
      [-140.0, 70.0],
      [-120.0, 70.0],
      [-120.0, 60.0],
      [-140.0, 60.0]
    ]
  ]
}
```

#### B. Add Coordinates

Format: `longitude,latitude`

Examples:
- British Columbia: `-123.1207,49.2827`
- Ontario: `-79.3832,43.6532`
- Quebec: `-71.2080,46.8139`

#### C. Add Points of Interest

For each destination, add POIs with:
- **Name**: e.g., "CN Tower"
- **Coordinates**: `-79.3871,43.6426`
- **Description**: Optional short text
- **Image**: Optional thumbnail

### 4. Create Destinations

1. Go to **Destinations > Add New**
2. Fill in basic info:
   - Title: e.g., "British Columbia"
   - Content: Full description
   - Excerpt: Short description for map panel
   - Featured Image: Main territory image

3. Fill in Map Data fields:
   - **GeoJSON Boundary**: Paste GeoJSON
   - **Center Coordinates**: e.g., `-123.1207,49.2827`
   - **Zoom Level**: 6 (adjust as needed)
   - **Territory Type**: Province/Territory/City/Region
   - **Map Color**: #dc2626 (or custom color)

4. Add Points of Interest:
   - Click "Add Point"
   - Fill in name, coordinates, description, image
   - Repeat for each POI

5. Publish destination

### 5. Add Map to Page

Use the shortcode anywhere:

```
[canada_interactive_map]
```

**With custom parameters:**
```
[canada_interactive_map height="800px" initial_zoom="4" center_lat="56.1304" center_lng="-106.3468"]
```

### 6. Test the Map

1. Visit page with shortcode
2. Check if map loads correctly
3. Click on territories to test selection
4. Verify info panel appears
5. Check POI markers display
6. Test mobile responsive behavior

## 📊 Data Structure

### Destination Post Type Fields:

```
destination
├── title (post_title)ss
├── content (post_content) - Full description
├── excerpt (post_excerpt) - Short description
├── featured_image (thumbnail)
└── ACF Fields:
    ├── map_geojson - Territory boundary
    ├── map_coordinates - Center point
    ├── map_zoom_level - Zoom on select
    ├── territory_type - Province/Territory/City
    ├── map_color - Territory color on map
    └── points_of_interest (repeater)
        ├── name
        ├── coordinates
        ├── description
        └── image
```

## 🔌 REST API Endpoints

### Get All Destinations:
```
GET /wp-json/tw/v1/map/destinations
```

Response:
```json
[
  {
    "id": 123,
    "title": "British Columbia",
    "slug": "british-columbia",
    "excerpt": "Beautiful Pacific province...",
    "thumbnail": "https://...",
    "url": "https://...",
    "coordinates": { "lng": -123.1207, "lat": 49.2827 },
    "zoom_level": 6,
    "territory_type": "province",
    "map_color": "#dc2626",
    "geojson": { ... }
  }
]
```

### Get Single Destination:
```
GET /wp-json/tw/v1/map/destination/123
```

Response includes POIs array.

## 🎨 Customization

### Change Map Style

In `canada-interactive-map.js`, line 60:
```javascript
style: 'mapbox://styles/mapbox/light-v11',
```

Available styles:
- `streets-v12` - Default streets
- `light-v11` - Light theme
- `dark-v11` - Dark theme
- `satellite-v9` - Satellite imagery
- `outdoors-v12` - Outdoor activities

### Adjust Camera Pitch

In `canada-interactive-map.js`, line 65:
```javascript
pitch: 45, // Change to 0-85
```

### Modify Territory Colors

Edit in WordPress admin or in ACF field `map_color`.

### Customize Info Panel Width

In `canada-interactive-map.css`, line 61:
```css
width: 480px; /* Change as needed */
```

### Change POI Marker Style

In `canada-interactive-map.css`, line 235-250.

## 🐛 Troubleshooting

### Map doesn't load
- Check Mapbox token is configured
- Check browser console for errors
- Verify GeoJSON format is valid

### Territories don't appear
- Verify GeoJSON data is present and valid
- Check coordinates are in correct format (lng, lat)
- Ensure destinations are published

### POI markers don't show
- Check coordinates format in POI repeater
- Verify POI data is saved correctly
- Check browser console for errors

### Mobile issues
- Clear cache
- Check responsive breakpoints in CSS
- Test on actual device, not just browser resize

## 📱 Mobile Behavior

- **Desktop (>1024px)**: Sidebar info panel, full map interactions
- **Mobile (<1024px)**: Bottom overlay panel, dropdown territory selector

## ⚡ Performance Tips

1. **Limit POIs**: Max 10-15 per destination
2. **Optimize Images**: Compress featured images and POI images
3. **Cache**: Use WordPress caching plugin
4. **GeoJSON**: Simplify complex boundaries (use [Mapshaper](https://mapshaper.org/))
5. **Lazy Load**: Map only loads when shortcode is present

## 🌐 Browser Support

- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support (iOS 13.4+)
- IE11: ❌ Not supported (Mapbox GL JS requirement)

## 📚 Resources

- [Mapbox GL JS Documentation](https://docs.mapbox.com/mapbox-gl-js/)
- [GeoJSON Specification](https://geojson.org/)
- [Mapbox Studio](https://studio.mapbox.com/) - Create custom styles
- [GeoJSON.io](https://geojson.io/) - Draw boundaries

## 🎯 Example Usage

### Basic Map:
```
[canada_interactive_map]
```

### Custom Height:
```
[canada_interactive_map height="600px"]
```

### Custom Initial View:
```
[canada_interactive_map initial_zoom="4.5" center_lat="60" center_lng="-95"]
```

---

**Need help?** Check WordPress admin notices or browser console for detailed error messages.

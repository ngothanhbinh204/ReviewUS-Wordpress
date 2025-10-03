# 🗺️ Canada Interactive Map Component

Complete WordPress implementation of a 3D interactive map using Mapbox GL JS for Canadian territories and destinations.

---

## 📦 What's Included

### Core Files:
```
theme/
├── inc/
│   └── canada-interactive-map.php       # Main component (ACF, REST API, shortcode)
├── js/
│   └── canada-interactive-map.js        # JavaScript map controller
├── css/
│   └── canada-interactive-map.css       # Styling and animations
└── docs/
    ├── INTERACTIVE_MAP_SETUP.md         # Complete setup guide
    └── SAMPLE_GEOJSON_DATA.md           # Sample data for all provinces
```

---

## 🚀 Quick Start (5 Minutes)

### 1. Get Mapbox Token
- Visit [Mapbox](https://account.mapbox.com/)
- Create free account
- Generate access token
- Copy token

### 2. Configure WordPress
- Go to **Settings > Mapbox**
- Paste your token
- Save

### 3. Create Your First Destination
- Go to **Destinations > Add New**
- Title: "British Columbia"
- Add featured image
- Add excerpt

**In Map Data section:**
- GeoJSON Boundary: (copy from `SAMPLE_GEOJSON_DATA.md`)
- Coordinates: `-123.1207,54.7267`
- Zoom Level: `5.5`
- Territory Type: `Province`
- Map Color: `#dc2626`

**Add Points of Interest:**
- Click "Add Point"
- Name: "Vancouver"
- Coordinates: `-123.1207,49.2827`
- Description: "Coastal seaport city"

### 4. Add Map to Page
Insert shortcode:
```
[canada_interactive_map]
```

### 5. Test!
- Visit your page
- See map load
- Click territory
- View info panel
- Check POI markers

---

## ✨ Features

### 🎯 User Interactions:
- ✅ Click territories to select (not hover)
- ✅ Smooth 3D camera transitions (2 seconds)
- ✅ Info panel with destination details
- ✅ POI markers with popups
- ✅ Drag, zoom, rotate map freely
- ✅ Mobile dropdown territory selector

### 🎨 Visual Features:
- ✅ 3D map with 45° pitch
- ✅ Custom territory colors
- ✅ Hover effects on territories
- ✅ Loading states
- ✅ Error handling
- ✅ Smooth animations

### 📱 Responsive:
- ✅ Desktop: Sidebar info panel (480px)
- ✅ Mobile: Bottom overlay panel (full width)
- ✅ Touch-friendly interactions
- ✅ Optimized for all screen sizes

### ⚡ Performance:
- ✅ Lazy loading (only loads when shortcode present)
- ✅ REST API caching ready
- ✅ Optimized GeoJSON rendering
- ✅ Minimal dependencies

---

## 🎓 Architecture

### Data Flow:
```
WordPress Destination CPT
    ↓ (ACF Fields)
REST API Endpoints
    ↓ (JSON)
JavaScript Component
    ↓ (Mapbox GL JS)
Interactive 3D Map
```

### Technology Stack:
- **Backend**: WordPress + ACF
- **REST API**: Custom WP REST endpoints
- **Map Engine**: Mapbox GL JS v2.15.0
- **Styling**: Custom CSS + Tailwind utilities
- **Data Format**: GeoJSON for boundaries

---

## 📊 Custom Post Type Structure

```
Destination (destination)
├── Standard Fields:
│   ├── Title
│   ├── Content (full description)
│   ├── Excerpt (short description for panel)
│   └── Featured Image
│
└── ACF Map Fields:
    ├── map_geojson (GeoJSON boundary data)
    ├── map_coordinates (center point: lng,lat)
    ├── map_zoom_level (4-12)
    ├── territory_type (province/territory/city/region)
    ├── map_color (hex color)
    └── points_of_interest (repeater):
        ├── name
        ├── coordinates (lng,lat)
        ├── description
        └── image
```

---

## 🔌 REST API

### Endpoints:

**Get All Destinations:**
```
GET /wp-json/tw/v1/map/destinations
```

Returns array of destinations with basic info and GeoJSON.

**Get Single Destination:**
```
GET /wp-json/tw/v1/map/destination/{id}
```

Returns full destination data including POIs.

### Authentication:
Public endpoints (no auth required).

---

## 📖 Usage Examples

### Basic Map:
```php
[canada_interactive_map]
```

### Custom Height:
```php
[canada_interactive_map height="800px"]
```

### Custom Initial View:
```php
[canada_interactive_map 
    height="700px" 
    initial_zoom="4" 
    center_lat="56.1304" 
    center_lng="-106.3468"]
```

### In PHP Template:
```php
<?php echo do_shortcode('[canada_interactive_map]'); ?>
```

---

## 🎨 Customization

### Change Map Style:
Edit `js/canada-interactive-map.js`, line 60:
```javascript
style: 'mapbox://styles/mapbox/dark-v11', // Change to any Mapbox style
```

### Adjust Camera Behavior:
```javascript
pitch: 60,     // 0-85 degrees
bearing: -20,  // Rotation
duration: 3000 // Transition speed (ms)
```

### Modify Territory Colors:
- Set per-destination in ACF field `map_color`
- Or override globally in CSS

### Customize Info Panel:
Edit `css/canada-interactive-map.css`:
```css
.map-info-panel {
    width: 600px; /* Change panel width */
}
```

---

## 🐛 Troubleshooting

### Map doesn't load:
1. Check Mapbox token is configured
2. Open browser console for errors
3. Verify ACF plugin is active

### Territories don't appear:
1. Check GeoJSON format is valid (use [geojson.io](https://geojson.io/))
2. Verify coordinates format: `lng,lat` (not `lat,lng`)
3. Ensure destination is published

### POIs don't show:
1. Verify coordinates are in correct format
2. Check POI data is saved in ACF
3. Territory must be selected first

### Performance issues:
1. Simplify complex GeoJSON (use [Mapshaper](https://mapshaper.org/))
2. Limit POIs to 10-15 per destination
3. Optimize images (compress thumbnails)
4. Enable WordPress caching

---

## 🌐 Browser Support

| Browser | Support |
|---------|---------|
| Chrome/Edge | ✅ Full |
| Firefox | ✅ Full |
| Safari | ✅ iOS 13.4+ |
| IE11 | ❌ Not supported |

---

## 📚 Resources

### Documentation:
- [Setup Guide](./INTERACTIVE_MAP_SETUP.md) - Detailed instructions
- [Sample Data](./SAMPLE_GEOJSON_DATA.md) - GeoJSON for all provinces
- [Mapbox Docs](https://docs.mapbox.com/mapbox-gl-js/)

### Tools:
- [GeoJSON.io](https://geojson.io/) - Draw/edit boundaries
- [Mapbox Studio](https://studio.mapbox.com/) - Create custom styles
- [Mapshaper](https://mapshaper.org/) - Simplify GeoJSON

### Data Sources:
- [Natural Earth](https://www.naturalearthdata.com/)
- [Canada Open Data](https://open.canada.ca/)
- [OpenStreetMap](https://www.openstreetmap.org/)

---

## 🎯 Use Cases

Perfect for:
- ✅ Tourism websites
- ✅ Travel destination guides
- ✅ Regional directories
- ✅ Real estate listings
- ✅ Event location maps
- ✅ Educational geography sites

---

## 💡 Tips & Best Practices

### GeoJSON:
- Use simplified boundaries (reduce points)
- Test on [geojsonlint.com](https://geojsonlint.com/)
- Keep file size under 100KB per territory

### Performance:
- Compress images before upload
- Use CDN for featured images
- Enable object caching
- Lazy load map (already implemented)

### UX:
- Add clear instructions for users
- Provide territory list/legend
- Show loading states
- Handle errors gracefully

### Mobile:
- Test on real devices
- Ensure touch targets are large enough
- Consider simplified view for small screens

---

## 🔄 Future Enhancements

Potential additions:
- [ ] Search/filter destinations
- [ ] Route planning between POIs
- [ ] Clustering for many POIs
- [ ] Custom map styles
- [ ] Print-friendly view
- [ ] Share/permalink functionality
- [ ] Multi-language support
- [ ] Analytics integration

---

## 📝 License

This component is part of your WordPress theme and follows the same license.

---

## 🤝 Support

For issues or questions:
1. Check the setup guide
2. Review troubleshooting section
3. Check browser console for errors
4. Verify Mapbox token is valid

---

**Ready to create amazing interactive maps! 🗺️✨**

# USA Interactive Map - Documentation

## 🇺🇸 Overview

Interactive 3D map component for reviewing USA destinations with multi-site architecture support.

## 🏗️ Architecture

```
Country (USA)
 └── Region (West, South, Midwest, Northeast)
      └── State (California, Texas, New York, etc.)
           └── City (Los Angeles, Houston, NYC, etc.)
                └── Attraction (Specific POIs)
```

## 🎯 Key Features

### 1. **Permanent Featured Sidebar (Right)**
- Always visible on initial load
- Shows featured destinations (States, Cities, or Attractions)
- Click to zoom and explore
- Horizontal scroll on mobile

### 2. **Slide-in Info Panel (Left)**
- Appears when clicking on a territory or featured item
- Shows destination details, images, and POIs
- Hides the featured sidebar automatically
- Click "Close" to return to initial view

### 3. **Multi-site Ready**
- Hierarchical structure
- Parent-child relationships
- Each state can have its own sub-site
- Featured flag for main map visibility

## 🚀 Quick Start

### 1. Configure Mapbox Token
```
WordPress Admin → Settings → Mapbox
Add your Mapbox Access Token
Get token: https://account.mapbox.com/
```

### 2. Create Your First State
```
Posts → Destinations → Add New

Title: California
Destination Level: State
Featured on Main Map: Yes (✓)
Featured Order: 1

Map Data:
- Center Coordinates: -119.4179,36.7783
- Zoom Level: 6
- Map Color: #dc2626
- GeoJSON: [Paste California boundary data]
```

### 3. Add the Map to a Page
```
[usa_interactive_map]

Attributes (optional):
height="700px"
initial_zoom="4"
center_lat="39.8283"
center_lng="-98.5795"
```

### 4. Add Featured Destinations
- Create multiple states/cities
- Check "Featured on Main Map"
- Set Featured Order (1, 2, 3...)
- They'll appear in the right sidebar

## 📊 ACF Fields Reference

### Destination Level
- **Country**: USA (main entity)
- **Region**: West, South, Midwest, Northeast
- **State**: California, Texas, New York, etc.
- **City**: Los Angeles, Houston, NYC, etc.
- **Attraction**: Specific POI (Golden Gate Bridge, etc.)

### Parent Destination
Link to parent entity:
- State → Region
- City → State
- Attraction → City

### Featured Settings
- **Is Featured**: Show in sidebar
- **Featured Order**: Display order (lower = first)

### Map Data
- **GeoJSON Boundary**: Territory outline
- **Center Coordinates**: lng,lat format
- **Zoom Level**: 4-12 (state=6, city=10)
- **Map Color**: Hex code (#dc2626)

### Points of Interest
Repeater field for attractions within destination:
- Name
- Coordinates (lng,lat)
- Description
- Image

## 🎨 UI/UX Flow

### Initial View
```
┌─────────────────────────────────────┐
│                                     │
│          3D Globe View              │
│     (Centered on USA)               │
│                                     │
│                                     │
└─────────────────────────────────────┘
                                    ┌─────┐
                                    │ F E │
                                    │ A T │
                                    │ T U │
                                    │ U R │
                                    │ R E │
                                    │ E D │
                                    └─────┘
```

### After Clicking Territory/Featured Item
```
┌─────┐
│ I N │  ← Info Panel (Left)
│ F O │     Shows destination details
│     │     Click "Close" to return
│ P A │
│ N E │
│ E L │
└─────┘
         ┌──────────────────────────┐
         │                          │
         │    Zoomed Territory      │
         │    with POI Markers      │
         │                          │
         └──────────────────────────┘
```

## 🌐 Multi-Site Strategy

### Main Site: `yoursite.com`
- Overview of all USA regions
- Featured states/cities
- Country-level content

### Sub-Sites (Multisite):
- `california.yoursite.com` - California destinations
- `texas.yoursite.com` - Texas destinations
- `newyork.yoursite.com` - New York destinations

### Implementation
1. Enable WordPress Multisite
2. Create sub-site for each state
3. Use same theme across all sites
4. Filter destinations by parent state
5. Customize featured items per sub-site

## 🎯 REST API Endpoints

### Get All Destinations
```
GET /wp-json/tw/v1/map/destinations

Returns: Array of all destinations with GeoJSON
```

### Get Featured Destinations
```
GET /wp-json/tw/v1/map/featured

Returns: Array of featured destinations only
```

### Get Single Destination
```
GET /wp-json/tw/v1/map/destination/{id}

Returns: Full destination data with POIs
```

## 🎨 Customization

### Colors
Edit `css/usa-interactive-map.css`:
```css
/* Primary color */
#dc2626 → Your brand color

/* Sidebar gradient */
background: linear-gradient(...);
```

### Map Style
Edit `js/usa-interactive-map.js`:
```javascript
style: 'mapbox://styles/mapbox/light-v11'

Options:
- light-v11
- dark-v11
- streets-v12
- outdoors-v12
- satellite-v9
```

### Initial View
```
Center: -98.5795, 39.8283 (USA center)
Zoom: 4 (full country view)
Pitch: 45° (3D angle)
```

## 📱 Responsive Behavior

### Desktop (>1024px)
- Sidebar: Right, 320px wide
- Info Panel: Left, 400px wide

### Tablet (768px - 1024px)
- Sidebar: Right, 280px wide
- Info Panel: Left, 320px wide

### Mobile (<768px)
- Sidebar: Bottom, 40% height, horizontal scroll
- Info Panel: Full overlay from bottom
- Mobile selector dropdown at bottom

## 🔧 Troubleshooting

### Map not loading?
- Check Mapbox token in Settings → Mapbox
- Open browser console for errors
- Verify token has correct permissions

### No territories visible?
- Add GeoJSON data to destinations
- Check map_coordinates field format
- Ensure destinations are published

### Sidebar empty?
- Check "Featured on Main Map" checkbox
- Set Featured Order values
- Verify destinations have thumbnails

### POI markers not showing?
- Check Points of Interest repeater data
- Verify coordinates format (lng,lat)
- Ensure destination is selected first

## 💡 Best Practices

### Data Entry
1. Start with major states (CA, TX, NY, FL)
2. Add 5-7 featured destinations for sidebar
3. Include high-quality thumbnails
4. Write concise excerpts (15-20 words)

### GeoJSON
- Use simplified boundaries (not too detailed)
- Test on [geojson.io](https://geojson.io/)
- Keep file size under 50KB per state

### Performance
- Limit featured items to 10-12
- Compress images (WebP format)
- Use CDN for thumbnails
- Cache REST API responses

### SEO
- Each destination = separate post
- Hierarchical URLs via parent-child
- Meta descriptions in excerpt field
- Schema markup for locations

## 🚢 Production Checklist

- [ ] Mapbox token configured
- [ ] All 50 states created
- [ ] GeoJSON boundaries added
- [ ] Featured items selected
- [ ] Thumbnails optimized
- [ ] Mobile tested
- [ ] Multi-site configured (if applicable)
- [ ] SSL certificate installed
- [ ] Caching enabled
- [ ] CDN configured

## 📚 Resources

- [Mapbox GL JS Docs](https://docs.mapbox.com/mapbox-gl-js/)
- [GeoJSON Spec](https://geojson.org/)
- [US State Boundaries](https://github.com/PublicaMundi/MappingAPI/tree/master/data/geojson)
- [WordPress Multisite](https://wordpress.org/support/article/create-a-network/)

## 🆘 Support

For issues or questions:
1. Check documentation above
2. Review sample data in `SAMPLE_USA_DATA.md`
3. Inspect browser console for errors
4. Verify Mapbox account status

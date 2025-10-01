# 🎯 Quick Reference - Globe Interaction Features

## 🌍 Visual Hierarchy

```
┌──────────────────────────────────────────────────────┐
│                                                      │
│  🌎 World (Faded 30%)                               │
│     All countries except USA                        │
│     Color: #2d3748                                  │
│     Not clickable                                   │
│                                                      │
│     ┌───────────────────────────────────┐          │
│     │                                   │          │
│     │  🇺🇸 USA (Solid Gray 80%)        │          │
│     │     Color: #4a5568                │          │
│     │     CLICKABLE ← Focus camera     │          │
│     │     HOVERABLE ← Cursor + opacity │          │
│     │                                   │          │
│     │  ┌──────┐  ┌──────┐  ┌──────┐   │          │
│     │  │  CA  │  │  TX  │  │  NY  │   │          │
│     │  │ Red  │  │Orange│  │Purple│   │          │
│     │  │      │  │      │  │      │   │          │
│     │  │ • LA │  │• Aust│  │• NYC │   │          │
│     │  │ • SF │  │• Houst│ │• Buff│   │          │
│     │  └──────┘  └──────┘  └──────┘   │          │
│     │     States (Custom Colors)       │          │
│     │     CLICKABLE ← Zoom to state   │          │
│     │                                   │          │
│     └───────────────────────────────────┘          │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## 🎯 Click Interactions

### 1. Click on USA Territory (Gray Area)
```
Action: Click anywhere on gray USA
Result: 
  ✓ Camera flies to USA center
  ✓ Zoom: 4
  ✓ Pitch: 45°
  ✓ Duration: 2 seconds
  ✓ Shows all states
```

### 2. Click on State Polygon (Colored)
```
Action: Click California (red)
Result:
  ✓ Camera flies to California center
  ✓ Zoom: 6-10 (based on data)
  ✓ Info panel slides in (left)
  ✓ Featured sidebar hides (right)
  ✓ POI markers appear
```

### 3. Click on City Marker (Red Dot)
```
Action: Click Los Angeles marker
Result:
  ✓ Camera flies to LA
  ✓ Zoom: 10
  ✓ Info panel shows city data
  ✓ Related POIs displayed
```

---

## 🎨 Hover Effects

### USA Territory Hover
```
Mouse Enter:
  ✓ Cursor: pointer
  ✓ Opacity: 80% → 100%

Mouse Leave:
  ✓ Cursor: default
  ✓ Opacity: 100% → 80%
```

### State Polygon Hover
```
Mouse Enter:
  ✓ Cursor: pointer
  ✓ Opacity: 70% → 90%

Mouse Leave:
  ✓ Cursor: default
  ✓ Opacity: 90% → 70%
```

---

## 📊 Layer Stack

```
TOP
  10. City Labels (text)
   9. City Markers Inner (red #dc2626)
   8. City Markers Outer (white)
   7. State Labels (text)
   6. State Outlines (white 3px)
   5. State Fills (ACF colors 70-90%)
   4. USA Outline (white 2px)
   3. USA Highlight (gray #4a5568 80%) ← CLICKABLE
   2. World Outline (dark)
   1. World Countries (faded #2d3748 30%)
BOTTOM
```

---

## 🔧 Key Methods (Modular)

### Initialization
```javascript
addWorldBoundaries()        // Faded world countries
addUSAHighlight()           // Solid USA territory
setupGlobeInteractions()    // All click/hover handlers
```

### Interaction Handlers
```javascript
handleUSATerritoryClick(event)    // USA country click
handleUSATerritoryHover(bool)     // USA hover effect
handleTerritoryClick(id)          // State/region click
```

### Layer Management
```javascript
addDestinationLayers()      // State polygons
addCityMarkers()           // City dots
add3DTerrain()             // 3D elevation
```

---

## 🎨 Color Palette

| Element | Color | Opacity | Hex |
|---------|-------|---------|-----|
| World Faded | Dark Gray | 30% | `#2d3748` |
| USA Solid | Medium Gray | 80% | `#4a5568` |
| Borders | White | 100% | `#ffffff` |
| CA State | Red | 70% | `#dc2626` |
| TX State | Orange | 70% | `#ea580c` |
| City Marker | Red | 100% | `#dc2626` |

---

## ⚙️ Configuration

### Camera Defaults
```javascript
center: [-98.5795, 39.8283]  // Kansas (USA center)
zoom: 4                      // Full USA view
pitch: 10                    // Slight tilt
bearing: 0                   // North facing
```

### Scroll Behavior
```javascript
scrollZoom: false            // Disabled by default
Ctrl + Scroll: Enabled       // Manual zoom
```

---

## 🚀 Quick Customization

### Change USA Highlight Color
```javascript
// In addUSAHighlight()
'fill-color': '#1e40af',    // Blue
'fill-color': '#059669',    // Green
'fill-color': '#7c3aed',    // Purple
```

### Adjust USA Opacity
```javascript
'fill-opacity': 0.6,        // More transparent
'fill-opacity': 1.0,        // Fully solid
```

### Change World Fade Level
```javascript
// In addWorldBoundaries()
'fill-opacity': 0.1,        // Very faded
'fill-opacity': 0.5,        // More visible
```

---

## 🧪 Testing Commands

### Browser Console
```javascript
// Access map instance
const map = document.querySelector('.usa-map').__mapboxglMap;

// Toggle USA highlight
map.setLayoutProperty('usa-highlight-fill', 'visibility', 'none');
map.setLayoutProperty('usa-highlight-fill', 'visibility', 'visible');

// Change USA color
map.setPaintProperty('usa-highlight-fill', 'fill-color', '#ff0000');

// Get camera position
console.log('Center:', map.getCenter());
console.log('Zoom:', map.getZoom());
console.log('Pitch:', map.getPitch());
```

---

## 💡 Extension Examples

### Add Tooltip on Hover
```javascript
handleUSATerritoryHover(isHovering, event) {
    if (isHovering) {
        this.showTooltip('Click to explore USA', event.lngLat);
    } else {
        this.hideTooltip();
    }
}
```

### Add Sound Effect on Click
```javascript
handleUSATerritoryClick(event) {
    this.playSound('/sounds/click.mp3');
    this.map.flyTo({ /* ... */ });
}
```

### Track Analytics
```javascript
handleTerritoryClick(destinationId) {
    gtag('event', 'territory_click', {
        destination_id: destinationId
    });
    this.selectDestination(destinationId);
}
```

---

## 🐛 Common Issues & Fixes

### Issue: USA Not Clickable
```javascript
// Check if layer exists
console.log(map.getLayer('usa-highlight-fill'));

// Verify event listener
map.listens('click');
```

### Issue: Wrong Colors
```javascript
// Check paint properties
console.log(map.getPaintProperty('usa-highlight-fill', 'fill-color'));

// Reset to default
map.setPaintProperty('usa-highlight-fill', 'fill-color', '#4a5568');
```

### Issue: Layers Not Showing
```javascript
// Check layer visibility
map.getLayoutProperty('usa-highlight-fill', 'visibility');

// Force visible
map.setLayoutProperty('usa-highlight-fill', 'visibility', 'visible');
```

---

## 📋 Checklist

### Initial Setup
```
□ Mapbox token configured
□ Base map loads (dark-v11 style)
□ USA appears in gray
□ World countries appear faded
```

### Interactions
```
□ Click USA → Camera focuses
□ Hover USA → Cursor changes + opacity
□ Click State → Zoom + info panel
□ Click City → Zoom + details
□ Ctrl+Scroll → Map zooms
□ Normal scroll → Page scrolls
```

### Visual
```
□ USA: Solid gray #4a5568 (80%)
□ World: Faded #2d3748 (30%)
□ States: Custom colors (70-90%)
□ Cities: Red dots with white border
□ Borders: White, clearly visible
```

---

## 📚 Documentation Files

1. **GLOBE_INTERACTION_GUIDE.md** ← Full documentation
2. **CAMERA_AND_UX_GUIDE.md** ← Camera & scroll settings
3. **VISUAL_GUIDE.md** ← ASCII art & visual examples
4. **MIGRATION_SUMMARY.md** ← Migration from Canada
5. **SAMPLE_USA_DATA.md** ← GeoJSON data samples

---

## 🎉 Summary

**3 Main Features:**
1. ✅ Click USA territory on globe → Focus camera
2. ✅ USA solid gray (80%), world faded (30%)
3. ✅ Modular code for easy extensions

**Benefits:**
- Better UX (direct globe interaction)
- Clear visual hierarchy (USA stands out)
- Developer-friendly (modular handlers)
- Performance optimized (vector tiles)

**Next Steps:**
1. Test on your local server
2. Add destinations with GeoJSON
3. Customize colors if needed
4. Add tooltips/animations (optional)

Ready to use! 🚀

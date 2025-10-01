# 🎨 Visual Guide - Map Layers & Markers

## Cấu Trúc Layers

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │                    SKY LAYER                         │  │
│  │            (Atmosphere effect)                       │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐           │  │
│  │  │ STATE 1  │  │ STATE 2  │  │ STATE 3  │           │  │
│  │  │ #dc2626  │  │ #ea580c  │  │ #0891b2  │           │  │
│  │  │          │  │          │  │          │           │  │
│  │  │  ● City  │  │  ● City  │  │  ● City  │           │  │
│  │  │  ● City  │  │  ● City  │  │          │           │  │
│  │  └──────────┘  └──────────┘  └──────────┘           │  │
│  │          STATE POLYGONS + CITY MARKERS               │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              3D TERRAIN LAYER                        │  │
│  │           (exaggeration: 1.5)                        │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## State Polygon Rendering

### Visual Appearance:

```
╔═══════════════════════════════════════╗
║                                       ║
║   ┌─────────────────────────────┐    ║
║   │                             │    ║
║   │      CALIFORNIA             │    ║
║   │   (State Polygon)           │    ║
║   │                             │    ║
║   │   Fill: #dc2626 (50%)       │    ║
║   │   Outline: #ffffff (3px)    │    ║
║   │                             │    ║
║   │   ● Los Angeles             │    ║
║   │   ● San Francisco           │    ║
║   │   ● San Diego               │    ║
║   │                             │    ║
║   └─────────────────────────────┘    ║
║                                       ║
╚═══════════════════════════════════════╝
```

### CSS Properties:

| Property | Value | Description |
|----------|-------|-------------|
| **fill-color** | `#dc2626` (from ACF) | Màu nền state |
| **fill-opacity** | `0.5` (normal)<br>`0.7` (hover) | Độ trong suốt |
| **line-color** | `#ffffff` | Màu viền trắng |
| **line-width** | `3px` | Độ dày viền |
| **line-opacity** | `0.8` | Viền hơi trong suốt |

---

## City Marker Design

### Structure:

```
        City Label
           ↓
     ┌─────────┐
     │ SAN FRAN│
     └────┬────┘
          │
      ┌───▼───┐
      │ ┌───┐ │  ← Outer circle (white, 8px)
      │ │ ● │ │  ← Inner circle (red, 6px)
      │ └───┘ │
      └───────┘
```

### Code Implementation:

```javascript
// Layer 1: Outer white circle
{
    'id': 'city-markers-outer',
    'type': 'circle',
    'paint': {
        'circle-radius': 8,
        'circle-color': '#ffffff',
        'circle-opacity': 1
    }
}

// Layer 2: Inner red circle
{
    'id': 'city-markers-inner',
    'type': 'circle',
    'paint': {
        'circle-radius': 6,
        'circle-color': '#dc2626',
        'circle-opacity': 1
    }
}

// Layer 3: City name label
{
    'id': 'city-labels',
    'type': 'symbol',
    'layout': {
        'text-field': ['get', 'title'],
        'text-size': 11,
        'text-offset': [0, 1.5],  // Offset xuống dưới marker
        'text-anchor': 'top'
    },
    'paint': {
        'text-color': '#dc2626',
        'text-halo-color': '#ffffff',
        'text-halo-width': 2
    }
}
```

---

## Scroll Behavior Flow

### Before Fix (❌ Lỗi):

```
User Action:        Map Behavior:
─────────────       ──────────────
  Scroll ↓     →    Zoom In  🔍
  Scroll ↑     →    Zoom Out 🔍
  
❌ Không scroll được trang!
```

### After Fix (✅ Đúng):

```
User Action:              Map Behavior:
──────────────────        ──────────────
  Scroll ↓           →    Page scrolls down 📄
  Scroll ↑           →    Page scrolls up 📄
  
  Ctrl + Scroll ↓    →    Map Zoom In  🔍
  Ctrl + Scroll ↑    →    Map Zoom Out 🔍
  
✅ Scroll trang bình thường!
✅ Zoom map khi cần (Ctrl)!
```

### Code Logic:

```javascript
this.map.on('wheel', (e) => {
    if (e.originalEvent.ctrlKey) {
        // User đang giữ Ctrl
        e.originalEvent.preventDefault();
        this.map.scrollZoom.enable();  // Cho phép zoom
    } else {
        // Scroll bình thường
        this.map.scrollZoom.disable(); // Không zoom
        // → Browser scroll trang
    }
});
```

---

## Camera Position Explained

### Coordinate System:

```
                North (0°)
                    ↑
                    │
    West (-180°) ←──┼──→ East (180°)
                    │
                    ↓
                South (180°)


USA Center: [-98.5795, 39.8283]
           ↓           ↓
        Longitude   Latitude
        (lng)       (lat)
```

### Camera Properties:

```javascript
{
    center: [-98.5795, 39.8283],  // Trung tâm Kansas
    zoom: 4,                       // Nhìn toàn USA
    pitch: 45,                     // Góc nghiêng 45°
    bearing: 0                     // Hướng Bắc (không xoay)
}
```

### Pitch Visualization:

```
Pitch = 0° (Top-Down View):
┌─────────────────┐
│   ╔═══════╗     │
│   ║ STATE ║     │
│   ╚═══════╝     │
└─────────────────┘


Pitch = 45° (3D Tilted View):
      ┌──────────┐
     ╱          ╱│
    ╱  STATE   ╱ │
   ╱          ╱  │
  └──────────┘   │
   │         │   │
   │         │  ╱
   │         │ ╱
   └─────────┘╱
```

### Bearing Visualization:

```
Bearing = 0° (North):          Bearing = 90° (East):
        ↑                              →
       USA                            USA
        

Bearing = 180° (South):        Bearing = 270° (West):
        ↓                              ←
       USA                            USA
```

---

## Interaction States

### State Polygon Hover:

```
Normal State:
┌─────────────┐
│  CALIFORNIA │
│             │  opacity: 0.5
│             │  cursor: default
└─────────────┘


Hover State:
┌─────────────┐
│  CALIFORNIA │
│             │  opacity: 0.7 (darker)
│             │  cursor: pointer
└─────────────┘
```

### City Marker Hover:

```
Normal:              Hover:
   ●                   ●
San Diego          San Diego
cursor: default    cursor: pointer
```

### Click Flow:

```
1. Initial View:
   ┌────────────────────────────────┐
   │    [Featured Sidebar] →        │
   │    California                  │
   │    Texas                       │
   │                                │
   │    🗺️ Map (zoom: 4)          │
   │    All states visible          │
   └────────────────────────────────┘

2. Click State/City:
   ┌────────────────────────────────┐
   │ ← [Info Panel]                 │
   │   Title, Image                 │
   │   Description                  │
   │   POIs                         │
   │                                │
   │    🗺️ Map (zoom: 6-10)       │
   │    Focused on selection        │
   └────────────────────────────────┘
   [Sidebar hidden]

3. Click Close:
   → Return to Initial View
```

---

## Color Scheme Recommendation

### Regional Colors:

```
╔════════════════════════════════════════╗
║  WEST COAST                            ║
║  ┌────┐ ┌────┐ ┌────┐                 ║
║  │ CA │ │ OR │ │ WA │                 ║
║  │🔴 │ │🔴 │ │🔴 │                 ║
║  └────┘ └────┘ └────┘                 ║
║  #dc2626  #b91c1c  #991b1b            ║
╚════════════════════════════════════════╝

╔════════════════════════════════════════╗
║  SOUTHWEST                             ║
║  ┌────┐ ┌────┐ ┌────┐                 ║
║  │ AZ │ │ NM │ │ TX │                 ║
║  │🟠 │ │🟠 │ │🟠 │                 ║
║  └────┘ └────┘ └────┘                 ║
║  #ea580c  #c2410c  #9a3412            ║
╚════════════════════════════════════════╝

╔════════════════════════════════════════╗
║  MIDWEST                               ║
║  ┌────┐ ┌────┐ ┌────┐                 ║
║  │ IL │ │ OH │ │ MI │                 ║
║  │🔵 │ │🔵 │ │🔵 │                 ║
║  └────┘ └────┘ └────┘                 ║
║  #0891b2  #0e7490  #155e75            ║
╚════════════════════════════════════════╝

╔════════════════════════════════════════╗
║  NORTHEAST                             ║
║  ┌────┐ ┌────┐ ┌────┐                 ║
║  │ NY │ │ MA │ │ PA │                 ║
║  │🟣 │ │🟣 │ │🟣 │                 ║
║  └────┘ └────┘ └────┘                 ║
║  #7c3aed  #6d28d9  #5b21b6            ║
╚════════════════════════════════════════╝
```

---

## Performance Tips

### Layer Order (Bottom to Top):

```
┌─────────────────────────────────┐
│  9. City Labels (text)          │ ← Top
├─────────────────────────────────┤
│  8. City Markers Inner (red)    │
├─────────────────────────────────┤
│  7. City Markers Outer (white)  │
├─────────────────────────────────┤
│  6. State Labels (text)         │
├─────────────────────────────────┤
│  5. State Outlines (lines)      │
├─────────────────────────────────┤
│  4. State Fills (polygons)      │
├─────────────────────────────────┤
│  3. Sky Layer                   │
├─────────────────────────────────┤
│  2. 3D Terrain                  │
├─────────────────────────────────┤
│  1. Base Map Style              │ ← Bottom
└─────────────────────────────────┘
```

### Data Requirements:

```
✅ States:
   - Must have: GeoJSON polygon
   - Must have: map_color
   - Must have: destination_level = 'state'

✅ Cities:
   - Must have: coordinates (lng, lat)
   - Must have: destination_level = 'city'
   - Optional: parent_destination (link to state)
```

---

## Testing Checklist

### Visual Testing:

```
□ States hiển thị với màu sắc khác nhau
□ Ranh giới viền trắng rõ ràng
□ Cities hiển thị dấu chấm đỏ
□ Labels hiển thị không bị chồng lấp
□ Hover state thay đổi opacity
□ 3D terrain có độ nổi rõ ràng
```

### Interaction Testing:

```
□ Scroll thường → trang scroll (không zoom map)
□ Ctrl+Scroll → map zoom in/out
□ Click state → zoom đến state
□ Click city → zoom đến city
□ Hover state → cursor pointer
□ Hover city → cursor pointer
```

### Camera Testing:

```
□ Initial center: Kansas (-98.5795, 39.8283)
□ Initial zoom: 4 (nhìn toàn USA)
□ Initial pitch: 45° (3D view)
□ Initial bearing: 0° (North)
□ flyTo animation smooth
```

---

Sử dụng guide này để debug và test map của bạn! 🚀

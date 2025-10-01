# 🗺️ USA Interactive Map - Camera & UX Settings

## ✅ Các Thay Đổi Đã Thực Hiện

### 1. **Vị Trí Camera và Góc Nhìn Mặc Định**

```javascript
// File: js/usa-interactive-map.js
// Lines: 51-64

// ===================================================
// VỊ TRÍ CAMERA VÀ GÓC NHÌN MẶC ĐỊNH
// ===================================================
const initialZoom = 4;              // Mức zoom (4 = nhìn toàn USA)
const centerLat = 39.8283;          // Vĩ độ (Kansas - trung tâm USA)
const centerLng = -98.5795;         // Kinh độ
const pitch = 45;                    // Góc nghiêng 45° (3D view)
const bearing = 0;                   // Góc xoay 0° (hướng Bắc)
// ===================================================

this.map = new mapboxgl.Map({
    center: [centerLng, centerLat],  // [-98.5795, 39.8283]
    zoom: initialZoom,               // 4
    pitch: 45,                       // Góc nghiêng 45°
    bearing: 0,                      // Hướng Bắc
});
```

#### Tùy Chỉnh Camera:

| Thuộc Tính | Giá Trị Hiện Tại | Mô Tả | Tùy Chỉnh |
|------------|------------------|-------|-----------|
| **center** | `[-98.5795, 39.8283]` | Trung tâm USA (Kansas) | Đổi lng/lat để di chuyển |
| **zoom** | `4` | Nhìn toàn USA | 3-5: Toàn quốc<br>6-8: Vùng/Bang<br>9-12: Thành phố |
| **pitch** | `45` | Góc nghiêng 45° | 0: 2D top-down<br>45-60: 3D tilted |
| **bearing** | `0` | Hướng Bắc | 0-360 độ xoay |

---

### 2. **Sửa Scroll Zoom (Chỉ Zoom Khi Giữ Ctrl)**

**Vấn Đề:** User scroll xuống trang → Map bị zoom in/out

**Giải Pháp:** ✅

```javascript
// File: js/usa-interactive-map.js
// Lines: 69-82

this.map = new mapboxgl.Map({
    scrollZoom: false    // TẮT scroll zoom mặc định
});

// Chỉ cho phép zoom khi giữ Ctrl + scroll
this.map.on('wheel', (e) => {
    if (e.originalEvent.ctrlKey) {
        // Cho phép zoom khi giữ Ctrl
        e.originalEvent.preventDefault();
        this.map.scrollZoom.enable();
    } else {
        // Ngăn zoom, cho phép scroll trang bình thường
        this.map.scrollZoom.disable();
    }
});
```

#### Hành Vi Mới:

- ✅ **Scroll thường**: Trang web scroll xuống bình thường
- ✅ **Ctrl + Scroll**: Map zoom in/out
- ✅ **Pinch zoom** trên mobile: Vẫn hoạt động
- ✅ **Nút +/- zoom**: Vẫn hoạt động

---

### 3. **States/Regions Hiển Thị Trực Tiếp Trên Map**

**Trước:** States hiển thị trong popup (không đúng UX)

**Sau:** ✅ States được vẽ trực tiếp với:
- ✅ Ranh giới rõ ràng (viền trắng 3px)
- ✅ Màu sắc phân vùng (từ ACF field `map_color`)
- ✅ Opacity 50% mặc định, 70% khi hover
- ✅ Label tên State hiển thị trên map

```javascript
// File: js/usa-interactive-map.js
// Method: addDestinationLayers()

// Chỉ hiển thị State/Region (có GeoJSON polygon)
const destinationsWithGeoJSON = this.destinations.filter(d =>
    d.geojson && (d.destination_level === 'state' || d.destination_level === 'region')
);

// Fill layer - Màu nền
'fill-opacity': [
    'case',
    ['boolean', ['feature-state', 'hover'], false],
    0.7,    // Hover: rõ hơn
    0.5     // Mặc định: rõ ràng, dễ nhìn
]

// Outline layer - Ranh giới
'line-color': '#ffffff',    // Viền trắng
'line-width': 3,            // Độ dày rõ ràng
'line-opacity': 0.8
```

---

### 4. **City Markers (Dấu Chấm Tròn) Trên Map**

**Tính Năng Mới:** ✅ Cities được vẽ bằng circular markers

```javascript
// File: js/usa-interactive-map.js
// Method: addCityMarkers()

// Chỉ lấy destinations có level = 'city'
const cities = this.destinations.filter(d =>
    d.destination_level === 'city' && d.coordinates
);

// Vẽ 2 layer circle:
// 1. Outer circle: Viền trắng (radius 8px)
// 2. Inner circle: Màu đỏ (radius 6px)
```

#### Style City Markers:

| Layer | Màu | Radius | Mục Đích |
|-------|-----|--------|----------|
| **Outer** | Trắng (#ffffff) | 8px | Viền nổi bật |
| **Inner** | Đỏ (#dc2626) | 6px | Marker chính |
| **Label** | Đỏ + Halo trắng | 11px | Tên city |

#### Tương Tác:
- ✅ Hover: Cursor pointer
- ✅ Click: Zoom đến city + hiện info panel

---

## 📐 Cấu Trúc Hierarchy

```
Map Layers:
├── 3D Terrain (exaggeration: 1.5)
├── Sky Atmosphere
├── State/Region Fills (polygons với màu)
├── State/Region Outlines (viền trắng)
├── State/Region Labels
├── City Markers Outer (white circles)
├── City Markers Inner (red circles)
└── City Labels
```

---

## 🎨 Màu Sắc và Phân Vùng

### Cách Thiết Lập Màu:

1. **Trong WordPress Admin:**
```
Posts → Destinations → Edit State
Map Data → Map Color: #dc2626
```

2. **Gợi Ý Màu Theo Vùng:**

| Region | States | Màu Hex | Mô Tả |
|--------|--------|---------|-------|
| **West** | CA, OR, WA, NV | `#dc2626` | Đỏ |
| **Southwest** | AZ, NM, TX | `#ea580c` | Cam đậm |
| **Midwest** | IL, OH, MI | `#0891b2` | Xanh dương |
| **South** | FL, GA, NC | `#059669` | Xanh lá |
| **Northeast** | NY, MA, PA | `#7c3aed` | Tím |

---

## 🗂️ Data Structure Cần Có

### 1. States (với GeoJSON):
```php
Title: California
Destination Level: state
Map GeoJSON: {"type":"Polygon",...}
Map Coordinates: -119.4179,36.7783
Map Zoom Level: 6
Map Color: #dc2626
```

### 2. Cities (với coordinates):
```php
Title: Los Angeles
Destination Level: city
Parent Destination: California
Map Coordinates: -118.2437,34.0522
Map Zoom Level: 10
```

### 3. Flow:
```
State (polygon) → chứa nhiều → Cities (circles)
```

---

## 🚀 Test Checklist

### Camera & Controls:
- [ ] Map load với center đúng (-98.5795, 39.8283)
- [ ] Zoom level 4 (nhìn toàn USA)
- [ ] Pitch 45° (góc 3D)
- [ ] Bearing 0° (hướng Bắc)

### Scroll Behavior:
- [ ] Scroll thường → trang web scroll xuống (không zoom map)
- [ ] Ctrl + Scroll → map zoom in/out
- [ ] Pinch zoom mobile hoạt động

### Visual:
- [ ] States hiển thị với màu sắc rõ ràng
- [ ] Ranh giới viền trắng 3px
- [ ] Cities hiển thị dấu chấm đỏ
- [ ] Label tên State/City rõ ràng

### Interaction:
- [ ] Hover State → opacity tăng + cursor pointer
- [ ] Click State → zoom đến + info panel
- [ ] Click City → zoom đến + info panel
- [ ] Featured sidebar hiển thị bên phải

---

## 🔧 Debug Commands

### Test Scroll Zoom:
```javascript
// Mở Console (F12)
// Test scroll behavior
console.log('Scroll Zoom Enabled:', map.scrollZoom._enabled);

// Force enable/disable
map.scrollZoom.enable();  // Bật
map.scrollZoom.disable(); // Tắt
```

### Check Camera Position:
```javascript
// Current camera
console.log('Center:', map.getCenter());
console.log('Zoom:', map.getZoom());
console.log('Pitch:', map.getPitch());
console.log('Bearing:', map.getBearing());
```

### Change Camera Programmatically:
```javascript
// Fly to new position
map.flyTo({
    center: [-98.5795, 39.8283],
    zoom: 4,
    pitch: 45,
    bearing: 0,
    duration: 2000
});
```

---

## 📝 Summary

### ✅ Đã Hoàn Thành:

1. **Camera Position:**
   - Center: Kansas (trung tâm USA)
   - Zoom: 4 (toàn quốc)
   - Pitch: 45° (3D view)
   - Bearing: 0° (hướng Bắc)
   - **Comment rõ ràng** tại lines 51-64

2. **Scroll Zoom Fix:**
   - Tắt scroll zoom mặc định
   - Chỉ zoom khi Ctrl + Scroll
   - Scroll thường → trang web scroll

3. **States On Map:**
   - Vẽ polygon với màu sắc
   - Ranh giới rõ ràng
   - Không dùng popup

4. **City Markers:**
   - Dấu chấm tròn đỏ
   - Label tên city
   - Click để xem chi tiết

### 🎯 Kết Quả:
```
┌────────────────────────────────────────┐
│                                        │
│    🗺️ Map với States (màu sắc)       │
│       + Cities (chấm đỏ)              │
│       + Labels                         │
│                                        │
│    📜 Scroll xuống → Trang scroll     │
│    🔍 Ctrl+Scroll → Map zoom          │
│                                        │
└────────────────────────────────────────┘
```

Tất cả đã sẵn sàng! Test ngay bằng cách tạo States với GeoJSON và Cities với coordinates. 🎉

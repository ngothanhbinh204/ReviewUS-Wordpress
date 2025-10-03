# 🏙️ Import Cities Data - Complete Guide

## 📌 Overview

File này chứa dữ liệu đầy đủ cho **25 cities** (thành phố) trong **10 states** phổ biến nhất của Mỹ. Mỗi city sẽ hiển thị như **red circular marker** (chấm tròn đỏ) trên bản đồ.

---

## 🎯 Cấu trúc dữ liệu

```
USA (Country)
  └── California (State - Polygon màu đỏ)
      ├── Los Angeles (City - Chấm đỏ)
      ├── San Francisco (City - Chấm đỏ)
      └── San Diego (City - Chấm đỏ)
  └── Texas (State - Polygon màu cam)
      ├── Austin (City - Chấm đỏ)
      ├── Houston (City - Chấm đỏ)
      └── Dallas (City - Chấm đỏ)
  └── ... (các bang khác)
```

---

## 📊 Danh sách Cities

### California (5 cities)
1. **Los Angeles** - `-118.2437,34.0522`
2. **San Francisco** - `-122.4194,37.7749`
3. **San Diego** - `-117.1611,32.7157`
4. **Sacramento** - `-121.4944,38.5816`
5. **Santa Barbara** - `-119.6982,34.4208`

### Texas (3 cities)
1. **Austin** - `-97.7431,30.2672`
2. **Houston** - `-95.3698,29.7604`
3. **Dallas** - `-96.7970,32.7767`

### Florida (3 cities)
1. **Miami** - `-80.1918,25.7617`
2. **Orlando** - `-81.3792,28.5383`
3. **Tampa** - `-82.4572,27.9506`

### New York (2 cities)
1. **New York City** - `-74.0060,40.7128`
2. **Buffalo** - `-78.8784,42.8864`

### Nevada (2 cities)
1. **Las Vegas** - `-115.1398,36.1699`
2. **Reno** - `-119.8138,39.5296`

### Arizona (2 cities)
1. **Phoenix** - `-112.0740,33.4484`
2. **Tucson** - `-110.9747,32.2226`

### Hawaii (2 cities)
1. **Honolulu** - `-157.8583,21.3099`
2. **Hilo** - `-155.0844,19.7070`

### Colorado (2 cities)
1. **Denver** - `-104.9903,39.7392`
2. **Colorado Springs** - `-104.8214,38.8339`

### Washington (2 cities)
1. **Seattle** - `-122.3321,47.6062`
2. **Spokane** - `-117.4260,47.6588`

### Oregon (2 cities)
1. **Portland** - `-122.6765,45.5152`
2. **Eugene** - `-123.0868,44.0521`

---

## 🚀 Method 1: PHP Script (Recommended)

### Bước 1: Tạo States trước

Đầu tiên bạn phải có **10 states** trong database với ID cụ thể. Chạy script này để tạo states:

```php
<?php
/**
 * IMPORT STATES FIRST
 * Thêm vào functions.php, chạy 1 lần rồi xóa đi
 */

function reviewus_import_states() {
    $states = [
        [
            'title' => 'California',
            'slug' => 'california',
            'coordinates' => '-119.4179,36.7783',
            'zoom' => 6,
            'color' => '#dc2626',
            'geojson' => '{"type":"Polygon","coordinates":[[[-124.48,42.00],[-124.48,32.53],[-114.13,32.53],[-114.13,42.00],[-124.48,42.00]]]}',
            'excerpt' => 'Golden State with beaches, mountains, and entertainment capital of the world.',
            'featured' => true,
            'order' => 1,
        ],
        [
            'title' => 'Texas',
            'slug' => 'texas',
            'coordinates' => '-99.9018,31.9686',
            'zoom' => 6,
            'color' => '#b91c1c',
            'geojson' => '{"type":"Polygon","coordinates":[[[-106.65,36.50],[-106.65,25.84],[-93.51,25.84],[-93.51,36.50],[-106.65,36.50]]]}',
            'excerpt' => 'Everything is bigger in Texas - cities, culture, BBQ, and cowboy spirit.',
            'featured' => true,
            'order' => 2,
        ],
        [
            'title' => 'Florida',
            'slug' => 'florida',
            'coordinates' => '-81.5158,27.6648',
            'zoom' => 6.5,
            'color' => '#ea580c',
            'geojson' => '{"type":"Polygon","coordinates":[[[-87.63,31.00],[-87.63,24.52],[-80.03,24.52],[-80.03,31.00],[-87.63,31.00]]]}',
            'excerpt' => 'Sunshine State with theme parks, beaches, and tropical paradise.',
            'featured' => true,
            'order' => 3,
        ],
        [
            'title' => 'New York',
            'slug' => 'new-york',
            'coordinates' => '-74.2179,43.2994',
            'zoom' => 6.5,
            'color' => '#7c3aed',
            'geojson' => '{"type":"Polygon","coordinates":[[[-79.76,45.01],[-79.76,40.50],[-71.86,40.50],[-71.86,45.01],[-79.76,45.01]]]}',
            'excerpt' => 'Empire State with NYC, Niagara Falls, and diverse attractions.',
            'featured' => true,
            'order' => 4,
        ],
        [
            'title' => 'Nevada',
            'slug' => 'nevada',
            'coordinates' => '-116.4194,38.8026',
            'zoom' => 6,
            'color' => '#d97706',
            'geojson' => '{"type":"Polygon","coordinates":[[[-120.01,42.00],[-120.01,35.00],[-114.04,35.00],[-114.04,42.00],[-120.01,42.00]]]}',
            'excerpt' => 'Silver State with Las Vegas entertainment and desert beauty.',
            'featured' => true,
            'order' => 5,
        ],
        [
            'title' => 'Arizona',
            'slug' => 'arizona',
            'coordinates' => '-111.0937,34.0489',
            'zoom' => 6.5,
            'color' => '#c2410c',
            'geojson' => '{"type":"Polygon","coordinates":[[[-114.82,37.00],[-114.82,31.33],[-109.05,31.33],[-109.05,37.00],[-114.82,37.00]]]}',
            'excerpt' => 'Grand Canyon State with stunning natural wonders and desert landscapes.',
            'featured' => true,
            'order' => 6,
        ],
        [
            'title' => 'Hawaii',
            'slug' => 'hawaii',
            'coordinates' => '-155.5828,19.8968',
            'zoom' => 7,
            'color' => '#059669',
            'geojson' => '{"type":"MultiPolygon","coordinates":[[[[-160.25,22.23],[-160.25,18.91],[-154.81,18.91],[-154.81,22.23],[-160.25,22.23]]]]}',
            'excerpt' => 'Aloha State with tropical paradise, volcanoes, and island culture.',
            'featured' => true,
            'order' => 7,
        ],
        [
            'title' => 'Colorado',
            'slug' => 'colorado',
            'coordinates' => '-105.7821,39.5501',
            'zoom' => 7,
            'color' => '#0891b2',
            'geojson' => '{"type":"Polygon","coordinates":[[[-109.06,41.00],[-109.06,37.00],[-102.04,37.00],[-102.04,41.00],[-109.06,41.00]]]}',
            'excerpt' => 'Centennial State with Rocky Mountains and world-class ski resorts.',
            'featured' => true,
            'order' => 8,
        ],
        [
            'title' => 'Washington',
            'slug' => 'washington',
            'coordinates' => '-120.7401,47.7511',
            'zoom' => 6.5,
            'color' => '#047857',
            'geojson' => '{"type":"Polygon","coordinates":[[[-124.73,49.00],[-124.73,45.54],[-116.92,45.54],[-116.92,49.00],[-124.73,49.00]]]}',
            'excerpt' => 'Evergreen State with Seattle, rainforests, and Pacific Northwest beauty.',
            'featured' => true,
            'order' => 9,
        ],
        [
            'title' => 'Oregon',
            'slug' => 'oregon',
            'coordinates' => '-120.5542,43.8041',
            'zoom' => 6.5,
            'color' => '#0d9488',
            'geojson' => '{"type":"Polygon","coordinates":[[[-124.57,46.26],[-124.57,41.99],[-116.46,41.99],[-116.46,46.26],[-124.57,46.26]]]}',
            'excerpt' => 'Beaver State with Portland, Crater Lake, and rugged coastline.',
            'featured' => true,
            'order' => 10,
        ],
    ];

    $created_states = [];

    foreach ($states as $state) {
        // Check if state already exists
        $existing = get_page_by_path($state['slug'], OBJECT, 'destination');
        
        if ($existing) {
            $created_states[$state['slug']] = $existing->ID;
            echo "State '{$state['title']}' already exists (ID: {$existing->ID})<br>";
            continue;
        }

        // Create state post
        $post_id = wp_insert_post([
            'post_title' => $state['title'],
            'post_name' => $state['slug'],
            'post_type' => 'destination',
            'post_status' => 'publish',
            'post_excerpt' => $state['excerpt'],
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Update ACF fields
            update_field('destination_level', 'state', $post_id);
            update_field('map_coordinates', $state['coordinates'], $post_id);
            update_field('map_zoom_level', $state['zoom'], $post_id);
            update_field('map_color', $state['color'], $post_id);
            update_field('map_geojson', $state['geojson'], $post_id);
            update_field('is_featured', $state['featured'], $post_id);
            update_field('featured_order', $state['order'], $post_id);

            $created_states[$state['slug']] = $post_id;
            echo "Created state: {$state['title']} (ID: {$post_id})<br>";
        }
    }

    // Save state IDs for next step
    update_option('reviewus_state_ids', $created_states);
    
    echo "<br><strong>✅ DONE! Created " . count($created_states) . " states</strong><br>";
    echo "<pre>" . print_r($created_states, true) . "</pre>";
    
    return $created_states;
}

// Chạy function này 1 lần
// add_action('init', 'reviewus_import_states');
```

### Bước 2: Import Cities

Sau khi có states, chạy script này để tạo cities (các chấm đỏ):

```php
<?php
/**
 * IMPORT CITIES (Red Dots)
 * Thêm vào functions.php, chạy 1 lần rồi xóa đi
 */

function reviewus_import_cities() {
    // Get state IDs from previous import
    $state_ids = get_option('reviewus_state_ids', []);
    
    if (empty($state_ids)) {
        echo "❌ ERROR: No states found! Run reviewus_import_states() first.<br>";
        return;
    }

    $cities = [
        // California cities
        [
            'title' => 'Los Angeles',
            'parent_slug' => 'california',
            'coordinates' => '-118.2437,34.0522',
            'zoom' => 10,
            'excerpt' => 'Entertainment capital with Hollywood, beaches, and diverse culture.',
            'featured' => true,
            'order' => 1,
        ],
        [
            'title' => 'San Francisco',
            'parent_slug' => 'california',
            'coordinates' => '-122.4194,37.7749',
            'zoom' => 11,
            'excerpt' => 'Iconic Golden Gate Bridge, cable cars, and tech innovation hub.',
            'featured' => true,
            'order' => 2,
        ],
        [
            'title' => 'San Diego',
            'parent_slug' => 'california',
            'coordinates' => '-117.1611,32.7157',
            'zoom' => 10,
            'excerpt' => 'Beautiful beaches, perfect weather, and world-famous zoo.',
            'featured' => false,
        ],
        [
            'title' => 'Sacramento',
            'parent_slug' => 'california',
            'coordinates' => '-121.4944,38.5816',
            'zoom' => 11,
            'excerpt' => 'California capital with rich Gold Rush history.',
            'featured' => false,
        ],
        [
            'title' => 'Santa Barbara',
            'parent_slug' => 'california',
            'coordinates' => '-119.6982,34.4208',
            'zoom' => 12,
            'excerpt' => 'American Riviera with Spanish architecture and wine country.',
            'featured' => false,
        ],

        // Texas cities
        [
            'title' => 'Austin',
            'parent_slug' => 'texas',
            'coordinates' => '-97.7431,30.2672',
            'zoom' => 11,
            'excerpt' => 'Live music capital with vibrant culture and tech scene.',
            'featured' => true,
            'order' => 3,
        ],
        [
            'title' => 'Houston',
            'parent_slug' => 'texas',
            'coordinates' => '-95.3698,29.7604',
            'zoom' => 10,
            'excerpt' => 'Space City with NASA, diverse culture, and amazing food.',
            'featured' => false,
        ],
        [
            'title' => 'Dallas',
            'parent_slug' => 'texas',
            'coordinates' => '-96.7970,32.7767',
            'zoom' => 10,
            'excerpt' => 'Modern metropolis with arts, cowboys, and business hub.',
            'featured' => false,
        ],

        // Florida cities
        [
            'title' => 'Miami',
            'parent_slug' => 'florida',
            'coordinates' => '-80.1918,25.7617',
            'zoom' => 11,
            'excerpt' => 'Art Deco, beaches, nightlife, and Latin American culture.',
            'featured' => true,
            'order' => 4,
        ],
        [
            'title' => 'Orlando',
            'parent_slug' => 'florida',
            'coordinates' => '-81.3792,28.5383',
            'zoom' => 10,
            'excerpt' => 'Theme park capital with Disney World and Universal Studios.',
            'featured' => true,
            'order' => 5,
        ],
        [
            'title' => 'Tampa',
            'parent_slug' => 'florida',
            'coordinates' => '-82.4572,27.9506',
            'zoom' => 11,
            'excerpt' => 'Gulf Coast gem with beaches, culture, and adventure.',
            'featured' => false,
        ],

        // New York cities
        [
            'title' => 'New York City',
            'parent_slug' => 'new-york',
            'coordinates' => '-74.0060,40.7128',
            'zoom' => 11,
            'excerpt' => 'The city that never sleeps - Times Square, Central Park, Lady Liberty.',
            'featured' => true,
            'order' => 6,
        ],
        [
            'title' => 'Buffalo',
            'parent_slug' => 'new-york',
            'coordinates' => '-78.8784,42.8864',
            'zoom' => 11,
            'excerpt' => 'Gateway to Niagara Falls with rich industrial history.',
            'featured' => false,
        ],

        // Nevada cities
        [
            'title' => 'Las Vegas',
            'parent_slug' => 'nevada',
            'coordinates' => '-115.1398,36.1699',
            'zoom' => 11,
            'excerpt' => 'Entertainment and casino capital - The Strip, shows, nightlife.',
            'featured' => true,
            'order' => 7,
        ],
        [
            'title' => 'Reno',
            'parent_slug' => 'nevada',
            'coordinates' => '-119.8138,39.5296',
            'zoom' => 11,
            'excerpt' => 'The Biggest Little City with casinos and nearby Lake Tahoe.',
            'featured' => false,
        ],

        // Arizona cities
        [
            'title' => 'Phoenix',
            'parent_slug' => 'arizona',
            'coordinates' => '-112.0740,33.4484',
            'zoom' => 10,
            'excerpt' => 'Valley of the Sun with desert beauty and Southwest culture.',
            'featured' => true,
            'order' => 8,
        ],
        [
            'title' => 'Tucson',
            'parent_slug' => 'arizona',
            'coordinates' => '-110.9747,32.2226',
            'zoom' => 11,
            'excerpt' => 'Sonoran Desert oasis with Mexican influence and cacti forests.',
            'featured' => false,
        ],

        // Hawaii cities
        [
            'title' => 'Honolulu',
            'parent_slug' => 'hawaii',
            'coordinates' => '-157.8583,21.3099',
            'zoom' => 11,
            'excerpt' => 'Capital city with Waikiki Beach, Diamond Head, and aloha spirit.',
            'featured' => true,
            'order' => 9,
        ],
        [
            'title' => 'Hilo',
            'parent_slug' => 'hawaii',
            'coordinates' => '-155.0844,19.7070',
            'zoom' => 12,
            'excerpt' => 'Big Island gateway with waterfalls, rainforests, and volcanoes.',
            'featured' => false,
        ],

        // Colorado cities
        [
            'title' => 'Denver',
            'parent_slug' => 'colorado',
            'coordinates' => '-104.9903,39.7392',
            'zoom' => 11,
            'excerpt' => 'Mile High City with mountains, breweries, and outdoor adventures.',
            'featured' => true,
            'order' => 10,
        ],
        [
            'title' => 'Colorado Springs',
            'parent_slug' => 'colorado',
            'coordinates' => '-104.8214,38.8339',
            'zoom' => 11,
            'excerpt' => 'Pikes Peak, Garden of the Gods, and Olympic Training Center.',
            'featured' => false,
        ],

        // Washington cities
        [
            'title' => 'Seattle',
            'parent_slug' => 'washington',
            'coordinates' => '-122.3321,47.6062',
            'zoom' => 11,
            'excerpt' => 'Emerald City with Space Needle, coffee culture, and tech giants.',
            'featured' => false,
        ],
        [
            'title' => 'Spokane',
            'parent_slug' => 'washington',
            'coordinates' => '-117.4260,47.6588',
            'zoom' => 11,
            'excerpt' => 'Eastern Washington hub with Riverfront Park and outdoor recreation.',
            'featured' => false,
        ],

        // Oregon cities
        [
            'title' => 'Portland',
            'parent_slug' => 'oregon',
            'coordinates' => '-122.6765,45.5152',
            'zoom' => 11,
            'excerpt' => 'Keep Portland Weird - food carts, breweries, and eco-friendly culture.',
            'featured' => false,
        ],
        [
            'title' => 'Eugene',
            'parent_slug' => 'oregon',
            'coordinates' => '-123.0868,44.0521',
            'zoom' => 11,
            'excerpt' => 'Track Town USA with University of Oregon and outdoor lifestyle.',
            'featured' => false,
        ],
    ];

    $created_cities = 0;
    $skipped_cities = 0;

    foreach ($cities as $city) {
        // Get parent state ID
        $parent_id = isset($state_ids[$city['parent_slug']]) ? $state_ids[$city['parent_slug']] : null;
        
        if (!$parent_id) {
            echo "❌ Skipped '{$city['title']}': Parent state '{$city['parent_slug']}' not found<br>";
            $skipped_cities++;
            continue;
        }

        // Check if city already exists
        $existing = get_page_by_path(sanitize_title($city['title']), OBJECT, 'destination');
        if ($existing) {
            echo "⚠️  City '{$city['title']}' already exists (ID: {$existing->ID})<br>";
            $skipped_cities++;
            continue;
        }

        // Create city post
        $post_id = wp_insert_post([
            'post_title' => $city['title'],
            'post_type' => 'destination',
            'post_status' => 'publish',
            'post_excerpt' => $city['excerpt'],
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Update ACF fields
            update_field('destination_level', 'city', $post_id);
            update_field('parent_destination', $parent_id, $post_id);
            update_field('map_coordinates', $city['coordinates'], $post_id);
            update_field('map_zoom_level', $city['zoom'], $post_id);
            
            if (isset($city['featured'])) {
                update_field('is_featured', $city['featured'], $post_id);
            }
            if (isset($city['order'])) {
                update_field('featured_order', $city['order'], $post_id);
            }

            echo "✅ Created city: {$city['title']} (ID: {$post_id}) → Parent: {$city['parent_slug']}<br>";
            $created_cities++;
        } else {
            echo "❌ Failed to create: {$city['title']}<br>";
            $skipped_cities++;
        }
    }

    echo "<br><strong>✅ DONE!</strong><br>";
    echo "Created: {$created_cities} cities<br>";
    echo "Skipped: {$skipped_cities} cities<br>";
    echo "<br><strong>🗺️  Reload your map to see red dots!</strong><br>";
}

// Chạy function này 1 lần
// add_action('init', 'reviewus_import_cities');
```

---

## 📝 Hướng dẫn sử dụng

### Step 1: Import States
1. Mở file `functions.php` của theme
2. Copy toàn bộ code **IMPORT STATES FIRST**
3. Uncomment dòng: `add_action('init', 'reviewus_import_states');`
4. Reload trang admin bất kỳ (VD: Dashboard)
5. Xem kết quả in ra màn hình
6. Comment lại hoặc xóa code

### Step 2: Import Cities
1. Mở file `functions.php` của theme
2. Copy toàn bộ code **IMPORT CITIES**
3. Uncomment dòng: `add_action('init', 'reviewus_import_cities');`
4. Reload trang admin
5. Xem kết quả
6. Comment lại hoặc xóa code

### Step 3: Kiểm tra kết quả
1. Truy cập: **Posts > Destinations**
2. Sẽ thấy:
   - 10 States (California, Texas, Florida...)
   - 25 Cities (Los Angeles, Miami, Las Vegas...)
3. Mở trang có shortcode `[usa_interactive_map]`
4. Sẽ thấy:
   - **Colored polygons** cho states
   - **Red circular dots** cho cities
   - Sidebar bên phải có **Featured destinations**

---

## 🎨 Kết quả mong đợi

### Trên bản đồ:
```
🌍 Globe
  └── USA (solid gray, clickable)
      └── California (red polygon)
          ├── 🔴 Los Angeles
          ├── 🔴 San Francisco
          ├── 🔴 San Diego
          ├── 🔴 Sacramento
          └── 🔴 Santa Barbara
      └── Texas (orange polygon)
          ├── 🔴 Austin
          ├── 🔴 Houston
          └── 🔴 Dallas
      └── Florida (orange polygon)
          ├── 🔴 Miami
          ├── 🔴 Orlando
          └── 🔴 Tampa
      └── ... (các bang khác với red dots)
```

### Trong sidebar:
```
Featured Destinations
1. Los Angeles
2. San Francisco
3. Austin
4. Miami
5. Orlando
6. New York City
7. Las Vegas
8. Phoenix
9. Honolulu
10. Denver
```

---

## 🔧 Troubleshooting

### Problem: Không thấy red dots
**Solution:**
- Kiểm tra `destination_level` = `'city'` (không phải 'City')
- Kiểm tra `map_coordinates` format: `'lng,lat'` (VD: `-118.2437,34.0522`)
- Không có `map_geojson` cho cities (chỉ có coordinates)

### Problem: Cities không liên kết với States
**Solution:**
- Kiểm tra `parent_destination` field có ID của state
- Verify state IDs bằng: `print_r(get_option('reviewus_state_ids'));`

### Problem: Sidebar không hiển thị cities
**Solution:**
- Check `is_featured` = `true` hoặc `1`
- Check `featured_order` có giá trị số (1, 2, 3...)
- REST API endpoint: `/wp-json/tw/v1/map/featured`

### Problem: Red dots quá nhỏ hoặc quá lớ
**Solution:**
Sửa trong `js/usa-interactive-map.js`:
```javascript
// Tìm dòng này (khoảng line 150-170)
'circle-radius': [
    'interpolate',
    ['linear'],
    ['zoom'],
    4, 3,    // Zoom 4 → radius 3px
    8, 6,    // Zoom 8 → radius 6px (default)
    12, 10   // Zoom 12 → radius 10px
],

// Thay đổi thành lớn hơn:
'circle-radius': [
    'interpolate',
    ['linear'],
    ['zoom'],
    4, 5,    // Zoom 4 → radius 5px
    8, 8,    // Zoom 8 → radius 8px
    12, 12   // Zoom 12 → radius 12px
],
```

---

## 🎯 Quick Test

Sau khi import, test ngay:

```javascript
// Mở browser console (F12)

// 1. Check cities layer exists
map.getLayer('city-markers-inner');

// 2. Check cities data
fetch('/wp-json/tw/v1/map/destinations')
    .then(r => r.json())
    .then(data => {
        const cities = data.features.filter(f => f.properties.destination_level === 'city');
        console.log(`Found ${cities.length} cities:`, cities);
    });

// 3. Check featured cities
fetch('/wp-json/tw/v1/map/featured')
    .then(r => r.json())
    .then(data => {
        console.log('Featured destinations:', data);
    });

// 4. Toggle city markers visibility
map.setLayoutProperty('city-markers-inner', 'visibility', 'none'); // Hide
map.setLayoutProperty('city-markers-inner', 'visibility', 'visible'); // Show
```

---

## 📊 Summary

| Item | Count | Type | Display |
|------|-------|------|---------|
| States | 10 | Polygon | Colored shapes |
| Cities | 25 | Point | Red circular dots |
| Featured | 10 | Mixed | Sidebar items |

**Total destinations**: 35

---

## 💡 Next Steps

Sau khi có cities, bạn có thể:

1. **Add more cities**: Copy format trong script, thêm cities mới
2. **Add attractions**: Tạo level `'attraction'` làm con của cities
3. **Add images**: Upload featured image cho mỗi destination
4. **Customize markers**: Thay đổi màu, size, icon của red dots
5. **Add tooltips**: Hiển thị popup khi hover vào red dots

---

🎉 **Good luck! Sau khi chạy 2 scripts, bạn sẽ có bản đồ hoàn chỉnh với states + cities!**

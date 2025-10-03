# Sample GeoJSON Data for Canadian Provinces

## British Columbia
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-139.06, 60.00],
      [-139.06, 48.30],
      [-114.03, 49.00],
      [-114.03, 60.00],
      [-139.06, 60.00]
    ]
  ]
}
```

**Coordinates**: `-123.1207,54.7267`
**Zoom Level**: 5.5

---

## Alberta
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-120.00, 60.00],
      [-120.00, 49.00],
      [-110.00, 49.00],
      [-110.00, 60.00],
      [-120.00, 60.00]
    ]
  ]
}
```

**Coordinates**: `-114.0719,53.9333`
**Zoom Level**: 6

---

## Ontario
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-95.15, 56.85],
      [-95.15, 41.67],
      [-74.32, 41.67],
      [-74.32, 56.85],
      [-95.15, 56.85]
    ]
  ]
}
```

**Coordinates**: `-85.3232,51.2538`
**Zoom Level**: 5.5

---

## Quebec
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-79.76, 62.58],
      [-79.76, 45.00],
      [-57.10, 45.00],
      [-57.10, 62.58],
      [-79.76, 62.58]
    ]
  ]
}
```

**Coordinates**: `-71.2080,52.9399`
**Zoom Level**: 5

---

## Manitoba
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-102.00, 60.00],
      [-102.00, 49.00],
      [-89.00, 49.00],
      [-89.00, 60.00],
      [-102.00, 60.00]
    ]
  ]
}
```

**Coordinates**: `-98.7393,53.7609`
**Zoom Level**: 6

---

## Saskatchewan
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-110.00, 60.00],
      [-110.00, 49.00],
      [-101.36, 49.00],
      [-101.36, 60.00],
      [-110.00, 60.00]
    ]
  ]
}
```

**Coordinates**: `-106.3468,52.9399`
**Zoom Level**: 6

---

## Nova Scotia
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-66.33, 47.00],
      [-66.33, 43.42],
      [-59.68, 43.42],
      [-59.68, 47.00],
      [-66.33, 47.00]
    ]
  ]
}
```

**Coordinates**: `-63.5859,44.6820`
**Zoom Level**: 7

---

## New Brunswick
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-69.05, 48.07],
      [-69.05, 44.60],
      [-63.77, 44.60],
      [-63.77, 48.07],
      [-69.05, 48.07]
    ]
  ]
}
```

**Coordinates**: `-66.4619,46.5653`
**Zoom Level**: 7

---

## Prince Edward Island
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-64.42, 47.06],
      [-64.42, 45.95],
      [-61.97, 45.95],
      [-61.97, 47.06],
      [-64.42, 47.06]
    ]
  ]
}
```

**Coordinates**: `-63.1311,46.5107`
**Zoom Level**: 8.5

---

## Newfoundland and Labrador
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-67.80, 60.37],
      [-67.80, 46.61],
      [-52.62, 46.61],
      [-52.62, 60.37],
      [-67.80, 60.37]
    ]
  ]
}
```

**Coordinates**: `-57.6604,53.1355`
**Zoom Level**: 5.5

---

## Yukon
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-141.00, 69.65],
      [-141.00, 60.00],
      [-123.50, 60.00],
      [-123.50, 69.65],
      [-141.00, 69.65]
    ]
  ]
}
```

**Coordinates**: `-135.0568,64.2823`
**Zoom Level**: 5.5

---

## Northwest Territories
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-136.47, 78.75],
      [-136.47, 60.00],
      [-102.00, 60.00],
      [-102.00, 78.75],
      [-136.47, 78.75]
    ]
  ]
}
```

**Coordinates**: `-117.0543,64.8255`
**Zoom Level**: 4.5

---

## Nunavut
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [-120.00, 83.11],
      [-120.00, 60.00],
      [-61.00, 60.00],
      [-61.00, 83.11],
      [-120.00, 83.11]
    ]
  ]
}
```

**Coordinates**: `-95.0820,70.2998`
**Zoom Level**: 4

---

## Sample Points of Interest

### British Columbia POIs:
1. **Vancouver**
   - Coordinates: `-123.1207,49.2827`
   - Description: "Coastal seaport city"

2. **Victoria**
   - Coordinates: `-123.3656,48.4284`
   - Description: "Provincial capital"

3. **Whistler**
   - Coordinates: `-122.9574,50.1163`
   - Description: "World-class ski resort"

### Ontario POIs:
1. **Toronto**
   - Coordinates: `-79.3832,43.6532`
   - Description: "Canada's largest city"

2. **Ottawa**
   - Coordinates: `-75.6972,45.4215`
   - Description: "National capital"

3. **Niagara Falls**
   - Coordinates: `-79.0377,43.0896`
   - Description: "Famous waterfalls"

### Quebec POIs:
1. **Montreal**
   - Coordinates: `-73.5673,45.5017`
   - Description: "Largest city in Quebec"

2. **Quebec City**
   - Coordinates: `-71.2080,46.8139`
   - Description: "Provincial capital"

3. **Mont-Tremblant**
   - Coordinates: `-74.5971,46.2122`
   - Description: "Popular ski resort"

---

## Quick Import Script

Use this PHP script to quickly import all provinces:

```php
// Add to functions.php temporarily
function import_canadian_provinces() {
    $provinces = [
        [
            'title' => 'British Columbia',
            'coordinates' => '-123.1207,54.7267',
            'zoom' => 5.5,
            'type' => 'province',
            'geojson' => '{"type":"Polygon","coordinates":[[[-139.06,60.00],[-139.06,48.30],[-114.03,49.00],[-114.03,60.00],[-139.06,60.00]]]}',
        ],
        // Add more provinces...
    ];
    
    foreach ($provinces as $province) {
        $post_id = wp_insert_post([
            'post_title' => $province['title'],
            'post_type' => 'destination',
            'post_status' => 'publish',
        ]);
        
        if ($post_id) {
            update_field('map_coordinates', $province['coordinates'], $post_id);
            update_field('map_zoom_level', $province['zoom'], $post_id);
            update_field('territory_type', $province['type'], $post_id);
            update_field('map_geojson', $province['geojson'], $post_id);
        }
    }
}
// Run once: import_canadian_provinces();
```

---

## Notes

- **Coordinates are simplified** - For production, use more accurate GeoJSON from official sources
- **Test each territory** after importing to ensure proper display
- **Adjust zoom levels** based on territory size and desired view
- Use [GeoJSON.io](https://geojson.io/) to refine boundaries

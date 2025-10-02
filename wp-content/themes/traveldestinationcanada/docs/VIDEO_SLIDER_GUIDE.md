# Video Slider Guide

## Overview
Video slider section hiển thị các video posts với thumbnail và chức năng phát video fullscreen modal khi click vào play button.

## Cấu trúc File

### section-slideZoomCenter.php
**Location:** `/theme/template-parts/layout/section-slideZoomCenter.php`

**Chức năng:**
- Query video posts từ Custom Post Type 'video'
- Hiển thị video thumbnails trong Swiper slider
- Modal popup để phát video fullscreen
- Hỗ trợ YouTube, Vimeo và direct video files

## Cấu hình Video

### 1. Đăng ký Custom Post Type 'video'
Đã được đăng ký trong `functions.php`:
```php
register_post_type('video', array(
    'labels' => array(
        'name' => 'Videos',
        'singular_name' => 'Video'
    ),
    'public' => true,
    'supports' => array('title', 'editor', 'thumbnail'),
    'menu_icon' => 'dashicons-embed-video',
    'show_in_rest' => true,
));
```

### 2. ACF Field cho Video
**Field name:** `video_embed`
**Field type:** Text hoặc URL
**Location:** Video Post Type

**Cách thêm ACF field:**
1. Vào ACF → Field Groups
2. Tạo Field Group mới: "Video Settings"
3. Thêm field:
   - Label: Video Embed URL
   - Name: `video_embed`
   - Type: Text hoặc URL
4. Location: Post Type is equal to video
5. Save

## Cách sử dụng

### 1. Thêm Video mới
```
1. Vào WordPress Admin → Videos → Add New
2. Điền Title (tên video)
3. Thêm Featured Image (thumbnail video)
4. Thêm Excerpt (mô tả ngắn)
5. Trong field "Video Embed URL", dán link video:
   - YouTube: https://www.youtube.com/watch?v=VIDEO_ID
   - Vimeo: https://vimeo.com/VIDEO_ID
   - Direct file: https://domain.com/video.mp4
6. Publish
```

### 2. Hiển thị Video Slider
Trong template file (homepage.php hoặc bất kỳ template nào):
```php
<?php get_template_part('template-parts/layout/section-slideZoomCenter'); ?>
```

## Các tính năng

### 1. Query Videos
```php
$video_args = array(
    'post_type' => 'video',
    'posts_per_page' => 8, // Maximum 8 videos
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
);
```

**Tuỳ chỉnh số lượng video:**
- Thay đổi `posts_per_page` từ 8 sang số khác
- -1 để hiển thị tất cả videos

### 2. Video Modal
**Tự động convert video URLs:**
- YouTube watch URL → Embed URL với autoplay
- Vimeo URL → Embed player URL
- Direct video files (.mp4, .webm) → HTML5 video player

**Controls:**
- Click Play Button → Mở modal
- Click Close Button (X) → Đóng modal
- Click outside modal → Đóng modal
- Press ESC key → Đóng modal
- Body scroll bị prevent khi modal mở

### 3. Responsive Design
```css
- Mobile: 1 video per view
- Tablet: 2-3 videos per view (depending on Swiper config)
- Desktop: 3-4 videos per view
- Modal: Fullscreen với 16:9 aspect ratio
```

## Styling

### Play Button Hover Effect
```css
.video-play-button:hover svg {
    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
    transform: scale(1.1);
}
```

### Modal Animation
```css
@keyframes modalSlideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
```

### Custom Styles
Thêm CSS vào `style.css` để tuỳ chỉnh:
```css
/* Custom video slider styles */
.slideZoomCetner .swiper-slide {
    /* Your styles */
}

.video-modal {
    /* Custom modal styles */
}
```

## JavaScript Functions

### getEmbedUrl(url)
Converts various video URL formats to embed URLs:
```javascript
// YouTube
youtube.com/watch?v=ABC123 → youtube.com/embed/ABC123?autoplay=1
youtu.be/ABC123 → youtube.com/embed/ABC123?autoplay=1

// Vimeo
vimeo.com/123456789 → player.vimeo.com/video/123456789?autoplay=1

// Direct file
domain.com/video.mp4 → Uses HTML5 video player
```

### Modal Functions
```javascript
// Open modal
playButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        // Get video embed URL from data attribute
        // Convert to embed URL
        // Insert into modal
        // Show modal
    });
});

// Close modal
function closeModal() {
    // Hide modal
    // Clear video container
    // Enable body scroll
}
```

## Troubleshooting

### Video không hiển thị
**Giải pháp:**
1. Kiểm tra CPT 'video' đã được register chưa
2. Kiểm tra có video posts đã publish chưa
3. Kiểm tra featured image đã được set chưa
4. Check console log có errors không

### Video không play khi click
**Giải pháp:**
1. Kiểm tra ACF field `video_embed` có giá trị chưa
2. Xem console log có errors không
3. Kiểm tra video URL format có đúng không
4. Test với YouTube/Vimeo URLs khác

### Modal không đóng
**Giải pháp:**
1. Check JavaScript có load chưa
2. Kiểm tra console errors
3. Test ESC key và click outside
4. Verify close button ID matches

### Thumbnails không hiển thị
**Giải pháp:**
```php
// Add fallback image
if (!$video_thumbnail) {
    $video_thumbnail = get_template_directory_uri() . '/assets/images/default-video-thumb.jpg';
}
```

## Advanced Customization

### 1. Filter Videos by Category
```php
$video_args = array(
    'post_type' => 'video',
    'posts_per_page' => 8,
    'tax_query' => array(
        array(
            'taxonomy' => 'video_category',
            'field' => 'slug',
            'terms' => 'featured'
        )
    )
);
```

### 2. Add Video Duration
```php
$video_duration = get_field('video_duration', $video_id);
if ($video_duration) {
    echo '<span class="video-duration">' . esc_html($video_duration) . '</span>';
}
```

### 3. Add View Count
```php
$view_count = get_post_meta($video_id, 'video_views', true);
echo '<span class="video-views">' . number_format($view_count) . ' views</span>';
```

### 4. Lazy Load Videos
```php
// Add loading="lazy" to thumbnails
<img loading="lazy" src="<?php echo esc_url($video_thumbnail); ?>">
```

### 5. Custom Swiper Configuration
```javascript
const swiper = new Swiper('.travelSwiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 40,
        },
        1280: {
            slidesPerView: 4,
            spaceBetween: 50,
        },
    }
});
```

## Performance Tips

1. **Optimize Thumbnails:**
   ```php
   // Use specific image size
   $video_thumbnail = get_the_post_thumbnail_url($video_id, 'medium_large');
   ```

2. **Limit Video Count:**
   ```php
   'posts_per_page' => 8, // Don't load too many videos
   ```

3. **Lazy Load:**
   ```html
   <img loading="lazy" decoding="async">
   ```

4. **Defer Scripts:**
   ```php
   wp_enqueue_script('video-modal', get_template_directory_uri() . '/js/video-modal.js', array(), '1.0', true);
   ```

## SEO Considerations

### 1. Add Schema Markup
```php
$schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'VideoObject',
    'name' => get_the_title(),
    'description' => get_the_excerpt(),
    'thumbnailUrl' => $video_thumbnail,
    'uploadDate' => get_the_date('c'),
    'embedUrl' => $video_embed
);
echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
```

### 2. Alt Text for Thumbnails
```php
<img alt="<?php echo esc_attr($video_title); ?>" src="...">
```

### 3. ARIA Labels
```html
<button aria-label="Play Video: <?php echo esc_attr($video_title); ?>">
```

## Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Dependencies

- Swiper JS (already loaded)
- Tailwind CSS
- ACF Pro
- Modern browser with ES6 support

## Future Enhancements

1. Add video categories/tags filtering
2. Add search functionality
3. Add video playlists
4. Add video analytics tracking
5. Add social sharing buttons
6. Add related videos section
7. Add video transcripts
8. Add subtitles support

## Support

Nếu gặp vấn đề, kiểm tra:
1. Console log errors
2. ACF field configuration
3. Video URLs format
4. Featured images uploaded
5. Video posts published

## Version History

- v1.0 (2025-01-XX): Initial release with YouTube, Vimeo, and direct video support

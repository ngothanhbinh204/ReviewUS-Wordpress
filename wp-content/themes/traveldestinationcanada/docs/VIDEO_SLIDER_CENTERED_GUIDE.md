# Video Slider - Centered with Peek Effect Guide

## Overview
Video slider với hiệu ứng **centered slides** - hiển thị slide active ở giữa và 1 phần nhỏ của slide trước/sau ở hai bên.

## Visual Effect

### Active Slide (Center)
- **Opacity:** 1 (full visible)
- **Scale:** 1 (100% size)
- **Height:** 584px (desktop) / 484px (mobile)
- **z-index:** 10 (on top)

### Inactive Slides (Sides)
- **Opacity:** 0.5 (50% visible)
- **Scale:** 0.85 (85% size)
- **Height:** 400px (compressed)
- **Play button:** 60% opacity, smaller

## Swiper Configuration

### Basic Settings
```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    slidesPerView: 'auto',      // Auto width
    centeredSlides: true,        // Center active slide
    spaceBetween: 30,            // Gap between slides
    loop: true,                  // Infinite loop
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});
```

### Responsive Breakpoints
```javascript
breakpoints: {
    320: {
        slidesPerView: 1.2,  // Show 20% of next slide
        spaceBetween: 20,
    },
    640: {
        slidesPerView: 1.3,  // Show 30% of next slide
        spaceBetween: 25,
    },
    1024: {
        slidesPerView: 1.5,  // Show 50% of next slide
        spaceBetween: 30,
    },
    1280: {
        slidesPerView: 1.8,  // Show 80% of next slide
        spaceBetween: 40,
    }
}
```

## CSS Styling

### Slide Transitions
```css
.travelSwiper .swiper-slide {
    opacity: 0.5;
    transform: scale(0.85);
    transition: all 0.4s ease;
}

.travelSwiper .swiper-slide-active {
    opacity: 1;
    transform: scale(1);
    z-index: 10;
}
```

### Height Adjustments
```css
/* Non-active slides have shorter height */
.travelSwiper .swiper-slide:not(.swiper-slide-active) .relative {
    height: 400px !important;
}

/* Active slide keeps original height (484px/584px) */
```

### Play Button Styling
```css
/* Non-active slides: smaller, dimmer play button */
.travelSwiper .swiper-slide:not(.swiper-slide-active) .video-play-button {
    opacity: 0.6;
    transform: translate(-50%, -50%) scale(0.8);
    top: 200px !important; /* Adjusted for smaller height */
}

/* Active slide: full opacity, normal size */
.travelSwiper .swiper-slide-active .video-play-button {
    opacity: 1;
}
```

## Customization Options

### 1. Change Peek Amount
Thay đổi `slidesPerView` để hiển thị nhiều/ít slide bên cạnh:

```javascript
breakpoints: {
    1280: {
        slidesPerView: 2.2,  // Show more of side slides
        spaceBetween: 40,
    }
}
```

**Values:**
- `1.2` = Show 20% of side slides (less peek)
- `1.5` = Show 50% of side slides (medium peek)
- `1.8` = Show 80% of side slides (more peek)
- `2.2` = Show more than 1 slide on each side

### 2. Adjust Opacity
Thay đổi opacity của inactive slides:

```css
.travelSwiper .swiper-slide {
    opacity: 0.3; /* More dimmed (0.3 instead of 0.5) */
}
```

### 3. Change Scale Effect
Thay đổi scale để tạo hiệu ứng zoom mạnh/nhẹ hơn:

```css
.travelSwiper .swiper-slide {
    transform: scale(0.7); /* Smaller side slides (0.7 instead of 0.85) */
}

.travelSwiper .swiper-slide-active {
    transform: scale(1.1); /* Larger active slide (1.1 instead of 1.0) */
}
```

### 4. Add Blur Effect
Thêm blur cho inactive slides:

```css
.travelSwiper .swiper-slide {
    filter: blur(2px);
}

.travelSwiper .swiper-slide-active {
    filter: blur(0);
}
```

### 5. Disable Loop
Tắt infinite loop nếu không muốn:

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    loop: false, // Disable loop
    // ... other settings
});
```

## Advanced Features

### 1. Auto Play
Thêm auto play để slider tự động chuyển:

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    autoplay: {
        delay: 3000,           // 3 seconds
        disableOnInteraction: false,
    },
    // ... other settings
});
```

### 2. Keyboard Navigation
Enable điều khiển bằng keyboard:

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    keyboard: {
        enabled: true,
        onlyInViewport: true,
    },
    // ... other settings
});
```

### 3. Pagination Dots
Thêm pagination dots:

```html
<div class="swiper-pagination"></div>
```

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
    },
    // ... other settings
});
```

```css
.swiper-pagination {
    bottom: 10px !important;
}

.swiper-pagination-bullet {
    background: white;
    opacity: 0.5;
}

.swiper-pagination-bullet-active {
    opacity: 1;
}
```

### 4. Mouse Wheel Control
Enable scroll bằng mouse wheel:

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    mousewheel: {
        invert: false,
        forceToAxis: true,
    },
    // ... other settings
});
```

### 5. Touch Gestures
Tuỳ chỉnh touch gestures:

```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    touchRatio: 1,
    touchAngle: 45,
    grabCursor: true,
    // ... other settings
});
```

## Performance Tips

### 1. Lazy Load Images
```php
<img loading="lazy" 
     decoding="async"
     src="<?php echo esc_url($video_thumbnail); ?>">
```

### 2. Limit Video Count
```php
$video_args = array(
    'posts_per_page' => 6, // Reduce from 8 to 6
);
```

### 3. Use Smaller Image Sizes
```php
$video_thumbnail = get_the_post_thumbnail_url($video_id, 'medium_large');
// Instead of 'large'
```

### 4. Disable Loop for Few Slides
```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    loop: document.querySelectorAll('.swiper-slide').length > 3,
    // Only enable loop if more than 3 slides
});
```

## Responsive Behavior

### Mobile (320px - 639px)
- **slidesPerView:** 1.2
- **spaceBetween:** 20px
- Active slide dominates
- Small peek of next slide (20%)

### Tablet (640px - 1023px)
- **slidesPerView:** 1.3
- **spaceBetween:** 25px
- Balanced view
- Medium peek (30%)

### Desktop (1024px - 1279px)
- **slidesPerView:** 1.5
- **spaceBetween:** 30px
- Active + half of sides
- Large peek (50%)

### Large Desktop (1280px+)
- **slidesPerView:** 1.8
- **spaceBetween:** 40px
- Active + most of sides
- Very large peek (80%)

## Animation Timeline

```
Slide Change Event:
├─ 0ms: Click navigation button
├─ 50ms: Start transition
│   ├─ Current active: opacity 1 → 0.5
│   ├─ Current active: scale 1 → 0.85
│   ├─ Current active: height 584px → 400px
│   ├─ New active: opacity 0.5 → 1
│   ├─ New active: scale 0.85 → 1
│   └─ New active: height 400px → 584px
└─ 400ms: Transition complete
```

## Browser Support

- ✅ Chrome 60+
- ✅ Firefox 60+
- ✅ Safari 12+
- ✅ Edge 79+
- ✅ iOS Safari 12+
- ✅ Chrome Android 60+

## Troubleshooting

### Issue: Slides not centered
**Solution:**
```css
.travelSwiper {
    overflow: visible; /* Change from hidden */
}

.section_slider {
    overflow: hidden; /* Add to parent instead */
}
```

### Issue: Side slides cut off
**Solution:**
```css
.travelSwiper {
    padding: 20px 50px; /* Add horizontal padding */
}
```

### Issue: Navigation buttons hidden
**Solution:**
```css
.travelSwiper .swiper-button-prev {
    left: 20px; /* Move inward */
}

.travelSwiper .swiper-button-next {
    right: 20px; /* Move inward */
}
```

### Issue: Jump on slide change
**Solution:**
```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    speed: 600, // Increase transition speed
    // ... other settings
});
```

### Issue: Slides too close together
**Solution:**
```javascript
breakpoints: {
    1280: {
        slidesPerView: 1.5, // Reduce from 1.8
        spaceBetween: 60,   // Increase spacing
    }
}
```

## Accessibility

### Keyboard Navigation
- **Left Arrow:** Previous slide
- **Right Arrow:** Next slide
- **Tab:** Focus navigation buttons
- **Enter/Space:** Activate focused button

### ARIA Labels
```html
<button aria-label="Play Video: <?php echo esc_attr($video_title); ?>">
<button aria-label="Previous Slide" class="swiper-button-prev">
<button aria-label="Next Slide" class="swiper-button-next">
```

### Screen Reader Support
```html
<div role="region" aria-label="Video Slider">
    <div class="swiper" aria-live="polite">
        <!-- Slides -->
    </div>
</div>
```

## SEO Considerations

### 1. Lazy Load Non-Active Slides
```javascript
const travelSwiper = new Swiper('.travelSwiper', {
    lazy: {
        loadPrevNext: true,
        loadPrevNextAmount: 2,
    },
});
```

### 2. Add Alt Text
```php
<img alt="Video: <?php echo esc_attr($video_title); ?>" src="...">
```

### 3. Structured Data
Already included in main VIDEO_SLIDER_GUIDE.md

## Design Variations

### Variation 1: Extreme Peek (Show 2+ side slides)
```javascript
slidesPerView: 2.5,
spaceBetween: 30,
```

### Variation 2: Minimal Peek (Just a hint)
```javascript
slidesPerView: 1.1,
spaceBetween: 10,
```

### Variation 3: No Scale, Only Opacity
```css
.travelSwiper .swiper-slide {
    opacity: 0.3;
    transform: scale(1); /* No scale */
}

.travelSwiper .swiper-slide-active {
    opacity: 1;
}
```

### Variation 4: Grayscale Effect
```css
.travelSwiper .swiper-slide {
    filter: grayscale(100%);
}

.travelSwiper .swiper-slide-active {
    filter: grayscale(0%);
}
```

### Variation 5: 3D Effect
```css
.travelSwiper .swiper-slide {
    transform: perspective(1000px) rotateY(10deg);
}

.travelSwiper .swiper-slide-active {
    transform: perspective(1000px) rotateY(0deg);
}
```

## Testing Checklist

- [ ] Slider centers active slide
- [ ] Side slides visible (peek effect)
- [ ] Navigation buttons work
- [ ] Touch swipe works on mobile
- [ ] Keyboard navigation works
- [ ] Active slide has full opacity
- [ ] Inactive slides dimmed
- [ ] Play button scales properly
- [ ] Video modal still works
- [ ] Responsive on all breakpoints
- [ ] Smooth transitions
- [ ] Loop works correctly
- [ ] Performance is good

## Version History

- v2.0 (2025-01-XX): Added centered slides with peek effect
- v1.0 (2025-01-XX): Initial basic slider

## References

- [Swiper Centered Slides Demo](https://swiperjs.com/demos#centered)
- [Swiper API Documentation](https://swiperjs.com/swiper-api)

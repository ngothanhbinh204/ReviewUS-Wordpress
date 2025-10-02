# 📱 Hướng Dẫn Setup ACF Options - Socials

## 🎯 Tổng Quan

Hướng dẫn cấu hình ACF Options Page để quản lý social media links cho toàn site.

---

## 📥 Bước 1: Add ACF Options Code

### 1.1 Mở Functions.php

1. Vào **Appearance > Theme Editor**
2. Chọn **functions.php**
3. Hoặc mở trực tiếp: `/wp-content/themes/traveldestinationcanada/theme/functions.php`

### 1.2 Thêm Code

Paste code này vào cuối file `functions.php`:

```php
<?php
/**
 * ACF Options Page - Socials
 */
add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_68dddb49309cb',
	'title' => 'Options',
	'fields' => array(
		array(
			'key' => 'field_68dddb4abc2e0',
			'label' => 'Socials',
			'name' => 'socials',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => 'Add social media links that will appear in Follow Us section',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'pagination' => 0,
			'min' => 0,
			'max' => 6, // Maximum 6 social icons
			'collapsed' => '',
			'button_label' => 'Add Social',
			'rows_per_page' => 20,
			'sub_fields' => array(
				array(
					'key' => 'field_68dddb5ebc2e1',
					'label' => 'URL',
					'name' => 'url',
					'aria-label' => '',
					'type' => 'url',
					'instructions' => 'Full URL including https://',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '60%',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://www.facebook.com/yourpage',
					'parent_repeater' => 'field_68dddb4abc2e0',
				),
				array(
					'key' => 'field_68dddb70bc2e2',
					'label' => 'Icon',
					'name' => 'icon',
					'aria-label' => '',
					'type' => 'image',
					'instructions' => '24x24px SVG or PNG icon',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '40%',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'library' => 'all',
					'min_width' => 16,
					'min_height' => 16,
					'min_size' => '',
					'max_width' => 64,
					'max_height' => 64,
					'max_size' => '50KB',
					'mime_types' => 'png,jpg,jpeg,svg',
					'preview_size' => 'thumbnail',
					'parent_repeater' => 'field_68dddb4abc2e0',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => 'Global site options including social media links',
	'show_in_rest' => 0,
) );
} );

/**
 * Add ACF Options Page
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' => 'Site Options',
        'menu_title' => 'Site Options',
        'menu_slug' => 'site-options',
        'capability' => 'edit_posts',
        'icon_url' => 'dashicons-admin-generic',
        'position' => 30,
    ));
}
```

### 1.3 Save & Refresh

1. Click **Update File**
2. Refresh WordPress admin
3. Kiểm tra menu bên trái có **"Site Options"**

---

## ⚙️ Bước 2: Configure Options Page

### 2.1 Access Options

1. Vào **Site Options** trong admin menu
2. Sẽ thấy tab **"Socials"**

### 2.2 Add Social Media

Click **"Add Social"** để thêm từng social media:

#### **Row 1: Facebook**
- **URL**: `https://www.facebook.com/DiscoverAmerica`
- **Icon**: Upload Facebook icon (24x24px)

#### **Row 2: Instagram**  
- **URL**: `https://www.instagram.com/exploreusa/`
- **Icon**: Upload Instagram icon (24x24px)

#### **Row 3: Twitter/X**
- **URL**: `https://www.twitter.com/exploreusa`
- **Icon**: Upload Twitter icon (24x24px)

#### **Row 4: YouTube (Optional)**
- **URL**: `https://www.youtube.com/c/DiscoverAmerica`
- **Icon**: Upload YouTube icon (24x24px)

#### **Row 5: TikTok (Optional)**
- **URL**: `https://www.tiktok.com/@exploreusa`
- **Icon**: Upload TikTok icon (24x24px)

#### **Row 6: Pinterest (Optional)**
- **URL**: `https://www.pinterest.com/exploreusa/`
- **Icon**: Upload Pinterest icon (24x24px)

### 2.3 Save Options

Click **"Update Options"** để lưu thay đổi.

---

## 🎨 Chuẩn Bị Social Icons

### Download Social Icons

**Free Sources:**
- [Heroicons](https://heroicons.com/) - Clean, minimal
- [Feather Icons](https://feathericons.com/) - Lightweight
- [Simple Icons](https://simpleicons.org/) - Brand icons
- [Font Awesome](https://fontawesome.com/icons) - Popular choice

### Icon Specifications

**Kích thước:** 24x24px (hoặc 32x32px)
**Format:** SVG (preferred) hoặc PNG
**Style:** Outline hoặc solid
**Color:** White (#ffffff) hoặc transparent (sẽ được CSS tô màu)

### Sample Icons Code

Nếu không có icon, dùng SVG inline:

**Facebook:**
```html
<svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
</svg>
```

**Instagram:**
```html
<svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.435-3.296-1.154-.848-.72-1.296-1.615-1.296-2.571 0-.955.448-1.850 1.296-2.570.848-.72 1.999-1.155 3.296-1.155 1.296 0 2.447.435 3.295 1.155.849.72 1.297 1.615 1.297 2.570 0 .956-.448 1.851-1.297 2.571-.848.719-1.999 1.154-3.295 1.154z"/>
</svg>
```

---

## 📋 Bước 3: Test Results

### 3.1 View Homepage

1. Visit homepage: `https://yoursite.com/`
2. Scroll xuống section **"Follow us and share"**
3. Kiểm tra:
   - ✅ Social icons hiển thị đúng
   - ✅ Links hoạt động (mở tab mới)
   - ✅ Hover effects work
   - ✅ Icons có màu trắng

### 3.2 Test Gallery

Kiểm tra gallery section:
- ✅ Hiển thị tối đa 4 ảnh
- ✅ Grid layout responsive
- ✅ Hover effects (zoom + caption)
- ✅ Click vào ảnh → Instagram link

### 3.3 Test Fallbacks

**Test khi không có socials:**
1. Xóa hết socials trong Options
2. Reload page
3. Sẽ thấy 3 default icons (Facebook, Instagram, Twitter)

**Test khi không có gallery:**
1. Xóa gallery trong homepage ACF
2. Reload page  
3. Section "Follow Us" sẽ không hiển thị

---

## 🔧 Customization

### Change Hashtag

Trong `section-followUs.php`, tìm dòng:
```php
<span class="block hyphens-none font-bold">#ExploreUSA</span>
```

Đổi thành:
```php
<span class="block hyphens-none font-bold">#DiscoverAmerica</span>
```

### Add More Social Platforms

Chỉnh sửa `max` trong field group:
```php
'max' => 10, // Tăng từ 6 lên 10
```

### Change Icon Size

Trong `section-followUs.php`, tìm:
```php
width="24" height="24"
```

Đổi thành:
```php
width="32" height="32"
```

### Add Custom CSS

Thêm vào `style.css`:
```css
.icon_social {
    transition: all 0.3s ease;
}

.icon_social:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    padding: 4px;
}

.gallery a:hover img {
    filter: brightness(1.1);
}
```

---

## 🐛 Troubleshooting

### Problem: Options page không xuất hiện

**Solution:**
1. Check ACF Pro đã active chưa
2. Clear cache (browser + WordPress)
3. Check functions.php syntax error
4. Re-add options page code

### Problem: Social icons không hiển thị

**Solution:**
1. Check đã điền URL trong Options chưa
2. Check icon đã upload chưa
3. Inspect element xem có lỗi 404 cho icon
4. Re-upload icon với format khác (SVG → PNG)

### Problem: Gallery không responsive

**Solution:**
1. Check Tailwind CSS đã load chưa
2. Clear cache
3. Test trên mobile device thật
4. Check grid classes in devtools

### Problem: Links không mở tab mới

**Solution:**
1. Check `target="_blank"` có trong code
2. Check URL format: `https://...`
3. Test với browser khác
4. Check popup blocker settings

---

## 📊 Data Structure

```
Site Options (ACF Options Page)
└── Socials (Repeater)
    ├── Row 1: Facebook
    │   ├── url: "https://www.facebook.com/page"
    │   └── icon: facebook-icon.svg
    ├── Row 2: Instagram
    │   ├── url: "https://www.instagram.com/account"
    │   └── icon: instagram-icon.svg
    └── Row 3+: Other platforms

Homepage ACF
└── Gallery (Gallery Field)
    ├── Image 1: beach-sunset.jpg
    ├── Image 2: mountain-view.jpg
    ├── Image 3: city-skyline.jpg
    └── Image 4: nature-scene.jpg (max 4 images)
```

---

## 💡 Best Practices

### Social Media URLs

**Format correctly:**
```
✅ https://www.facebook.com/YourPage
✅ https://www.instagram.com/youraccount/
✅ https://twitter.com/YourHandle

❌ facebook.com/YourPage (missing https)
❌ www.facebook.com/YourPage (missing protocol)
```

### Icon Optimization

**File sizes:**
- SVG: < 5KB
- PNG: < 10KB
- Optimize with SVGOMG or TinyPNG

**Naming convention:**
- `facebook-icon.svg`
- `instagram-icon.svg`
- `twitter-icon.svg`

### Gallery Images

**Optimal specs:**
- Size: 800x800px (1:1 ratio)
- Format: WebP or JPG
- File size: < 150KB each
- Alt text: Descriptive

**Caption examples:**
```
"Golden Gate Bridge at sunset #California"
"Rocky Mountains hiking trail #Colorado"
"Times Square at night #NewYork"
"Beach volleyball at Miami #Florida"
```

---

## ✅ Final Checklist

### Setup
- [ ] Added ACF Options code to functions.php
- [ ] Site Options menu appears in admin
- [ ] Can access Options page

### Socials Configuration  
- [ ] Added Facebook URL + icon
- [ ] Added Instagram URL + icon
- [ ] Added Twitter URL + icon
- [ ] (Optional) Added YouTube, TikTok, Pinterest
- [ ] All URLs start with https://
- [ ] All icons are 24x24px or similar
- [ ] Saved options successfully

### Gallery Configuration
- [ ] Uploaded 2-4 images to homepage gallery ACF
- [ ] Images are properly sized (800x800px recommended)
- [ ] Added alt text to all images
- [ ] Added captions with hashtags
- [ ] Images optimized (< 150KB each)

### Testing
- [ ] Homepage loads without errors
- [ ] Social icons display in Follow Us section
- [ ] All social links open in new tab
- [ ] Gallery displays max 4 images
- [ ] Gallery grid is responsive
- [ ] Hover effects work (icon scale, image zoom)
- [ ] Fallbacks work when data is empty

### Performance
- [ ] Icons optimized (< 10KB each)
- [ ] Images optimized (WebP if possible)
- [ ] Page load time < 3 seconds
- [ ] Mobile responsive

---

## 🎉 Done!

Your Follow Us section is now dynamic with:
- ✅ **Social icons** from ACF Options (global)
- ✅ **Gallery images** from homepage ACF (max 4)
- ✅ **Responsive layout** adapting to image count
- ✅ **Hover effects** for better UX
- ✅ **Fallback content** when data is empty

**Next steps:**
1. Add more social platforms
2. Update gallery regularly
3. Monitor social engagement
4. A/B test different hashtags

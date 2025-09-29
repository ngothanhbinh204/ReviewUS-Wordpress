# Hướng dẫn Setup Mega Menu

## 1. Tạo Menu trong WordPress Admin

1. Vào **Appearance > Menus**
2. Tạo menu mới tên "Primary Menu"
3. Assign vào location "Primary"

## 2. Cấu trúc Menu

### Menu cấp 1 (Top Level):
- Places to go
- Things to do  
- Plan your trip
- Travel packages
- Wildfire guidance

### Menu cấp 2 (Sub Items) cho "Places to go":
- Western Canada
  - British Columbia
  - Alberta
  - Vancouver
  - Victoria
  - Calgary
  - Edmonton
- The Prairies of Canada
  - Manitoba
  - Saskatchewan
- Central Canada
  - Ontario
  - Quebec
- Atlantic Canada
  - New Brunswick
  - Nova Scotia
  - Prince Edward Island
  - Newfoundland and Labrador
- Northern Canada
  - Northwest Territories
  - Nunavut
  - Yukon

## 3. Setup ACF Field

1. Vào **Custom Fields > Field Groups**
2. Tạo field group mới: "Menu Item Image"
3. Thêm field:
   - Field Label: Menu Item Image
   - Field Name: menu_item_image
   - Field Type: Image
   - Return Format: Image URL
4. Location Rules:
   - Nav Menu Item is equal to All

## 4. Thêm ảnh cho Menu Items

1. Vào **Appearance > Menus**
2. Click vào mỗi menu item
3. Scroll xuống dưới sẽ thấy field "Menu Item Image"
4. Upload ảnh cho mỗi destination

## 5. Nếu không có ảnh menu item

System sẽ tự động tìm:
1. Featured Image của destination/page
2. ACF field 'featured_image' của destination/page  
3. Default placeholder image

## 6. Custom Post Types được hỗ trợ

- destination
- thing_to_do
- page
- post

## 7. Responsive Design

- Desktop: 4 columns grid
- Tablet: 3 columns grid  
- Mobile: 2 columns grid
- Small mobile: 1 column

## 8. Features

- Mega menu with image cards
- Smooth hover animations
- Mobile responsive menu
- Search overlay
- Auto-hide header on scroll
- Keyboard navigation support
- WCAG accessible

## 9. Customization

Để customize styling, edit file:
- `/css/header-menu.css` - CSS styles
- `/js/header-menu.js` - JavaScript interactions
- `/inc/class-custom-menu-walker.php` - Desktop menu walker
- `/inc/class-mobile-menu-walker.php` - Mobile menu walker

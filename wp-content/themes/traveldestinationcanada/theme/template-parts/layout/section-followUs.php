 <?php
	// Get gallery images from query vars or ACF
	$gallery_images = get_query_var('gallery_images', array());

	// Fallback: Try to get from current page ACF if not passed
	if (empty($gallery_images)) {
		$gallery_images = get_field('gallery');
	}

	// Get socials from ACF Options
	$socials = get_field('socials', 'option');

	// Limit gallery to maximum 4 images
	if (!empty($gallery_images) && is_array($gallery_images)) {
		$gallery_images = array_slice($gallery_images, 0, 4);
	}

	// Don't display section if no gallery images
	if (empty($gallery_images) || !is_array($gallery_images)) {
		return;
	}
	?>

 <section class="section_follow">
     <div
         class="px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto flex flex-col items-center justify-between gap-8 py-8 text-center lg:flex-row lg:text-left">
         <h3 class="break-words text-[32px] leading-tight lg:text-[38px] 2xl:text-[42px] text-primary">
             <span>Follow us and share:
             </span>
             <span class="block hyphens-none font-bold">#ExploreUSA
             </span>
         </h3>
         <div class="flex gap-4 text-canadianRedFlag">
             <?php if (!empty($socials) && is_array($socials)): ?>
             <?php foreach ($socials as $social): ?>
             <?php
						$social_url = isset($social['url']) ? $social['url'] : '#';
						$social_icon = isset($social['icon']) ? $social['icon'] : null;

						// Skip if no URL
						if (empty($social_url) || $social_url === '#') {
							continue;
						}
						?>
             <a target="_blank" class="group icon_social" href="<?php echo esc_url($social_url); ?>">
                 <?php if ($social_icon && isset($social_icon['url'])): ?>
                 <img src="<?php echo esc_url($social_icon['url']); ?>"
                     alt="<?php echo esc_attr($social_icon['alt'] ?: 'Social Media'); ?>" width="24" height="24"
                     class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                 <?php else: ?>
                 <!-- Fallback: Default Facebook SVG -->
                 <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="SocialIconTitle"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 23"
                     class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                     <title id="SocialIconTitle">Social Media</title>
                     <path
                         d="M9.85592 4.83063H7.59492C6.73217 4.83063 6.62309 5.1678 6.62309 6.05038V8.15272H9.96501L9.63776 11.812H6.62309V22.7798H2.30934V11.812H0.0483398V8.04363H2.30934V5.1678C2.30934 2.40105 3.70759 0.963135 6.94042 0.963135H9.84601V4.84055L9.85592 4.83063Z">
                     </path>
                 </svg>
                 <?php endif; ?>
             </a>
             <?php endforeach; ?>
             <?php else: ?>
             <!-- Fallback: Default social icons if no ACF data -->
             <a target="_blank" class="group icon_social" href="https://www.facebook.com/">
                 <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="FacebookIconTitle"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 23"
                     class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                     <title id="FacebookIconTitle">Facebook</title>
                     <path
                         d="M9.85592 4.83063H7.59492C6.73217 4.83063 6.62309 5.1678 6.62309 6.05038V8.15272H9.96501L9.63776 11.812H6.62309V22.7798H2.30934V11.812H0.0483398V8.04363H2.30934V5.1678C2.30934 2.40105 3.70759 0.963135 6.94042 0.963135H9.84601V4.84055L9.85592 4.83063Z">
                     </path>
                 </svg>
             </a>
             <a target="_blank" class="group icon_social" href="https://www.instagram.com/">
                 <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="InstagramIconTitle"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                     <title id="InstagramIconTitle">Instagram</title>
                     <path
                         d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                 </svg>
             </a>
             <a target="_blank" class="group icon_social" href="https://www.twitter.com/">
                 <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="TwitterIconTitle"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                     <title id="TwitterIconTitle">Twitter</title>
                     <path
                         d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                 </svg>
             </a>
             <?php endif; ?>
         </div>
     </div>

     <div class="gallery">
         <?php
			// Determine grid layout based on number of images
			$image_count = count($gallery_images);
			$grid_classes = '';

			switch ($image_count) {
				case 1:
					$grid_classes = 'grid grid-cols-1';
					break;
				case 2:
					$grid_classes = 'grid grid-cols-1 md:grid-cols-2';
					break;
				case 3:
					$grid_classes = 'grid grid-cols-2 md:grid-cols-3 grid-rows-2 md:grid-rows-1';
					break;
				case 4:
				default:
					$grid_classes = 'grid grid-cols-[3fr_1fr_3fr] grid-row-2 lg:grid-cols-[8fr_6fr_12fr_9fr]';
					break;
			}
			?>

         <div class="<?php echo esc_attr($grid_classes); ?> gap-2">
             <?php foreach ($gallery_images as $index => $image): ?>
             <?php
					// Check image data
					if (!isset($image['url'])) {
						continue;
					}

					$image_url = $image['url'];
					$image_alt = isset($image['alt']) ? $image['alt'] : 'Gallery image ' . ($index + 1);
					$image_caption = isset($image['caption']) ? $image['caption'] : '';
					$image_title = isset($image['title']) ? $image['title'] : $image_alt;

					// Determine grid span classes for visual interest
					$span_classes = '';
					if ($image_count === 4) {
						// For 4 images: First image spans 2 columns on mobile
						if ($index === 0) {
							$span_classes = 'col-span-2 lg:col-span-1';
						}
					} elseif ($image_count === 3) {
						// For 3 images: Last image spans 2 columns on mobile
						if ($index === 2) {
							$span_classes = 'col-span-2 md:col-span-1';
						}
					}

					// Get Instagram link from first social or default
					$instagram_url = '#';
					if (!empty($socials)) {
						foreach ($socials as $social) {
							if (isset($social['url']) && strpos($social['url'], 'instagram') !== false) {
								$instagram_url = $social['url'];
								break;
							}
						}
					}
					if ($instagram_url === '#') {
						$instagram_url = 'https://www.instagram.com/exploreusa/';
					}
					?>

             <a href="<?php echo esc_url($instagram_url); ?>" target="_blank"
                 title="<?php echo esc_attr($image_title); ?>"
                 class="relative h-[80vw] image-item-gallery max-h-[480px] md:h-[55vw] lg:h-[30vw] overflow-hidden group z-10 <?php echo esc_attr($span_classes); ?>">
                 <img alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" decoding="async"
                     class="h-full w-full rounded-sm object-cover object-center transition-transform duration-300 group-hover:scale-110"
                     src="<?php echo esc_url($image_url); ?>">

                 <?php if ($image_caption): ?>
                 <div
                     class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                     <p class="text-white text-center text-sm">
                         <?php echo esc_html($image_caption); ?>
                     </p>
                 </div>
                 <?php endif; ?>
             </a>
             <?php endforeach; ?>
         </div>
     </div>
 </section>

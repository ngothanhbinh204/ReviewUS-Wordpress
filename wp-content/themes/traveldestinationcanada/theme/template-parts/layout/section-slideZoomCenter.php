    <?php
	// Query videos from Custom Post Type
	$video_args = array(
		'post_type' => 'video',
		'posts_per_page' => 8, // Maximum 8 videos
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$video_query = new WP_Query($video_args);

	// Don't display section if no videos
	if (!$video_query->have_posts()) {
		return;
	}
	?>

    <section class="section_slider slideZoomCetner py-6 lg:py-12 ">
        <!-- Slider Swiper -->
        <div class="swiper travelSwiper">
            <div class="swiper-wrapper">
                <?php while ($video_query->have_posts()) : $video_query->the_post(); ?>
                <?php
					$video_id = get_the_ID();
					$video_title = get_the_title();
					$video_excerpt = get_the_excerpt();
					$video_thumbnail = get_the_post_thumbnail_url($video_id, 'large');
					$video = get_field('video', $video_id); // ACF field for video embed URL

					// Fallback thumbnail
					if (!$video_thumbnail) {
						$video_thumbnail = get_template_directory_uri() . '/assets/images/default-video-thumb.jpg';
					}

					// Skip if no video embed
					if (empty($video)) {
						continue;
					}
					?>

                <div class="swiper-slide group" data-video-id="<?php echo esc_attr($video_id); ?>">
                    <div>
                        <div class="flex flex-col gap-3">
                            <div class="relative h-[484px] group-[.swiper-slide-active]:sm:h-[584px] order-3">
                                <figure class="relative h-full w-full">
                                    <img alt="<?php echo esc_attr($video_title); ?>" loading="lazy" decoding="async"
                                        data-nimg="fill" class="rounded object-cover"
                                        style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;"
                                        sizes="100vw" src="<?php echo esc_url($video_thumbnail); ?>">
                                </figure>
                            </div>

                            <h3
                                class="break-words text-[22px] font-bold leading-tight lg:text-[24px] 2xl:text-[28px] mt-2 text-center order-1 !mt-0">
                                <?php echo esc_html($video_title); ?>
                            </h3>

                            <?php if ($video_excerpt): ?>
                            <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mt-0 text-center order-2">
                                <p class="mb-3 last:mb-0 empty:hidden">
                                    <?php echo esc_html($video_excerpt); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <button
                            class="video-play-button absolute left-1/2 top-[242px] group-[.swiper-slide-active]:top-[292px] z-10 h-16 w-16 -translate-x-1/2 -translate-y-1/2 group-[.swiper-slide-active]:h-24 group-[.swiper-slide-active]:w-24 transition-all duration-300 hover:scale-110"
                            aria-label="Play Video" data-video-embed="<?php echo esc_attr($video); ?>"
                            data-video-title="<?php echo esc_attr($video_title); ?>">
                            <svg width="32px" height="32px" fill="white" aria-labelledby="PlayIconTitleId"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                class="h-full max-h-full w-full max-w-full block">
                                <title>Play Icon</title>
                                <g clip-path="url(#clip0_3989_34887)">
                                    <path
                                        d="M29.5083 19.1607L15.8046 12.161C15.5206 12.0158 15.1817 12.0287 14.9101 12.1952C14.6386 12.3617 14.4727 12.6575 14.4727 12.9763V27.0225C14.4727 27.3419 14.6391 27.6377 14.9113 27.8042C15.0571 27.8933 15.2225 27.9382 15.3884 27.9382C15.5318 27.9382 15.6753 27.9045 15.807 27.8366L29.5107 20.7902C29.8159 20.6332 30.0078 20.3185 30.0078 19.9749C30.0078 19.6313 29.8147 19.3172 29.5089 19.1607H29.5083Z"
                                        fill="white"></path>
                                    <path
                                        d="M19.9997 0C8.97227 0 0 8.97227 0 19.9997C0 31.0271 8.97227 39.9994 19.9997 39.9994C31.0271 39.9994 40 31.0277 40 19.9997C40 8.97168 31.0277 0 19.9997 0ZM19.9997 38.0924C10.0232 38.0924 1.90698 29.9762 1.90698 19.9997C1.90698 10.0232 10.0238 1.90757 19.9997 1.90757C29.9756 1.90757 38.0924 10.0238 38.0924 20.0003C38.0924 29.9768 29.9762 38.093 19.9997 38.093V38.0924Z"
                                        fill="white"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_3989_34887">
                                        <rect width="40" height="40" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>

            </div>
            <button class=" group swiper-button-prev prev_cus button_ctrlSlide button-center-vertical c_arrow_left"
                aria-label="Go to Previous Slide">
                <svg class="max-w-6 group-hover:xl:scale-125 md:text-2xl xl:anim--default xl:scale-100"
                    stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                    height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>

            </button>

            <button class="group swiper-button-next next_cus button_ctrlSlide button-center-vertical c_arrow_left">

                <svg class="max-w-6 group-hover:xl:scale-125 md:text-2xl xl:anim--default xl:scale-100"
                    stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                    height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

        </div>
    </section>

    <!-- Video Modal -->
    <div id="videoModal"
        class="video-modal hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-95 backdrop-blur-sm">
        <div class="video-modal-content relative w-full max-w-6xl mx-4">
            <!-- Close Button -->
            <button id="closeVideoModal"
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors duration-200 z-10"
                aria-label="Close Video">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="rgba(255, 255, 255, 0.1)" />
                    <path d="M14 14L26 26M26 14L14 26" stroke="white" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>

            <!-- Video Container -->
            <div class="video-wrapper relative w-full" style="padding-bottom: 56.25%;">
                <div id="videoContainer" class="absolute inset-0 w-full h-full">
                    <!-- Video embed will be inserted here -->
                </div>
            </div>

            <!-- Video Title -->
            <h3 id="videoTitle" class="text-white text-center text-2xl font-bold mt-6"></h3>
        </div>
    </div>

    <style>
/* Video Modal Styles */
.video-modal {
    transition: opacity 0.3s ease-in-out;
}

.video-modal.hidden {
    opacity: 0;
    pointer-events: none;
}

.video-modal:not(.hidden) {
    opacity: 1;
    pointer-events: all;
}

.video-modal-content {
    animation: modalSlideIn 0.4s ease-out;
}

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

/* Responsive video container */
.video-wrapper iframe,
.video-wrapper video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 8px;
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Play button hover effect */
.video-play-button {
    cursor: pointer;
}

.video-play-button:hover svg {
    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
}
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('videoModal');
    const closeBtn = document.getElementById('closeVideoModal');
    const videoContainer = document.getElementById('videoContainer');
    const videoTitle = document.getElementById('videoTitle');
    const playButtons = document.querySelectorAll('.video-play-button');

    // Function to convert video URL to embed URL
    function getEmbedUrl(url) {
        // YouTube
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            let videoId = '';
            if (url.includes('youtube.com/watch?v=')) {
                videoId = url.split('v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            } else if (url.includes('youtube.com/embed/')) {
                return url + '?autoplay=1';
            }
            return `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        }

        // Vimeo
        if (url.includes('vimeo.com')) {
            let videoId = url.split('vimeo.com/')[1].split('?')[0];
            return `https://player.vimeo.com/video/${videoId}?autoplay=1`;
        }

        // If already an embed URL or direct video file
        return url;
    }

    // Open modal
    playButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const videoEmbed = this.getAttribute('data-video-embed');
            const title = this.getAttribute('data-video-title');

            if (videoEmbed) {
                const embedUrl = getEmbedUrl(videoEmbed);

                // Check if it's a direct video file or embed
                if (embedUrl.endsWith('.mp4') || embedUrl.endsWith('.webm') || embedUrl
                    .endsWith('.ogg')) {
                    videoContainer.innerHTML = `
                        <video controls autoplay class="w-full h-full">
                            <source src="${embedUrl}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    `;
                } else {
                    videoContainer.innerHTML = `
                        <iframe
                            src="${embedUrl}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    `;
                }

                videoTitle.textContent = title || '';
                modal.classList.remove('hidden');
                document.body.classList.add('modal-open');
            }
        });
    });

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
        videoContainer.innerHTML = '';
        videoTitle.textContent = '';
        document.body.classList.remove('modal-open');
    }

    closeBtn.addEventListener('click', closeModal);

    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
    </script>

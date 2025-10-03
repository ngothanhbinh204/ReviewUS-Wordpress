<?php
// Get slide configuration from query vars
$cfg = get_query_var('slide_config', []);
$slide_id = $cfg['id'] ?? 'default-slide';
$title = $cfg['title'] ?? '';
$sub_title = $cfg['sub_title'] ?? 'Default Title';
$link = $cfg['link'] ?? false;
$link_text = $cfg['link_text'] ?? 'Get inspired';
$link_target = $cfg['link_target'] ?? '';
$posts = $cfg['posts'] ?? array();
$loop = $cfg['loop'] ?? false;

// Don't display if no title and no posts
if (empty($sub_title) && empty($posts)) {
	return;
}
?>

<section class="section_slide_4 natural relative z-0 bg-light-grey py-12 lg:py-16">
    <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
        <div class="items-end justify-between md:flex">
            <div class="mb-2 md:mb-0 md:max-w-[70%] xl:max-w-none">
                <?php if (!empty($title)): ?>
                <p class="text-base leading-[26px] xl:text-lg xl:leading-[28px] text-mid-grey mb-1 font-semibold">
                    <?php echo esc_html($title); ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($sub_title)): ?>
                <h3 class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] text-primary">
                    <?php echo esc_html($sub_title); ?>
                </h3>
                <?php endif; ?>
            </div>

            <?php if ($link && $link !== false && $link !== '#'): ?>
            <a class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors text-primary button_has_icon group pl-0 pr-5 text-lg font-semibold text-left gtm-cta gtm-readmore md:!py-1 lg:!py-2 gtm-cta"
                href="<?php echo esc_url($link); ?>"
                <?php echo $link_target ? 'target="' . esc_attr($link_target) . '"' : ''; ?>>
                <span class="flex items-center gap-3">
                    <?php echo esc_html($link_text); ?>
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20"
                        aria-hidden="true" class="anim--default will-change-transform group-hover:translate-x-1"
                        height="1.25rem" width="1.25rem" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="relative wrapper_slide mt-4 md:mt-6 lg:mt-7 xl:mt-12 pr0">
        <div class="swiper <?php echo esc_attr($slide_id); ?>">
            <div class="swiper-wrapper">
                <?php if (!empty($posts) && is_array($posts)): ?>
                <?php foreach ($posts as $post_item): ?>
                <?php
						// Kiểm tra nếu là taxonomy term hay post thật
						$is_term = is_object($post_item) && isset($post_item->post_type) && $post_item->post_type === 'theme_term';

						if ($is_term) {
							// Xử lý cho taxonomy term
							$post_id = $post_item->ID;
							$post_title = $post_item->post_title;
							$post_link = $post_item->guid;
							$post_excerpt = $post_item->post_excerpt;

							// Lấy thumbnail từ object hoặc ACF
							if (isset($post_item->thumbnail) && is_array($post_item->thumbnail) && isset($post_item->thumbnail['url'])) {
								$post_thumbnail = $post_item->thumbnail['url'];
							} else {
								$post_thumbnail = '';
							}
						} else {
							// Xử lý cho post thông thường
							$post_id = is_object($post_item) ? $post_item->ID : $post_item;
							$post_title = get_the_title($post_id);
							$post_link = get_permalink($post_id);
							$post_thumbnail = get_the_post_thumbnail_url($post_id, 'large');
							$post_excerpt = get_the_excerpt($post_id);
						}

						// Fallback image
						if (!$post_thumbnail) {
							$post_thumbnail = '/wp-content/uploads/2025/10/default-destination.webp';
						}
						?>
                <div class="swiper-slide has_effect fu_effect">
                    <div class="overflow-hidden w-full">
                        <div class="relative overflow-hidden rounded h-[66vh] sm:h-[46vw] lg:h-[32vw]">
                            <a class="absolute inset-0 block overflow-hidden" href="<?php echo esc_url($post_link); ?>">
                                <div class="relative h-full w-full">
                                    <figure class="relative h-full w-full">
                                        <img alt="<?php echo esc_attr($post_title); ?>" loading="lazy"
                                            class="object-cover h-full w-full"
                                            src="<?php echo esc_url($post_thumbnail); ?>">
                                    </figure>
                                </div>
                            </a>
                        </div>
                        <div class="mt-5">
                            <h3
                                class="break-words text-[22px] font-bold leading-tight lg:text-[24px] 2xl:text-[28px] mt-2">
                                <a class="primary2 transition-all duration-150 ease-linear"
                                    href="<?php echo esc_url($post_link); ?>">
                                    <?php echo esc_html($post_title); ?>
                                </a>
                            </h3>
                            <?php if ($post_excerpt): ?>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                <?php echo esc_html($post_excerpt); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <!-- Fallback: Placeholder slides if no posts -->
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="swiper-slide has_effect fu_effect">
                    <div class="overflow-hidden w-full">
                        <div class="relative overflow-hidden rounded h-[66vh] sm:h-[46vw] lg:h-[32vw] bg-gray-200">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <p class="text-gray-400 text-center px-4">
                                    No posts selected<br>
                                    <span class="text-xs">Add posts in ACF</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h3
                                class="break-words text-[22px] font-bold leading-tight lg:text-[24px] 2xl:text-[28px] mt-2">
                                <span class="text-gray-400">Sample Item <?php echo $i + 1; ?></span>
                            </h3>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
        <button class="<?php echo esc_attr($slide_id); ?>-prev button_ctrlSlide button-center-vertical c_arrow_left">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                class="xl:anim--default xl:scale-100 group-hover:xl:scale-125 md:text-2xl" height="1em" width="1em"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
        <button class="<?php echo esc_attr($slide_id); ?>-next button_ctrlSlide button-center-vertical c_arrow_right">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                class="xl:anim--default xl:scale-100 group-hover:xl:scale-125 md:text-2xl" height="1em" width="1em"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        const swiper = new Swiper('.<?php echo esc_js($slide_id); ?>', {
            slidesPerView: 1,
            loop: <?php echo $loop ? 'true' : 'false'; ?>,
            spaceBetween: 24,
            speed: 500,
            navigation: {
                nextEl: '.<?php echo esc_js($slide_id); ?>-next',
                prevEl: '.<?php echo esc_js($slide_id); ?>-prev',
            },
            on: {
                init: function() {
                    const slideId = this.el.classList[0];
                    this._prevEl = document.querySelector(`.${slideId}-prev`);
                    this._nextEl = document.querySelector(`.${slideId}-next`);
                    updateNavButtons4(this);
                    animateNextSlides(this);
                },
                slideChange: function() {
                    updateNavButtons4(this);
                    animateNextSlides(this);
                }
            },
            breakpoints: {
                640: {
                    slidesPerView: 2.2,
                    slidesPerGroup: 2,
                },
                1024: {
                    slidesPerView: 3.2,
                    slidesPerGroup: 3,
                },
                1280: {
                    slidesPerView: 4.2,
                    slidesPerGroup: 4,
                },

            },
        });

        function animateNextSlides(swiperInstance) {
            const activeIndex = swiperInstance.activeIndex;
            const previousIndex = swiperInstance.previousIndex;

            // Chỉ animate nếu đi tới (Next)
            if (activeIndex > previousIndex) {
                swiperInstance.slides.forEach((slide, index) => {
                    if (index > activeIndex && slide.classList.contains('fu_effect')) {
                        gsap.set(slide, {
                            opacity: 0,
                            y: 30
                        });
                    }
                });

                swiperInstance.slides.forEach((slide, index) => {
                    if (index > activeIndex && slide.classList.contains('fu_effect')) {
                        gsap.to(slide, {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            ease: 'power2.out',
                            delay: (index - activeIndex) * 0.1
                        });
                    }
                });
            }
        }
    }
});


// function updateNavButtons4(swiperInstance) {
//     const $prev = document.querySelector('.<?= $slide_id ?>-prev');
//     const $next = document.querySelector('.<?= $slide_id ?>-next');
//     prevEl?.classList.remove('swiper_cus_hidden');
//     nextEl?.classList.remove('swiper_cus_hidden');
//     if ($prev && $next) {
//         if (swiperInstance.isBeginning) {
//             prevEl?.classList.add('swiper_cus_hidden');
//         } else {
//             prevEl?.classList.remove('swiper_cus_hidden');
//         }

//         if (swiperInstance.isEnd) {
//             nextEl?.classList.add('swiper_cus_hidden');
//         } else {
//             nextEl?.classList.remove('swiper_cus_hidden');
//         }
//     }
// }

// Optimize
function updateNavButtons4(swiper) {
    const prevEl = swiper._prevEl;
    const nextEl = swiper._nextEl;

    if (!prevEl || !nextEl) return;

    prevEl.classList.toggle('swiper_cus_hidden', swiper.isBeginning);
    nextEl.classList.toggle('swiper_cus_hidden', swiper.isEnd);
}
</script>

<style>
/* Ensure proper line-clamp works */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

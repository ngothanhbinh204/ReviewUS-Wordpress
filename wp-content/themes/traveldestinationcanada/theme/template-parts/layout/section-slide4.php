<?php
$cfg = get_query_var('slide_config', []);
$slide_id = $cfg['id'] ?? 'default-slide';
$title = $cfg['title'] ?? 'Tiêu đề mặc định';
$sub_title = $cfg['sub_title'] ?? 'Tiêu đề mặc định';
$link = $cfg['link'] ?? '#';
$loop = $cfg['loop'] ?? false;
?>

<section class="section_slide_4 natural relative z-0 bg-light-grey my-16 lg:my-24">
	<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
		<div class="items-end justify-between md:flex">
			<div class="mb-2 md:mb-0 md:max-w-[70%] xl:max-w-none">
				<?php if ($title != false): ?>
					<p class="text-base leading-[26px] xl:text-lg xl:leading-[28px] text-mid-grey mb-1 font-semibold">
						<?= esc_html($title); ?></p>
				<?php endif; ?>
				<h3 class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] text-primary">
					<?= esc_html($sub_title); ?></h3>
			</div>

			<?php if ($link != false): ?>
				<a class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors text-primary button_has_icon group pl-0 pr-5 text-lg font-semibold text-left gtm-cta gtm-readmore md:!py-1 lg:!py-2 gtm-cta"
					href="<?= esc_url($link); ?>">
					<span class="flex items-center gap-3">Get inspired<svg stroke="currentColor" fill="currentColor"
							stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
							class="anim--default will-change-transform group-hover:translate-x-1" height="1.25rem"
							width="1.25rem" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd"
								d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
								clip-rule="evenodd"></path>
						</svg></span></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="relative wrapper_slide mt-4 md:mt-6 lg:mt-7 xl:mt-12 pr0">
		<div class="swiper <?= esc_attr($slide_id); ?>">
			<div class=" swiper-wrapper">
				<?php for ($i = 0; $i < 9; $i++): ?>
					<div class="swiper-slide has_effect fu_effect">
						<div class=" overflow-hidden w-full ">
							<div class=" relative overflow-hidden rounded h-[66vh] sm:h-[46vw] lg:h-[32vw]">
								<a target="" class=" absolute inset-0 block overflow-hidden" href="#">
									<div class="relative h-full w-full">
										<figure class="relative h-full w-full">
											<img alt="" loading="lazy" class="object-cover h-full "
												src="http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp"
												alt="">
											<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">
												Travel Alberta / Chris Amat</figcaption>
										</figure>
									</div>
								</a>
							</div>
							<div class=" mt-5">
								<h3
									class="break-words text-[22px] font-bold leading-tight lg:text-[24px] 2xl:text-[28px] mt-2">
									<a class="primary2 transition-all duration-150 ease-linear" target=""
										href="https://travel.destinationcanada.com/en-us/places-to-go/alberta/calgary">Calgary</a>
								</h3>
							</div>
						</div>

					</div>
				<?php endfor; ?>
			</div>
		</div>
		<button class=" <?= esc_attr($slide_id); ?>-prev button_ctrlSlide button-center-vertical c_arrow_left  ">
			<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
				class="xl:anim--default text-sm xl:scale-100 group-hover:xl:scale-125 text-sm md:text-2xl" height="1em"
				width="1em" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd"
					d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
					clip-rule="evenodd"></path>
			</svg>
		</button>
		<button class=" <?= esc_attr($slide_id); ?>-next button_ctrlSlide button-center-vertical c_arrow_right ">
			<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
				class="xl:anim--default text-sm xl:scale-100 group-hover:xl:scale-125 text-sm md:text-2xl" height="1em"
				width="1em" xmlns="http://www.w3.org/2000/svg">
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
			const swiper = new Swiper('.<?= $slide_id ?>', {
				slidesPerView: 1,
				loop: <?= $loop ? 'true' : 'false' ?>,
				spaceBetween: 24,
				speed: 500,
				navigation: {
					nextEl: '.<?= $slide_id ?>-next',
					prevEl: '.<?= $slide_id ?>-prev',
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

<?php
$slides = [
	[
		'title' => 'Places To Go',
		'image' => [
			'url' => 'http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp',
			'alt' => 'Alt Places To Go'
		],
	],
];
if ($slides && count($slides) > 1) :
?>
	<!-- Swiper Container nếu có nhiều hơn 1 slide -->
	<section class="c-herobanner relative z-0">
		<div class="swiper heroSwiper">
			<div class="swiper-wrapper">
				<?php foreach ($slides as $slide) : ?>
					<div class="swiper-slide">
						<div
							class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-end py-12 md:py-16 pt-40 md:pt-52">
							<div class="absolute inset-0 overflow-hidden">
								<div class="relative h-full w-full">
									<figure class="relative h-full w-full">
										<img src="<?= esc_url($slide['image']['url']) ?>" alt="<?= esc_attr($slide['title']) ?>"
											class="object-cover w-full h-full" />
									</figure>
								</div>
							</div>
							<div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
								<div class="c-container px-4 md:px-16 2xl:px-20 text-white">
									<div class="c-grid grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
										<div
											class="text-left sm:px-10 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
											<h1
												class="uppercase break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
												<?= esc_html($slide['title']) ?>
											</h1>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<button class="swiper--hero-button-prev prev_cus_dark left-5 top-1/2 -translate-y-1/2 xl:left-20">
				<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
					height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
						d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
						clip-rule="evenodd"></path>
				</svg>
			</button>

			<button class="swiper--hero-button-next next_cus_dark right-5 top-1/2 -translate-y-1/2 xl:right-20">
				<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
					height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
						d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
						clip-rule="evenodd"></path>
				</svg>
			</button>
		</div>
	</section>
<?php else: ?>
	<!-- Nếu chỉ có 1 slide hoặc không có thì hiển thị static -->
	<?php
	$slide = $slides[0] ?? null;
	if ($slide) :
	?>
		<section class="c-herobanner relative z-0">
			<div
				class="c-herobanner__slide relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-end py-12 md:py-16 pt-40 md:pt-52">
				<div class="absolute inset-0 overflow-hidden">
					<div class="relative h-full w-full">
						<figure class="relative h-full w-full">
							<img src="<?= esc_url($slide['image']['url']) ?>" alt="<?= esc_attr($slide['title']) ?>"
								class="object-cover w-full h-full" />
						</figure>
					</div>
				</div>
				<div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
					<div class="c-container px-4 md:px-16 2xl:px-20 text-white">
						<div class="c-grid grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
							<div class="text-left sm:px-10 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
								<h1
									class="uppercase break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
									<?= esc_html($slide['title']) ?>
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<?php endif;
endif; ?>

<script>
	// Banner Hero Swiper

	document.addEventListener('DOMContentLoaded', function() {
		if (typeof Swiper !== 'undefined') {
			const heroSwiper = document.getElementsByClassName('heroSwiper');
			if (heroSwiper) {
				const swiperHero = new Swiper('.heroSwiper', {
					loop: false,
					effect: 'slide',
					speed: 800,
					autoplay: {
						delay: 5000,
						disableOnInteraction: false,
					},

					navigation: {
						nextEl: '.swiper--hero-button-next',
						prevEl: '.swiper--hero-button-prev',
					},
				});
			}
		}
	});
</script>
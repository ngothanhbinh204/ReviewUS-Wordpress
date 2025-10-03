<?php
$slides = $args['slides'] ?? [];
?>
<section class="c-herobanner relative z-0">
    <div class="swiper heroComingSwiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $slide) : ?>
            <div class="swiper-slide">
                <div
                    class=" relative flex flex-col min-h-[100vw] md:min-h-[75vw] lg:min-h-[360px] sm:py-16 justify-center pb-8 pt-32">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="relative h-full w-full">
                            <figure class="relative h-full w-full">
                                <img src="<?= esc_url($slide['image']['url']) ?>" alt="<?= esc_attr($slide['title']) ?>"
                                    class="object-cover w-full h-full" />
                                <div
                                    class="absolute inset-0 z-10 bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.6)]">
                                </div>

                            </figure>
                        </div>
                    </div>
                    <div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
                        <div class="c-container px-4 md:px-16 2xl:px-20 text-white">
                            <div class=" grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
                                <div
                                    class="text-center lg:col-start-3 3xl:col-start-4 sm:px-10 text-shadow 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
                                    <h1
                                        class="break-words title_font_canada text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
                                        <?= esc_html($slide['title']) ?></h1>
                                    <div class="text-base-banner">
                                        <p class="mb-3 last:mb-0 empty:hidden"> <?= esc_html($slide['sub_title']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="swiper--heroComing-button-prev prev_cus_dark left-5 top-1/2 -translate-y-1/2 xl:left-20">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>

        <button class="swiper--heroComing-button-next next_cus_dark right-5 top-1/2 -translate-y-1/2 xl:right-20">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</section>


<script>
// Banner Hero Swiper

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        const heroComingSwiper = document.getElementsByClassName('heroComingSwiper');
        if (heroComingSwiper) {
            const swiperHero = new Swiper('.heroComingSwiper', {
                loop: false,
                effect: 'slide',
                speed: 800,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },

                navigation: {
                    nextEl: '.swiper--heroComing-button-next',
                    prevEl: '.swiper--heroComing-button-prev',
                },
            });
        }
    }
});
</script>
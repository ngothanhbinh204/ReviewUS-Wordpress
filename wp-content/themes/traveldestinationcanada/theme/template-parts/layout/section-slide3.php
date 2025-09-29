<?php
$cfg = get_query_var('slide_config', []);
$slide_id = $cfg['id'] ?? 'default-slide';
$title = $cfg['title'] ?? 'Tiêu đề mặc định';
$sub_title = $cfg['sub_title'] ?? 'Tiêu đề mặc định';
$link = $cfg['link'] ?? '#';
$loop = $cfg['loop'] ?? false;
$perView = $cfg['perView'] ?? 3;
?>

<section class="section_slide_3 natural relative z-0 bg-light-grey py-8 lg:py-16 my-16 lg:my-24">
    <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
        <div class="items-end justify-between md:flex">
            <div class="mb-2 md:mb-0 md:max-w-[70%] xl:max-w-none">
                <p class="text-base leading-[26px] xl:text-lg xl:leading-[28px] text-mid-grey mb-1 font-semibold">
                    <?= esc_html($title); ?></p>
                <h3 class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] text-primary">
                    <?= esc_html($sub_title); ?></h3>
            </div>
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
        </div>
    </div>
    <div class="relative wrapper_slide mt-4 md:mt-6 lg:mt-7 xl:mt-12 pr0">
        <div class="swiper <?= esc_attr($slide_id); ?>">
            <div class=" swiper-wrapper">
                <?php for ($i = 0; $i < 7; $i++): ?>
                <div class="swiper-slide has_effect fadeup_effect">
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
        new Swiper('.<?= $slide_id ?>', {
            slidesPerView: 3,
            loop: false,
            loopFillGroupWithBlank: true,
            spaceBetween: 24,
            speed: 600,
            navigation: {
                nextEl: '.<?= $slide_id ?>-next',
                prevEl: '.<?= $slide_id ?>-prev',
            },
            on: {
                init: function() {
                    updateNavButtons3(this);
                },
                slideChange: function() {
                    updateNavButtons3(this);
                },
            },
            breakpoints: {
                200: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 16,
                },
                640: {
                    slidesPerView: 2.3,
                    slidesPerGroup: 2,
                    spaceBetween: 16,

                },
                1024: {
                    slidesPerView: 3.3,
                    slidesPerGroup: 3,
                    spaceBetween: 24,
                },
                1280: {
                    slidesPerView: 3.3,
                    slidesPerGroup: 3,
                    spaceBetween: 24,
                },
            },
        });
    }
});

function updateNavButtons3(swiperInstance) {
    const $prev = document.querySelector('.<?= $slide_id ?>-prev');
    const $next = document.querySelector('.<?= $slide_id ?>-next');

    if ($prev && $next) {
        if (swiperInstance.isBeginning) {
            $prev.classList.add('swiper_cus_hidden');
        } else {
            $prev.classList.remove('swiper_cus_hidden');
        }

        if (swiperInstance.isEnd) {
            $next.classList.add('swiper_cus_hidden');
        } else {
            $next.classList.remove('swiper_cus_hidden');
        }
    }
}
</script>

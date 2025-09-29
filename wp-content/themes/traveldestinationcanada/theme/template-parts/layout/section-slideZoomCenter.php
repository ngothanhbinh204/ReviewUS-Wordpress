    <section class="section_slider slideZoomCetner">
        <!-- Slider Swiper -->
        <div class="swiper travelSwiper">
            <div class="swiper-wrapper">
                <?php for ($i = 0; $i < 4; $i++): ?>

                <div class="swiper-slide group">
                    <div>
                        <div class="flex flex-col gap-3">
                            <div class="relative h-[484px] group-[.swiper-slide-active]:sm:h-[584px] order-3">
                                <figure class="relative h-full w-full"><img alt="See the northern lights" loading="lazy"
                                        decoding="async" data-nimg="fill" class="rounded object-cover"
                                        style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;"
                                        sizes="100vw"
                                        src="http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp">
                                </figure>
                            </div>
                            <h3
                                class="break-words text-[22px] font-bold leading-tight lg:text-[24px] 2xl:text-[28px] mt-2 text-center order-1 !mt-0">
                                The sky can be a real show off</h3>
                            <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mt-0 text-center order-2">
                                <p class="mb-3 last:mb-0 empty:hidden">Here, spectacular doesn't need a spotlight.</p>
                            </div>
                        </div><button
                            class="absolute left-1/2 top-[242px] group-[.swiper-slide-active]:top-[292px] z-10 h-16 w-16  -translate-x-1/2 -translate-y-1/2 group-[.swiper-slide-active]:h-24  group-[.swiper-slide-active]:w-24"
                            aria-label="Play Video"><svg width="32px" height="32px" fill="white"
                                aria-labelledby="PlayIconTitleId" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
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
                            </svg></button>
                    </div>
                </div>

                <?php endfor; ?>

            </div>
            <button class=" swiper-button-prev prev_cus " aria-label="Go to Previous Slide"><svg stroke="currentColor"
                    fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" height="1em" width="1em"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd"></path>
                </svg></button>

            <button class="swiper-button-next next_cus">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                    height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg></button>
        </div>


    </section>

    <section class="section_articles">

        <!-- Filter Section  -->
        <div class="bg-primary py-4">

            <div class="filter-container">
                <!-- Filter Buttons -->
                <div
                    class="filter-buttons c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto flex flex-wrap justify-center gap-4">
                    <div class="filter-wrapper">
                        <button class="filter_btn_thingtodo" data-filter="provinces">
                            <!-- <span class="filter-badge hidden ml-2 text-xs bg-red-500 text-white rounded-full px-2 py-0.5"></span> -->
                            Provinces and territories
                            <span class="dropdown-icon">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20"
                                    aria-hidden="true" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </button>
                        <!-- Provinces Dropdown -->

                        <div class="filter-dropdown" id="provinces-dropdown" style="display: none;">
                            <div class="filter-options">
                                <?php
								$provinces = get_terms(array(
									'taxonomy' => 'provinces_territories',
									'hide_empty' => false,
								));
								if ($provinces && !is_wp_error($provinces)) {
									foreach ($provinces as $province) {
										echo '<label class="filter-option">
                            <input type="checkbox" name="provinces[]" value="' . $province->slug . '">
                            <span>' . $province->name . '</span>
                        </label>';
									}
								}
								?>
                            </div>
                            <div class="filter-actions">
                                <button class="apply-filter_btn_thingtodo button_primary" disabled
                                    data-filter="provinces">Apply
                                    Filter(s)</button>
                                <button class="reset-filter_btn_thingtodo" disabled
                                    data-filter="provinces">Reset</button>
                            </div>
                        </div>
                    </div>

                    <div class="filter-wrapper">
                        <!-- Themes Button -->
                        <button class="filter_btn_thingtodo" data-filter="themes">
                            <!-- <span class="filter-badge hidden ml-2 text-xs bg-red-500 text-white rounded-full px-2 py-0.5"></span> -->

                            Themes
                            <span class="dropdown-icon">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20"
                                    aria-hidden="true" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </button>

                        <!-- Themes Dropdown -->
                        <div class="filter-dropdown" id="themes-dropdown" style="display: none;">
                            <div class="filter-options">
                                <?php
								$themes = get_terms(array(
									'taxonomy' => 'thing_themes',
									'hide_empty' => false,
								));
								if ($themes && !is_wp_error($themes)) {
									foreach ($themes as $theme) {
										echo '<label class="filter-option">
                            <input type="checkbox" name="themes[]" value="' . $theme->slug . '">
                            <span>' . $theme->name . '</span>
                        </label>';
									}
								}
								?>
                            </div>
                            <div class="filter-actions">
                                <button class="apply-filter_btn_thingtodo button_primary" disabled
                                    data-filter="themes">Apply
                                    Filter(s)</button>
                                <button class="reset-filter_btn_thingtodo" disabled data-filter="themes">Reset</button>
                            </div>
                        </div>
                    </div>

                    <div class="filter-wrapper">

                        <button class="filter_btn_thingtodo" data-filter="seasons">

                            Seasons
                            <span class="dropdown-icon">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20"
                                    aria-hidden="true" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>


                        </button>

                        <!-- Seasons Dropdown -->
                        <div class="filter-dropdown" id="seasons-dropdown" style="display: none;">
                            <div class="filter-options">
                                <?php
								$seasons = get_terms(array(
									'taxonomy' => 'seasons',
									'hide_empty' => false,
								));
								if ($seasons && !is_wp_error($seasons)) {
									foreach ($seasons as $season) {
										echo '<label class="filter-option">
                            <input type="checkbox" name="seasons[]" value="' . $season->slug . '">
                            <span>' . $season->name . '</span>
                        </label>';
									}
								}
								?>
                            </div>
                            <div class="filter-actions">
                                <button class="apply-filter_btn_thingtodo button_primary" disabled
                                    data-filter="seasons">Apply
                                    Filter(s)</button>
                                <button class="reset-filter_btn_thingtodo" disabled data-filter="seasons">Reset</button>
                            </div>
                        </div>
                    </div>

                </div>



                <!-- Filter Dropdowns -->
                <!-- <div class="filter-dropdowns">
    				<div class="filter-dropdown" id="territories-dropdown" style="display: none;">
    					<div class="filter-options">
    						<?php
							$territories = get_terms(array(
								'taxonomy' => 'category-territory',
								'hide_empty' => false,
							));
							if ($territories && !is_wp_error($territories)) {
								foreach ($territories as $territory) {
									echo '<label class="filter-option">
                            <input type="checkbox" name="territories[]" value="' . $territory->slug . '">
                            <span>' . $territory->name . '</span>
                        </label>';
								}
							}
							?>
    					</div>
    					<div class="filter-actions">
    						<button class="apply-filter_btn_thingtodo button_primary" disabled
    							data-filter="territories">Apply
    							Filter(s)</button>
    						<button class="reset-filter_btn_thingtodo" disabled data-filter="territories">Reset</button>
    					</div>
    				</div>

    				<div class="filter-dropdown" id="themes-dropdown" style="display: none;">
    					<div class="filter-options">
    						<?php
							$themes = get_terms(array(
								'taxonomy' => 'category-theme',
								'hide_empty' => false,
							));
							if ($themes && !is_wp_error($themes)) {
								foreach ($themes as $theme) {
									echo '<label class="filter-option">
                            <input type="checkbox" name="themes[]" value="' . $theme->slug . '">
                            <span>' . $theme->name . '</span>
                        </label>';
								}
							}
							?>
    					</div>
    					<div class="filter-actions">
    						<button class="apply-filter_btn_thingtodo button_primary" disabled
    							data-filter="themes">Apply
    							Filter(s)</button>
    						<button class="reset-filter_btn_thingtodo" disabled data-filter="themes">Reset</button>
    					</div>
    				</div>

    				<div class="filter-dropdown" id="seasons-dropdown" style="display: none;">
    					<div class="filter-options">
    						<?php
							$seasons = get_terms(array(
								'taxonomy' => 'category-season',
								'hide_empty' => false,
							));
							if ($seasons && !is_wp_error($seasons)) {
								foreach ($seasons as $season) {
									echo '<label class="filter-option">
                            <input type="checkbox" name="seasons[]" value="' . $season->slug . '">
                            <span>' . $season->name . '</span>
                        </label>';
								}
							}
							?>
    					</div>
    					<div class="filter-actions">
    						<button class="apply-filter_btn_thingtodo button_primary" disabled
    							data-filter="seasons">Apply
    							Filter(s)</button>
    						<button class="reset-filter_btn_thingtodo" disabled data-filter="seasons">Reset</button>
    					</div>
    				</div>
    			</div> -->
            </div>

            <!-- Results Container -->
            <!-- <div class="results-container">
                <div class="results-count">Displaying <span id="results-count">0</span> Things to do</div>
                <div id="posts-container">
                </div>
            </div> -->
        </div>
        <!-- End Filter Section  -->

        <div class="container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
            <div class="flex items-center justify-between pt-8 results-count">
                <p class="text-base font-medium leading-[26px] xl:text-lg xl:leading-[28px] ">Displaying <span
                        id="results-count">0</span> of <span id="totalCount">0</span> Things to do</p>
                <button class="clear-all-filters button_primary mt-0" style="display: none;">Clear filters</button>

            </div>
            <div class="mt-4 empty:mt-0">
                <!-- Active Filters Tags -->
                <div class="active-filters">
                    <div class="filter-tags"></div>
                </div>
            </div>
        </div>
        <section class="py-16 lg:py-20">
            <div id="wrapper-posts-container"
                class="container flex flex-col justify-center items-center px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
                <div id="posts-container" class=" grid sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-8 md:gap-8">


                    <!-- Load Item AJAX Filter Here  -->
                </div>

                <button class="button_primary loadMoreFilter">
                    <span class="flex items-center gap-3 justify-center">
                        <span class="loadMoreText">Load More</span>
                        <div class="loader_inButton"></div>
                    </span>
                </button>


            </div>
        </section>
    </section>

<?php

/**
 * Template Name: Things To Go Page
 */

get_header()

?>


<?php
// include section-hero
// Truyền xuống template
$slides = get_field('hero_slides');
set_query_var('slideHero_config', $slides);
get_template_part('template-parts/layout/section-hero', 'dataHero');
?>

<?php
// include breadcrumb
// $slides = get_field('hero_slides');
// set_query_var('slideHero_config', $slides);
get_template_part('template-parts/layout/breadcrumb', 'breadcumbData');
?>

<!-- Basic Content -->

<section class="relative z-0 text-left my-16 lg:my-24">
    <div>
        <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
            <div class="">
                <div class="text-base mb-6 last:mb-0">
                    <p class="mb-3  text-[16px] leading-[26px] xl:text-lg xl:leading-[28px] last:mb-0 empty:hidden">
                        Spread from the Pacific to the Atlantic and the
                        furthest reaches of the Arctic Circle, Canada is one of the most naturally and culturally
                        diverse places on Earth. Vast areas of serene and raw wilderness, punctuated by vibrant cities
                        and even brighter spirit—there’s a lifetime of adventure just waiting to be discovered with each
                        daring exploration of the True North.</p>
                </div>
                <div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start"></div>
            </div>
        </div>
    </div>
</section>

<?php
set_query_var('slide_config', [
	'id' => 'Arts_slide',
	'title' => "More to see",
	'sub_title' => "Discover incredible destinations",
	'link' => false,
	'loop' => false,
]);

get_template_part('template-parts/layout/section-slide4', 'slide');
?>


<?php
set_query_var('slide_config', [
	'id' => 'adventures_slide',
	'title' => "Inspiring adventures",
	'sub_title' => "Incredible travel stories & guides
",
	'link' => true,
	'loop' => false,
]);

get_template_part('template-parts/layout/section-slide4', 'slide');
?>


<?php
set_query_var('slide_config', [
	'id' => 'thingtodo_slide',
	'title' => "Things to do",
	'sub_title' => "Undeniably Canadian experiences",
	'link' => true,
	'loop' => false,
]);

get_template_part('template-parts/layout/section-slide4', 'slide');
?>


<?php
get_template_part('template-parts/layout/section-bannerTextLeft', 'Data');
?>


<?php
set_query_var('slide_config', [
	'id' => 'thingtodo_slide',
	'title' => "Things to do",
	'sub_title' => "Undeniably Canadian experiences",
	'link' => true,
	'loop' => false,
]);

get_template_part('template-parts/layout/section-slide4', 'slide');
?>



<?php
get_footer();
?>

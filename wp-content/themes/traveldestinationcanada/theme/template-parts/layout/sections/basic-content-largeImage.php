<?php

/**
 * @var array $section
 */
$section = $args['section'] ?? [];

?>

<section id="<?= esc_attr($section['slug']) ?>" class="templates relative z-0 text-left my-16 lg:my-24 target-section">
    <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">

        <!-- Mobile -->
        <div class="block lg:hidden">
            <?php if (!empty($section['title'])): ?>
            <h2
                class="text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 text-black">
                <?= esc_html($section['title']) ?>
            </h2>
            <?php endif; ?>

            <?php if (!empty($section['sub_title'])): ?>
            <div class="text-lg xl:text-xl mb-4">
                <p class="mb-3"><?= esc_html($section['sub_title']) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($section['image']['url'])): ?>
            <div class="relative mb-4">
                <img src="<?= esc_url($section['image']['url']) ?>" alt="<?= esc_attr($section['title']) ?>">
            </div>
            <?php endif; ?>

            <?php if (!empty($section['content'])): ?>
            <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6">
                <?= $section['content'] ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Desktop -->
        <div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
            <?php if (!empty($section['image']['url'])): ?>
            <div class="relative mb-4 overflow-hidden last:mb-0 xl:col-span-2 order-2">
                <div>
                    <img class=" h-auto mx-auto" src="<?= esc_url($section['image']['url']) ?>"
                        alt="<?= esc_attr($section['title']) ?>">
                </div>
            </div>
            <?php endif; ?>

            <div class="self-center lg:col-span-1 xl:col-span-1">
                <?php if (!empty($section['title'])): ?>
                <h2
                    class="text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 text-black">
                    <?= esc_html($section['title']) ?>
                </h2>
                <?php endif; ?>

                <?php if (!empty($section['sub_title'])): ?>
                <div class="text-lg xl:text-xl mb-4">
                    <p class="mb-3"><?= esc_html($section['sub_title']) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($section['content'])): ?>
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6">
                    <?= $section['content'] ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
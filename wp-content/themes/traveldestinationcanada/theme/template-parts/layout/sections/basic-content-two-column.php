<?php

/**
 * @var array $section
 */
$section = $args['section'] ?? [];
?>

<section id="<?= esc_attr($section['slug'] ?? '') ?>" class="templates my-16 lg:my-24 target-section">
    <div class="container max-w-screen-2xl px-4 md:px-16 2xl:px-20 3xl:px-0 mx-auto">

        <?php if (!empty($section['title'])): ?>
        <h2
            class="text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:block after:w-[50px] after:mt-2 after:mb-3 after:h-[3px] after:bg-secondary mb-6 text-black">
            <?= esc_html($section['title']) ?>
        </h2>
        <?php endif; ?>

        <?php if (!empty($section['sub_title'])): ?>
        <div class="text-lg xl:text-xl mb-6">
            <p><?= esc_html($section['sub_title']) ?></p>
        </div>
        <?php endif; ?>

        <div class="grid md:grid-cols-2 gap-4 md:gap-8">
            <?php if (!empty($section['content_left'])): ?>
            <div class="text-base leading-6 xl:text-lg xl:leading-7">
                <?= $section['content_left'] ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($section['content_right'])): ?>
            <div class="text-base leading-6 xl:text-lg xl:leading-7">
                <?= $section['content_right'] ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>
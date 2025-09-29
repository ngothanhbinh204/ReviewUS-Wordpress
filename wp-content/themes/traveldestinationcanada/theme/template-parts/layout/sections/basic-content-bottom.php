<?php

/**
 * @var string $title
 * @var string $slug
 * @var string $content
 * @var array $button
 */

$title = $args['title'] ?? '';
$content = $args['content'] ?? '';
$button = $args['button'] ?? null;
?>

<section class="relative z-0 text-center my-16 lg:my-24">
    <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">

        <!-- Mobile Layout -->
        <div class="block lg:hidden">
            <h2 class="text-[32px] font-bold leading-tight text-center text-primary !mb-6">
                <?= esc_html($title) ?>
            </h2>
            <?php if (!empty($content)): ?>
            <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6">
                <?= wp_kses_post($content) ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($button)): ?>
            <div class="mb-6 flex flex-wrap justify-center gap-4">
                <a href="<?= esc_url($button['url']) ?>" class="button_primary">
                    <span class="flex items-center gap-3"><?= esc_html($button['title']) ?></span>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Desktop Layout -->
        <div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
            <div class="self-center lg:col-span-2 xl:col-span-3">
                <h2 class="text-[32px] font-bold leading-tight text-center text-primary !mb-6">
                    <?= esc_html($title) ?>
                </h2>
                <?php if (!empty($content)): ?>
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6">
                    <?= wp_kses_post($content) ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($button)): ?>
                <div class="mb-6 flex flex-wrap justify-center gap-4">
                    <a href="<?= esc_url($button['url']) ?>" class="button_primary">
                        <span class="flex items-center gap-3"><?= esc_html($button['title']) ?></span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

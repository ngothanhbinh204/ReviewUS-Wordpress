<?php

/**
 * @var array $section
 */
$section = $args['section'] ?? [];

?>

<section id="<?= esc_attr($section['slug']) ?>"
    class="templates mx-auto max-w-screen-2xl grid-cols-2 lg:grid my-16 lg:my-24 target-section">
    <?php if (!empty($section['items'])): ?>
    <?php foreach ($section['items'] as $item): ?>
    <div
        class="border-y border-[#E1E1E1] px-5 py-8 first:border-t-2 last:border-b-2 lg:px-20 lg:pb-24 lg:pt-14 lg:even:border-l-2 lg:[&:nth-last-of-type(-n+2)]:border-b-2 lg:[&:nth-of-type(-n+2)]:border-t-2">
        <div class="mb-4 flex items-start justify-between">
            <h3
                class="text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary">
                <?= esc_html($item['title']) ?>
            </h3>
            <?php if (!empty($item['image'])): ?>
            <div class="ml-2 hidden w-20 shrink-0 md:block">
                <img src="<?= esc_url($item['image']['url']) ?>" alt="<?= esc_attr($item['title']) ?>"
                    class="w-full object-contain">
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($item['sub_title'])): ?>
        <div class="text-lg xl:text-xl mb-4">
            <p class="mb-3"><?= esc_html($item['sub_title']) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($item['image'])): ?>
        <div class="relative mx-auto mb-4 aspect-square w-20 md:hidden">
            <img src="<?= esc_url($item['image']['url']) ?>" alt="<?= esc_attr($item['title']) ?>"
                class="object-contain object-top absolute inset-0 w-full h-full">
        </div>
        <?php endif; ?>

        <?php if (!empty($item['content'])): ?>
        <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-4">
            <?= $item['content'] ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</section>

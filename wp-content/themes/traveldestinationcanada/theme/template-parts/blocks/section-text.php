<?php
// Lấy dữ liệu từ các field
$title = get_field('section_text_title');
$content = get_field('section_text_content');
?>

<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
    <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] 3xl:pl-12 xl:pr-44">
        <?php if ($title): ?>
        <h2 class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] mb-3">
            <?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($content): ?>
        <div class="mb-3 last:mb-0 empty:hidden"><?php echo apply_filters('the_content', $content); ?></div>
        <?php endif; ?>
    </div>
</div>
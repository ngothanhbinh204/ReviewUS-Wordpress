<?php
$sections = get_field('sections');
if (!$sections || !is_array($sections)) return;
?>
<div id="subnav-marker" class=""></div>

<div class="subnav sticky top-[0] z-10 transition-colors text-black">
    <!-- Desktop nav -->
    <div class="c-container px-4 md:px-16 2xl:px-20 hidden lg:block">
        <nav class="flex justify-center flex-wrap">
            <?php foreach ($sections as $section):
				$slug = sanitize_title($section['title'] ?? '');
				$label = $section['title'] ?? 'Section';
			?>
            <a href="#<?= esc_attr($slug) ?>"
                class="inline-block p-5 font-bold uppercase hover:bg-primary hover:text-white transition-colors scroll-link subnav-item">
                <?= esc_html($label) ?>
            </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <!-- Mobile dropdown -->
    <?php if (!empty($sections)): ?>
    <div class="lg:hidden p-5 relative z-20">
        <button id="dropdownToggleSticky" type="button"
            class="flex w-full justify-between items-center border border-black px-4 py-3 text-sm text-black shadow"
            aria-expanded="false">
            <span id="dropdownLabel">Jump to section</span>
            <svg width="20" height="20" fill="currentColor" class="ml-2" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div id="dropdownMenuSticky" class="hidden absolute top-full left-0 right-0 bg-black shadow-lg">
            <?php foreach ($sections as $section):
					$slug = sanitize_title($section['title'] ?? '');
					$label = $section['title'] ?? 'Section';
				?>
            <a href="#<?= esc_attr($slug) ?>"
                class="block px-6 py-3 text-sm hover:bg-primary hover:text-white text-white subnav-item"><?= esc_html($label) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

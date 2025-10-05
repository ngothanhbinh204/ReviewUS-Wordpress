<?php
/**
 * Content Processor
 * 
 * Processes and formats content from Google Sheets into SEO-friendly HTML
 *
 * @package    WP_Google_Sheets_Import_Pro
 * @subpackage WP_Google_Sheets_Import_Pro/includes
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPGSIP_Content_Processor
{
    /**
     * Process raw content into formatted HTML
     *
     * @param string $content Raw content from Google Sheets
     * @return string Processed HTML content
     */
    public function process($content)
    {
        if (empty($content)) {
            return '';
        }

        // Split content into lines
        $lines = explode("\n", $content);
        $processed_lines = array();
        $in_list = false;
        $list_type = '';

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) {
                // Close list if we're in one
                if ($in_list) {
                    $processed_lines[] = $this->close_list($list_type);
                    $in_list = false;
                    $list_type = '';
                }
                $processed_lines[] = '';
                continue;
            }

            // Remove content in square brackets [...]
            $line = preg_replace('/\[([^\]]*)\]/', '', $line);
            $line = trim($line);

            // Skip if line becomes empty after removing brackets
            if (empty($line)) {
                continue;
            }

            // Process headings (H1-H6)
            if (preg_match('/^(H[1-6]):\s*(.+)$/i', $line, $matches)) {
                // Close list if we're in one
                if ($in_list) {
                    $processed_lines[] = $this->close_list($list_type);
                    $in_list = false;
                    $list_type = '';
                }

                $heading_level = strtolower($matches[1]);
                $heading_text = $this->process_inline_formatting($matches[2]);
                $processed_lines[] = "<{$heading_level}>{$heading_text}</{$heading_level}>";
                continue;
            }

            // Process meta description (extract but don't include in content)
            if (preg_match('/^Meta description.*?:\s*(.+)$/i', $line)) {
                // Skip meta description in content output
                continue;
            }

            // Process unordered list items (-, *, •)
            if (preg_match('/^[-*•]\s*(.+)$/', $line, $matches)) {
                if (!$in_list || $list_type !== 'ul') {
                    if ($in_list) {
                        $processed_lines[] = $this->close_list($list_type);
                    }
                    $processed_lines[] = '<ul>';
                    $in_list = true;
                    $list_type = 'ul';
                }
                $list_content = $this->process_inline_formatting($matches[1]);
                $processed_lines[] = "<li>{$list_content}</li>";
                continue;
            }

            // Process ordered list items (1., 2., etc.)
            if (preg_match('/^\d+\.\s*(.+)$/', $line, $matches)) {
                if (!$in_list || $list_type !== 'ol') {
                    if ($in_list) {
                        $processed_lines[] = $this->close_list($list_type);
                    }
                    $processed_lines[] = '<ol>';
                    $in_list = true;
                    $list_type = 'ol';
                }
                $list_content = $this->process_inline_formatting($matches[1]);
                $processed_lines[] = "<li>{$list_content}</li>";
                continue;
            }

            // Process blockquotes (lines starting with >)
            if (preg_match('/^>\s*(.+)$/', $line, $matches)) {
                // Close list if we're in one
                if ($in_list) {
                    $processed_lines[] = $this->close_list($list_type);
                    $in_list = false;
                    $list_type = '';
                }
                $quote_content = $this->process_inline_formatting($matches[1]);
                $processed_lines[] = "<blockquote><p>{$quote_content}</p></blockquote>";
                continue;
            }

            // Regular paragraph
            // Close list if we're in one
            if ($in_list) {
                $processed_lines[] = $this->close_list($list_type);
                $in_list = false;
                $list_type = '';
            }

            $paragraph_content = $this->process_inline_formatting($line);
            $processed_lines[] = "<p>{$paragraph_content}</p>";
        }

        // Close any remaining list
        if ($in_list) {
            $processed_lines[] = $this->close_list($list_type);
        }

        return implode("\n", $processed_lines);
    }

    /**
     * Process inline formatting (bold, italic, links, etc.)
     *
     * @param string $text Text to process
     * @return string Processed text with HTML tags
     */
    private function process_inline_formatting($text)
    {
        // Convert **bold** to <strong>
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

        // Convert *italic* to <em>
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);

        // Convert __underline__ to <u>
        $text = preg_replace('/__(.+?)__/', '<u>$1</u>', $text);

        // Convert ~~strikethrough~~ to <del>
        $text = preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);

        // Convert `code` to <code>
        $text = preg_replace('/`(.+?)`/', '<code>$1</code>', $text);

        // Convert [link text](url) to <a href="url">link text</a>
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $text);

        // Convert plain URLs to clickable links
        $text = preg_replace(
            '/(https?:\/\/[^\s<]+)/',
            '<a href="$1" target="_blank" rel="noopener">$1</a>',
            $text
        );

        return $text;
    }

    /**
     * Close list tag
     *
     * @param string $type List type (ul or ol)
     * @return string Closing tag
     */
    private function close_list($type)
    {
        return $type === 'ul' ? '</ul>' : '</ol>';
    }

    /**
     * Extract meta description from content
     *
     * @param string $content Raw content
     * @return string|null Meta description or null
     */
    public function extract_meta_description($content)
    {
        if (preg_match('/Meta description.*?:\s*(.+?)$/im', $content, $matches)) {
            $desc = trim($matches[1]);
            // Remove any markdown formatting
            $desc = strip_tags($this->process_inline_formatting($desc));
            return $desc;
        }
        return null;
    }

    /**
     * Extract H1 from content (for post title if needed)
     *
     * @param string $content Raw content
     * @return string|null H1 text or null
     */
    public function extract_h1($content)
    {
        if (preg_match('/^H1:\s*(.+)$/im', $content, $matches)) {
            $h1 = trim($matches[1]);
            // Remove content in square brackets
            $h1 = preg_replace('/\[([^\]]*)\]/', '', $h1);
            // Remove markdown formatting
            $h1 = strip_tags($this->process_inline_formatting($h1));
            return trim($h1);
        }
        return null;
    }

    /**
     * Clean and sanitize content before processing
     *
     * @param string $content Raw content
     * @return string Cleaned content
     */
    public function clean_content($content)
    {
        // Normalize line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Remove multiple consecutive empty lines
        $content = preg_replace("/\n{3,}/", "\n\n", $content);

        // Trim whitespace
        $content = trim($content);

        return $content;
    }

    /**
     * Generate excerpt from content
     *
     * @param string $content Processed HTML content
     * @param int $length Maximum length in characters
     * @return string Excerpt
     */
    public function generate_excerpt($content, $length = 160)
    {
        // Strip all HTML tags
        $text = wp_strip_all_tags($content);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Truncate to length
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length);
            // Find last space to avoid cutting words
            $last_space = mb_strrpos($text, ' ');
            if ($last_space !== false) {
                $text = mb_substr($text, 0, $last_space);
            }
            $text .= '...';
        }

        return $text;
    }

    /**
     * Process content for SEO optimization
     *
     * @param string $content Raw content from sheet
     * @return array Processed data with content, meta, title, etc.
     */
    public function process_for_seo($content)
    {
        // Clean content first
        $content = $this->clean_content($content);

        $result = array(
            'content' => '',
            'meta_description' => '',
            'title' => '',
            'excerpt' => ''
        );

        // Extract meta description
        $result['meta_description'] = $this->extract_meta_description($content);

        // Extract H1 for title
        $result['title'] = $this->extract_h1($content);

        // Process content to HTML
        $result['content'] = $this->process($content);

        // Generate excerpt if meta description not found
        if (empty($result['meta_description']) && !empty($result['content'])) {
            $result['excerpt'] = $this->generate_excerpt($result['content']);
        } else {
            $result['excerpt'] = $result['meta_description'];
        }

        return $result;
    }

    /**
     * Add Table of Contents (TOC) to content
     *
     * @param string $content HTML content
     * @param array $options TOC options
     * @return string Content with TOC
     */
    public function add_table_of_contents($content, $options = array())
    {
        $defaults = array(
            'min_headings' => 3,
            'max_level' => 3,
            'title' => 'Nội dung bài viết'
        );
        $options = wp_parse_args($options, $defaults);

        // Extract headings
        preg_match_all('/<h([2-6])>(.*?)<\/h[2-6]>/i', $content, $matches, PREG_SET_ORDER);

        if (count($matches) < $options['min_headings']) {
            return $content;
        }

        $toc = '<div class="wpgsip-toc">';
        $toc .= '<h2>' . esc_html($options['title']) . '</h2>';
        $toc .= '<ul>';

        foreach ($matches as $index => $match) {
            $level = intval($match[1]);
            $text = strip_tags($match[2]);
            $id = 'heading-' . ($index + 1);

            // Add ID to heading in content
            $content = str_replace(
                $match[0],
                '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>',
                $content
            );

            // Add to TOC
            if ($level <= $options['max_level']) {
                $indent = str_repeat('    ', $level - 2);
                $toc .= $indent . '<li><a href="#' . $id . '">' . esc_html($text) . '</a></li>';
            }
        }

        $toc .= '</ul>';
        $toc .= '</div>';

        // Insert TOC after first paragraph or H1
        $content = preg_replace('/(<\/h1>|<\/p>)/', '$1' . $toc, $content, 1);

        return $content;
    }
}

<?php


namespace Nextend\SmartSlider3\Renderable\Item\Text;


use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;
use function Nextend\Framework\Sanitize;

class ItemTextFrontend extends AbstractItemFrontend {

    public function render() {
        return $this->getHtml();
    }

    public function renderAdminTemplate() {
        return $this->getHtml();
    }

    private function getHTML() {
        $owner = $this->layer->getOwner();

        $font = $owner->addFont($this->data->get('font'), 'paragraph');

        $style = $owner->addStyle($this->data->get('style'), 'heading');

        $tagName = 'p';
        if (Platform::needStrongerCSS()) {
            $tagName = 'ss-p';
        }

        $html    = '';
        $content = str_replace(array(
            '<p>',
            '</p>'
        ), array(
            '<' . $tagName . ' class="' . $font . ' ' . $style . ' ">',
            '</' . $tagName . '>'
        ), $this->wpautop(Sanitize::filter_allowed_html($this->closeTags($owner->fill($this->data->get('content', ''))), '<p>')));

        $class = '';

        $hasMobile = false;
        if ($this->data->get('content-mobile-enabled')) {
            $hasMobile = true;
            $html      .= Html::tag('div', array(
                'data-hide-desktoplandscape' => 1,
                'data-hide-desktopportrait'  => 1,
                'data-hide-tabletlandscape'  => 1,
                'data-hide-tabletportrait'   => 1
            ), str_replace(array(
                '<p>',
                '</p>'
            ), array(
                '<' . $tagName . ' class="' . $font . ' ' . $style . ' ">',
                '</' . $tagName . '>'
            ), $this->wpautop(Sanitize::filter_allowed_html($this->closeTags($owner->fill($this->data->get('contentmobile', ''))), '<p>'))));
        }

        $hasTablet = false;
        if ($this->data->get('content-tablet-enabled')) {
            $hasTablet = true;

            $attributes = array(
                'class'                      => $class,
                'data-hide-desktoplandscape' => 1,
                'data-hide-desktopportrait'  => 1,
            );

            if ($hasMobile) {
                $attributes['data-hide-mobilelandscape'] = 1;
                $attributes['data-hide-mobileportrait']  = 1;
            } else {
                $hasMobile = true;
            }

            $html  .= Html::tag('div', $attributes, str_replace(array(
                '<p>',
                '</p>'
            ), array(
                '<' . $tagName . ' class="' . $font . ' ' . $style . '">',
                '</' . $tagName . '>'
            ), $this->wpautop(Sanitize::filter_allowed_html($this->closeTags($owner->fill($this->data->get('contenttablet', '')))), '<p>')));
            $class = '';
        }


        $attributes = array(
            'class' => $class
        );

        if ($hasMobile) {
            $attributes['data-hide-mobilelandscape'] = 1;
            $attributes['data-hide-mobileportrait']  = 1;
        }

        if ($hasTablet) {
            $attributes['data-hide-tabletlandscape'] = 1;
            $attributes['data-hide-tabletportrait']  = 1;
        }
        $html .= Html::tag('div', $attributes, $content);

        return Html::tag('div', array(
            'class' => 'n2-ss-item-content n2-ss-text n2-ow-all'
        ), $html);
    }


    public function closeTags($html) {
        $html = str_replace(array(
            '<>',
            '</>'
        ), array(
            '',
            ''
        ), $html);
        // Put all opened tags into an array
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/| ])>#iU', $html, $result);
        $openedtags = $result[1];   #put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # Check if all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                if ($openedtags[$i] != 'br') {
                    // Ignores <br> tags to avoid unnessary spacing
                    // at the end of the string
                    $html .= '</' . $openedtags[$i] . '>';
                }
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    private function wpautop($pee, $br = true) {
        $pre_tags = array();

        if (trim($pee) === '') {
            return '';
        }

        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";

        /*
         * Pre tags shouldn't be touched by autop.
         * Replace pre tags with placeholders and bring them back after autop.
         */
        if (strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee  = array_pop($pee_parts);
            $pee       = '';
            $i         = 0;

            foreach ($pee_parts as $pee_part) {
                $start = strpos($pee_part, '<pre');

                // Malformed HTML?
                if (false === $start) {
                    $pee .= $pee_part;
                    continue;
                }

                $name            = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

                $pee .= substr($pee_part, 0, $start) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }
        // Change multiple <br>'s into two line breaks, which will turn into paragraphs.
        $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        // Add a double line break above block-level opening tags.
        $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee);

        // Add a double line break below block-level closing tags.
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

        // Add a double line break after hr tags, which are self closing.
        $pee = preg_replace('!(<hr\s*?/?>)!', "$1\n\n", $pee);

        // Standardize newline characters to "\n".
        $pee = str_replace(array(
            "\r\n",
            "\r"
        ), "\n", $pee);

        // Find newlines in all elements and add placeholders.
        $pee = self::wp_replace_in_html_tags($pee, array("\n" => ' <!-- wpnl --> '));

        // Collapse line breaks before and after <option> elements so they don't get autop'd.
        if (strpos($pee, '<option') !== false) {
            $pee = preg_replace('|\s*<option|', '<option', $pee);
            $pee = preg_replace('|</option>\s*|', '</option>', $pee);
        }

        /*
         * Collapse line breaks inside <object> elements, before <param> and <embed> elements
         * so they don't get autop'd.
         */
        if (strpos($pee, '</object>') !== false) {
            $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
            $pee = preg_replace('|\s*</object>|', '</object>', $pee);
            $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
        }

        /*
         * Collapse line breaks inside <audio> and <video> elements,
         * before and after <source> and <track> elements.
         */
        if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
            $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
            $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
            $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
        }

        // Collapse line breaks before and after <figcaption> elements.
        if (strpos($pee, '<figcaption') !== false) {
            $pee = preg_replace('|\s*(<figcaption[^>]*>)|', '$1', $pee);
            $pee = preg_replace('|</figcaption>\s*|', '</figcaption>', $pee);
        }

        // Remove more than two contiguous line breaks.
        $pee = preg_replace("/\n\n+/", "\n\n", $pee);

        // Split up the contents into an array of strings, separated by double line breaks.
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

        // Reset $pee prior to rebuilding.
        $pee = '';

        // Rebuild the content as a string, wrapping every bit with a <p>.
        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace('|<p>\s*</p>|', '', $pee);

        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee);

        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee);

        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace('|<p>(<li.+?)</p>|', '$1', $pee);

        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $pee);

        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee);

        // Optionally insert line breaks.
        if ($br) {
            // Replace newlines that shouldn't be touched with a placeholder.
            $pee = preg_replace_callback('/<(script|style|svg).*?<\/\\1>/s', array(
                $this,
                '_autop_newline_preservation_helper'
            ), $pee);

            // Normalize <br>
            $pee = str_replace(array(
                '<br>',
                '<br/>'
            ), '<br />', $pee);

            // Replace any new line characters that aren't preceded by a <br /> with a <br />.
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

            // Replace newline placeholders with newlines.
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }

        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $pee);

        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);

        // Replace placeholder <pre> tags with their original content.
        if (!empty($pre_tags)) {
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
        }

        // Restore newlines in all elements.
        if (false !== strpos($pee, '<!-- wpnl -->')) {
            $pee = str_replace(array(
                ' <!-- wpnl --> ',
                '<!-- wpnl -->'
            ), "\n", $pee);
        }

        return $pee;
    }

    /**
     * Replace characters or phrases within HTML elements only.
     *
     * @param string $haystack      The text which has to be formatted.
     * @param array  $replace_pairs In the form array('from' => 'to', ...).
     *
     * @return string The formatted text.
     * @since 4.2.3
     *
     */
    private function wp_replace_in_html_tags($haystack, $replace_pairs) {
        // Find all elements.
        $textarr = self::wp_html_split($haystack);
        $changed = false;

        // Optimize when searching for one item.
        if (1 === count($replace_pairs)) {
            // Extract $needle and $replace.
            foreach ($replace_pairs as $needle => $replace) {
            }

            // Loop through delimiters (elements) only.
            for ($i = 1, $c = count($textarr); $i < $c; $i += 2) {
                if (false !== strpos($textarr[$i], $needle)) {
                    $textarr[$i] = str_replace($needle, $replace, $textarr[$i]);
                    $changed     = true;
                }
            }
        } else {
            // Extract all $needles.
            $needles = array_keys($replace_pairs);

            // Loop through delimiters (elements) only.
            for ($i = 1, $c = count($textarr); $i < $c; $i += 2) {
                foreach ($needles as $needle) {
                    if (false !== strpos($textarr[$i], $needle)) {
                        $textarr[$i] = strtr($textarr[$i], $replace_pairs);
                        $changed     = true;
                        // After one strtr() break out of the foreach loop and look at next element.
                        break;
                    }
                }
            }
        }

        if ($changed) {
            $haystack = implode($textarr);
        }

        return $haystack;
    }

    /**
     * Separate HTML elements and comments from the text.
     *
     * @param string $input The text which has to be formatted.
     *
     * @return string[] Array of the formatted text.
     * @since 4.2.4
     *
     */
    private function wp_html_split($input) {
        return preg_split(self::get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE);
    }

    /**
     * Retrieve the regular expression for an HTML element.
     *
     * @return string The regular expression
     * @since 4.4.0
     *
     */
    private function get_html_split_regex() {
        static $regex;

        if (!isset($regex)) {
            // phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
            $comments = '!'             // Start of comment, after the <.
                . '(?:'         // Unroll the loop: Consume everything until --> is found.
                . '-(?!->)' // Dash not followed by end of comment.
                . '[^\-]*+' // Consume non-dashes.
                . ')*+'         // Loop possessively.
                . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata = '!\[CDATA\['    // Start of comment, after the <.
                . '[^\]]*+'     // Consume non-].
                . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                . '](?!]>)' // One ] not followed by end of comment.
                . '[^\]]*+' // Consume non-].
                . ')*+'         // Loop possessively.
                . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped = '(?='             // Is the element escaped?
                . '!--' . '|' . '!\[CDATA\[' . ')' . '(?(?=!-)'      // If yes, which type?
                . $comments . '|' . $cdata . ')';

            $regex = '/('                // Capture the entire match.
                . '<'           // Find start of element.
                . '(?'          // Conditional expression follows.
                . $escaped  // Find end of escaped element.
                . '|'           // ...else...
                . '[^>]*>?' // Find end of normal element.
                . ')' . ')/';
            // phpcs:enable
        }

        return $regex;
    }

    public function _autop_newline_preservation_helper($matches) {
        return str_replace("\n", '<WPPreserveNewline />', $matches[0]);
    }
}
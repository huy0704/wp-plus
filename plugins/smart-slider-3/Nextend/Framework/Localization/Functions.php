<?php

use Nextend\Framework\Localization\Localization;

function n2_($text, $domain = 'nextend', $escape = true) {
    $translations = Localization::getTranslationsForDomain($domain);

    if ($escape) {
        return esc_html($translations->translate($text));
    } else {
        return $translations->translate($text);
    }
}

function n2_e($text, $domain = 'nextend') {
    echo esc_html(n2_($text, $domain, false));
}

function n2_n($single, $plural, $number, $domain = 'nextend') {
    $translations = Localization::getTranslationsForDomain($domain);

    return $translations->translate_plural($single, $plural, $number);
}

function n2_en($single, $plural, $number, $domain = 'nextend') {
    echo esc_html(n2_n($single, $plural, $number, $domain));
}

function n2_x($text, $context, $domain = 'nextend') {
    $translations = Localization::getTranslationsForDomain($domain);

    return $translations->translate($text, $context);
}

function n2_ex($text, $context, $domain = 'nextend') {
    echo esc_html(n2_x($text, $context, $domain));
}
<?php

namespace Nextend\SmartSlider3\Application\Admin\Help;


use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Sanitize;
use Nextend\Framework\Url\Url;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this ViewHelpIndex
 */

$conflicts = $this->getConflicts();

?>
<div class="n2_help_center">

    <div class="n2_help_center__getting_started">
        <div class="n2_help_center__getting_started__heading">
            <?php n2_e('Welcome to Help Center'); ?>
        </div>
        <div class="n2_help_center__getting_started__subheading">
            <?php n2_e('To help you get started, we\'ve put together a super tutorial video that shows you the basic settings.'); ?>
        </div>
        <div class="n2_help_center__getting_started__video">
            <div class="n2_help_center__getting_started__video_placeholder"></div>
            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/videoseries?list=PLSawiBnEUNfvVeY7M8Yx7UdyOpBEmoH7Z&rel=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>


    <?php
    if (!empty($conflicts)) {
        ?>
        <div class="n2_help_center__conflicts" id="n2_help_center__possible_conflicts">
            <div class="n2_help_center__conflicts_icon"><i class="ssi_48 ssi_48--bug"></i></div>
            <div class="n2_help_center__conflicts_label"><?php n2_e('Possible conflicts'); ?></div>
            <div class="n2_help_center__conflicts_description">
                <div class="n2_help_center__conflicts_test_api">
                    <a href="<?php echo esc_url($this->getUrlHelpTestApi()); ?>">
                        <?php n2_e('Test connection'); ?>
                    </a>
                </div>
                <?php
                ?>
                <?php if (empty($conflicts)): ?>
                    <div class="n2_help_center__no_conflicts_detected"><?php n2_e('No conflicts detected.'); ?></div>
                <?php else: ?>
                    <?php foreach ($conflicts as $conflict): ?>
                        <div class="n2_help_center__conflicts_detected"><?php echo wp_kses($conflict, Sanitize::$basicTags); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php
    }

    ?>


    <div class="n2_help_center__search">

        <div class="n2_help_center__search_heading">
            <?php n2_e('Hello! How can we help you today?'); ?>
        </div>

        <div class="n2_help_center__search_field">
            <form target="_blank" action="https://smartslider.helpscoutdocs.com/search" method="get">
                <input name="query" type="text" placeholder="<?php n2_e('Search in the knowledge base'); ?>">
                <button type="submit"><?php n2_e('Search'); ?></button>
            </form>
        </div>
    </div>

    <div class="n2_help_center__actions">
        <div class="n2_help_center__action">
            <a class="n2_help_center__action_link"
               href="<?php echo esc_url('https://smartslider.helpscoutdocs.com/?utm_campaign=' . SmartSlider3Info::$campaign . '&utm_source=dashboard-documentation&utm_medium=smartslider-' . Platform::getName() . '-' . SmartSlider3Info::$plan); ?>"
               target="_blank"></a>
            <div class="n2_help_center__action_icon"><i class="ssi_48 ssi_48--doc"></i></div>
            <div class="n2_help_center__action_label"><?php n2_e('Documentation'); ?></div>
            <div class="n2_help_center__action_description"><?php n2_e('To get started with Smart Slider 3, please refer to this guide for downloading, installing, and using.'); ?></div>
        </div>
        <div class="n2_help_center__action">
            <a class="n2_help_center__action_link" href="https://smartslider3.com/contact-us/support/"
               onclick="document.getElementById('n2_support_form').submit(); return false;"></a>
            <div class="n2_help_center__action_icon"><i class="ssi_48 ssi_48--help"></i></div>
            <div class="n2_help_center__action_label"><?php n2_e('Email support'); ?></div>
            <div class="n2_help_center__action_description"><?php n2_e('Need one-to-one assistance? Get in touch with our Support team! We\'d love the opportunity to help you.'); ?></div>
        </div>
        <div class="n2_help_center__action">
            <a class="n2_help_center__action_link"
               href="<?php echo esc_url('https://www.youtube.com/watch?v=3PPtkRU7D74&list=PLSawiBnEUNfvVeY7M8Yx7UdyOpBEmoH7Z&utm_campaign=' . SmartSlider3Info::$campaign . '&utm_source=dashboard-watch-videos&utm_medium=smartslider-' . Platform::getName() . '-' . SmartSlider3Info::$plan); ?>"
               target="_blank"></a>
            <div class="n2_help_center__action_icon"><i class="ssi_48 ssi_48--camera"></i></div>
            <div class="n2_help_center__action_label"><?php n2_e('Tutorial videos'); ?></div>
            <div class="n2_help_center__action_description"><?php n2_e('Check our video tutorials which cover everything you need to know about Smart Slider 3.'); ?></div>
        </div>
    </div>

    <div class="n2_help_center__articles_heading">
        <?php n2_e('Selected articles'); ?>
    </div>

    <div class="n2_help_center__articles">
        <?php
        foreach ($this->getArticles() as $article) {
            ?>
            <div class="n2_help_center__article">
                <a class="n2_help_center__article_link" href="<?php echo esc_url($article['url']); ?>" target="_blank"></a>
                <div class="n2_help_center__article_label"><?php echo esc_html($article['label']); ?></div>
                <i class="ssi_16 ssi_16--breadcrumb n2_help_center__article_icon"></i>
            </div>
            <?php
        }
        ?>
    </div>


    <?php
    if (empty($conflicts)) {
        ?>
        <div class="n2_help_center__conflicts" id="n2_help_center__possible_conflicts">
            <div class="n2_help_center__conflicts_icon"><i class="ssi_48 ssi_48--bug"></i></div>
            <div class="n2_help_center__conflicts_label"><?php n2_e('Possible conflicts'); ?></div>
            <div class="n2_help_center__conflicts_description">
                <div class="n2_help_center__conflicts_test_api">
                    <a href="<?php echo esc_url($this->getUrlHelpTestApi()); ?>">
                        <?php n2_e('Test connection'); ?>
                    </a>
                </div>
                <?php
                ?>

                <?php if (empty($conflicts)): ?>
                    <div class="n2_help_center__no_conflicts_detected"><?php n2_e('No conflicts detected.'); ?></div>
                <?php else: ?>
                    <?php foreach ($conflicts as $conflict): ?>
                        <div class="n2_help_center__conflicts_detected"><?php echo wp_kses($conflict, Sanitize::$basicTags); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php
    }

    ?>


    <?php
    ?>

    <div class="n2_help_center__system_information">

        <div class="n2_help_center__system_information_label">
            <?php n2_e('Debug information'); ?>
        </div>

        <form id="n2_support_form" class="n2_help_center__system_information_form" method="post"
              action="https://smartslider3.com/contact-us/support/" target="_blank">
            <?php
            $debug = array(
                'Smart Slider 3 - version: ' . SmartSlider3Info::$completeVersion,
                'Plan: ' . SmartSlider3Info::$plan,
                'Platform: ' . Platform::getLabel() . ' - ' . Platform::getVersion(),
                'Site url: ' . Platform::getSiteUrl(),
                'Path: ' . Filesystem::getBasePath(),
                'Uri: ' . Url::getBaseUri(),
                'Browser: ' . Request::$SERVER->getVar('HTTP_USER_AGENT'),
                ''
            );

            $curlLog = $this->getCurlLog();
            if (!empty($curlLog)) {
                $debug   = array_merge($debug, $curlLog);
                $debug[] = '';
            }

            if (function_exists('ini_get')) {
                $debug[] = 'PHP: ' . phpversion();
                $debug[] = 'PHP - memory_limit: ' . ini_get('memory_limit');
                $debug[] = 'PHP - max_input_vars: ' . ini_get('max_input_vars');

                $opcache = ini_get('opcache.enable');
                $debug[] = 'PHP - opcache.enable: ' . intval($opcache);

                if ($opcache) {
                    $debug[] = 'PHP - opcache.revalidate_freq: ' . ini_get('opcache.revalidate_freq');
                }

                $debug[] = '';
            }

            if (extension_loaded('gd')) {
                $debug[] = 'GD modules status:';
                foreach (gd_info() as $module => $status) {
                    $debug[] = $module . ' : ' . (!empty($status) ? $status : "0");
                }
            }
            $debug[] = '';

            if (function_exists('get_loaded_extensions')) {

                $debug[] = 'Uncommon PHP extensions:';

                $debug[] = implode(" \t", array_diff(get_loaded_extensions(), array(
                    'Core',
                    'openssl',
                    'pcre',
                    'zlib',
                    'SPL',
                    'session',
                    'standard',
                    'cgi-fcgi',
                    'mysqlnd',
                    'PDO',
                    'bz2',
                    'calendar',
                    'filter',
                    'hash',
                    'Reflection',
                    'zip',
                    'Zend OPcache',
                    'shmop',
                    'sodium',
                    'date',
                    'dom',
                    'ctype',
                    'xml',
                    'libxml',
                    'fileinfo',
                    'ftp',
                    'gettext',
                    'iconv',
                    'intl',
                    'json',
                    'exif',
                    'mysqli',
                    'pdo_mysql',
                    'Phar',
                    'posix',
                    'readline',
                    'SimpleXML',
                    'soap',
                    'sockets',
                    'sysvmsg',
                    'sysvsem',
                    'sysvshm',
                    'tokenizer',
                    'wddx',
                    'xmlreader',
                    'xmlwriter',
                    'xsl'
                )));

                $debug[] = '';
            }


            $debugConflicts = $this->getDebugConflicts();
            if (empty($debugConflicts)) {
                $debug[] = 'No conflicts detected';
            } else {
                $debug[] = 'Conflicts:';
                foreach ($debugConflicts as $conflict) {
                    $debug[] = ' - ' . $conflict;
                }
                $debug[] = '';
            }

            $debug = array_merge($debug, Platform::getDebug());

            ?>
            <textarea readonly name="debug_information"
                      style="width:100%;height:800px;"><?php echo esc_html(implode("\n", $debug)); ?></textarea>
        </form>
    </div>
</div>

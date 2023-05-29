<?php


namespace Nextend\Framework;


class PageFlow {

    public static function markApplicationEnd() {

        Plugin::doAction('exit');
    }

    public static function exitApplication() {

        self::markApplicationEnd();

        exit;
    }

    public static function cleanOutputBuffers() {
        $handlers = ob_list_handlers();
        while (count($handlers) > 0 && $handlers[count($handlers) - 1] != 'ob_gzhandler' && $handlers[count($handlers) - 1] != 'zlib output compression') {
            ob_end_clean();
            $handlers = ob_list_handlers();
        }
    }
}
<?php
/**
 * Copy & Delete Posts – default menu.
 *
 * @package CDP
 * @subpackage Notifications
 * @author CopyDeletePosts
 * @since 1.0.0
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/** –– **\
 * There is dynamic html for notifications.
 * @since 1.0.0
 */
  function cdp_notifications_menu() {

    $current = current_time('timestamp');
    $cdp_cron = get_option('_cdp_crons', array());
    $sorts = array('done' => array(), 'undone' => array());
    $to_show = 0;

    if (sizeof($cdp_cron) > 0) {
      foreach ($cdp_cron as $cron => $val) {
        $entry = array('t' => $cron, 's' => $val['shown'], 'f' => $val['f'], 'st' => $val['start']);
        if (isset($val['data'])) $entry['data'] = $val['data'];

        if ($val['done'] == false) array_push($sorts['undone'], $entry);
        else if ($val['done'] == true) array_push($sorts['done'], $entry);

        if ($val['shown'] == false) $to_show++;
      }
    }
 
    $html = '<span class="cdp-admin-bar-icon-noti"></span>';
    $html .= __('Copy & Delete', 'copy-delete-posts') . ' [<span class="cdp-notis-counter">' . $to_show . '</span>]';
    $html .= '<div class="ab-sub-wrapper cdp-ab-sub">
      <ul class="ab-submenu cdp-dropdown-submenu">';

    $html .= '<li class="cdp-undone-notis"' . ((sizeof($sorts['undone']) <= 0)?' style="display: none;"':'') . '>
          <a class="ab-item cdp-dropdown-menu">
            <div style="display: block;">
              <div class="cdp-dropdown-left-header">'. __('In progress:', 'copy-delete-posts').'</div>
            </div>
          </a>
        </li>
      <li class="cdp-dd-undone-list">';

    foreach ($sorts['undone'] as $index => $val) {
      $red = (($current - $val['st']) >= 86400) ? true : false;
      $html .= '<a class="ab-item cdp-dropdown-menu cdp-dd-item cdp-dd-ud' . (($val['s'] == false)?' cdp-bnew-notis':'') . '" data-token="' . $val['t'] . '">
        <div style="display: block; width: calc(100% - 3px);">
          <div class="cdp-dropdown-left' . (($red)?' cdp-red':'') . '">' . (($val['f'] == 'delete')? __('Deleting Posts', 'copy-delete-posts'):__('Auto Cleaning-up', 'copy-delete-posts')) . '</div>
          <div class="cdp-dropdown-right cdp-noti-kill-btn">' . __('Kill', 'copy-delete-posts') . '</div>
        </div>
      </a>';
    }

    $html .= '</li>
        <hr class="cdp-dropdown-hr cdp-undone-hr"'. ((sizeof($sorts['undone']) <= 0)?' style="display: none;"':'') .'>';


    $html .= '<li class="cdp-done-notis"' . ((sizeof($sorts['done']) <= 0)?' style="display: none;"':'') . '>
          <a class="ab-item cdp-dropdown-menu">
            <div style="display: block;">
              <div class="cdp-dropdown-left-header">' . __('Done:', 'copy-delete-posts') . '</div>
            </div>
          </a>
        </li>
      <li class="cdp-dd-done-list">';

    foreach ($sorts['done'] as $index => $val) {
      $format = ''; $ftext = '';
      if (isset($val['data'])) {
        if (array_key_exists('formated-date', $val['data'])) $format = $val['data']['formated-date'];
        if (array_key_exists('text', $val['data'])) $ftext = $val['data']['text'];

        if ($ftext != '') $ftext = 'data-ftext="' . $ftext . '" ';
        if ($format != '') $format = 'data-fdate="Completed at ' . $format . '" ';
      }

      $html .= '<a ' . $ftext . $format . 'class="ab-item cdp-dropdown-menu cdp-dd-item cdp-dd-d' . (($val['s'] == false)?' cdp-bnew-notis':'') . '" data-token="' . $val['t'] . '">
        <div style="display: block; width: calc(100% - 3px);">
          <div class="cdp-dropdown-left">' . (($val['f'] == 'delete')?__('Deleted Posts', 'copy-delete-posts'):__('Auto Cleaned-up', 'copy-delete-posts')) . '</div>
          <div class="cdp-dropdown-right cdp-noti-hide-btn">' . __('Hide', 'copy-delete-posts') . '</div>
        </div>
      </a>';
    }

    $html .= '</li>
        <hr class="cdp-dropdown-hr cdp-done-hr"'. ((sizeof($sorts['done']) <= 0)?' style="display: none;"':'') .'>';

    $html .= '<li><a class="ab-item cdp-dropdown-clear-all-notis" href="#">' . __('Clear notifiactions', 'copy-delete-posts'). '</a></li>
      </ul>
    </div>';

    return array('html' => $html, 'in_list' => (sizeof($sorts['done']) + sizeof($sorts['undone'])));

  }
/** –– **/

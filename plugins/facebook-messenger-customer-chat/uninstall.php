<?php
/**
 * Plugin uninstaller.
 *
 * @package Facebook_Messenger_Customer_Chat
 *
 * Copyright (C) 2017-present, Facebook, Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( 'fbmcc_pageID' );
delete_option( 'fbmcc_locale' );
delete_option( 'fbmcc_generatedCode' );
delete_option( 'fbmcc_enabled' );

<?php

/**
 * Poka Subtitle
 *
 * @package     Poka Subtitle
 * @author      thientran
 * @copyright   2020 poka-media
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Poka Subtitle
 * Plugin URI:  https://pokamedia.com/poka-subtitle
 * Description: A simple plugin add Subtitle to post
 * Version:     1.0.0
 * Author:      thientran
 * Author URI:  http://fb.com/makiosp1
 * Text Domain: poka-subtitle
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */
// Block direct access to file
defined('ABSPATH') or die('Not Authorized!');

// Plugin Defines
define("PST_FILE", __FILE__);
define("PST_DIRECTORY", dirname(__FILE__));
define("PST_TEXT_DOMAIN", dirname(__FILE__));
define("PST_DIRECTORY_BASENAME", plugin_basename( PST_FILE ));
define("PST_DIRECTORY_PATH", plugin_dir_path( PST_FILE ));
define("PST_DIRECTORY_URL", plugins_url( null, PST_FILE ));

// Require the main class file
require_once( PST_DIRECTORY . '/include/main-class.php' );

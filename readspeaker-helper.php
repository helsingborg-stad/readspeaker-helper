<?php

/**
 * Plugin Name:       ReadSpeaker Helper
 * Plugin URI:        (#plugin_url#)
 * Description:       A helper which will help you include the ReadSpeaker.com readspeaker solution to you WordPress site
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        (#plugin_author_url#)
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       readspeaker-helper
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('READSPEAKERHELPER_PATH', plugin_dir_path(__FILE__));
define('READSPEAKERHELPER_URL', plugins_url('', __FILE__));
define('READSPEAKERHELPER_TEMPLATE_PATH', READSPEAKERHELPER_PATH . 'templates/');

load_plugin_textdomain('readspeaker-helper', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once READSPEAKERHELPER_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once READSPEAKERHELPER_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ReadSpeakerHelper\Vendor\Psr4ClassLoader();
$loader->addPrefix('ReadSpeakerHelper', READSPEAKERHELPER_PATH);
$loader->addPrefix('ReadSpeakerHelper', READSPEAKERHELPER_PATH . 'source/php/');
$loader->register();

// Start application
new ReadSpeakerHelper\App();

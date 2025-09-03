<?php
/**
 * Plugin Name: Photo with Title and Description
 * Plugin URI: https://www.gurgurkhatech.com
 * Description: A custom Divi module to display photos or videos with titles and descriptions in a slider.
 * Version: 1.0.0
 * Author: Gurkha Technology
 * Author URI: https://www.gurgurkhatech.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ptd-divi-module
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class PTD_Extension extends ET_Builder_Extension {

    /**
     * The get_modules() method is responsible for returning an array of modules
     * that are supported by, or included with, the extension.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_modules() {
        return array(
            'AchievementsShowcaseModule',
        );
    }

    /**
     * The get_module_dir() method is responsible for returning the directory name
     * of the extension's modules.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_module_dir() {
        return 'includes';
    }
}

function ptd_initialize_extension() {
    require_once __DIR__ . '/includes/AchievementsShowcaseModule.php';
    require_once __DIR__ . '/includes/AchievementsShowcaseItem.php';
    new PTD_Extension();
}
add_action( 'divi_extensions_init', 'ptd_initialize_extension' );

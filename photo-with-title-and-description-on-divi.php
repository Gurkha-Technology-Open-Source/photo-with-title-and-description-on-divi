<?php
/**
 * Plugin Name: Photo with Title and Description
 * Plugin URI: https://www.gurgurkhatech.com
 * Description: A custom Divi module to display photos or videos with titles and descriptions in a slider.
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Tested up to: 6.5
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

    public function __construct() {
        parent::__construct();
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        // Register assets
        wp_register_style( 'ptd-swiper-style', plugins_url( 'lib/swiper-bundle.min.css', __FILE__ ) );
        wp_register_style( 'ptd-style', plugins_url( 'css/style.css', __FILE__ ) );

        wp_register_script( 'ptd-swiper-script', plugins_url( 'lib/swiper-bundle.min.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
        wp_register_script( 'ptd-frontend-script', plugins_url( 'js/frontend.js', __FILE__ ), array( 'jquery', 'ptd-swiper-script' ), '1.0.0', true );

        // Conditionally enqueue assets only on pages with the module
        $post = get_post();
        if ( is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ptd_achievements_showcase' ) ) {
            wp_enqueue_style( 'ptd-swiper-style' );
            wp_enqueue_style( 'ptd-style' );
            wp_enqueue_script( 'ptd-swiper-script' );
            wp_enqueue_script( 'ptd-frontend-script' );
        }
    }

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

<?php
/**
 * @package rac-social-media
 * @version 1.0.0
 */
/*
Plugin Name: RAC-social-media
Description: This adds a social media menu
Author: Cyrille Meichel
Version: 1.0.0
Author URI: https://www.linkedin.com/in/cmeichel/
*/


if (!function_exists('add_action')) {
    echo 'go out of here';
    die;
}



if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

require_once (ABSPATH . 'wp-settings.php');



class SocialMediaPlugin {

    public $plugin;

    function __construct() {
        $this->plugin = plugin_basename(__FILE__);
    }

    function register() {
        add_action('init', array($this, 'registerMyMenus'));
		add_action('wp_enqueue_scripts', array($this, 'loadStyle'));
    }

    function activate() {

    }

    function deactivate() {

    }

    function registerMyMenus() {
        register_nav_menus(
            array(
                'rac-social-menu' => __( 'Social Media Menu' )
            )
        );
    }

    function loadStyle() {
		wp_enqueue_style('socialmediastyle', plugins_url('/assets/style.css', __FILE__));
		wp_enqueue_style( 'dashicons' );
    }
}

if ( class_exists('SocialMediaPlugin')) {
    $meteoPlugin = new SocialMediaPlugin();
    $meteoPlugin->register();
}

// activation
register_activation_hook(__FILE__, array($meteoPlugin, 'activate'));

// deactivation
register_deactivation_hook(__FILE__, array($meteoPlugin, 'deactivate'));

if(!function_exists("jetpack_social_menu")) {
    function jetpack_social_menu() { 
        if ( has_nav_menu( 'rac-social-menu' ) ) : 
            $menu_type = 'svg'; 
            ?>
            <nav class="jetpack-social-navigation jetpack-social-navigation-<?php echo esc_attr( $menu_type ); ?>" role="navigation" aria-label="<?php esc_html_e( 'Social Links Menu', 'jetpack' ); ?>"> 
                <?php 
                    wp_nav_menu( array( 
                        'theme_location' => 'rac-social-menu',  
                        'depth' => 1,  
     ) ); 
                ?> 
            </nav><!-- .jetpack-social-navigation --> 
        <?php endif; 
    } 
}



if ( ! function_exists( 'jetpack_social_menu_get_svg' ) ) {
	/**
	 * Return SVG markup.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 * }
	 * @return string SVG markup.
	 */
	function jetpack_social_menu_get_svg( $args = array() ) {
		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return esc_html__( 'Please define default parameters in the form of an array.', 'jetpack' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return esc_html__( 'Please define an SVG icon filename.', 'jetpack' );
		}

		// Set defaults.
		$defaults = array(
			'icon'     => '',
			'fallback' => false,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = ' aria-hidden="true"';

		// Begin SVG markup.
		$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . ' role="img">';

		/*
		 * Display the icon.
		 *
		 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
		 *
		 * See https://core.trac.wordpress.org/ticket/38387.
		 */
		$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

		// Add some markup to use as a fallback for browsers that do not support SVGs.
		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		return $svg;
	}
}
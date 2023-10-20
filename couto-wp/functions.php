<?php
/**
 * coti Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package coti Theme
 */

define('__VERSION__', 'coti01');

if ( ! function_exists( 'coti_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function coti_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Codeskill Theme Theme, use a find and replace
		 * to change 'coti' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'coti', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Custom image sizes
		add_image_size( 'coti-blog-small', 320, 212, true );
		add_image_size( 'coti-blog-small-2x', 640, 424, true );
		add_image_size( 'coti-square', 190, 190, true );
		add_image_size( 'coti-square-2x', 380, 380, true );
		add_image_size( 'coti-history', 1108, 613, true );


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'coti_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function coti_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'coti' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'coti' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'coti_widgets_init' );

/**
 * Enqueue scripts and styles.
 */

function coti_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), __VERSION__ );
	wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css' );
	wp_enqueue_style( 'styles', get_template_directory_uri() . '/assets/css/styles.css', array(), __VERSION__ );
	wp_enqueue_style( 'owl', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', array(), __VERSION__ );
	wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/assets/css/owl.theme.default.min.css', array(), __VERSION__ );
	wp_enqueue_style( 'animate.css', get_template_directory_uri() . '/assets/css/animate.min.css', array(), __VERSION__ );
	wp_enqueue_style( 'hover-min', get_template_directory_uri() . '/assets/css/hover-min.css', array(), __VERSION__ );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

function scm_ajax_enqueue_scripts(){
    
wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), __VERSION__, true );
wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js', array(), __VERSION__, true );
wp_enqueue_script( 'jqueryui', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array(), __VERSION__, true );
wp_enqueue_script( 'owl', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), __VERSION__, true );
wp_enqueue_script( 'timeme', get_template_directory_uri() . '/assets/js/timeme.min.js', array(), __VERSION__, true );
// wp_enqueue_script( 'App', get_template_directory_uri() . '/assets/js/App.js', array('jquery'), __VERSION__, true );

wp_enqueue_script( 'App', get_theme_file_uri( '/assets/js/App.js'), array('jquery') );

wp_localize_script( 'App', 'ajax_var', array(
	'url'    => admin_url( 'admin-ajax.php' ),
	'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
	'action' => 'post_fb_event'
) );

  
}

function load_animate_css() {
	// Load Boostrap CSS
	wp_enqueue_style( 'animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css' );
  
	// Load Css
	wp_enqueue_style( 'style', get_stylesheet_uri() );
  
  }
  add_action( 'wp_enqueue_scripts', 'load_animate_css' );

add_action( 'wp_enqueue_scripts', 'coti_scripts' );
add_action( 'wp_enqueue_scripts', 'scm_ajax_enqueue_scripts' );

function register_my_menus() {
	register_nav_menus(
	  array(
		'header-menu' => __( 'Header Menu' ),
		'footer-1' => __( 'Footer' ),
	   )
	 );
   }
   add_action( 'init', 'register_my_menus' );
   

add_action( 'login_enqueue_scripts', 'coti_login' );

function coti_login() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url('http://cotiwp.local/wp-content/uploads/2021/12/cropped-Asset-1-1.png');
            background-size: contain;
            width: auto;
}
body.login div#login #wp-submit {
    background: #39566D;
    border: 2px solid #39566D;
    box-shadow: none;
    height: auto;
    text-shadow: none;
}
.login .button.wp-hide-pw .dashicons {
      color: #39566D;  
}
        }
    </style>
<?php }


add_action('init', function() {
    if ( function_exists('pll_register_string') ) {
        pll_register_string('coti', 'Horario de atención');
		pll_register_string('coti', 'ENVIAR');
		pll_register_string('coti', 'Lunes a viernes 7:00am - 4:00pm');
		pll_register_string('coti', 'Teléfonos');
		pll_register_string('coti', 'Dirección');
		pll_register_string('coti', 'Correo electrónico');
		pll_register_string('coti', 'COMO LLEGAR');
		pll_register_string('coti', 'Colapsar');
    } 
  });


/**
 * Polylang Shortcode - https://wordpress.org/plugins/polylang/
 * Add this code in your functions.php
 * Put shortcode [polylang_langswitcher] to post/page for display flags
 *
 * @return string
 */
function custom_polylang_langswitcher() {
	ob_start();
    if(function_exists('pll_the_languages'))
    {
        echo '<ul class="polylang-flags">';
        pll_the_languages(array(
            'show_flags'    => 0,
            'show_names'    => 1,
            'hide_current'  => 1,
        )); 
        echo '</ul>';
	}
    return ob_get_clean();
}

add_shortcode( 'polylang_langswitcher', 'custom_polylang_langswitcher' );

add_filter( 'show_admin_bar', '__return_false' );
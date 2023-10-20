<?php 

class CM_Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		// $id = 'id="target'.$this->curItem->ID.'"';
		$id = 'id="target'.$this->curItem->ID.'"';

		$output .= "{$n}{$indent}<ul $id $class_names>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$this->curItem = $item;

		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		if( in_array('menu-item-has-children', $item->classes) ) {
			$output .= $indent . '<li' . $id . $class_names . ' data-toggle="collapse" data-target="#target'.$item->ID.'">';
		} else {
			$output .= $indent . '<li' . $id . $class_names . '>';
		}

		$atts                 = array();
		$atts['title']        = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target']       = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']          = ! empty( $item->xfn ) ? $item->xfn : '';
		if( in_array('menu-item-has-children', $item->classes) === false ) {
			$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		}
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}

add_filter( 'nav_menu_css_class', 'cm_primary_menu_item_class', 10, 4 );
add_filter( 'nav_menu_link_attributes', 'cm_primary_menu_link_attributes', 10, 4 );
add_filter( 'nav_menu_item_args', 'cm_primary_menu_item_args', 10, 3 );
add_filter( 'nav_menu_submenu_css_class', 'cm_primary_submenu_class', 10, 3 );
add_filter( 'nav_menu_item_title', 'cm_primary_menu_search_icon', 10, 4 );

function cm_primary_menu_item_class($classes, $item, $args, $depth) {
	if($args->theme_location === 'menu-1') {

		$classes[] = ($depth === 0) ? 'nav-item' : 'align-items-end d-flex justify-content-center';

	} else if($args->theme_location === 'mobile-1') {
		if($depth === 0) {
			$classes[] = 'd-flex justify-content-center nav-item';
			if(in_array('menu-item-has-children', $classes)) {
				$classes[] = 'flex-column';
			}
		} else {
			$classes[] = 'd-flex justify-content-center align-items-end';
		}
	}
	
	return $classes;
}

function cm_primary_menu_link_attributes($atts, $item, $args, $depth) {

	if($args->theme_location === 'menu-1' || $args->theme_location === 'mobile-1') {
		if($depth === 0) {
			if(!isset($atts['class']))
				$atts['class'] = '';

			$atts['class'] .= ' nav-link';

			if($item->post_title === '[search]') {
				$atts['class'] .= ' d-flex align-items-center js-search';
			}

			// if( $args->theme_location === 'mobile-1' ) {
			// 	if( in_array('menu-item-has-children', $item->classes) ) {
			// 		$atts['data-toggle'] = 'collapse';
			// 		$atts['data-collapse'] = '#target' . $item->ID;
			// 	}
			// }
		}
	}
	return $atts;
}

function cm_primary_menu_item_args($args, $item, $depth) {
	if($args->theme_location === 'menu-1' || $args->theme_location === 'mobile-1') {
		if($depth === 0 && in_array('menu-item-has-children', $item->classes)) {
			$args->link_after = '<i class="ml-2 fas fa-angle-down"></i>';
		} else {
			$args->link_after = '';
		}
	}
	return $args;
}

function cm_primary_submenu_class($classes, $args, $depth) {
	if($args->theme_location === 'menu-1') {
		$classes[] = 'nav-tabs justify-content-center submenu js-submenu-inst';
	} else if($args->theme_location === 'mobile-1') {
		$classes[] = 'sub-sub collapse';
	}
	return $classes;
}

function cm_primary_menu_search_icon($title, $item, $args, $depth) {
	if($args->theme_location === 'menu-1' && $title === '[search]') {
		return '<i class="fa fa-search" aria-hidden="true"></i>';
	}
	return $title;
}
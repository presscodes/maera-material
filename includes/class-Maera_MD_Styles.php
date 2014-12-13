<?php

class Maera_MD_Styles {

	function __construct() {
		$this->colors = maera_md_colors();
		add_filter( 'maera/styles', array( $this, 'color_mods' ) );
		add_filter( 'maera/styles', array( $this, 'custom_header_css' ) );
		add_filter( 'body_class', array( $this, 'body_background_classes' ) );
	}

	function color_mods( $css ) {

		$colors = $this->colors;
		$simple_colors = array();

		foreach ( $colors as $color => $values ) {
			$simple_colors[$color] = $values['label'];
		}
		$styles = get_transient( 'maera_md_colors' );

		if ( false === ( $styles ) ) {

			$styles = '';

			foreach ( $simple_colors as $color => $classes ) {
				$bg_obj  = new Jetpack_Color( '#' . $color );
				$classes = '.' . str_replace( ' ', '.', $classes );

				$bg         = '#' . str_replace( '#', '', $bg_obj->toHex() );
				$color      = '#' . $bg_obj->getReadableContrastingColor( $bg_obj, 2 )->toHex();
				$link_color = ( '000000' == $color ) ? 'rgba(0,0,0,.75)' : 'rgba(255,255,255,.75)';

				$styles .= $classes . ',' . $classes . ' p{background:' . $bg . ';color:' . $color . ';}' . $classes . ' a{color:' . $link_color . ';}';
			}

			set_transient( 'maera_md_colors', $styles, 60 * 60 );

		}

		return $css . $styles;

	}

	/**
	 * Add background color classes to the body
	 */
	function body_background_classes( $classes ) {

		$current_color = get_theme_mod( 'body_bg', '' );

		if ( ! empty( $current_color ) ) {
			$classes[] = $this->colors[$current_color];
		}

		return $classes;

	}

	function custom_header_css( $styles ) {

		$custom_header = get_header_image();

		if ( $custom_header ) {
			$styles .= '.header.hero .parallax-layer-back{background-image:url("' . $custom_header . '");}';
		}
		$styles .= '.header.hero{color:#' . get_theme_mod( 'header_textcolor', '333333' ) . '}';

		return $styles;

	}

}
$styles = new Maera_MD_Styles();

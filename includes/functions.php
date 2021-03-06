<?php
/**
 * LSX Search functions.
 *
 * @package lsx-search
 */

namespace lsx\sharing\includes\functions;

/**
 * Gets the sharing text.
 *
 * @return array
 */
function get_restricted_post_types() {
	$post_types = array(
		'page',
		'attachment',
		'forum',
		'topic',
		'lesson',
		'quiz',
		'question',
		'reply',
		'popup',
		'sensei_message',
		'envira',
		'soliloquy',
		'certificate_template',
		'certificate',
		'project',
	);
	return apply_filters( 'lsx_sharing_get_restricted_post_types', $post_types );
}

/**
 * Gets the sharing text.
 *
 * @return array
 */
function get_to_post_types() {
	$post_types = array(
		'accommodation',
		'tour',
		'destination',
		'review',
		'special',
		'vehicle',
		'activity',
	);
	return apply_filters( 'lsx_sharing_get_to_post_types', $post_types );
}

/**
 * Gets the LSX HP Post types
 *
 * @return array
 */
function get_hp_post_types() {
	$post_types = array(
		'workout',
		'exercise',
		'recipe',
		'meal',
		'tip',
	);
	return apply_filters( 'lsx_sharing_get_hp_post_types', $post_types );
}

/**
 * Gets a specific option from the array.
 *
 * @return boolean
 */
function is_button_disabled( $post_type = '', $service = '' ) {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display'][ 'sharing_disable_' . $service ] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && ! empty( $sharing->options[ $post_type . '_disable_' . $service ] ) ) {
		$option = true;
	}
	return apply_filters( 'lsx_sharing_is_button_disabled', $option );
}

/**
 * Gets a specific option from the array.
 *
 * @return boolean
 */
function is_pt_disabled( $post_type = '' ) {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display'][ 'sharing_disable_pt_' . $post_type ] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && isset( $sharing->options[ $post_type . '_disable_pt' ] ) ) {
		$option = true;
	}
	return apply_filters( 'lsx_sharing_is_pt_disabled', $option );
}

/**
 * If the sharing has been disabled.
 *
 * @return boolean
 */
function is_disabled() {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display']['sharing_disable_all'] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && isset( $sharing->options['global_disable_all'] ) ) {
		$option = true;
	}
	return apply_filters( 'lsx_sharing_is_disabled', $option );
}

/**
 * Gets the sharing text.
 *
 * @return string
 */
function get_sharing_text( $post_type = '' ) {
	$sharing = lsx_sharing();
	$text    = '';
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display']['sharing_label_text'] ) ) {
		$text = $sharing->options['display']['sharing_label_text'];
	} elseif ( true === $sharing->is_new_options ) {
		if ( isset( $sharing->options[ $post_type . '_label_text' ] ) ) {
			$text = $sharing->options[ $post_type . '_label_text' ];
		} elseif ( isset( $sharing->options['global_label_text'] ) ) {
			$text = $sharing->options['global_label_text'];
		}
	}
	return $text;
}

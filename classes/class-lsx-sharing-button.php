<?php
/**
 * LSX_Sharing_Button
 *
 * @package lsx-sharing
 */

if ( ! class_exists( 'LSX_Sharing_Button' ) ) {

	/**
	 * LSX Sharing buttons class.
	 *
	 * @package lsx-sharing
	 */
	class LSX_Sharing_Button {

		/**
		 * Services available.
		 *
		 * @var string
		 */
		public $services = array(
			'email',
			'facebook',
			'twitter',
			'pinterest',
		);

		/**
		 * Current service.
		 *
		 * @var string
		 */
		public $service = '';

		/**
		 * Constructor.
		 */
		public function __construct( $service, $options ) {
			$this->options = $options;

			if ( ! in_array( $service, $this->services, true ) ) {
				return;
			}

			if ( isset( $this->options['display'] ) && ! empty( $this->options['display'][ 'sharing_disable_' . $service ] ) ) {
				return;
			}

			$this->service = $service;
		}

		/**
		 * Get service link to share.
		 */
		public function get_link( $post ) {
			if ( empty( $post ) ) {
				return '';
			}

			if ( 'email' === $this->service ) {
				return $this->get_link_email( $post );
			} elseif ( 'facebook' === $this->service ) {
				return $this->get_link_facebook( $post );
			} elseif ( 'twitter' === $this->service ) {
				return $this->get_link_twitter( $post );
			} elseif ( 'pinterest' === $this->service ) {
				return $this->get_link_pinterest( $post );
			}
		}

		/**
		 * Get email link to share.
		 */
		public function get_link_email( $post ) {
			$permalink = get_permalink( $post->ID );
			return $permalink;
		}

		/**
		 * Get Facebook link to share.
		 */
		public function get_link_facebook( $post ) {
			$permalink = get_permalink( $post->ID );
			$title     = apply_filters( 'the_title', $post->post_title );

			return 'https://www.facebook.com/sharer.php?display=page&u=' . rawurlencode( $permalink ) . '&t=' . rawurlencode( $title );
		}

		/**
		 * Get Twitter link to share.
		 */
		public function get_link_twitter( $post ) {
			$permalink = get_permalink( $post->ID );
			$title     = apply_filters( 'the_title', $post->post_title );

			if ( function_exists( 'mb_stripos' ) ) {
				$strlen = 'mb_strlen';
				$substr = 'mb_substr';
			} else {
				$strlen = 'strlen';
				$substr = 'substr';
			}

			$short_url_length = 24;

			if ( ( $strlen( $title ) + $short_url_length ) > 140 ) {
				$text = $substr( $title, 0, ( 140 - $short_url_length - 1 ) ) . "\xE2\x80\xA6";
			} else {
				$text = $title;
			}

			return 'https://twitter.com/intent/tweet?text=' . rawurlencode( $text ) . '&url=' . rawurlencode( $permalink );
		}

		/**
		 * Get Pinterest link to share.
		 */
		public function get_link_pinterest( $post ) {
			$permalink = get_permalink( $post->ID );
			$title     = apply_filters( 'the_title', $post->post_title );

			if ( ! has_post_thumbnail( $post ) ) {
				if ( class_exists( 'lsx\legacy\Placeholders' ) ) {
					$image = lsx\legacy\Placeholders::placeholder_url( null, $post->post_type );
				} elseif ( class_exists( 'LSX_Placeholders' ) ) {
					$image = LSX_Placeholders::placeholder_url( null, $post->post_type );
				}
			} else {
				$image = get_the_post_thumbnail_url( $post->ID, 'large' );
			}

			return 'https://www.pinterest.com/pin/create/button/?url=' . rawurlencode( $permalink ) . '&media=' . rawurlencode( $image ) . '&description=' . rawurlencode( $title );
		}

	}

}

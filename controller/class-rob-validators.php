<?php
/**
 * All plugin validators here
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ROB_Validators' ) ) {

	class ROB_Validators {

		/**
		 * Validator for preset edit fields
		 *
		 * @return array
		 */
		public static function validate_fields() {
			if ( empty( $_POST ) ) {
				return array();
			}

			$result = array();
			foreach ( $_POST as $key => $val ) {
				switch ( $key ) {
					case 'rob_request_filters':
						$filters = explode( "\n", $val );
						$items   = array();
						foreach ( $filters as $item ) {
							$rule    = explode( '@#$', $item );
							$rule    = array_splice( $rule, 0, 5 );
							$rule[0] = sanitize_text_field( $rule[0] );
							if ( isset( $rule[1] ) ) {
								$rule[1] = 'true' === sanitize_text_field( $rule[1] );
								if ( isset( $rule[2] ) ) {
									$res     = stripslashes( $rule[2] );
									$rule[2] = sanitize_text_field( $res );
									if ( ! ROB_Common::is_json( $rule[2] ) ) {
										$rule[2] = '{}';
									}
									if ( isset( $rule[3] ) ) {
										$rule[3] = sanitize_text_field( $rule[3] );
										if ( ! in_array( $rule[3], array( 'object', 'array', 'string' ) ) ) {
											$rule[3] = 'string';
										}
										if ( isset( $rule[4] ) ) {
											$res     = stripslashes( $rule[4] );
											$rule[4] = sanitize_text_field( $res );
											if ( ! ROB_Common::is_json( $rule[4] ) ) {
												$rule[4] = '{}';
											}
										}
									}
								}
							}
							array_push( $items, implode( '@#$', $rule ) );
						}
						$result[ $key ] = $items;
						break;
				}
			}

			return $result;
		}

		/**
		 * Get and validate errors messages from $_REQUEST
		 *
		 * @return array
		 */
		public static function get_errors() {
			if ( empty( $_REQUEST['error'] ) ) {
				return array();
			}

			$mgs = array( urldecode( $_REQUEST['error'] ) );
			$mgs = array_map( 'sanitize_text_field', $mgs );

			return $mgs;
		}

		/**
		 * Get and validate success messages from $_REQUEST
		 *
		 * @return array
		 */
		public static function get_success() {
			if ( empty( $_REQUEST['success'] ) ) {
				return array();
			}

			$mgs = array( urldecode( $_REQUEST['success'] ) );
			$mgs = array_map( 'sanitize_text_field', $mgs );

			return $mgs;
		}

		/**
		 * Sanitize string to integer value
		 *
		 * @param $text
		 *
		 * @return int
		 */
		public static function clear_digits( $text ) {
			return intval( preg_replace( '@[^\d]+@si', '', $text ) );
		}
	}
}

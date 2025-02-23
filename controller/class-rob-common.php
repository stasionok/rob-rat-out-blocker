<?php
/**
 * The core plugin class.
 *
 * It is used to define startup settings and requirements
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ROB_Common' ) ) {

	class ROB_Common {
		/**
		 * @var string Plugin common system name
		 */
		const PLUGIN_SYSTEM_NAME = 'rob-rat-out-blocker';

		/**
		 * @var string Human readable plugin name for front end
		 */
		const PLUGIN_HUMAN_NAME = 'ROB (rat out blocker)';

		/**
		 * @var string Path to plugin root directory
		 */
		public $plugin_base_path = '';

		public function __construct() {
			$this->plugin_base_path = self::get_plugin_root_path();

			$this->load_dependencies();
			$this->set_locale();
		}

		/**
		 * Plugin entry point
		 */
		public function run() {
			$this->define_admin_hooks();
			$this->define_common_hooks();
		}

		/**
		 * Get path or uri for plugin based folder
		 *
		 * @param string $type switch path or url for result
		 *
		 * @return string
		 */
		public static function get_plugin_root_path( $type = 'path' ) {
			if ( 'url' == $type ) {
				return plugin_dir_url( dirname( __FILE__ ) );
			}

			return plugin_dir_path( dirname( __FILE__ ) );
		}

		/**
		 * Load plugin files
		 */
		private function load_dependencies() {
			require_once $this->plugin_base_path . 'controller/class-rob-validators.php';
		}

		/**
		 * Add localization support
		 */
		private function set_locale() {
			load_plugin_textdomain(
				self::PLUGIN_SYSTEM_NAME,
				false,
				self::PLUGIN_SYSTEM_NAME . '/languages/'
			);
		}

		/**
		 * Print error messages
		 *
		 * @param null|WP_Error $error
		 *
		 * @return false|string
		 */
		public static function print_error( $error = null ) {
			$get_error = ROB_Validators::get_errors();
			if ( $error ) {
				if ( is_wp_error( $error ) ) {
					$error = $error->get_error_message();
					$error = esc_html(__( $error, 'rob-rat-out-blocker' ));
				}
				array_push( $get_error, $error );
			}

			ob_start();
			if ( ! empty( $get_error ) ) {
				echo '<div class="notice notice-error">';
				foreach ( $get_error as $msg ) {
					echo '<p>' . esc_html($msg) . '</p>';
				}
				echo '</div>';
			}

			return ob_get_clean();
		}

		/**
		 * Print success messages
		 *
		 * @param null|string $success
		 *
		 * @return false|string
		 */
		public static function print_success( $success = null ) {
			$get_success = ROB_Validators::get_success();

			if ( $success ) {
				array_push( $get_success, $success );
			}

			ob_start();
			if ( ! empty( $get_success ) ) {
				echo '<div class="notice notice-success">';
				foreach ( $get_success as $msg ) {
					echo '<p>' . esc_html($msg) . '</p>';
				}
				echo '</div>';
			}

			return ob_get_clean();
		}

		/**
		 * Add actions and work for admin part of plugin
		 */
		private function define_admin_hooks() {
			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'register_settings_pages' ) );
				add_filter(
					"plugin_action_links_" . plugin_basename( dirname( __DIR__ ) . '/bootstrap.php' ),
					array( $this, 'plugin_action_links' ),
					10,
					4
				);
			}
		}

		/**
		 * Add actions and work for common part of plugin
		 */
		private function define_common_hooks() {
			add_action( 'pre_http_request', array( $this, 'pre_http_request' ), 999, 3 );
		}

		/**
		 * Add link to plugin settings page im plugins list
		 *
		 * @param $actions
		 * @param $plugin_file
		 * @param $plugin_data
		 * @param $context
		 *
		 * @return mixed
		 */
		public static function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			array_unshift( $actions,
				sprintf( '<a href="%s" aria-label="%s">%s</a>',
					menu_page_url( 'rob-rat-out-blocker', false ),
					esc_attr__( 'open ROB page', 'rob-rat-out-blocker' ),
					esc_html__( "open ROB", 'rob-rat-out-blocker' )
				)
			);

			return $actions;
		}

		/**
		 * Register tools page
		 */
		public function register_settings_pages() {
			add_submenu_page(
				'tools.php',
				__( 'ROB (rat out blocker)', 'rob-rat-out-blocker' ),
				__( 'ROB (rat out blocker)', 'rob-rat-out-blocker' ),
				'administrator',
				'rob-rat-out-blocker',
				__CLASS__ . '::markup_settings_page'
			);
		}

		/**
		 * Markup for all plugin pages
		 */
		public static function markup_settings_page() {
			$filters = get_option( 'rob_request_filters', array() );
			if ( ! empty( $_POST ) ) {
				$post_data = ROB_Validators::validate_fields();

				if ( isset( $post_data['rob_request_filters'] ) ) {
					$filters = $post_data['rob_request_filters'];
					update_option( 'rob_request_filters', $filters );
				}
			}


			echo self::render( 'settings-page', array( 'filters' => implode( "\n", $filters ) ) );
		}

		/**
		 * Render plugin views function
		 *
		 * @param $name
		 * @param null|array $vars
		 *
		 * @return false|string
		 */
		public static function render( $name, $vars = null ) {
			if ( is_array( $vars ) ) {
				extract( $vars );
			}
			ob_start();
			$name = str_replace( '.php', '', $name ) . '.php';
			$path = self::get_plugin_root_path() . 'views/' . $name;
			if ( file_exists( $path ) ) {
				require( $path );
			}

			return ob_get_clean();
		}

		/**
		 * Main handler with hook pre_http_request filter
		 *
		 * @param $first
		 * @param $parsed_args
		 * @param $url
		 *
		 * @return bool|mixed|WP_Error
		 */
		public function pre_http_request( $first, $parsed_args, $url ) {
			$filters = get_option( 'rob_request_filters', array() );

			if ( ! empty( $filters ) ) {
				foreach ( $filters as $part ) {
					$rule      = explode( '@#$', $part );
					$is_regexp = isset( $rule[1] ) &&  $rule[1];

					$catchByBodyCondition = $this->checkBodySkip( $rule[4] ?? null, $parsed_args );

					if ( ! $is_regexp && false !== stripos( $url, $rule[0] ) ) {
						if (empty($rule[4]) || $catchByBodyCondition ) {
							return $this->make_response( $rule );
						}
					}

					$pattern = '@' . str_replace( '@', '\@', $rule[0] ) . '@si';
					if ( $is_regexp && preg_match( $pattern, $url ) ) {
						if (empty($rule[4]) || $catchByBodyCondition ) {
							return $this->make_response( $rule );
						}
					}
				}
			}

			return false; // skip here and continue request
		}

		private function checkBodySkip( $rule, $parsed_args ) {
			if ( empty( $rule ) ) {
				return false;
			}

			$bodyCheck     = json_decode( $rule, true );
			$bodyCheckKeys = array_keys( $bodyCheck );

			foreach ( $bodyCheckKeys as $key ) {
				if ( isset( $parsed_args['body'][ $key ] ) ) {
					if ( $parsed_args['body'][ $key ] == $bodyCheck[ $key ] ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Select which type of response return
		 * rule[0] rule[1]           rule[2]    rule[3]
		 * REGEXP@#$is_regexp=false@#${ANSWER}@#$json='object|array|string'
		 *
		 * @param array $rule Of response parameters
		 *
		 * @return mixed|WP_Error
		 */
		private function make_response( array $rule ) {
			// if response not set but need to block just response with some common response error
			if ( ! isset( $rule[2] ) or ! self::is_json( $rule[2] ) ) {
				return new WP_Error( 'http_request_failed', esc_html(__( 'Too many redirects.' )) );
			}

			// check type of response
			if ( empty( $rule[3] ) or ! in_array( $rule[3], array( 'object', 'array', 'string' ) ) ) {
				$rule[3] = 'string';
			}

			switch ( $rule[3] ) {
				case 'object':
					return json_decode( $rule[2] );
				case 'array':
					return json_decode( $rule[2], true );
				case 'string':
					return $rule[2];
			}

			return '';
		}

		/**
		 * Check parameter string is json or not
		 *
		 * @param $string
		 *
		 * @return bool True is json or false else
		 */
		public static function is_json( $string ) {
			if ( ! function_exists( 'json_decode' ) ) {
				return true;
			}

			$decoded = json_decode( $string );
			if ( ! is_object( $decoded ) && ! is_array( $decoded ) ) {
				return false;
			}

			return JSON_ERROR_NONE === json_last_error();
		}
	}
}

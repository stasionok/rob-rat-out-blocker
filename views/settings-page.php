<?php
defined( 'ABSPATH' ) || exit;

/* @var string $filters */
/* @var array $success */
/* @var array $error */

$placeholder = 'stats.mystatgather.net/new-install' . "\n";
$placeholder .= 'pingback.([^\.]+).com/activate/(.+)@#$true' . "\n";
$placeholder .= 'get.adobez.ru/regist@#$false@#${\'result\':\'ok\'}' . "\n";
$placeholder .= 'touch.apzle.com@#$false@#${}@#$array' . "\n";
$placeholder .= 'touch.test.com@#$false@#${}@#$array@#${\'somekey\':\'someval\'}' . "\n";
?>
<?php echo ROB_Common::print_error( @$error ) ?>
<?php echo ROB_Common::print_success( @$success ) ?>
<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'ROB (rat out blocker)', 'rob-rat-out-blocker' ); ?>
    </h1>
    <form method="post">
        <h2 class="title"><?php _e( 'Rules', 'rob-rat-out-blocker' ) ?></h2>

        <p>
            <label for="rob_request_filters">
				<?php esc_html_e( 'Any new line read as separate rule.', 'rob-rat-out-blocker' ) ?><br>
				<?php esc_html_e( 'You can use from 1 to all parameters per rule', 'rob-rat-out-blocker' ) ?><br>
				<?php esc_html_e( 'Full rule format is:', 'rob-rat-out-blocker' ) ?>
                <strong>URL_REGEXP_OR_URL_PART@#$IS_REGEXP_BOOL(true|false)@#$ANSWER_JSON_STRING@#$RESPONSE_FORMAT(object|array|string)@#$REQ_BODY_FILTER</strong><br>
                (<?php esc_html_e( 'Separator for parameters is ', 'rob-rat-out-blocker' ) ?>
                <strong>@#$</strong>,
				<?php esc_html_e( 'empty parameter equal to ', 'rob-rat-out-blocker' ) ?>
                <strong>false</strong>)
            </label>
        </p>

        <textarea name="rob_request_filters" id="rob_request_filters" class="large-text code" rows="10"
                  placeholder="<?php echo $placeholder ?>"><?php esc_html_e( $filters ) ?></textarea>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php esc_html_e( 'Save', 'rob-rat-out-blocker' ) ?>">
        </p>
    </form>
    <!-- Добавляем подсказку здесь -->
    <div class="notice notice-info">
        <p>
			<?php
			printf(
			/* translators: %s: plugin URL */
				wp_kses(
					__( 'Need to catch HTTP requests? Try the <a href="%s" target="_blank">Log HTTP Requests</a> plugin to track and analyze outgoing requests.', 'rob-rat-out-blocker' ),
					array( 'a' => array( 'href' => array(), 'target' => array() ) )
				),
				'https://wordpress.org/plugins/log-http-requests/'
			);
			?>
        </p>
    </div>

</div> <!-- .wrap -->
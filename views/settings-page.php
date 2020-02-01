<?php
defined( 'ABSPATH' ) || exit;

/* @var string $filters */
/* @var array $success */
/* @var array $error */

$placeholder = 'stats.mystatgather.net/new-install' . "\n";
$placeholder .= 'pingback.([^\.]+).com/activate/(.+)@#$true' . "\n";
$placeholder .= 'get.adobez.ru/regist@#$false@#${\'result\':\'ok\'}' . "\n";
$placeholder .= 'touch.apzle.com@#$false@#${}@#$array';
?>
<?php echo ROB_Common::print_error( @$error ) ?>
<?php echo ROB_Common::print_success( @$success ) ?>
<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( ROB_Common::PLUGIN_HUMAN_NAME, ROB_Common::PLUGIN_SYSTEM_NAME ); ?>
    </h1>
    <form method="post">
        <h2 class="title"><?php _e( 'Rules', ROB_Common::PLUGIN_SYSTEM_NAME ) ?></h2>

        <p>
            <label for="rob_request_filters">
				<?php _e( 'Any new line read as separate rule.', ROB_Common::PLUGIN_SYSTEM_NAME ) ?><br>
				<?php _e( 'You can use from 1 to all parameters per rule', ROB_Common::PLUGIN_SYSTEM_NAME ) ?><br>
				<?php _e( 'Full rule format is:', ROB_Common::PLUGIN_SYSTEM_NAME ) ?>
                <strong>URL_REGEXP_OR_URL_PART@#$IS_REGEXP_BOOL(true|false)@#$ANSWER_JSON_STRING@#$RESPONSE_FORMAT(object|array|string)</strong><br>
                (<?php _e( 'Separator for parameters is ', ROB_Common::PLUGIN_SYSTEM_NAME ) ?>
                <strong>@#$</strong>,
				<?php _e( 'empty parameter equal to ', ROB_Common::PLUGIN_SYSTEM_NAME ) ?>
                <strong>false</strong>)
            </label>
        </p>

        <textarea name="rob_request_filters" id="rob_request_filters" class="large-text code" rows="10"
                  placeholder="<?php echo $placeholder ?>"><?php esc_html_e( $filters ) ?></textarea>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php _e( 'Save', ROB_Common::PLUGIN_SYSTEM_NAME ) ?>">
        </p>
    </form>
</div> <!-- .wrap -->
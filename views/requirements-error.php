<?php
defined( 'ABSPATH' ) || exit;

/* @var array $errors */
?>
<div class="error">
    <p><?php echo __( ROB_Common::PLUGIN_HUMAN_NAME, ROB_Common::PLUGIN_SYSTEM_NAME ) . ' ' . __( 'error: Your environment does not meet all of the system requirements listed below.', ROB_Common::PLUGIN_SYSTEM_NAME ) ?> </p>

    <ul class="ul-disc">
		<?php foreach ( $errors as $error ): ?>
            <li>
                <strong><?php echo $error ?></strong>
            </li>
		<?php endforeach; ?>
    </ul>

    <p><?php _e( 'If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to the', ROB_Common::PLUGIN_SYSTEM_NAME ) ?>
        <a href="http://codex.wordpress.org/Upgrading_WordPress">Codex</a>.</p>
</div>

<h1><?php esc_html_e('Welcome to alexProperty Plugin','alexproperty'); ?></h1>
<div class="content">
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php
			settings_fields('alexproperty_settings');
			do_settings_sections('alexproperty_settings');
			submit_button();
		?>
	</form>
</div>
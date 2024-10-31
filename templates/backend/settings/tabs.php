<div>
	<h1>Settings</h1>
	<?php if ( ! empty( $args['tabs'] ) ) : ?>
		<div class="nav-tab-wrapper" id="tabs">
			<?php foreach ( $args['tabs'] as $nav_tab ) : ?>
				<a class="<?php echo esc_attr( $nav_tab->get_class() ); ?>"
				   href="<?php echo esc_url( $nav_tab->get_url() ); ?>"
				>
					<?php echo esc_html( $nav_tab->get_label() ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

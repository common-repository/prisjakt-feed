<label for="<?php echo esc_attr( $args['element']->get_id() ); ?>">
	<?php if ( $label = esc_html( $args['element']->get_label() ) ) : ?>
		<?php echo esc_html( $label ); ?>
	<?php endif; ?>
	<input

		<?php echo $args['element']->get_required() ? 'required' : ''; ?>
			max="<?php echo esc_attr( $args['element']->get_max_input_length() ); ?>"
			min="<?php echo esc_attr( $args['element']->get_min_input_length() ); ?>"
			name="<?php echo esc_attr( $args['element']->get_name() ); ?>"
			class="<?php echo esc_attr( $args['element']->get_class() ); ?>"
			value="<?php echo esc_attr( $args['element']->get_value() ); ?>"
			type="text"
	/>
</label>

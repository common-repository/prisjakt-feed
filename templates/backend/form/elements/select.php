<label>
	<select name="<?php echo esc_attr( $args['element']->get_name() ); ?>" <?php echo esc_attr( $args['element']->get_required() ) ? 'required' : ''; ?>>
		<?php foreach ( $args['element']->get_options() as $option_key => $option_value ) : ?>
			<option value="<?php echo esc_attr( $option_key ); ?>" <?php echo selected( esc_attr( $args['element']->get_value() === $option_key ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
		<?php endforeach; ?>
	</select>
</label>

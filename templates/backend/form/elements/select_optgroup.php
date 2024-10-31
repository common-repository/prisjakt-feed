<label>
	<select class="<?php echo esc_attr( $args['element']->get_class() ); ?>"
			name="<?php echo esc_attr( $args['element']->get_name() ); ?>" <?php echo esc_attr( $args['element']->get_required() ) ? 'required' : ''; ?>>
		<option></option>
		<?php foreach ( $args['element']->get_options() as $optgroup_key => $optgroup ) : ?>
			<optgroup label="<?php echo esc_attr( $optgroup['label'] ); ?>">
				<?php foreach ( $optgroup['options'] as $option_key => $option_value ) : ?>
					<option value="<?php echo esc_attr( $option_key ); ?>" <?php echo selected( esc_attr( $args['element']->get_value() === $option_key ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
				<?php endforeach; ?>
			</optgroup>

		<?php endforeach; ?>
	</select>
</label>

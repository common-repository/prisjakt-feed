<?php
$value = esc_attr( $args['element']->get_value() );
$i     = 0;
?>
<?php foreach ( $args['element']->get_radio_buttons() as $radio_button_key => $radio_button_value ) : ?>
	<div>
		<label>
			<input type="radio" name="<?php echo esc_attr( $args['element']->get_name() ); ?>"
				   value="<?php echo esc_attr( $radio_button_key ); ?>"
				<?php
				if ( ! empty( $value ) ) {
					checked( $args['element']->get_value() === $radio_button_key );
				}
				if ( empty( $value ) && ( 0 === $i ) ) {
					checked( true );
				}
				?>
			>
			<?php echo esc_html( $radio_button_value ); ?>
		</label>
	</div>

	<?php $i++; ?>
<?php endforeach; ?>

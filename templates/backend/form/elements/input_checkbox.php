<?php
$checked  = false;
$disabled = false;

if ( $args['element']->get_required_enabled() ) {
	$checked  = true;
	$disabled = true;
} else {
	$checked = esc_attr( $args['element']->get_value() );
}

?>
<label for="<?php echo esc_attr( $args['element']->get_id() ); ?>">
	<?php if ( $label = $args['element']->get_label() ) : ?>
		<?php echo esc_html( $label ); ?>
	<?php endif; ?>
	<input
		<?php echo checked( $checked ); ?>
		<?php echo $disabled ? 'readonly' : ''; ?>
			name="<?php echo esc_attr( $args['element']->get_name() ); ?>"
			class="<?php echo esc_attr( $args['element']->get_class() ); ?>"
			type="checkbox"
	/>
</label>

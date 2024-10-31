<?php if ( $form_title = wp_kses_post( $args['title'] ) ) : ?>
	<h3 class="form-title"><?php echo wp_kses_post( $form_title ); ?></h3>
<?php endif; ?>

<?php if ( $notices = $args['notices'] ) : ?>
	<?php foreach ( $notices as $notice ) : ?>
		<div class="<?php echo esc_attr( $notice->get_class() ); ?>">
			<p><?php echo wp_kses_post( $notice->get_message() ); ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<table class="form-table prisjakt-table" role="presentation">
	<tbody>
	<?php foreach ( $args['rows'] as $row ) : ?>
		<tr>
			<?php foreach ( $row->get_cells() as $cell_index => $cell ) : ?>
				<?php if ( 0 === $cell_index ) : ?>
					<th scope="row"><?php $cell->get_content(); ?></th>
				<?php else : ?>
					<td><?php $cell->get_content(); ?></td>
				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>

	<?php if ( $table_actions = $args['table_actions'] ) : ?>
		<tr class="table-actions">
			<td colspan="<?php echo( count( $args['columns'] ) - 1 ); ?>">
				<?php foreach ( $table_actions as $table_action ) : ?>
					<label>
						<input
								name="<?php echo esc_attr( $table_action->get_name() ); ?>"
								id="<?php echo esc_attr( $table_action->get_id() ); ?>"
								type="<?php echo esc_attr( $table_action->get_type() ); ?>"
								class="<?php echo esc_attr( $table_action->get_class() ); ?>"
								value="<?php echo esc_attr( $table_action->get_label() ); ?>"
							<?php echo wp_kses_post( $table_action->get_data() ); ?>
						/>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
	<?php endif; ?>

	</tbody>
</table>

<div class="tablenav bottom">
	<?php if ( $form_actions = $args['form_actions'] ) : ?>

		<?php foreach ( $form_actions as $form_action ) : ?>
			<label>
				<input
						name="<?php echo esc_attr( $form_action->get_name() ); ?>"
						id="<?php echo esc_attr( $form_action->get_id() ); ?>"
						type="<?php echo esc_attr( $form_action->get_type() ); ?>"
						class="<?php echo esc_attr( $form_action->get_class() ); ?>"
						value="<?php echo esc_attr( $form_action->get_label() ); ?>"
					<?php echo wp_kses_post( $form_action->get_data() ); ?>
				/>
			</label>
		<?php endforeach; ?>

	<?php endif; ?>
</div>

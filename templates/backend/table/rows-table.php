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

<table class="wp-list-table widefat fixed striped table-view-list prisjakt-feed-table">
	<!--Columns-->
	<thead>
	<tr>
		<?php foreach ( $args['columns'] as $column ) : ?>
			<td class="<?php echo esc_attr( $column->get_class() ); ?>">
				<?php echo esc_html( $column->get_label() ); ?>
			</td>
		<?php endforeach; ?>
	</tr>
	</thead>
	<!--End Columns-->

	<tbody>

	<?php if ( $rows = $args['rows'] ) : ?>
		<!--Rows-->

		<?php foreach ( $rows as $row ) : ?>
			<tr>
				<?php foreach ( $row->get_cells() as $cell ) : ?>
					<td><?php $cell->get_content(); ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		<!--End Rows-->

	<?php endif; ?>

	<?php if ( $hidden_rows = $args['hidden_rows'] ) : ?>
		<!--Hidden Rows-->

		<?php foreach ( $args['hidden_rows'] as $hidden_row_index => $hidden_row ) : ?>
			<tr class="hidden-rows action-row action-index-<?php echo esc_attr( $hidden_row_index ); ?>">
				<?php foreach ( $hidden_row->get_cells() as $cell ) : ?>
					<td><?php $cell->get_content(); ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		<!--End Hidden Rows-->

	<?php endif; ?>


	<?php if ( $table_actions = $args['table_actions'] ) : ?>
		<!--Actions-->

		<tr class="table-actions">
			<td colspan="<?php echo esc_attr( $args['columns_count'] ); ?>">
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

		<!--End Actions-->
	<?php endif; ?>

	</tbody>

	<!--Columns-->
	<tfoot>
	<tr>
		<?php foreach ( $args['columns'] as $column ) : ?>
			<td class="<?php echo esc_attr( $column->get_class() ); ?>">
				<?php echo esc_html( $column->get_label() ); ?>
			</td>
		<?php endforeach; ?>
	</tr>
	</tfoot>
	<!--End Columns-->

</table>


<?php if ( $form_actions = $args['form_actions'] ) : ?>
	<!--Form Actions-->
	<div class="tablenav bottom">
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
	</div>
	<!--End Form Actions-->
<?php endif; ?>

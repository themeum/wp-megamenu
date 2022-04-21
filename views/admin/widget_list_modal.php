<div class="wpmm-modal" tabindex="-1" role="dialog">
	<div class="wpmm-modal-dialog" role="document">
		<div class="wpmm-modal-content">
			<div class="wpmm-modal-header wpmm-space-between">
				<h5 class="wpmm-modal-title">Widget List</h5>
				<button type="button" class="close-widget-modal fa fa-close" data-dismiss="wpmm-modal" aria-label="Close">
				</button>
			</div>
			<div class="wpmm-modal-body">
				<div class="wpmm-item-row wpmm-flex-column wpmm-h-100">

					<div class="widget_search"><input id="widget_search_field" class="wpmm-form-control" type="search" placeholder="Search for widgets"></div>

					<div class="wpmm-widget-items">
						<div class="no_item" style="display: none;padding:10px">No Widget found</div>
						<div class="wpmm-item-grid wpmm-gap-1 wpmm-grid-4">
							<!-- <pre> -->
							<?php

							// Widgets Manager
							$widgets_manager = new wp_megamenu_widgets();
							$widgets         = $widgets_manager->get_all_registered_widget();
							// global $wp_registered_widgets;

							// print_r($wp_registered_widgets);

							// wp_list_widgets();
							?>
								<?php
								if ( count( $widgets ) ) {
									foreach ( $widgets as $key => $value ) {
										print_r( $value );
										echo '<div class="widget-list-item" data-widget-id-base="' . esc_attr( $value['id_base'] ) . '" data-type="outsideWidget"> ' . esc_html( $value['name'] ) . '</div>';
									}
								}
								?>
							<!-- </pre> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

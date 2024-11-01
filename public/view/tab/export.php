<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<br>
<div class="tabs-content">
	<form method="post" id="export_order_form">

		<input type="hidden" name="settings[post_type]"
		value="shop_order">
		<div id="my-left" style="float: left; width: 49%; max-width: 500px;">
				<div id="date-filter" class="filter-block">
					<div style="display: inline;">
						<span class="filter-block-header"><?php _e( 'Date range', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></span>
						<input type=text class='date' name="settings[from_date]" id="from_date" value=''>
						<?php _e( 'to', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
						<input type=text class='date' name="settings[to_date]" id="to_date"    value=''>
					</div>
					<br>
				</div>
				<div id="my-sort" class="filter-block line-height__3">
					<span class="filter-block-header"><?php _e( 'Sort orders by', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></span>
					<?php
					$sort = array(
						'order_id'      => __( 'Order ID', 'vgp-import-export-for-autolynk-in-woocommerce' ),
						'post_date'     => __( 'Order Date', 'vgp-import-export-for-autolynk-in-woocommerce' ),
						'post_modified' => __( 'Modification Date', 'vgp-import-export-for-autolynk-in-woocommerce' ),
						'post_status'   => __( 'Order status', 'vgp-import-export-for-autolynk-in-woocommerce' ),
					);
					?>
					<select name="settings[sort]">
						<?php
						foreach ( $sort as $value => $text ) :
							?>
															 <option value='<?php echo $value; ?>' 
									  <?php
										echo selected(
											@$settings['sort'],
											$value
										)
										?>
							 ><?php echo $text; ?></option>
						<?php endforeach; ?>
					</select>
					<select name="settings[sort_direction]">
						<option value='DESC' 
						<?php
						echo selected(
							@$settings['sort_direction'],
							'DESC'
						)
						?>
						 ><?php _e( 'Descending', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>                            <option value='ASC' 
						<?php
						echo selected(
							@$settings['sort_direction'],
							'ASC'
						)
						?>
						 ><?php _e( 'Ascending', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></option>
					</select>
				</div>
		</div>

		<div id="my-right" style="float: left; width: 48%; margin: 0px 10px; max-width: 500px;">
			<div class="filter-block">
				<span class="filter-block-header"><?php _e( 'Filter by order status', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
				<div id="my-order">
						<select id="statuses" class="select2-i18n" name="settings[statuses][]" multiple="multiple"
						style="width: 100%; max-width: 25%;">
						<?php
						foreach (
						apply_filters( 'waei_export_order_statuses', wc_get_order_statuses() ) as $i => $status
						) {
							?>
							<option value="<?php echo $i; ?>" 
													  <?php
														if ( $i == 'wc-processing' ) {
															echo 'selected';
														}
														?>
										   ><?php echo $status; ?></option>
						<?php } ?>
						</select>
				</div>
			</div>
			<div class="filter-block">
				<span class="filter-block-header"><?php _e( 'Filter by Products', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
				<div id="my-product">
					<select id="products" class="select2-i18n" data-select2-i18n-ajax-method="get_woei_products"
						name="settings[products][]" multiple="multiple"
						style="width: 100%; max-width: 25%;">
					</select>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<br>
		<p class="submit">
			<input type="submit" id='export-btn' class="button button-primary"
			value="<?php _e( 'Export', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>"/>
		</p>

	</form>
	<iframe id='export_csv_frame' width=0 height=0 style='display:none'></iframe>
</div>

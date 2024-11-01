<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="tabs-content" id="import-tab">
	<form method="post" id="import_order_form" enctype="multipart/form-data" >
		<div id="my-sort" class="filter-block">
			<span class="filter-block-header"><?php _e( 'Upload Order CSV', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></span>
			<div class="import-form-section">
				<input type="file" name="order_csv" accept=".csv" id="order_csv"/> 
				<input type="hidden" name="action" value="woei_order_exporter">
				<input type="hidden" name="method" value="import_csv">
				<input type="hidden" name="waei_nonce" value="" id="waei_nonce">
				<input type="hidden" name="tab" value="" id="tab">
				<p class="submit">
					<input type="submit" id='import-btn' class="button button-primary"
					value="<?php _e( 'Import', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>"/>
				</p> 
			</div>
			<div class="form-group" id="process" style="display:none;">
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
					<div class="progress-count"><span id="process_data">0</span> - <span id="total_data">0</span></div>
				</div>
			</div>
		</div>
	</form>
	<form method="post" id="tracking_form">
		<div id="my-import-url" class="filter-block csv-import-url-section">
			<div>
				<span class="filter-block-header"><?php _e( 'Tracking URL', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
				<br/>
				<span class="sample-url">
					<b><?php _e( 'Sample URL: ', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></b>
					<?php _e( 'Sample URL: https://track.AnPost.ie/TrackingResults.aspx?rtt=1&items=##TRACK-NO##', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
				<?php $tracking_url = get_option( 'woei_tracking_url' ); ?>
				<div class="import-text">
					<input type="text" name="tracking_url" value="<?php echo esc_url( $tracking_url ); ?>" required>
				</div>
				<span class="note">
					<b><?php _e( 'Note: ', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></b>
					<?php _e( 'Use ##TRACK-NO## for Tracking No', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
			</div>
			<br/>
			<br/>
			<div class="customer-note-section" id="customer-note">
				<span class="filter-block-header"><?php _e( 'Customer Note', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></span>
				<br/>
				<span class="note">
					<b><?php _e( 'Note: ', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?></b>
					<?php _e( 'Use ##URL## for Tracking URL and Use ##TRACK-NO## for Tracking No', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>
				</span>
				<br/>
				<div class="customer-note-textbox">
					<?php
					// $tracking_note = get_option('woei_customer_tracking_note', false);
					?>
					<?php
					$editor_id     = 'customer_note';
					$tracking_note = get_option( 'woei_customer_tracking_note', false );

					wp_editor(
						$tracking_note,
						$editor_id,
						array(
							'textarea_name' => 'customer_note',
							'textarea_rows' => 10,
							'media_buttons' => false,
						)
					);
					?>
				</div>
			</div>
			<p class="submit">
				<input type="submit" id='track-btn' class="button button-primary"
				value="<?php _e( 'Save', 'vgp-import-export-for-autolynk-in-woocommerce' ); ?>"/>
			</p>
			
		</div>
	</form>
</div>

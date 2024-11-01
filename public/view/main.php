<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if ( isset( $_REQUEST['save'] ) ) : ?>
	<div class="update-nag"
		 style="color: #008000; border-left: 4px solid green; display: block; width: 70%;">
		 <?php
			_e(
				'Settings saved',
				'vgp-import-export-for-autolynk-in-woocommerce'
			)
			?>
			</div>
<?php endif; ?>
<h2 class="nav-tab-wrapper" id="tabs">
	  <a class="nav-tab <?php echo $active_tab == 'export' ? 'nav-tab-active' : ''; ?>"
	   href="<?php echo admin_url( 'admin.php?page=wc-autolynk-order-export&tab=export' ); ?>">
		Export
	</a>
	<a class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>"
	   href="<?php echo admin_url( 'admin.php?page=wc-autolynk-order-export&tab=import' ); ?>">
		Import
	</a>
</h2>

<script>
  var waei_ajaxurl = "<?php echo $ajaxurl; ?>"
  var waei_nonce = "<?php echo wp_create_nonce( 'waei_nonce' ); ?>"
  var waei_active_tab = "<?php echo $active_tab; ?>"
</script>

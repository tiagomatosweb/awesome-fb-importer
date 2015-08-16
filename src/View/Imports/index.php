<?php
	if (isset($_POST) AND isset($_POST['type_import'])) {
		if ($_POST['type_import'] == "url") {
			$import = new afbi_ImportsController();
			$import->url($_POST);
		}
	}
?>
<div class="wrap">
	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	<p>Welcome to the Awesome Facebook Importer plugin.</p>
	<p>Please, provide the follow data to allow us to connect to your Facebook Page.</p>

	<form method="post" action="admin.php?page=afbi_import" novalidate="novalidate">
		<?php $opt = get_option('afbiplugin_options'); ?>
		<input name="type_import" type="hidden" value="url">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="afbi_fb_id">Facebook Page ID</label></th>
					<td>
						<input value="<?php echo $opt['afbi_fb_id']; ?>" type="text" name="afbi_fb_id" id="afbi_fb_id" class="regular-text">
					</td>
				</tr>

				<tr>
					<th><label for="afbi_fb_access_token">Facebook Page Access Token</label></th>
					<td>
						<input value="<?php echo $opt['afbi_fb_access_token']; ?>" type="text" name="afbi_fb_access_token" id="afbi_fb_access_token" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>
		<button class="button-primary">Import</button>
	</form>
</div>
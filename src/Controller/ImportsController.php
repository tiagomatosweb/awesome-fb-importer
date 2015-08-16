<?php

class afbi_ImportsController extends afbi
{
	public function url($post) {
		if (!empty($post['afbi_fb_id']) AND !empty($post['afbi_fb_access_token'])) {
			$url = "https://graph.facebook.com/{$post['afbi_fb_id']}/feed?access_token={$post['afbi_fb_access_token']}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			curl_close($ch);


			if (!empty($result)) {
				$json = json_decode($result, false);

				if (isset($json->error)) {
					$adminNotice = array(
						'class' => 'error',
						'msg' => $json->error->message
					);
				} else {
					if ($this->saveData($json->data)) {
						$adminNotice = array(
							'class' => 'updated',
							'msg' => "Your data has been imported!"
						);
					} else {
						$adminNotice = array(
							'class' => 'error',
							'msg' => "Sorry, it could not import the data!"
						);
					}
				}

			}
		}
		$this->saveOptions($post);
		include (AFBI_PATH . 'src/View/Error/admin_notice.php');
	}


	public function saveData($data) {
		global $wpdb;

		$wp_post = null;
		foreach ($data as $k => $v) {
			$post_content = filter_var($v->message, FILTER_SANITIZE_STRING);
			$post_title = filter_var($v->name, FILTER_SANITIZE_STRING);
			$post_name = sanitize_title($post_title);
			$post_date = new DateTime($v->created_time);
			$post_modified = new DateTime($v->updated_time);

			$wp_post .= '(1, "'.$post_date->format('Y-m-d H:i:s').'", "'.$post_modified->format('Y-m-d H:i:s').'", "'.mysql_real_escape_string($post_title).'", "'.mysql_real_escape_string($post_title).'", "publish", "'.mysql_real_escape_string($post_name).'"),';
		}

		$wp_post = substr($wp_post, 0, -1).";";
		$q = $wpdb->query("INSERT INTO $wpdb->posts (`post_author`, `post_date`, `post_modified`, `post_content`, `post_title`, `post_status`, `post_name`) VALUES $wp_post");

		if ($q) {
			return true;
		} else {
			return false;
		}
	}

	public function saveOptions($post) {
		unset($post['type_import']);
		$s = serialize($post);

		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` = 'afbiplugin_options'");
		$wpdb->query("INSERT INTO $wpdb->options (`option_name`, `option_value`, `autoload`) VALUES ('afbiplugin_options', '$s', 'yes')");
	}
}
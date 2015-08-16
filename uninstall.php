<?php

if(!defined('WP_UNINSTALL_PLUGIN')) exit();
    global $wpdb;
	delete_option('afbiplugin_options');
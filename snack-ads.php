<?php
/*
Plugin Name: Snack Ads
Description: Allows to display Ad units managed by Snack Media Ad Ops team remotely
Version: 2.1.1
Author: Petr Paboucek -aka- BoUk
Author URI: https://wpadvisor.co.uk
Text Domain: snack-ads
*/

if ( ! defined( 'ABSPATH' ) ) 
    exit;

define( 'SNACK_ADS_PLUGIN_PATH', 	plugin_dir_path( __FILE__ ) );
define( 'SNACK_ADS_PLUGIN_URL', 	plugin_dir_url( __FILE__ ) );

/**
 * Use upload folder per sites basis for Multisite
 * Config should be defined on theme level e.g. functions.php rather than wp-config.php for MU installs
 */
$snackUploadDir = wp_upload_dir();
define( 'SNACK_ADS_UPLOAD_PATH', 	$snackUploadDir['basedir'] . '/snack-ads');

/**
 * Load required files
 */
require SNACK_ADS_PLUGIN_PATH . "vendor/autoload.php";

$model 			= new \Snack\Ads\Models\wpModel();
$controller 	= new \Snack\Ads\Controllers\wpController( $model );

/**
 * Making sure reccuring cron event is set
 */
add_action( 'init',	[ 
				$controller, 
				'scheduleCronEvent' 
			]);

/**
 * Make sure setup is correct
 */
add_action( 'admin_notices', [
				$controller,
				'adminNotices' 
			]);


/**
 * Clean up after deactivation
 */
register_deactivation_hook( __FILE__, [ 
				$controller, 
				'deactivate' 
			]);

/**
 * Remove re-occuring cron event
 */
add_action( 'snack_ads_plugin_deactivate', [
				$controller, 
				'removeCronEvents' 
			]);

/**
 * Check for the latest config and download (hourly)
 */
add_action( $model::RECCURING_EVENT_NAME, [ 
				$controller, 
				'downloadAds' 
			]);

/**
 * Anothe cron event for updating ads. This is single_event though which is scheduled 
 * via REST API call allowing adops to immediatelly trigger update.
 */
add_action( $model::SINGLE_EVENT_NAME, [ 
				$controller, 
				'downloadAds' 
			]);

$restController = new \Snack\Ads\Controllers\restController();

/**
 * Create custom REST endpoint to allow trigger update
 */
add_action( 'rest_api_init', [ 
				$restController, 
				'registerRoutes'
			]);

/**
 * Instantiate ads if config is available
 */
add_action( 'init', function()
{
	if ( defined( 'SNACK_ADS_CONFIG_NAME' ) )
	{
		$snackAds = \Snack\Ads\Helpers\snackAdverts::getInstance(); 
		$snackAds->readSettings( SNACK_ADS_CONFIG_NAME ); 
	}
});
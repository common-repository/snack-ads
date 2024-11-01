<?php

namespace Snack\Ads\Controllers;

/**
 * 
 */
class wpController
{

	/**
	 * [$model description]
	 * @var [type]
	 */
	protected $model;

	/**
	 * [__construct description]
	 * @param \Snack\Ads\Models\wpModel $model [description]
	 */
	public function __construct( \Snack\Ads\Models\wpModel $model )
	{
		$this->model 	= $model;
	}

	/**
	 * [scheduleCronEvent description]
	 * @return [type] [description]
	 */
	public function scheduleCronEvent()
	{
		if ( ! wp_next_scheduled( $this->model::RECCURING_EVENT_NAME ) ) 
		{
    		$offset = rand( 0, HOUR_IN_SECONDS );
    		wp_schedule_event( time() + $offset, 'hourly', $this->model::RECCURING_EVENT_NAME );
		}
	}

	/**
	 * Action executed on plugin deactivation
	 * @return [type] [description]
	 */
	public function deactivate()
	{
		do_action( 'snack_ads_plugin_deactivate' );
	}

	/**
	 * [removeCronEvents description]
	 * @return [type] [description]
	 */
	public function removeCronEvents()
	{
		$timestamp = wp_next_scheduled( $this->model::RECCURING_EVENT_NAME );
		wp_unschedule_event( $timestamp, $this->model::RECCURING_EVENT_NAME );
	}

	/**
	 * [downloadAds description]
	 * @return [type] [description]
	 */
	public function downloadAds()
	{
		// Make sure config is set. For configs defined outside of wp-config.php
		$this->model->setConfig( SNACK_ADS_CONFIG_NAME );

		// Try to download config from remote location
		$response = $this->model->getRemoteConfig();

		if ( is_wp_error( $response ) ) 
			return;

		$responseCode = wp_remote_retrieve_response_code( $response );
		
		// Check if file was sucesfully downloaded
		if ( $responseCode !=  200 )
			return;

		// Unzip file
		if ( ! isset( $response['filename'] ) )
			return;

		$unzipResult = $this->model->extractRemoteConfig( $response['filename'] );
		
		if ( is_wp_error( $unzipResult ) )
			return;

		// Remove all definitions 
    	$this->model->removeDirectory( SNACK_ADS_UPLOAD_PATH );

    	// Rename tmp location
    	$this->model->moveDirectory( SNACK_ADS_UPLOAD_PATH . '_tmp', SNACK_ADS_UPLOAD_PATH );
	}

	/**
	 * [adminNotices description]
	 * @return [type] [description]
	 */
	public function adminNotices()
	{
		if ( ! defined( 'SNACK_ADS_CONFIG_NAME' ) )
		{
		?>
			<div class="notice notice-error is-dismissible">
        		<p>Please make sure you define SNACK_ADS_CONFIG_NAME constant in wp-config.php</p>
    		</div>
    	<?php
		}
	}

}
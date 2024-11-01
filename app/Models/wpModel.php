<?php

namespace Snack\Ads\Models;

/**
 * 
 */
class wpModel
{	
	/**
	 * Define name of cron recurring event
	 */
	const RECCURING_EVENT_NAME 	= 'snack_ads_generate';

	/**
	 * Define name of single cron event
	 */
	const SINGLE_EVENT_NAME 	= 'snack_ads_force_update';

	/**
	 * 
	 */
	const ADS_REMOTE_STORAGE 	= 'https://ads.snack-projects.co.uk';

	/**
	 * 
	 */
	public $site_config;

	/**
	 * [setConfig description]
	 * @param [type] $config [description]
	 */
	public function setConfig( $config )
	{
		$this->site_config = $config;
	}

	/**
	 * [getRemoteConfig description]
	 * @return [type] [description]
	 */
	public function getRemoteConfig()
	{
		if ( ! is_dir( SNACK_ADS_UPLOAD_PATH ) )
			$this->createDirectory( SNACK_ADS_UPLOAD_PATH );

		$response = wp_remote_get(
			$this::ADS_REMOTE_STORAGE . '/' . $this->site_config . '.zip',
		    [
		        'timeout'     	=> 20,
		        'stream' 		=> true,
    			'filename' 		=> SNACK_ADS_UPLOAD_PATH . '/' . $this->site_config . '.zip'
		    ]);

		return $response;
	}

	/**
	 * [extractRemoteConfig description]
	 * @param  [type] $source [description]
	 * @return [type]         [description]
	 */
	public function extractRemoteConfig( $source )
	{
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) )
			require_once ( ABSPATH . '/wp-admin/includes/file.php' );

    	WP_Filesystem();

		return unzip_file( $source, SNACK_ADS_UPLOAD_PATH . '_tmp' );
	}

	/**
	 * [deleteFile description]
	 * @param  [type] $source [description]
	 * @return [type]         [description]
	 */
	public function deleteFile( $source )
	{
		wp_delete_file( $source );
	}

	/**
	 * [createDirectory description]
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function createDirectory( $path )
	{
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    	
    	$filesystem = new \WP_Filesystem_Direct( new \StdClass() );
    	return $filesystem->mkdir( $path );
	}

	/**
	 * [removeDirectory description]
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function removeDirectory( $path )
	{
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    	
    	$filesystem = new \WP_Filesystem_Direct( new \StdClass() );
    	return $filesystem->rmdir( $path, true );
	}

	/**
	 * [removeDirectory description]
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function moveDirectory( $source, $destination )
	{
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    	
    	$filesystem = new \WP_Filesystem_Direct( new \StdClass() );
    	return $filesystem->move( $source, $destination, true );
	}
}
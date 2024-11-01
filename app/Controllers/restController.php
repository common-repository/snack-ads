<?php

namespace Snack\Ads\Controllers;

/**
 * 
 */
class restController
{
	/**
	 * [$version description]
	 * @var string
	 */
	protected $version = 'v1';

	/**
	 * [$namespace description]
	 * @var string
	 */
	protected $namespace = 'snack/ads';

	/**
	 * [registerRoutes description]
	 * @return [type] [description]
	 */
	public function registerRoutes() 
	{		
		register_rest_route( 
			$this->namespace . '/' . $this->version, 
			'/update', 			
      		[
        		'methods'				=> \WP_REST_Server::CREATABLE,
        		'callback'				=> [ $this, 'scheduleUpdate' ],
        		'permission_callback'   => '__return_true'   		
      		]
		);     
	}

	/**
	 * [create_item description]
	 * @param  [type] $request [description]
	 * @return [type]          [description]
	 */
	public function scheduleUpdate( $request )
	{
		$response = rest_ensure_response( null );
		$status   = 409;   		

		if ( ! wp_next_scheduled( 'snack_ads_force_update' ) ) 
		{    		
    		if ( wp_schedule_single_event( time(), 'snack_ads_force_update' ) )
    			$status = 201;    				    			    		
    	}

		$response->set_status( $status );
		$response->set_data( $data );
        
        return $response;       
	}
}
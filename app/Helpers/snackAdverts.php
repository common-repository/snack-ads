<?php

namespace Snack\Ads\Helpers;

/**
 * 
 */
class snackAdverts
{
	/**
	 * [$instance description]
	 * @var [type]
	 */
	private static $instance;

	/**
	 * [$settings description]
	 * @var [type]
	 */
	private $settings;

    /**
     * [__construct description]
     */
    private function __construct()
    {
    }

    /**
     * [readSettings description]
     * @param  [type] $domain [description]
     * @return [type]         [description]
     */
    public function readSettings( $domain )
    {
    	$settings 	= [];
    	$target 	= SNACK_ADS_UPLOAD_PATH . '/' . $domain;
    	
    	if ( ! is_dir( $target ) )
    		return false;

    	$currentDir = scandir( $target );

    	// Build Array of directories
    	foreach( $currentDir as $key => $name )
    	{
    		if ( in_array( $name , [ ".", ".."] ) )
    			continue;

    		if ( is_dir( $target . '/' . $name ) )
    			$settings[$name] = '';
    	}

    	// Read content of each file in directory and use filename as key
    	foreach( $settings as $subdir => $value )
    	{
    		$settings[$subdir] = [];

    		if ( $handle = opendir( $target . '/' . $subdir ) )
    		{
    			while ( false !== ( $file = readdir( $handle ))) 
    			{
    				if ( in_array( $file, [ ".", ".."] ) )
    					continue;
    				
    				$fileParts 	= pathinfo( $target . '/' . $subdir . '/' .$file );
    				$settings[$subdir][$fileParts['filename']]	= file_get_contents( $target . '/' . $subdir . '/' .$file );
    			}

    			closedir($handle);
    		}
    	}

    	$this->settings = $settings;
    }

    /**
     * Get Instance of the class
     * 
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * [getCode description]
     * @param  [type] $type [description]
     * @param  [type] $slot [description]
     * @return [type]       [description]
     */
    public function getCode( $type, $slot )
    {
    	if ( isset( $this->settings[$type][$slot] ) )
    		return $this->settings[$type][$slot];

    	return false;
    }

    /**
     * [printCode description]
     * @param  [type] $type [description]
     * @param  [type] $slot [description]
     * @return [type]       [description]
     */
    public function printCode( $type, $slot )
    {
        if ( isset( $this->settings[$type][$slot] ) )
            echo $this->settings[$type][$slot];

        return false;
    }
}
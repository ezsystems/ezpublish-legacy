<?php
/**
 * File containing ezpRestDebugHandler class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
final class ezpRestDebugHandler
{
    /**
     * Debug handler instance
     * @var ezpRestDebugHandler
     */
    private static $instance;
    
    /**
     * @var eZINI
     */
    private $restINI;
    
    /**
     * eZDebug instance
     * @var eZDebug
     */
    private $debug;
    
    /**
     * Private constructor
     */
    private function __construct()
    {
        $this->restINI = eZINI::instance( 'rest.ini' );
        $this->debug = eZDebug::instance();
    }

    /**
     * Singleton method
     * @return ezpRestDebugHandler
     */
    public static function instance()
    {
        if ( !self::$instance instanceof ezpRestDebugHandler )
        {
            self::$instance = new ezpRestDebugHandler();
        }
        
        return self::$instance;
    }
    
    /**
     * Checks if debug is enabled (locally in rest.ini and globally in site.ini)
     * @return bool
     */
    public function isDebugEnabled()
    {
        $isEnabled = false;
        $globalDebugEnabled = eZINI::instance()->variable( 'DebugSettings', 'DebugOutput' ) === 'enabled';
        $localDebugEnabled = $this->restINI->variable( 'DebugSettings', 'Debug' ) === 'enabled';
        
        if( $globalDebugEnabled && $localDebugEnabled )
            $isEnabled = true;
            
        return $isEnabled;
    }
}
?>

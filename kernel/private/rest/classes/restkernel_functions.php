<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
if ( !function_exists( 'eZUpdateDebugSettings' ) )
{
    function eZUpdateDebugSettings()
    {
        $ini = eZINI::instance();
        $debugSettings = array( 'debug-enabled' => false );
        $logList = $ini->variable( 'DebugSettings', 'AlwaysLog' );
        $logMap = array(
            'notice' => eZDebug::LEVEL_NOTICE,
            'warning' => eZDebug::LEVEL_WARNING,
            'error' => eZDebug::LEVEL_ERROR,
            'debug' => eZDebug::LEVEL_DEBUG,
            'strict' => eZDebug::LEVEL_STRICT
        );
        $debugSettings['always-log'] = array();
        foreach ( $logMap as $name => $level )
        {
            $debugSettings['always-log'][$level] = in_array( $name, $logList );
        }

        eZDebug::updateSettings( $debugSettings );
    }
}

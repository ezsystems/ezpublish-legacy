<?php
/**
 * File containing the ezpKernelTreeMenu class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

class ezpKernelTreeMenu implements ezpKernelHandler
{
    /**
     * Indicates is request has been properly initialized
     *
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * Hash of internal settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Current siteaccess data
     *
     * @var array
     */
    protected $access;

    /**
     * @var eZURI
     */
    protected $uri;

    public function __construct( array $settings = array() )
    {
        if ( isset( $settings['injected-settings'] ) )
        {
            $injectedSettings = array();
            foreach ( $settings["injected-settings"] as $keySetting => $injectedSetting )
            {
                list( $file, $section, $setting ) = explode( "/", $keySetting );
                $injectedSettings[$file][$section][$setting] = $injectedSetting;
            }
            // those settings override anything else in local .ini files and
            // their overrides
            eZINI::injectSettings( $injectedSettings );
        }
        $this->settings = $settings + array(
            'use-cache-headers'         => true,
            'max-age'                   => 86400,
            'siteaccess'                => null,
            'use-exceptions'            => false
        );
        unset( $settings );

        require_once __DIR__ . '/treemenu_functions.php';
        $this->setUseExceptions( $this->settings['use-exceptions'] );

        header( 'X-Powered-By: ' . eZPublishSDK::EDITION . ' (index_treemenu)' );
        if ( $this->settings['use-cache-headers'] === true )
        {
            define( 'MAX_AGE', $this->settings['max-age'] );
            if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
            {
                header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );
                header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
                header( 'Cache-Control: max-age=' . MAX_AGE );
                header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
                header( 'Pragma: ' );
                exit();
            }
        }

        // Tweaks ini filetime checks if not defined!
        // This makes ini system not check modified time so
        // that index_treemenu.php can assume that index.php does
        // this regular enough, set in config.php to override.
        if ( !defined('EZP_INI_FILEMTIME_CHECK') )
        {
            define( 'EZP_INI_FILEMTIME_CHECK', false );
        }

        eZExecution::addFatalErrorHandler(
            function ()
            {
                header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
            }
        );
        eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

        // Trick to get eZSys working with a script other than index.php (while index.php still used in generated URLs):
        $_SERVER['SCRIPT_FILENAME'] = str_replace( '/index_treemenu.php', '/index.php', $_SERVER['SCRIPT_FILENAME'] );
        $_SERVER['PHP_SELF'] = str_replace( '/index_treemenu.php', '/index.php', $_SERVER['PHP_SELF'] );

        $ini = eZINI::instance();
        $timezone = $ini->variable( 'TimeZoneSettings', 'TimeZone' );
        if ( $timezone )
        {
            putenv( "TZ=$timezone" );
        }

        // init uri code
        $GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );
        eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true' );
        $this->uri = eZURI::instance( eZSys::requestURI() );

        $GLOBALS['eZRequestedURI'] = $this->uri;

        // Check for extension
        eZExtension::activateExtensions( 'default' );

        // load siteaccess
        // Use injected siteaccess if available or match it internally.
        $this->access = isset( $this->settings['siteaccess'] ) ?
            $this->settings['siteaccess'] :
            eZSiteAccess::match(
                $this->uri,
                eZSys::hostname(),
                eZSys::serverPort(),
                eZSys::indexFile()
            )
        ;
        eZSiteAccess::change( $this->access );

        // Check for new extension loaded by siteaccess
        eZExtension::activateExtensions( 'access' );
    }

    /**
     * Initializes the session. If running, through Symfony the session
     * parameters from Symfony override the session parameter from eZ Publish.
     */
    protected function sessionInit()
    {
        if ( !isset( $this->settings['session'] ) || !$this->settings['session']['configured'] )
        {
            // running without Symfony2 or session is not configured
            // we keep the historic behaviour
            $ini = eZINI::instance();
            if ( $ini->variable( 'Session', 'ForceStart' ) === 'enabled' )
                eZSession::start();
            else
                eZSession::lazyStart();
        }
        else
        {
            $sfHandler = new ezpSessionHandlerSymfony(
                $this->settings['session']['has_previous']
                || $this->settings['session']['started']
            );
            $sfHandler->setStorage( $this->settings['session']['storage'] );
            eZSession::init(
                $this->settings['session']['name'],
                $this->settings['session']['started'],
                $this->settings['session']['namespace'],
                $sfHandler
            );
        }

        // let session specify if db is required
        $this->siteBasics['db-required'] = eZSession::getHandlerInstance()->dbRequired();
    }

    /**
     * Execution point for controller actions.
     * Returns false if not supported
     *
     * @return ezpKernelResult
     */
    public function run()
    {
        $db = eZDB::instance();
        if ( $db->isConnected() )
        {
            $this->sessionInit();
        }
        else
        {
            return $this->exitWithInternalError(
                ezpI18n::tr( 'kernel/content/treemenu', 'Database is not connected' )
            );
        }

        $moduleINI = eZINI::instance( 'module.ini' );
        $globalModuleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );
        eZModule::setGlobalPathList( $globalModuleRepositories );

        $module = eZModule::exists( 'content' );
        if ( !$module )
        {
            return $this->exitWithInternalError(
                ezpI18n::tr( 'kernel/content/treemenu', '"content" module could not be found.' )
            );
        }

        $function_name = 'treemenu';
        $this->uri->increase();
        $this->uri->increase();

        $currentUser = eZUser::currentUser();
        $siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );
        $hasAccessToSite = false;
        if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
        {
            $policyChecked = false;
            foreach ( $siteAccessResult['policies'] as $policy )
            {
                if ( isset( $policy['SiteAccess'] ) )
                {
                    $policyChecked = true;
                    $crc32AccessName = eZSys::ezcrc32( $this->access[ 'name' ] );
                    if ( in_array( $crc32AccessName, $policy['SiteAccess'] ) )
                    {
                        $hasAccessToSite = true;
                        break;
                    }
                }
                if ( $hasAccessToSite )
                {
                    break;
                }
            }
            if ( !$policyChecked )
            {
                $hasAccessToSite = true;
            }
        }
        else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
        {
            $hasAccessToSite = true;
        }

        if ( !$hasAccessToSite )
        {
            return $this->exitWithInternalError(
                ezpI18n::tr( 'kernel/content/treemenu', 'Insufficient permissions to display the treemenu.' ),
                403
            );
        }

        $GLOBALS['eZRequestedModule'] = $module;

        $content = $module->run(
            $function_name,
            $this->uri->elements( false ),
            false,
            array(
                 'use-cache-headers' => $this->settings['use-cache-headers']
            )
        );
        $attributes = isset( $content['lastModified'] ) ? array( 'lastModified' => $content['lastModified'] ) : array();
        $this->shutdown();
        return new ezpKernelResult( $content['content'], $attributes );
    }

    /**
     * Not supported by ezpKernelTreeMenu
     *
     * @throws \RuntimeException
     */
    public function runCallback( \Closure $callback, $postReinitialize = true )
    {
        throw new \RuntimeException( 'runCallback() method is not supported by ezpKernelTreeMenu' );
    }

    /**
     * Sets whether to use exceptions inside the kernel.
     *
     * @param bool $useExceptions
     */
    public function setUseExceptions( $useExceptions )
    {
        eZModule::$useExceptions = (bool)$useExceptions;
    }

    /**
     * Reinitializes the kernel environment.
     *
     * @return void
     */
    public function reInitialize()
    {
        $this->isInitialized = false;
    }

    /**
     * Runs the shutdown process
     */
    protected function shutdown( $reInitialize = true )
    {
        eZExecution::cleanup();
        eZExecution::setCleanExit();
        if ( $reInitialize )
            $this->isInitialized = false;
    }

    /**
     * Handles an internal error.
     * If not using exceptions, will return an ezpKernelResult object with JSON encoded message.
     *
     * @param string $errorMessage
     * @param int $errorCode
     * @return ezpKernelResult
     *
     * @throws RuntimeException
     * @throws ezpAccessDenied
     *
     * @see setUseExceptions()
     */
    protected function exitWithInternalError( $errorMessage, $errorCode = 500 )
    {
        if ( eZModule::$useExceptions )
        {
            switch ( $errorCode )
            {
                case 403:
                    throw new ezpAccessDenied( $errorMessage );

                case 500:
                default:
                    throw new RuntimeException( $errorMessage, $errorCode );
            }
        }
        else
        {
            header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
            eZExecution::cleanup();
            eZExecution::setCleanExit();

            return new ezpKernelResult(
                json_encode(
                    array(
                         'error'        => $errorMessage,
                         'code'         => $errorCode
                    )
                )
            );
        }
    }

    /**
     * Checks whether the kernel handler has the Symfony service container
     * container or not.
     *
     * @return bool
     */
    public function hasServiceContainer()
    {
        return false;
    }

    /**
     * Returns the Symfony service container if it has been injected,
     * otherwise returns null.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface|null
     */
    public function getServiceContainer()
    {
        return;
    }
}

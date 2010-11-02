<?php
/**
 * File containing the eZTemplate class.
 *
 * @package
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 *
 * <code>
 * </code>
 *
 * @package
 * @version //autogen//
 */

class eZTemplate
{
    const RESOURCE_FETCH = 1;
    const RESOURCE_QUERY = 2;

    const ELEMENT_TEXT = 1;
    const ELEMENT_SINGLE_TAG = 2;
    const ELEMENT_NORMAL_TAG = 3;
    const ELEMENT_END_TAG = 4;
    const ELEMENT_VARIABLE = 5;
    const ELEMENT_COMMENT = 6;

    const NODE_ROOT = 1;
    const NODE_TEXT = 2;
    const NODE_VARIABLE = 3;
    const NODE_FUNCTION = 4;
    const NODE_OPERATOR = 5;


    const NODE_INTERNAL = 100;
    const NODE_INTERNAL_CODE_PIECE = 101;

    const NODE_INTERNAL_VARIABLE_SET = 105;
    const NODE_INTERNAL_VARIABLE_UNSET = 102;

    const NODE_INTERNAL_NAMESPACE_CHANGE = 103;
    const NODE_INTERNAL_NAMESPACE_RESTORE = 104;

    const NODE_INTERNAL_WARNING = 120;
    const NODE_INTERNAL_ERROR = 121;

    const NODE_INTERNAL_RESOURCE_ACQUISITION = 140;
    const NODE_OPTIMIZED_RESOURCE_ACQUISITION = 141;

    const NODE_INTERNAL_OUTPUT_ASSIGN = 150;
    const NODE_INTERNAL_OUTPUT_READ = 151;
    const NODE_INTERNAL_OUTPUT_INCREASE = 152;
    const NODE_INTERNAL_OUTPUT_DECREASE = 153;

    const NODE_INTERNAL_OUTPUT_SPACING_INCREASE = 160;
    const NODE_INTERNAL_SPACING_DECREASE = 161;

    const NODE_OPTIMIZED_INIT = 201;


    const NODE_USER_CUSTOM = 1000;


    const TYPE_VOID = 0;
    const TYPE_STRING = 1;
    const TYPE_NUMERIC = 2;
    const TYPE_IDENTIFIER = 3;
    const TYPE_VARIABLE = 4;
    const TYPE_ATTRIBUTE = 5;
    const TYPE_OPERATOR = 6;
    const TYPE_BOOLEAN = 7;
    const TYPE_ARRAY = 8;
    const TYPE_DYNAMIC_ARRAY = 9;

    const TYPE_INTERNAL = 100;
    const TYPE_INTERNAL_CODE_PIECE = 101;
    const TYPE_PHP_VARIABLE = 102;

    const TYPE_OPTIMIZED_NODE = 201;
    const TYPE_OPTIMIZED_ARRAY_LOOKUP = 202;
    const TYPE_OPTIMIZED_CONTENT_CALL = 203;
    const TYPE_OPTIMIZED_ATTRIBUTE_LOOKUP = 204;

    const TYPE_INTERNAL_STOP = 999;


    const TYPE_STRING_BIT = 1;
    const TYPE_NUMERIC_BIT = 2;
    const TYPE_IDENTIFIER_BIT = 4;
    const TYPE_VARIABLE_BIT = 8;
    const TYPE_ATTRIBUTE_BIT = 16;
    const TYPE_OPERATOR_BIT = 32;

    const TYPE_NONE = 0;

    const TYPE_ALL = 63;

    const TYPE_BASIC = 47;

    const TYPE_MODIFIER_MASK = 48;

    const NAMESPACE_SCOPE_GLOBAL = 1;
    const NAMESPACE_SCOPE_LOCAL = 2;
    const NAMESPACE_SCOPE_RELATIVE = 3;

    const DEBUG_INTERNALS = false;

    const FILE_ERRORS = 1;

    private static $instance = null;
    private static $factory = false;
    public $oldTpl;
    private $newTpl;

    public function __construct()
    {
        $this->oldTpl = OldeZTemplate::instance();
        $this->newTpl = new ezpDebugTemplate(); 
    }

    public function __clone()
    {
        $this->oldTpl = clone $this->oldTpl;
        $this->newTpl = clone $this->newTpl;
    }

    /*!
     \deprecated Using one global instance is not valid in eZ publish 4.x anymore
                 since the template component requires a new instance for each processing.
                 Instead clone an existing instance.
     */
    public static function instance()
    {
        if( self::$instance === null )
        {

            self::$instance = new eZTemplate();
        }

        return self::$instance;
    }

    public function setVariable($name, $value, $namespace = "")
    {
        $this->oldTpl->setVariable($name, $value, $namespace);

        if ($value === null)
        {
            echo "The variable '$name' is 'null'. This value is changed to 'false' in the new template engine.";
            $value = false;
        }

        if ( $namespace != "" )
        {
            $name = $namespace . "_" . $name;
        }

        #print "SET VARIABLE: $name<br/>";
        $this->newTpl->send->$name = $value;
    }

    public function setVariableRef($name, &$value, $namespace = "")
    {
        $this->oldTpl->setVariableRef($name, $value, $namespace);

        if ( $namespace != "" )
        {
            $name = $namespace . "_" . $name;
        }

        print "SET VARIABLE REF: $name<br/>";
        $this->newTpl->send->$name = $value;
    }

    public function hasVariable($var, $namespace = "", $attrs = array())
    {
        $name = $var;
        if ( $namespace != "" )
        {
            $name = $namespace . "_" . $name;
        }
        return isset( $this->newTpl->receive->$name ) || $this->oldTpl->hasVariable($var, $namespace, $attrs);
    }


    public function fetch($templateName)
    {
/*        // Always execute templates.
        try
        {
            return $this->newTpl->process( $templateName );
        } 
        catch( ezcTemplateException $e)
        {
            print "<pre>";
            print $e->getMessage();
            print "</pre>";
            exit();
        }

        return;
/**/
        $this->setVariable( 'global', ezpGlobals::instance() );

        # Try the new template engine, if the template exists.
        

        $returnIt = false;
        $resourceData = $this->oldTpl->loadURIRoot( $templateName, true, $returnIt);
        $templateFile = $resourceData["template-filename"];
        $newTemplateFile = "new_templates/" . $templateFile;
        if( !file_exists($newTemplateFile) ||
            filemtime( $templateFile ) > filemtime( $newTemplateFile ) )
        {
            // Convert updated or missing templates
            $retval = 10; 
            // @TODO: Update for matterhorn patch
            system("../upgrade -v -A --no-update-runtime $templateFile 2>&1", $retval);

            if( $retval != 0)
            {
                throw new Exception("Could not update template file: $templateFile");
            }
        }

        if( file_exists($newTemplateFile) )
        {
            try
            {
                ezpDebugTemplate::$errorTemplates[] = array();
                $retval = $this->newTpl->process( $templateName );
                array_pop( ezpDebugTemplate::$errorTemplates );
                return $retval;
            } 
            catch( ezcTemplateException $e)
            {
                $errorTemplates = ezpDebugTemplate::$errorTemplates[count(ezpDebugTemplate::$errorTemplates) - 1];
                if ( ezpTemplateFunctions::isStrictMode() )
                {
                    $e->errorTemplates = $errorTemplates;
                    throw $e;
                }

                echo "<pre>";
                echo "ezcTemplate failed with ", get_class( $e ), ":\n";
                echo $e->getMessage();
                echo "</pre>";
                foreach ( $errorTemplates as $errorTemplate )
                {
                    if ( preg_match( "#^new_templates/(.*)$#", $errorTemplate, $matches ) ||
                         preg_match( "#^" . realpath( "." ) . "/new_templates/(.*)$#", $errorTemplate, $matches ) )
                    {
                        if ( file_exists( $matches[1] ) )
                            touch( $matches[1] );
                        $errorlog = ".ezpneo.tplerror.log";
                        if ( basename( realpath( "." ) ) == '.run' )
                        {
                            if ( file_exists( "../" . $matches[1] ) ) // Touch original file if we are running in a .run dir
                                touch( "../" . $matches[1] );
                            $errorlog = "../" . $errorlog;
                        }
                        $fd = fopen( $errorlog, "a" );
                        fwrite( $fd, $matches[1] . "\n" );
                    }
                }
                array_pop( ezpDebugTemplate::$errorTemplates );
            }
        }

        print ("[Old tpl: $templateName ($templateFile)]");
        $resourceData = $this->oldTpl->fetch($templateName, false, true);
        return ezpDebugTemplate::visualDebug( $resourceData["result_text"], $resourceData["template-filename"], "ez3: " );
        /**/
    }

    function setAutoloadPathList( $pathList )
    {
        return $this->oldTpl->setAutoloadPathList($pathList);
    }

    /*!
     Looks trough the pathes specified in autoloadPathList() and fetches autoload
     definition files used for autoloading functions and operators.
    */
    function autoload()
    {
        return $this->oldTpl->autoload();
    }

    function registerResource( $res )
    {
        return $this->oldTpl->registerResource( $res);
    }

    static function isDebugEnabled()
    {
        return OldeZTemplate::isDebugEnabled();
    }

    /**
     * Added in matterhorn patch.
     *
     */
    public static function setIsDebugEnabled( $debug )
    {
        OldeZTemplate::setIsDebugEnabled( $debug );
    }

    function isCachingAllowed()
    {
        return $this->oldTpl->isCachingAllowed();
    }

    function ini()
    {
        return $this->oldTpl->ini();
    }

    static function isTemplatesUsageStatisticsEnabled()
    {
        return OldeZTemplate::isTemplatesUsageStatisticsEnabled();
    }

    function processURI( $uri, $displayErrors = true, &$extraParameters, &$textElements, $rootNamespace, $currentNamespace )
    {
        return $this->oldTpl->processURI($uri, $displayErrors, $extraParameters, $textElements, $rootNamespace, $currentNamespace);
    }
 
    function unsetVariable( $var, $namespace = "" )
    {
        return $this->oldTpl->unsetVariable( $var, $namespace);
    }

    function variable( $var, $namespace = "", $attrs = array() )
    {
        $name = $var;
        if ( $namespace != "" )
        {
            $name = $namespace . "_" . $name;
        }
        if ( isset( $this->newTpl->receive->$name ) )
            return $this->newTpl->receive->$name;
        return $this->oldTpl->variable($var, $namespace, $attrs);
    }
 
    function templateFetchList()
    {
        return $this->oldTpl->templateFetchList();
    }

    function compileTemplateFile( $file, $returnResourceData = false )
    {
        return $this->oldTpl->compileTemplateFile($file, $returnResourceData);
    }

    /**
     * Copied over from OldeZTemplate, not part of original template patch
     *
     * Returns a shared instance of the eZTemplate class with
     * default settings applied, like:
     * - Autoload operators loaded
     * - Debug mode set
     * - eZTemplateDesignResource::instance registered
     *
     * @since 4.3
     * @return eZTemplate
     */
    public static function factory()
    {
        if ( self::$factory === false )
        {
            $instance = self::instance();

            $ini = eZINI::instance();
            if ( $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled' )
                eZTemplate::setIsDebugEnabled( true );

            $compatAutoLoadPath = $ini->variableArray( 'TemplateSettings', 'AutoloadPath' );
            $autoLoadPathList   = $ini->variable( 'TemplateSettings', 'AutoloadPathList' );

            $extensionAutoloadPath = $ini->variable( 'TemplateSettings', 'ExtensionAutoloadPath' );
            $extensionPathList     = eZExtension::expandedPathList( $extensionAutoloadPath, 'autoloads/' );

            $autoLoadPathList = array_unique( array_merge( $compatAutoLoadPath, $autoLoadPathList, $extensionPathList ) );

            $instance->setAutoloadPathList( $autoLoadPathList );
            $instance->autoload();

            $instance->registerResource( eZTemplateDesignResource::instance() );
            self::$factory = true;
        }
        return clone self::instance();
    }

}

?>

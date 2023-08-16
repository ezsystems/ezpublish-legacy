<?php
/**
 * File containing the eZWorkflowType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZWorkflowType does
/*!

*/

class eZWorkflowType
{
    public $Attributes;
    const STATUS_NONE = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DEFERRED_TO_CRON = 3;
    const STATUS_DEFERRED_TO_CRON_REPEAT = 4;
    const STATUS_RUN_SUB_EVENT = 5;
    const STATUS_WORKFLOW_CANCELLED = 6;
    const STATUS_FETCH_TEMPLATE = 7;
    const STATUS_FETCH_TEMPLATE_REPEAT = 8;
    const STATUS_REDIRECT = 10;
    const STATUS_WORKFLOW_DONE = 9;
    const STATUS_REDIRECT_REPEAT = 11;
    const STATUS_WORKFLOW_RESET = 12;

    /**
     * Constructor
     *
     * @param string $group
     * @param string $type
     * @param string $groupName
     * @param string $name
     */
    public function __construct( $group, $type, $groupName, $name )
    {
        $this->Group = $group;
        $this->Type = $type;
        $this->TypeString = $group . "_" . $type;
        $this->GroupName = $groupName;
        $this->Name = $name;
        $this->Information = "";
        $this->ActivationDate = false;
        $this->Attributes = array();
        $this->Attributes["group"] =& $this->Group;
        $this->Attributes["type"] =& $this->Type;
        $this->Attributes["type_string"] =& $this->TypeString;
        $this->Attributes["group_name"] =& $this->GroupName;
        $this->Attributes["name"] =& $this->Name;
        $this->Attributes["information"] =& $this->Information;
        $this->Attributes["activation_date"] =& $this->ActivationDate;
    }

    /**
     * @deprecated Use eZWorkflowType::__construct() instead
     * @param string $group
     * @param string $type
     * @param string $groupName
     * @param string $name
     */
    public function eZWorkflowType( $group, $type, $groupName, $name )
    {
        self::__construct( $group, $type, $groupName, $name );
    }

    static function statusName( $status )
    {
        $statusNameMap = self::statusNameMap();
        if ( isset( $statusNameMap[$status] ) )
            return $statusNameMap[$status];
        return false;
    }

    static function createType( $typeString )
    {
        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( !isset( $types[$typeString] ) )
        {
            $result = eZWorkflowType::loadAndRegisterType( $typeString );
            if ( $result === false )
                return null;
        }

        if ( isset( $types[$typeString] ) )
        {
            $class_name = $types[$typeString]["class_name"];

            if ( !isset( $GLOBALS["eZWorkflowTypeObjects"][$typeString] ) )
            {
                if ( class_exists( $class_name ) )
                {
                    $GLOBALS["eZWorkflowTypeObjects"][$typeString] = new $class_name();
                }
                else
                {
                    eZDebug::writeError( "Undefined event type class: $class_name", __METHOD__ );
                }
            }
            return $GLOBALS["eZWorkflowTypeObjects"][$typeString];
        }
        else
        {
            eZDebug::writeError( "Undefined type: $typeString", __METHOD__ );
        }
        return null;
    }

    static function fetchRegisteredTypes()
    {
        eZWorkflowType::loadAndRegisterAllTypes();
        $types = $GLOBALS["eZWorkflowTypes"];
        if ( is_array( $types ) )
        {
            foreach ( $types as $typeString => $type_def )
            {
                $class_name = $type_def["class_name"];
                $def =& $definition_objects[$typeString];
                if ( !isset( $GLOBALS["eZWorkflowTypeObjects"][$typeString] ) )
                {
                    if ( class_exists( $class_name ) )
                    {
                        $GLOBALS["eZWorkflowTypeObjects"][$typeString] = new $class_name();
                    }
                    else
                    {
                        eZDebug::writeError( "Undefined event type class: $class_name", __METHOD__ );
                    }
                }
            }
        }
        return $GLOBALS["eZWorkflowTypeObjects"];
    }

    static function allowedTypes()
    {
        if ( !isset( $GLOBALS["eZWorkflowAllowedTypes"] ) ||
             !is_array( $GLOBALS["eZWorkflowAllowedTypes"] ) )
        {
            $wfINI = eZINI::instance( 'workflow.ini' );
            $eventTypes = $wfINI->variable( "EventSettings", "AvailableEventTypes" );
            $GLOBALS["eZWorkflowAllowedTypes"] = array_unique( $eventTypes );
        }
        return $GLOBALS["eZWorkflowAllowedTypes"];
    }

    static function loadAndRegisterAllTypes()
    {
        $allowedTypes = eZWorkflowType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZWorkflowType::loadAndRegisterType( $type );
        }
    }

    static function registerType( $group, $type, $class_name )
    {
        $typeString = $group . "_" . $type;
        if ( !isset( $GLOBALS["eZWorkflowTypes"] ) || !is_array( $GLOBALS["eZWorkflowTypes"] ) )
        {
            $GLOBALS["eZWorkflowTypes"] = array();
        }
        if ( isset( $GLOBALS["eZWorkflowTypes"][$typeString] ) )
        {
            eZDebug::writeError( "Type already registered: $typeString", __METHOD__ );
        }
        else
        {
            $GLOBALS["eZWorkflowTypes"][$typeString] = array( "class_name" => $class_name );
        }
    }

    static function loadAndRegisterType( $typeString )
    {
        $typeElements = explode( "_", $typeString );
        if ( count( $typeElements ) < 2 )
        {
            eZDebug::writeError( "Workflow type not found: $typeString", __METHOD__ );
            return false;
        }

        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( isset( $types[ $typeString ] ) and
             isset( $types[ $typeString ][ 'class_name' ] ) and
             class_exists( $types[ $typeString ][ 'class_name' ] ) )
        {
            return true;
        }

        $group = $typeElements[0];
        $type = $typeElements[1];

        $baseDirectory = eZExtension::baseDirectory();
        $wfINI = eZINI::instance( 'workflow.ini' );
        $repositoryDirectories = $wfINI->variable( 'EventSettings', 'RepositoryDirectories' );
        $extensionDirectories = $wfINI->variable( 'EventSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/eventtypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$group/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                include_once( $includeFile );
                return true;
            }
        }

        eZDebug::writeError( "Workflow type not found: $typeString, searched in these directories: " . implode( ', ', $repositoryDirectories ), __METHOD__ );
        return false;
    }


    function attributes()
    {
        return array_merge( array( 'description',
                                   'allowed_triggers' ),
                            array_keys( $this->Attributes ) );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'description':
            {
                return $this->eventDescription();
            } break;

            case 'allowed_triggers':
            {
                return $this->TriggerTypes;
            } break;

            default:
            {
                if ( isset( $this->Attributes[$attr] ) )
                    return $this->Attributes[$attr];
            } break;
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function setAttribute( $attr, $value )
    {
        if ( array_key_exists( $attr, $this->Attributes ) )
            $this->Attributes[$attr] = $value;
    }

    /*!
     Set trigger types.

     \param allowed trigger types format :
              array( <module> => array( <function> => array( <event> ) ) )
            if all is allowed,
              array( '*' => true )
     */
    function setTriggerTypes( $allowedTypes )
    {
        $this->TriggerTypes = $allowedTypes;
    }

    function eventDescription()
    {
        return $this->Attributes["name"];
    }

    function execute( $process, $event )
    {
        return eZWorkflowType::STATUS_NONE;
    }

    function initializeEvent( $event )
    {
    }

    function validateHTTPInput( $http, $base, $event, &$validation )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function fixupHTTPInput( $http, $base, $event )
    {
        return true;
    }

    function fetchHTTPInput( $http, $base, $event )
    {
    }

    function setActivationDate( $date )
    {
        $this->ActivationDate = $date;
    }

    function setInformation( $inf )
    {
        $this->Information = $inf;
    }

    function needCleanup()
    {
        return false;
    }

    function cleanupAfterRemoving( $attr = array() )
    {
    }

    function cleanup( $process, $event )
    {
    }

    function attributeDecoder( $event, $attr )
    {
        return null;
    }

    function typeFunctionalAttributes( )
    {
        return array();
    }

    function customWorkflowEventHTTPAction( $http, $action, $workflowEvent )
    {
    }

    function workflowEventContent( $event )
    {
        return "";
    }
    function storeEventData( $event, $version )
    {
    }
    function storeDefinedEventData( $event )
    {
    }
    /*!
     Check if specified trigger is allowed

      \param $moduleName module name
      \param $functionName function name
      \param $connectType connection type

     \return true is allowed, false if not.
    */
    function isAllowed( $moduleName, $functionName, $connectType )
    {
        if ( isset( $this->TriggerTypes['*'] ) )
        {
            return true;
        }
        else if ( isset( $this->TriggerTypes[$moduleName] ) )
        {
            if ( isset( $this->TriggerTypes[$moduleName][$functionName] ) )
            {
                if ( in_array( $connectType, $this->TriggerTypes[$moduleName][$functionName] ) )
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get status name map.
     *
     * @return array Status name map
     */
    static function statusNameMap()
    {
        return array( self::STATUS_NONE => ezpI18n::tr( 'kernel/classes', 'No state yet' ),
                      self::STATUS_ACCEPTED => ezpI18n::tr( 'kernel/classes', 'Accepted event' ),
                      self::STATUS_REJECTED => ezpI18n::tr( 'kernel/classes', 'Rejected event' ),
                      self::STATUS_DEFERRED_TO_CRON => ezpI18n::tr( 'kernel/classes', 'Event deferred to cron job' ),
                      self::STATUS_DEFERRED_TO_CRON_REPEAT => ezpI18n::tr( 'kernel/classes', 'Event deferred to cron job, event will be rerun' ),
                      self::STATUS_RUN_SUB_EVENT => ezpI18n::tr( 'kernel/classes', 'Event runs a sub event' ),
                      self::STATUS_WORKFLOW_CANCELLED => ezpI18n::tr( 'kernel/classes', 'Canceled whole workflow' ),
                      self::STATUS_WORKFLOW_RESET => ezpI18n::tr( 'kernel/classes', 'Workflow was reset for reuse' ) );
    }

    /// \privatesection
    public $Group;
    public $Type;
    public $TypeString;
    public $GroupName;
    public $Name;
    public $ActivationDate;
    public $Information;
    public $TriggerTypes = array( '*' => true );
}

?>

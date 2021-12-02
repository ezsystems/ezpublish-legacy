<?php
/**
 * File containing the eZURLAlias class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZURLAliasML ezurlaliasml.php
  \brief Handles URL aliases in eZ Publish

  URL aliases are different names for existing URLs in eZ Publish.
  Using URL aliases allows for having better looking urls on the webpage
  as well as having fixed URLs pointing to various locations.

  This class handles storing, fetching, moving and subtree updates on
  eZ Publish URL aliases, this performed using methods from eZPersistentObject.

  The table used to store path information is designed to keep each element in
  the path (separated by /) in one row, ie. not the entire path.
  Each row uses the *parent* field to say which element is the parent of the current one,
  a value of 0 means a top-level path element.
  The system also supports path elemens in multiple languages, each language
  is stored in separate rows but with the same path element ID, the exception is
  when the text of multiple languages are the same then they will simply share the
  same row.

  Instead of manipulating path elements directly it is recommended to use one
  the higher level methods for fetching or storing a path.

  For objects the methods getChildren() and getPath() can be used to fetch the child elements and path string.

  Typically you will not have a path element object and should use on of these static functions:

  - storePath() - Stores a given path with specified action, all parent are created if they don't exist.
  - fetchByPath() - Fetch path elements by path string, some wildcard support is also available.
  - translate() - Translate requested path string into the internal path.

  For more detailed path element handling these static methods are available:

  - fetchByAction() - Fetch a path element based on the action.
  - fetchByParentID() - Fetch path elements based on parent ID.
  - fetchPathByActionList() - Fetch path string based on action values, this is more optimized than getPath().

  - setLangMaskAlwaysAvailable() - Updates language mask for path elements based on actions.

  Most of these methods have some common arguments, they can be:
  - $maskLanguages - If true then only elements which matches the currently prioritized languaes is processed.
  - $onlyPrioritized - If true then only the top prioritized language of the elements is considered. Requires $maskLanguages to be set to true.
  - $includeRedirections - If true then elements which redirects to this is also processed.

*/

class eZURLAliasML extends eZPersistentObject
{
    // Return values from storePath()
    const LINK_ID_NOT_FOUND = 1;
    const LINK_ID_WRONG_ACTION = 2;
    const LINK_ALREADY_TAKEN = 3;
    const ACTION_INVALID = 51;
    const DB_ERROR = 101;

    /**
     * Optionally computed path string for this element, used for caching purposes.
     *
     * @var string
     */
    public $Path;

    /**
     * @var int
     */
    public $ID;

    /**
     * @var int
     */
    public $Parent;

    /**
     * @var int
     */
    public $Link;

    /**
     * @var string
     */
    public $Text;

    /**
     * @var string
     */
    public $TextMD5;

    /**
     * @var int
     */
    public $LangMask;

    /**
     * @var string
     */
    public $Action;

    /**
     * @var string
     */
    public $ActionType;

    /**
     * @var int
     */
    public $AliasRedirects;

    /**
     * @var int
     */
    public $IsAlias;

    /**
     * @var bool
     */
    public $IsOriginal;

    /**
     * @var string|null
     */
    private static $charset = null;

    public function __construct( $row )
    {
        parent::__construct( $row );
        $this->Path = null;
        if ( isset( $row['path'] ) )
        {
            $this->Path = $row['path'];
        }
    }

    static public function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "parent" => array( 'name' => 'Parent',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "lang_mask" => array( 'name' => 'LangMask',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "text" => array( 'name' => 'Text',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "text_md5" => array( 'name' => 'TextMD5',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "action" => array( 'name' => 'Action',
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true ),
                                         "action_type" => array( 'name' => 'ActionType',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "link" => array( 'name' => 'Link',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ),
                                         "is_alias" => array( 'name' => 'IsAlias',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "is_original" => array( 'name' => 'IsOriginal',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "alias_redirects" => array( 'name' => 'AliasRedirects',
                                                                     'datatype' => 'integer',
                                                                     'default' => 1,
                                                                     'required' => true ) ),
                      "keys" => array( "parent", "text_md5" ),
                      "function_attributes" => array( "children" => "getChildren",
                                                      "path" => "getPath" ),
                      "class_name" => "eZURLAliasML",
                      "name" => "ezurlalias_ml" );
        return $definition;
    }

    /*!
     Unicode-aware strtolower, performs the conversion by using eZCharTransform
     */
    static function strtolower( $text )
    {
        //We need to detect our internal charset
        if ( self::$charset === null )
        {
            self::$charset = eZTextCodec::internalCharset();
        }

        return mb_strtolower( $text, self::$charset );
    }

    /*!
     Converts the action property into a real url which responds to the
     module/view on the site.
     */
    function actionURL()
    {
        return eZURLAliasML::actionToUrl( $this->Action );
    }

    /*!
     Creates a new path element with given arguments, MD5 sum is automatically created.

     \param $element The text string for the path element.
     \param $action  Action string.
     \param $parentID ID of parent path element.
     \param $language ID or mask of languages
     \param $languageName Name of language(s), comma separated
     */
    static function create( $element, $action, $parentID, $language )
    {
        $row = array( 'text'      => $element,
                      'text_md5'  => md5( eZURLAliasML::strtolower( $element ) ),
                      'parent'    => $parentID,
                      'lang_mask' => $language,
                      'action'    => $action );
        return new eZURLAliasML( $row );
    }

    /*!
     Overrides the default behaviour to automatically update TextMD5.
     */
    function setAttribute( $name, $value )
    {
        parent::setAttribute( $name, $value );
        if ( $name == 'text' )
        {
            $this->TextMD5 = md5( eZURLAliasML::strtolower( $value ) );
        }
        else if ( $name == 'action' )
        {
            $this->ActionType = null;
        }
    }

    /*!
     Generates the md5 for the alias and stores the values.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store( $fieldFilters = null )
    {
        if ( $this->ID === null )
        {
            $this->ID = self::getNewID();
        }
        if ( $this->Link === null )
        {
            $this->Link = $this->ID;
        }
        if ( $this->TextMD5 === null )
        {
            $this->TextMD5 = md5( eZURLAliasML::strtolower( $this->Text ) );
        }
        $this->IsOriginal = ($this->ID == $this->Link) ? 1 : 0;
        if ( $this->IsAlias )
            $this->IsOriginal = true;
        if ( $this->Action == "nop:" ) // nop entries can always be replaced
            $this->IsOriginal = false;
        if ( strlen( $this->ActionType ) == 0 )
        {
            if ( preg_match( "#^(.+):#", $this->Action, $matches ) )
                $this->ActionType = $matches[1];
            else
                $this->ActionType = 'nop';
        }

        parent::store( $fieldFilters );
    }

    /*!
     \static
     Removes all path elements which matches the action name $actionName and value $actionValue.
     */
    static public function removeByAction( $actionName, $actionValue )
    {
        // If this is an original element we must get rid of all elements which points to it.
        $db = eZDB::instance();
        $actionStr = $db->escapeString( $actionName . ':' . $actionValue );
        $query = "DELETE FROM ezurlalias_ml WHERE action = '{$actionStr}'";
        $db->query( $query );
    }

    /*!
     \static
     Removes a URL-Alias which has parent $parentID, MD5 text $textMD5 and language $language.
     If the entry has only the specified language and there are existing children the entry will be disabled instead of removed.
     If the entry has other languages other than the one which was specified the language bit is removed.

     \param $parentID ID of the parent element
     \param $textMD5  MD5 of the lowercase version of the text, see eZURLAliasML::strtolower().
     \param $language The language entry to remove, can be a string with the locale or a language object (eZContentLanguage).
     */
    public static function removeSingleEntry( $parentID, $textMD5, $language )
    {
        $parentID = (int)$parentID;
        if ( !is_object( $language ) )
            $language = eZContentLanguage::fetchByLocale( $language );
        $languageID = (int)$language->attribute( 'id' );
        $db = eZDB::instance();

        $bitDel   = $db->bitAnd( 'lang_mask' ,  (~$languageID) );
        $bitMatch = $db->bitAnd( 'lang_mask', $languageID ) . ' > 0';
        $bitMask  = $db->bitAnd( 'lang_mask', ~1 );


        // Fetch data for the given entry
        $rows = $db->arrayQuery( "SELECT * FROM ezurlalias_ml WHERE parent = {$parentID} AND text_md5 = '" . $db->escapeString( $textMD5 ) . "' AND $bitMatch" );
        if ( count( $rows ) == 0 )
            return false;

        $id   = (int)$rows[0]['id'];
        $mask = (int)$rows[0]['lang_mask'];
        if ( ($mask & ~($languageID | 1)) == 0 )
        {
            // No more languages for this entry so we need to check for children
            $childRows = $db->arrayQuery( "SELECT * FROM ezurlalias_ml WHERE parent = {$id}" );
            if ( count( $childRows ) > 0 )
            {
                // Turn entry into a nop: to disable it
                $element = new eZURLAliasML( $rows[0] );
                $element->LangMask = 1;
                $element->Action = "nop:";
                $element->ActionType = "nop";
                $element->IsAlias = 0;
                $element->store();
                return;
            }
        }
        // Remove language bit from selected entries and remove entries which have no languages.
        $db->query( "UPDATE ezurlalias_ml SET lang_mask = $bitDel WHERE parent = {$parentID} AND text_md5 = '" . $db->escapeString( $textMD5 ) . "' AND $bitMatch" );
        $db->query( "DELETE FROM ezurlalias_ml WHERE parent = {$parentID} AND text_md5 = '" . $db->escapeString( $textMD5 ) . "' AND $bitMask = 0" );
    }

    /*!
     Finds all the children of the current element.

     For more control over the list use fetchByParentID().
     */
    function getChildren()
    {
        return eZURLAliasML::fetchByParentID( $this->ID, true, true, false );
    }

    /*!
     Calculates the full path for the current item and returns it.

     \param $locale The locale for which a path should be calculated.
     \param $incomingLanguageList Array of locale codes representing the prioritized site language list.

     \note If you know the action values of the path use fetchPathByActionList() instead, it is more optimized.
     \note The calculated path is cached in $Path.
     */
    function getPath( $locale = null, $incomingLanguageList = null )
    {
        if ( $this->Path !== null )
            return $this->Path;

        // Fetch path 'text' elements of correct parent path
        $path = array( $this->Text );
        $id = (int)$this->Parent;
        $db = eZDB::instance();
        while ( $id != 0 )
        {
            $query = "SELECT parent, lang_mask, text FROM ezurlalias_ml WHERE id={$id}";
            if ( $locale !== null && is_string( $locale ) )
            {
                // We also want to consider the prioritized language list for the
                // destination siteaccess, so that untranslated objects, are not
                // disregarded from the URL.
                if ( $incomingLanguageList !== null )
                {
                    eZContentLanguage::setPrioritizedLanguages( $incomingLanguageList );
                }

                $langMask = trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) );
                $query .= " AND ({$langMask})";
            }
            $rows = $db->arrayQuery( $query );

            if ( count( $rows ) == 0 )
            {
                if ( $incomingLanguageList !== null )
                {
                    eZContentLanguage::clearPrioritizedLanguages();
                }
                break;
            }
            $result = eZURLAliasML::choosePrioritizedRow( $rows );

            if ( $incomingLanguageList !== null )
            {
                eZContentLanguage::clearPrioritizedLanguages();
            }

            if ( !$result )
            {
                $result = $rows[0];
            }
            $id = (int)$result['parent'];
            array_unshift( $path, $result['text'] );
        }
        $this->Path = implode( '/', $path );
        return $this->Path;
    }

    /*!
     \static
     Stores the full path $path to point to action $action, any missing parents are created as placeholders (ie. nop:).

     Returns an array containing the entry 'status' which is the status code, is \c true if all went well, a number otherwise (see class constants).
     Will contain 'path' for succesful creation or if the path already exists.

     \param $path String containing full path, leading and trailing slashes are stripped.
     \param $action Action string for entry.
     \param $languageName The language to use for entry, can be a string (locale code, e.g. 'nor-NO') an eZContentLanguage object or false for the top prioritized language.
     \param $linkID Numeric ID for link field, if it is set to false the entry will point to itself. Use this for redirections. Use \c true if you want to create an link/alias which points to a module (ie. no entry in urlalias table).
     \param $alwaysAvailable If true the entry will be available in any language.
     \param $rootID ID of the parent element to start at, use 0/false for the very top.
     \param $cleanupElements If true each element in the path will be cleaned up according to the current URL transformation rules.
     \param $autoAdjustName If true it will adjust the name until it is unique in the path. Used together with $linkID.
     \param $reportErrors If true it will report found errors using eZDebug, if \c false errors are only return in 'status'.
     \param $aliasRedirects If true and an alias is being stored it will redirect (using HTTP 301) to it's destination.
     */
    static function storePath( $path, $action,
                        $languageName = false, $linkID = false, $alwaysAvailable = false, $rootID = false,
                        $cleanupElements = true, $autoAdjustName = false, $reportErrors = true, $aliasRedirects = true )
    {
        $path = eZURLAliasML::cleanURL( $path );
        if ( $languageName === false )
        {
            $languageName = eZContentLanguage::topPriorityLanguage();
        }
        if ( is_object( $languageName ) )
        {
            $languageObj  = $languageName;
            $languageID   = (int)$languageName->attribute( 'id' );
            $languageName = $languageName->attribute( 'locale' );
        }
        else
        {
            $languageObj = eZContentLanguage::fetchByLocale( $languageName );
            $languageID  = (int)$languageObj->attribute( 'id' );
        }
        $languageMask = $languageID;
        if ( $alwaysAvailable )
            $languageMask |= 1;

        $path = eZURLAliasML::cleanURL( $path );
        $elements = explode( '/', $path );

        $db = eZDB::instance();
        $parentID = 0;

        // If the root ID is specified we will start the parent search from that
        if ( $rootID !== false )
        {
            $parentID = $rootID;
        }
        $i = 0;
        // Top element is handled separately.
        $topElement = array_pop( $elements );
        // Find correct parent, and create missing ones if necessary
        $createdPath = array();
        foreach ( $elements as $element )
        {
            $actionStr = $db->escapeString( $action );
            if ( $cleanupElements )
                $element = eZURLAliasML::convertToAlias( $element, 'noname' . (count($createdPath)+1) );
            $elementStr = $db->escapeString( eZURLAliasML::strtolower( $element ) );

            $query = "SELECT * FROM ezurlalias_ml WHERE text_md5 = " . eZURLAliasML::md5( $db, $elementStr, false ) . " AND parent = {$parentID}";
            $rows = $db->arrayQuery( $query );
            if ( count( $rows ) == 0 )
            {
                // Create a fake element to ensure we have a parent
                $elementObj = eZURLAliasML::create( $element, "nop:", $parentID, 1 );
                $elementObj->store();
                $parentID = (int)$elementObj->attribute( 'id' );
            }
            else
            {
                $parentID = (int)$rows[0]['link'];
            }
            $createdPath[] = $element;

            ++$i;
        }
        if ( $parentID != 0 )
        {
            $sql = "SELECT text, parent FROM ezurlalias_ml WHERE id = {$parentID}";
            $rows = $db->arrayQuery( $sql );
            if ( count( $rows ) > 0 )
            {
                // A special case. If the special entry with empty text is used as parent
                // the parent must be adjust to 0 (ie. real top level).
                if ( strlen( $rows[0]['text'] ) == 0 && $rows[0]['parent'] == 0 )
                {
                    $createdPath = array();
                    $parentID = 0;
                }
            }
        }

        if ( !preg_match( "#^(.+):(.+)$#", $action, $matches ) )
        {
            return array( 'status' => self::ACTION_INVALID,
                          'error_message' => "The action value " . var_export( $action, true ) . " is invalid",
                          'error_number' => self::ACTION_INVALID,
                          'path'    => null,
                          'element' => null );
        }
        $actionName  = $matches[1];
        $actionValue = $matches[2];
        $existingElementID = null;
        $alwaysMask = $alwaysAvailable ? 1 : 0;

        $actionStr = $db->escapeString( $action );
        $actionTypeStr = $db->escapeString( $actionName );

        $createdElement = null;
        if ( $linkID === false )
        {
            if ( $cleanupElements )
                $topElement = eZURLAliasML::convertToAlias( $topElement, 'noname' . (count($createdPath)+1) );

            $adjustName = false;
            $curElementID = null;
            $newElementID = null;
            $newText = $topElement;
            $uniqueCounter = 0;

            // Loop until we a valid entry point, which means:
            // 1. The entry does not exist yet, so create a new one
            // 2. The entry exists but is re-usable (e.g. nop or same action)
            // 3. The entry exists and cannot be re-used, instead the name is adjusted to be unique.
            while ( true )
            {
                $newText = $topElement;
                if ( $uniqueCounter > 0 )
                    $newText .= ($uniqueCounter + 1);
                $textMD5 = eZURLAliasML::md5( $db, $newText );

                $query = "SELECT * FROM ezurlalias_ml WHERE parent = $parentID AND text_md5 = {$textMD5}";
                $rows = $db->arrayQuery( $query );
                if ( count( $rows ) == 0 )
                {
                    // No such entry, create a new one
                    break;
                }

                $row = $rows[0];
                $curID = (int)$row['id'];
                $curAction = $row['action'];
                if ( $curAction == 'nop:' || $curAction == $action || $row['is_original'] == 0 )
                {
                    // We can reuse the element so record the ID
                    $curElementID = $curID;
                    $newElementID = $curID;
                    break;
                }

                if ( !$autoAdjustName )
                {
                    if ( $reportErrors )
                        eZDebug::writeError( "Tried to store path '{$path}' but the path already exists (ID: {$curID}) but with action '{$curAction}', the new action was '{$action}'" );
                    return array( 'status' => self::LINK_ALREADY_TAKEN,
                                  'path'    => $path,
                                  'element' => null );
                }
                // Need to adjust name, re-iterate
                ++$uniqueCounter;
            }
            $textEsc = $db->escapeString( $newText );

            // See if there is already a node in the same level with the same action
            if ( $newElementID === null )
            {
                $query = "SELECT * FROM ezurlalias_ml " .
                         "WHERE parent = $parentID AND action = '{$actionStr}' AND is_original = 1 AND is_alias = 0";
                $rows = $db->arrayQuery( $query );
                if ( count( $rows ) > 0 )
                {
                    $newElementID = (int)$rows[0]['id'];
                }
            }

            // Create or update the element
            if ( $curElementID !== null )
            {
                // Check if an already existing entry at the same level exists, with a different id
                // if so the id must be updated.
                $query = "SELECT * FROM ezurlalias_ml " .
                         "WHERE parent = $parentID AND action = '{$actionStr}' AND is_original = 1 AND is_alias = 0";
                $rows = $db->arrayQuery( $query );
                if ( count( $rows ) > 0 )
                {
                    $existingEntryId = (int)$rows[0]['id'];

                    if ( $existingEntryId != $curElementID )
                    {
                        // move history entry to the same id
                        $query = "UPDATE ezurlalias_ml SET id = {$existingEntryId} " .
                                 "WHERE parent = $parentID AND text_md5 = {$textMD5}";
                        $res = $db->query( $query );
                        if ( !$res ) return eZURLAliasML::dbError( $db );
                        $curElementID = $existingEntryId;
                    }
                }

                $bitOr = $db->bitOr( $db->bitAnd( 'lang_mask', ~1 ), $languageMask );
                // Note: The `text` field is updated too, this ensures case-changes are stored.
                $query = "UPDATE ezurlalias_ml SET link = id, lang_mask = {$bitOr}, text = '{$textEsc}', action = '{$actionStr}', action_type = '{$actionTypeStr}', is_alias = 0, is_original = 1 " .
                         "WHERE parent = $parentID AND text_md5 = {$textMD5}";
                $res = $db->query( $query );
                if ( !$res ) return eZURLAliasML::dbError( $db );
                $newElementID = $curElementID;
            }
            else
            {
                $element = new eZURLAliasML( array( 'id'=> $newElementID,
                                                    'link' => null,
                                                    'parent' => $parentID,
                                                    'text' => $newText,
                                                    'lang_mask' => $languageID | $alwaysMask,
                                                    'action' => $action ) );
                $element->store();
                $newElementID = (int)$element->attribute( 'id' );
                $createdElement = $element;
            }
            $createdPath[] = $newText;

            // OMS-urlalias-fix: We want to retain the lang_mask of url entries, but mark others as history elements is_original = 0
            // Furthermore this change is not performed on custom alias entries.
            $bitAnd = $db->bitAnd( 'lang_mask', $languageID );

            // First we look at the entries to mark as history entries, if an entry comprise more languages, it must not be set as history element.
            $query = "SELECT * FROM ezurlalias_ml " .
                     "WHERE action = '{$actionStr}' AND (${bitAnd} > 0) AND is_original = 1 AND is_alias = 0 AND (parent != $parentID OR text_md5 != {$textMD5})";
            $toBeUpdated = $db->arrayQuery( $query );

            // 0. Check if the entry to be updated represents multiple languages:
            // IF YES:
            //  1. "Downgrade" existing entry, by removing the active translation's language id from the language_mask.
            // IF NO:
            //  1. Mark entry as a history entry

            if ( count( $toBeUpdated ) > 0 )
            {
                $languageMask = $toBeUpdated[0]['lang_mask'];
                if ( ( $languageMask & ~( $languageID | 1 ) ) != 0 )
                {
                    // "Composite entry", downgrade current entry
                    $currentEntry = new eZURLAliasML( $toBeUpdated[0] );
                    $currentEntry->LangMask = (int)$currentEntry->LangMask & ~$languageID;
                    $currentEntry->store();
                }
                else
                {
                    // Mark as history element.
                    $query = "UPDATE ezurlalias_ml SET is_original = 0 " .
                             "WHERE action = '{$actionStr}' AND (${bitAnd} > 0) AND is_original = 1 AND is_alias = 0 AND (parent != $parentID OR text_md5 != {$textMD5})";
                    $res = $db->query( $query );
                    if ( !$res ) return eZURLAliasML::dbError( $db );
                }
            }

            // OMS-urlalias-fix: instead entries without language we look at history elements with same action (and language)
            // Look for other nodes with the same action and language
            // if found make then link to the new entry
            $bitAnd = $db->bitAnd( 'lang_mask', $languageID );
            $query = "SELECT * FROM ezurlalias_ml " .
                     "WHERE action = '{$actionStr}' AND (${bitAnd} > 0) AND is_original = 0 AND (parent != $parentID OR text_md5 != {$textMD5})";
            $rows = $db->arrayQuery( $query );
            foreach ( $rows as $row )
            {
                $idtmp = (int)$row['id'];
                if ( $idtmp == $newElementID )
                {
                    $idtmp = self::getNewID();
                }
                $parentIDTmp = (int)$row['parent'];
                $textMD5Tmp = eZURLAliasML::md5( $db, $row['text'] );

                // OMS-urlalias-fix: We do not touch the lang_mask here
                $res = $db->query( "UPDATE ezurlalias_ml SET id = {$idtmp}, link = {$newElementID}, is_alias = 0, is_original = 0 " .
                                   "WHERE parent = {$parentIDTmp} AND text_md5 = {$textMD5Tmp}" );
                if ( !$res ) return eZURLAliasML::dbError( $db );
            }
            $res = $db->query( $query );
            if ( !$res ) return eZURLAliasML::dbError( $db );

            // Look for other nodes which is a link for the current action
            // if found make then link to the new entry
            // OMS-urlalias-fix: We only want to update the links of entries within the same language.
            // Also, only to be applied on normal entries, not custom aliases
            $bitAnd = $db->bitAnd( 'lang_mask', $languageID );
            $query = "UPDATE ezurlalias_ml SET link = {$newElementID}, is_alias = 0, is_original = 0 " .
                     "WHERE action = '{$actionStr}' AND is_original = 0 AND is_alias = 0 AND (${bitAnd} > 0) AND (parent != $parentID OR text_md5 != {$textMD5})";
            $res = $db->query( $query );
            if ( !$res ) return eZURLAliasML::dbError( $db );


            // Move children from old node to the new node
            // Conflicts:
            // New       |       Old |  Action
            // -------------------------------
            // Element   | Link      | Delete old
            // Element   | Element   | Will not happen, if so delete old
            // Element   | Other     | Reparent with new name
            // Element   | nop       | Delete old
            // Link      | Link      | Delete old
            // Link      | Element   | Delete new, reparent
            // Link      | Other     | Delete new, reparent
            // Link      | nop       | Delete old
            // nop       | Link      | Delete new, reparent
            // nop       | Element   | Delete new, reparent
            // nop       | nop       | Delete old

            // TODO: Handle all conflict cases, for now only the `Delete old, reparent` action is done

            // OMS-urlalias-fix: We are only updating child nodes within the same language,
            // and only for real system-generated url aliases. Custom aliases are left alone.
            $bitAnd = $db->bitAnd( 'lang_mask', $languageID );
            $query = "SELECT id FROM ezurlalias_ml " .
                     "WHERE action = '{$actionStr}' AND is_alias = 0 AND (parent != $parentID OR text_md5 != {$textMD5})";
            $rows = $db->arrayQuery( $query );
            foreach ( $rows as $row )
            {
                $oldParentID = (int)$row['id'];
                $query = "UPDATE ezurlalias_ml SET parent = {$newElementID} " .
                         "WHERE parent = {$oldParentID} AND (${bitAnd} > 0)";
                $res = $db->query( $query );
                if ( !$res ) return eZURLAliasML::dbError( $db );
            }
        }
        else
        {
            // Check the link ID
            if ( $linkID !== true )
            {
                $linkID = (int)$linkID;
                // Step 1, find existing ID
                $query = "SELECT * FROM ezurlalias_ml WHERE id = '{$linkID}'";
                $rows = $db->arrayQuery( $query );
                // Some sanity checking
                if ( count( $rows ) == 0 )
                {
                    if ( $reportErrors )
                        eZDebug::writeError( "The link ID $linkID does not exist, cannot create the link", __METHOD__ );
                    return array( 'status' => eZURLAliasML::LINK_ID_NOT_FOUND );
                }
                if ( $rows[0]['action'] != $action )
                {
                    if ( $reportErrors )
                        eZDebug::writeError( "The link ID $linkID uses a different action ({$rows[0]['action']}) than the requested action ({$action}) for the link, cannot create the link", __METHOD__ );
                    return array( 'status' => eZURLAliasML::LINK_ID_WRONG_ACTION );
                }
                // If the element which is pointed to is a link, then grab the link id from that instead
                if ( $rows[0]['link'] != $rows[0]['id'] )
                {
                    $linkID = (int)$rows[0]['link'];
                }
            }
            else
            {
                $linkID = null;
            }

            if ( $cleanupElements )
                $topElement = eZURLAliasML::convertToAlias( $topElement, 'noname' . (count($createdPath)+1) );

            $adjustName = false;
            $curElementID  = null;
            $newText = $topElement;
            $uniqueCounter = 0;
            $rows = null; // Will be filled in by the while loop

            // Loop until we a valid entry point, which means:
            // 1. The entry does not exist yet, so create a new one
            // 2. The entry exists but is re-usable (e.g. nop or same action)
            // 3. The entry exists and cannot be re-used, instead the name is adjusted to be unique.
            while ( true )
            {
                $newText = $topElement;
                if ( $uniqueCounter > 0 )
                    $newText .= ($uniqueCounter + 1);
                $textMD5 = eZURLAliasML::md5( $db, $newText );

                $query = "SELECT * FROM ezurlalias_ml WHERE parent = $parentID AND text_md5 = {$textMD5}";
                $rows = $db->arrayQuery( $query );
                if ( count( $rows ) == 0 )
                {
                    // No such entry, create a new one
                    break;
                }

                $row = $rows[0];
                $curID = (int)$row['id'];
                $curLink = (int)$row['link'];
                $curAction = $row['action'];
                if ( $curAction == $action )
                {
                    // If the current node is the same action and is not a link we
                    // cannot replace it with a link node.
                    if ( $curID != $curLink )
                    {
                        // We can reuse the element so record the ID
                        $curElementID = $curID;
                        break;
                    }

                    // If the current node is the same action, but the language is different
                    // (enables adding the same URL alias for other languages)
                    if ( !( (int)$row['lang_mask'] & $languageID ) )
                    {
                        // We can reuse the element so record the ID
                        $curElementID = $curID;
                        break;
                    }
                }
                else if ( $curAction == 'nop:' || $row['is_original'] == 0 )
                {
                    // We can reuse the element so record the ID
                    $curElementID = $curID;
                    break;
                }

                if ( !$autoAdjustName )
                {
                    if ( $reportErrors )
                        eZDebug::writeError( "Tried to store path '{$path}' but the path already exists (ID: {$curID}) but with action '{$curAction}', the new action was '{$action}'" );
                    return array( 'status' => self::LINK_ALREADY_TAKEN,
                                  'path'    => $path,
                                  'element' => null );
                }
                // Need to adjust name, re-iterate
                ++$uniqueCounter;
            }
            $textEsc = $db->escapeString( $newText );

            // Create or update the element
            if ( $curElementID !== null )
            {
                $element = new eZURLAliasML( $rows[0] ); // $rows is from the while loop
                $element->LangMask  |= $languageID | $alwaysMask;
                $element->IsAlias    = 1;
                $element->Action     = $action;
                // Note: The `text` field is updated too, this ensures case-changes are stored.
                $element->Text       = $newText;
                $element->TextMD5    = null;
                $element->ActionType = null;
                $element->Link       = null;
            }
            else
            {
                $element = new eZURLAliasML( array( 'id'=> null,
                                                    'link' => null,
                                                    'parent' => $parentID,
                                                    'text' => $newText,
                                                    'lang_mask' => $languageID | $alwaysMask,
                                                    'action' => $action,
                                                    'is_alias' => 1 ) );
            }
            $element->AliasRedirects = $aliasRedirects ? 1 : 0;
            $element->store();
            $createdPath[]  = $topElement;
            $createdElement = $element;
        }
        return array( 'status' => true,
                      'path'    => join( "/", $createdPath ),
                      'element' => $createdElement );
    }

    /*!
     \static
     \private

     Returns a structure with the current database error.
     \note This is used by storePath().
     */
    static private function dbError( $db )
    {
        return array( 'status' => self::DB_ERROR,
                      'error_message' => $db->errorMessage(),
                      'error_number'  => $db->errorNumber(),
                      'path' => null,
                      'element' => null );
    }

    /*!
     \static
     Fetches real path element(s) which matches the action name $actionName and value $actionValue.

     Lets say we have the following elements:

     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========
     \endcode

     then we try to fetch a specific action:
     \code
     $elements = eZURLAliasML::fetchByAction( 'eznode', 5 );
     \endcode

     it would return:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     3   3    0      'bicycle'   'eznode:5'
     === ==== ====== =========== ==========
     \endcode

     Now let's try with an element which is redirecting:
     \code
     $elements = eZURLAliasML::fetchByAction( 'eznode', 10 );
     \endcode

     it would return:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     2   6    0      'spam'      'eznode:55'
     === ==== ====== =========== ==========
     \endcode
     */
    static public function fetchByAction( $actionName, $actionValue, $maskLanguages = false, $onlyPrioritized = false, $includeRedirections = false )
    {
        $action = $actionName . ":" . $actionValue;
        $db = eZDB::instance();
        $actionStr = $db->escapeString( $action );
        $langMask = '';
        if ( $maskLanguages === true )
        {
            $langMask = "(" . trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) ) . ") AND ";
        }
        else if ( is_string( $maskLanguages ) || is_array( $maskLanguages ) )
        {
            // maskByLocale can support array input, here we only want one item.
            $mask = eZContentLanguage::maskByLocale( (array)$maskLanguages );
            $langFilter = $db->bitAnd( 'lang_mask', $mask );
            $langMask = "({$langFilter} > 0) AND";
        }
        $query = "SELECT * FROM ezurlalias_ml WHERE $langMask action = '$actionStr'";
        if ( !$includeRedirections )
        {
            $query .= " AND is_original = 1 AND is_alias = 0";
        }

        $rows = $db->arrayQuery( $query );
        if ( count( $rows ) == 0 )
            return array();
        $rows = eZURLAliasML::filterRows( $rows, $onlyPrioritized );
        $objectList = eZPersistentObject::handleRows( $rows, 'eZURLAliasML', true );
        return $objectList;
    }

    /*!
     \static
     Fetches path element(s) which matches the parent ID $id.

     Lets say we have the following elements:

     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========

     then we try to fetch a specific ID:
     \code
     eZURLAliasML::fetchByParentID( 0 );
     \endcode

     it would return (ie. no redirections):
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     === ==== ====== =========== ==========
     \endcode

     Now let's try with an element which is redirecting:
     \code
     $includeRedirections = true;
     eZURLAliasML::fetchByParentID( 0, false, false, $includeRedirections );
     \endcode

     it would return:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     === ==== ====== =========== ==========
     \endcode
    */
    static public function fetchByParentID( $id, $maskLanguages = false, $onlyPrioritized = false, $includeRedirections = true )
    {
        $db = eZDB::instance();
        $id = (int)$id;
        $langMask = trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) );
        $redirSQL = '';
        if ( !$includeRedirections )
        {
            $redirSQL = " AND is_original = 1";
        }
        $langMask = '';
        if ( $maskLanguages )
        {
            $langMask = "(" . trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) ) . ") AND ";
        }
        $query = "SELECT * FROM ezurlalias_ml WHERE $langMask parent = {$id} $redirSQL";
        $rows = $db->arrayQuery( $query );
        $rows = eZURLAliasML::filterRows( $rows, $onlyPrioritized );
        return eZPersistentObject::handleRows( $rows, 'eZURLAliasML', true );
    }

    /*!
     \static
     Fetches the path string based on the action $actionName and the values $actionValues.
     The first entry in $actionValues would be the top-most path element in the path
     the second entry the child of the first path element and so on.

     Lets say we have the following elements:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========
     \endcode

     then we try to fetch a specific ID:
     \code
     $path = eZURLAliasML::fetchPathByActionList( 'eznode', array( 3, 5 ) );
     \endcode

     it would return:
     \code
     'bicycle/repairman'
     \endcode

     \note This function is faster than getPath() since it can fetch all elements in one SQL.
     \note If the fetched elements does not point to each other (parent/id) then null is returned.
     */
    static public function fetchPathByActionList( $actionName, $actionValues, $locale = null )
    {
        if ( !is_array( $actionValues ) || count( $actionValues ) == 0 )
        {
            eZDebug::writeError( "Action values array must not be empty", __METHOD__ );
            return null;
        }
        $db = eZDB::instance();
        $actionList = array();
        foreach ( $actionValues as $i => $value )
        {
            $actionList[] = "'" . $db->escapeString( $actionName . ":" . $value ) . "'";
        }
        $actionStr = join( ", ", $actionList );
        $filterSQL = trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) );
        $query = "SELECT id, parent, lang_mask, text, action FROM ezurlalias_ml WHERE ( {$filterSQL} ) AND action in ( {$actionStr} ) AND is_original = 1 AND is_alias=0";
        $rows = $db->arrayQuery( $query );
        $objects = eZContentObject::fetchByNodeID( $actionValues );
        $actionMap = array();
        foreach ( $rows as $row )
        {
            $action = $row['action'];
            if ( !isset( $actionMap[$action] ) )
                $actionMap[$action] = array();
            $actionMap[$action][] = $row;
        }

        if ( $locale !== null && is_string( $locale ) && !empty( $locale ) )
        {
            $selectedLanguage = eZContentLanguage::fetchByLocale( $locale );
            $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
            // Add $selectedLanguage on top of $prioritizedLanguages to take it into account with the highest priority
            if ( $selectedLanguage instanceof eZContentLanguage )
                array_unshift( $prioritizedLanguages, $selectedLanguage );
        }
        else
        {
            $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
        }

        $path = array();
        $lastID = false;
        foreach ( $actionValues as $actionValue )
        {
            $action = $actionName . ":" . $actionValue;
            if ( !isset( $actionMap[$action] ) )
            {
//                eZDebug::writeError( "The action '{$action}' was not found in the database for the current language language filter, cannot calculate path." );
                return null;
            }
            $actionRows = $actionMap[$action];
            $defaultRow = null;
            foreach( $prioritizedLanguages as $language )
            {
                foreach ( $actionRows as $row )
                {
                    $langMask   = (int)$row['lang_mask'];
                    $wantedMask = (int)$language->attribute( 'id' );
                    if ( ( $wantedMask & $langMask ) > 0 )
                    {
                        $defaultRow = $row;
                        break 2;
                    }
                    // If the 'always available' bit is set AND it corresponds to the main language, then choose it as the default
                    if ( $langMask & 1 && $objects[$actionValue]->attribute( 'initial_language_id' ) & $langMask )
                    {
                        $defaultRow = $row;
                    }
                }
            }
            if ( $defaultRow )
            {
                $id = (int)$defaultRow['id'];
                $paren = (int)$defaultRow['parent'];

                // If the parent is 0 it means the element is at the top, ie. reset the path and lastID
                if ( $paren == 0 )
                {
                    $lastID = false;
                    $path = array();
                }

                $path[] = $defaultRow['text'];

                // Check for a valid path
                if ( $lastID !== false && $lastID != $paren )
                {
                    eZDebug::writeError( "The parent ID $paren of element with ID $id does not point to the last entry which had ID $lastID, incorrect path would be calculated, aborting" );
                    return null;
                }
                $lastID = $id;
            }
            else
            {
                // No row was found
                eZDebug::writeError( "Fatal error, no row was chosen for action " . $actionName . ":" . $actionValue );
                return null;
            }
        }
        return join( "/", $path );
    }

    /*!
     \static
     Fetches the path element(s) which has the path $uriString.
     If $glob is set it will use $uriString as the folder to search in and $glob as
     the starting text to match against.

     Lets say we have the following elements:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========
     \endcode

     Then we try to fetch a specific path:
     \code
     $elements = eZURLAliasML::fetchByPath( "bicycle/repairman" );
     \endcode

     we would get:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     5   5    3      'repairman' 'eznode:42'
     === ==== ====== =========== ==========
     \endcode

     \code
     $elements = eZURLAliasML::fetchByPath( "bicycle", "rep" ); // bicycle/rep*
     \endcode

     we would get:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========
     \endcode
     */
    static public function fetchByPath( $uriString, $glob = false )
    {
        $uriString = eZURLAliasML::cleanURL( $uriString );

        $db = eZDB::instance();
        if ( $uriString == '' && $glob !== false )
            $elements = array();
        else
            $elements = explode( '/', $uriString );
        $len      = count( $elements );
        $i = 0;
        $selects = array();
        $tables  = array();
        $conds   = array();
        $prevTable = false;
        foreach ( $elements as $element )
        {
            $table     = "e" . $i;
            $langMask  = trim( eZContentLanguage::languagesSQLFilter( $table, 'lang_mask' ) );

            if ( $glob === false && ($i == $len - 1) )
                $selects[] = eZURLAliasML::generateFullSelect( $table );
            else
                $selects[] = eZURLAliasML::generateSelect( $table, $i, $len );
            $tables[]  = "ezurlalias_ml " . $table;
            $conds[]   = eZURLAliasML::generateCond( $table, $prevTable, $i, $langMask, $element );
            $prevTable = $table;
            ++$i;
        }
        if ( $glob !== false )
        {
            ++$len;
            $table     = "e" . $i;
            $langMask  = trim( eZContentLanguage::languagesSQLFilter( $table, 'lang_mask' ) );

            $selects[] = eZURLAliasML::generateFullSelect( $table );
            $tables[]  = "ezurlalias_ml " . $table;
            $conds[]   = eZURLAliasML::generateGlobCond( $table, $prevTable, $i, $langMask, $glob );
            $prevTable = $table;
            ++$i;
        }
        $elementOffset = $i - 1;
        $query = "SELECT DISTINCT " . join( ", ", $selects ) . " FROM " . join( ", ", $tables ) . " WHERE " . join( " AND ", $conds );

        $pathRows = $db->arrayQuery( $query );
        $elements = array();
        if ( count( $pathRows ) > 0 )
        {
            foreach ( $pathRows as $pathRow )
            {
                $redirectLink = false;
                $table = "e" . $elementOffset;
                $element = array( 'id'        => $pathRow[$table . "_id"],
                                  'parent'    => $pathRow[$table . "_parent"],
                                  'lang_mask' => $pathRow[$table . "_lang_mask"],
                                  'text'      => $pathRow[$table . "_text"],
                                  'action'    => $pathRow[$table . "_action"],
                                  'link'      => $pathRow[$table . "_link"] );
                $path = array();
                $lastID = false;
                for ( $i = 0; $i < $len; ++$i )
                {
                    $table = "e" . $i;
                    $id   = $pathRow[$table . "_id"];
                    $link = $pathRow[$table . "_link"];
                    $path[] = $pathRow[$table . "_text"];
                    if ( $link != $id )
                    {
                        // Mark the redirect link
                        $redirectLink = $link;
                        $redirectOffset = $i;
                    }
                    $lastID = $link;
                }
                if ( $redirectLink )
                {
                    $newLinkID = $redirectLink;
                    // Resolve new links until a real element is found.
                    // TODO: Add max redirection count?
                    while ( $newLinkID )
                    {
                        $query = "SELECT id, parent, lang_mask, text, link FROM ezurlalias_ml WHERE id={$newLinkID}";
                        $rows = $db->arrayQuery( $query );
                        if ( count( $rows ) == 0 )
                        {
                            return false;
                        }
                        $newLinkID = false;
                        if ( $rows[0]['id'] != $rows[0]['link'] )
                            $newLinkID = (int)$rows[0]['link'];
                    }
                    $id = (int)$newLinkID;
                    $path = array();

                    // Fetch path 'text' elements of correct parent path
                    while ( $id != 0 )
                    {
                        $query = "SELECT parent, lang_mask, text FROM ezurlalias_ml WHERE id={$id}";
                        $rows = $db->arrayQuery( $query );
                        if ( count( $rows ) == 0 )
                        {
                            break;
                        }
                        $result = eZURLAliasML::choosePrioritizedRow( $rows );
                        if ( !$result )
                        {
                            $result = $rows[0];
                        }
                        $id = (int)$result['parent'];
                        array_unshift( $path, $result['text'] );
                    }
                    // Fill in end of path elements
                    for ( $i = $redirectOffset; $i < $len; ++$i )
                    {
                        $table = "e" . $i;
                        $path[] = $pathRow[$table . "_text"];
                    }
                }
                $element['path'] = implode( '/', $path );
                $elements[] = $element;
            }
        }
        $rows = array();
        $ids = array();
        // Discard duplicates
        foreach ( $elements as $element )
        {
            $id = (int)$element['id'];
            if ( isset( $ids[$id] ) )
                continue;
            $ids[$id] = true;
            $rows[] = $element;
        }
        $objectList = eZPersistentObject::handleRows( $rows, 'eZURLAliasML', true );
        return $objectList;
    }

    /*!
     \static
     The same as 'fetchByPath' but extracting nodeID from action.
     Only first entry will be processed if 'fetchByPath' returns multiple result(e.g. $glob is wildcard).
     \return nodeID on success or \c false otherwise.
     */
    static public function fetchNodeIDByPath( $uriString, $glob = false )
    {
        $nodeID = false;

        $urlAliasMLList = eZURLAliasML::fetchByPath( $uriString, $glob );
        if ( is_array( $urlAliasMLList ) && count( $urlAliasMLList ) > 0 )
            $nodeID = eZURLAliasML::nodeIDFromAction( $urlAliasMLList[0]->Action );

        return $nodeID;
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it, the new URI is replaced in $uri.
     \return \c true is if successful, \c false otherwise
     \return The string with new url is returned if the translation was found, but the resource has moved.

     Lets say we have the following elements:
     \code
     === ==== ====== =========== ==========
     id  link parent text        action
     === ==== ====== =========== ==========
     1   1    0      'ham'       'eznode:4'
     2   6    0      'spam'      'eznode:55'
     3   3    0      'bicycle'   'eznode:5'
     4   4    0      'superman'  'nop:'
     5   5    3      'repairman' 'eznode:42'
     6   6    3      'repoman'   'eznode:55'
     === ==== ====== =========== ==========
     \endcode

     then we try to translate a path:
     \code
     $uri = "bicycle/repairman";
     $result = eZURLAliasML::translate( $uri );
     if ( $result )
     {
         echo $result, "\n";
         echo $uri, "\n";
     }
     \endcode

     we would get:
     \code
     '1'
     'content/view/full/42'
     \endcode

     If we then were to try:
     \code
     $uri = "spam";
     $result = eZURLAliasML::translate( $uri );
     if ( $result )
     {
         echo $result, "\n";
         echo $uri, "\n";
     }
     \endcode

     we would get:
     \code
     'bicycle/repoman'
     'error/301'
     \endcode

     Trying a non-existing path:
     \code
     $uri = "spam/a-lot";
     $result = eZURLAliasML::translate( $uri );
     if ( $result )
     {
         echo $result, "\n";
         echo $uri, "\n";
     }
     \endcode

     then $result would be empty:

     Alterntively we can also do a reverse lookup:
     \code
     $uri = "content/view/full/55";
     $result = eZURLAliasML::translate( $uri, true );
     if ( $result )
     {
         echo $result, "\n";
         echo $uri, "\n";
     }
     \endcode

     we would get:
     \code
     '1'
     'bicycle/repoman'
     \endcode
    */
    static public function translate( &$uri, $reverse = false )
    {
        if ( $uri instanceof eZURI )
        {
            $uriString = $uri->elements();
        }
        else
        {
            $uriString = $uri;
        }
        $uriString = eZURLAliasML::cleanURL( $uriString );
        $internalURIString = $uriString;
        $originalURIString = $uriString;

        if ( $reverse )
        {
            return eZURLAliasML::reverseTranslate( $uri, $uriString, $internalURIString );
        }

        $ini = eZINI::instance();

        $prefixAdded = false;
        $prefix = self::getPathPrefix();

        if ( $prefix )
        {
            $escapedPrefix = preg_quote( $prefix, '#' );
            // Only prepend the path prefix if it's not already the first element of the url.
            if ( !preg_match( "#^$escapedPrefix(/.*)?$#i", $uriString )  )
            {
                $exclude = $ini->hasVariable( 'SiteAccessSettings', 'PathPrefixExclude' )
                           ? $ini->variable( 'SiteAccessSettings', 'PathPrefixExclude' )
                           : false;
                $breakInternalURI = false;
                foreach ( $exclude as $item )
                {
                    $escapedItem = preg_quote( $item, '#' );
                    if ( preg_match( "#^$escapedItem(/.*)?$#i", $uriString )  )
                    {
                        $breakInternalURI = true;
                        break;
                    }
                }

                if ( !$breakInternalURI )
                {
                    $internalURIString = $prefix . '/' . $uriString;
                    $prefixAdded = true;
                }
            }
        }

        $db = eZDB::instance();
        $elements = explode( '/', $internalURIString );
        $len      = count( $elements );

        $i = 0;
        $selects = array();
        $tables  = array();
        $conds   = array();
        foreach ( $elements as $element )
        {
            $table = "e" . $i;

            $selectString = "{$table}.id AS {$table}_id, ";
            $selectString .= "{$table}.link AS {$table}_link, ";
            $selectString .= "{$table}.text AS {$table}_text, ";
            $selectString .= "{$table}.text_md5 AS {$table}_text_md5, ";
            $selectString .= "{$table}.is_alias AS {$table}_is_alias, ";

            if ( $i == $len - 1 )
                $selectString .= "{$table}.action AS {$table}_action, ";

            $selectString .= "{$table}.alias_redirects AS {$table}_alias_redirects";
            $selects[] = $selectString;

            $tables[]  = "ezurlalias_ml " . $table;
            $langMask = trim( eZContentLanguage::languagesSQLFilter( $table, 'lang_mask' ) );
            if ( $i == 0 )
            {
                $conds[]   = "{$table}.parent = 0 AND ({$langMask}) AND {$table}.text_md5 = " . eZURLAliasML::md5( $db, $element );
            }
            else
            {
                $conds[]   = "{$table}.parent = {$prevTable}.link AND ({$langMask}) AND {$table}.text_md5 = " . eZURLAliasML::md5( $db, $element );
            }
            $prevTable = $table;
            ++$i;
        }

        $query = "SELECT " . join( ", ", $selects ) . " FROM " . join( ", ", $tables ) . " WHERE " . join( " AND ", $conds );
        $return = false;
        $urlAliasArray = $db->arrayQuery( $query, array( 'limit' => 1 ) );
        if ( count( $urlAliasArray ) > 0 )
        {
            $pathRow = $urlAliasArray[0];
            $l   = count( $pathRow );
            $redirectLink = false;
            $redirectAction = false;
            $lastID = false;
            $action = false;
            $verifiedPath = array();
            $doRedirect = false;

            for ( $i = 0; $i < $len; ++$i )
            {
                $table = "e" . $i;
                $id   = $pathRow[$table . "_id"];
                $link = $pathRow[$table . "_link"];
                $text = $pathRow[$table . "_text"];
                $isAlias = $pathRow[$table . '_is_alias'];
                $aliasRedirects = $pathRow[$table . '_alias_redirects'];
                $verifiedPath[] = $text;
                if ( $i == $len - 1 )
                {
                    $action = $pathRow[$table . "_action"];
                }
                if ( $link != $id )
                {
                    $doRedirect = true;
                }
                else if ( $isAlias && $action !== false )
                {
                    if ( $aliasRedirects )
                    {
                        // If the entry is an alias and we have an action we redirect to the original
                        // url of that action.
                        $redirectAction = $action;
                        $doRedirect = true;
                    }
                }
                $lastID = $link;
            }

            if ( !$doRedirect )
            {
                $verifiedPathString = implode( '/', $verifiedPath );
                // Check for case difference
                if ( $prefixAdded )
                {
                    if ( strcmp( $originalURIString, substr( $verifiedPathString, strlen( $prefix ) + 1 ) ) != 0 )
                    {
                        $doRedirect = true;
                    }
                }
                else if ( strcmp( $verifiedPathString, $internalURIString ) != 0 )
                {
                    $doRedirect = true;
                }
            }

            if ( preg_match( "#^module:(.+)$#", $action, $matches ) and $doRedirect )
            {
                $uriString = 'error/301';
                $return = $matches[1];
            }
            else if ( $doRedirect )
            {
                if ( $redirectAction !== false )
                {
                    $query = "SELECT id FROM ezurlalias_ml WHERE action = '" . $db->escapeString( $action ) . "' AND is_original = 1 AND is_alias = 0";
                    $rows  = $db->arrayQuery( $query );
                    if ( count( $rows ) > 0 )
                    {
                        $id        = (int)$rows[0]['id'];
                    }
                    else
                    {
                        $id        = false;
                        $uriString = 'error/301';
                        $return    = join( "/", $pathData );
                    }
                }
                else
                {
                    $id = (int)$lastID;
                }

                if ( $id !== false )
                {
                    $pathData = array();
                    // Figure out the correct path by iterating down the parents until we have all
                    // elements figured out.

                    while ( $id != 0 )
                    {
                        $query = "SELECT parent, lang_mask, text FROM ezurlalias_ml WHERE id={$id}";
                        $rows = $db->arrayQuery( $query );
                        if ( count( $rows ) == 0 )
                        {
                            break;
                        }
                        $result = eZURLAliasML::choosePrioritizedRow( $rows );
                        if ( !$result )
                        {
                            $result = $rows[0];
                        }
                        $id = (int)$result['parent'];
                        array_unshift( $pathData, $result['text'] );
                    }
                    $uriString = 'error/301';
                    $return = join( "/", $pathData );
                }

                // Remove prefix of redirect uri if needed
                if ( $prefix && is_string( $return ) )
                {
                    if ( strncasecmp( $return, $prefix . '/', strlen( $prefix ) + 1 ) == 0 )
                    {
                        $return = substr( $return, strlen( $prefix ) + 1 );
                    }
                }
            }
            else
            {
                // See http://issues.ez.no/19062
                // If $uriString matches a nop action, we need to check if we also match a wildcard
                // since we might want to translate it.
                // Default action for nop actions is to display the root node "/" (see eZURLAliasML::actionToURL())
                if ( strpos( $action, 'nop') !== false && eZURLWildcard::wildcardExists( $uriString ) )
                {
                    $return = false;
                }
                else
                {
                    $uriString = eZURLAliasML::actionToUrl( $action );
                    $return = true;
                }

            }

            if ( $uri instanceof eZURI )
            {
                $uri->setURIString( $uriString, false );
            }
            else
            {
                $uri = $uriString;
            }
        }

        return $return;
    }

    /*!
     \private
     \static
     Perform reverse translation of uri, that is from system-url to url alias.
     */
    static public function reverseTranslate( &$uri, $uriString, $internalURIString )
    {
        $db = eZDB::instance();

        $action = eZURLAliasML::urlToAction( $internalURIString );
        if ( $action !== false )
        {
            $langMask = trim( eZContentLanguage::languagesSQLFilter( 'ezurlalias_ml', 'lang_mask' ) );
            $actionStr = $db->escapeString( $action );
            $query = "SELECT id, parent, lang_mask, text, action FROM ezurlalias_ml WHERE ($langMask) AND action='{$actionStr}' AND is_original = 1 AND is_alias = 0";
            $rows = $db->arrayQuery( $query );
            $path = array();
            $count = count( $rows );
            if ( $count != 0 )
            {
                $row = eZURLAliasML::choosePrioritizedRow( $rows );
                if ( $row === false )
                {
                    $row = $rows[0];
                }
                $paren = (int)$row['parent'];
                $path[] = $row['text'];
                // We have the parent so now do an iterative lookup until we have the top element
                while ( $paren != 0 )
                {
                    $query = "SELECT id, parent, lang_mask, text FROM ezurlalias_ml WHERE ($langMask) AND id=$paren AND is_original = 1 AND is_alias = 0";
                    $rows = $db->arrayQuery( $query );
                    $count = count( $rows );
                    if ( $count != 0 )
                    {
                        $row = eZURLAliasML::choosePrioritizedRow( $rows );
                        if ( $row === false )
                        {
                            $row = $rows[0];
                        }
                        $paren = (int)$row['parent'];
                        array_unshift( $path, $row['text'] );
                    }
                    else
                    {
                        eZDebug::writeError( "Lookup of parent ID $paren failed, cannot perform reverse lookup of alias." );
                        return false;
                    }
                }
                $uriString = join( '/', $path );
                $uriString = self::removePathPrefixFromURI( $uriString );
                if ( $uri instanceof eZURI )
                {
                    $uri->setURIString( $uriString, false );
                }
                else
                {
                    $uri = $uriString;
                }
                return true;
            }
            else
            {
                return false;
            }
        }
        return false;
    }

    /*!
     \private
     \static
     Returns PathPrefix without leading or trailing slashes if it's configured. Otherwise returns false.
     */
    static public function getPathPrefix()
    {
        $ini = eZINI::instance();

        return $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) &&
            $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) != ''
            ? eZURLAliasML::cleanURL( $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) )
            : false;
    }

    /*!
     \private
     \static
     Remove PathPrefix from the URI string, if it's configured and not affected by PathPrefixExclude. Returns the URI string.
     */
    static public function removePathPrefixFromURI($uriString)
    {
        $ini = eZINI::instance();

        $prefix = self::getPathPrefix();
        if ( !$prefix )
        {
            return $uriString;
        }

        $exclude = $ini->hasVariable( 'SiteAccessSettings', 'PathPrefixExclude' )
            ? $ini->variable( 'SiteAccessSettings', 'PathPrefixExclude' )
            : false;
        foreach ( $exclude as $item )
        {
            $escapedItem = preg_quote( $item, '#' );
            if ( preg_match( "#^$escapedItem(/.*)?$#i", $uriString ) )
            {
                return $uriString;
            }
        }

        $escapedPrefix = preg_quote( $prefix, '#' );
        $modifiedUriString = preg_replace( "#^$escapedPrefix/?#i", '', $uriString );
        return $modifiedUriString === null ? $uriString : $modifiedUriString;
    }

    /*!
     \static
     Checks if url translation should be used on the current url.

     \param $uri The current eZUri object
     */
    static public function urlTranslationEnabledByUri( eZURI $uri )
    {
        if ( $uri->isEmpty() )
            return false;

        $ini = eZINI::instance();
        if ( $ini->variable( 'URLTranslator', 'Translation' ) === 'enabled' )
        {
            if ( $ini->variable( 'URLTranslator', 'TranslatableSystemUrls' ) === 'disabled' )
            {
                $moduleName = $uri->element( 0 );
                $moduleINI  = eZINI::instance( 'module.ini' );
                $moduleList = $moduleINI->variable( 'ModuleSettings', 'ModuleList' );
                if ( in_array( $moduleName, $moduleList, true ) )
                  return false;
            }
            return true;
        }
        return false;
    }

    /*!
     \static
     Checks if the text entry $text is unique on the current level in the URL path.
     If not the name is adjusted with a number at the end until it becomes unique.
     The unique text string is returned.

     \param $text The text element which is to be checked
     \param $action The action string which is to be excluded from the check. Set to empty string to disable the exclusion.
     \param $linkCheck If true then it will see all existing entries as taken.
     */
    static public function findUniqueText( $parentElementID, $text, $action, $linkCheck = false, $languageID = false )
    {
        $db = eZDB::instance();
        $uniqueNumber =  0;
        // If there is no parent we need to check against reserved words
        if ( $parentElementID == 0 )
        {
            $moduleINI = eZINI::instance( 'module.ini' );
            $reserved = $moduleINI->variable( 'ModuleSettings', 'ModuleList' );
            foreach ( $reserved as $res )
            {
                if ( strcasecmp( $text, $res ) == 0 )
                {
                    // The name is a reserved word so it needs to be changed
                    ++$uniqueNumber;
                    break;
                }
            }
        }
        $suffix = '';
        if ( $uniqueNumber )
            $suffix = $uniqueNumber + 1;

        $actionSQL = '';
        if ( strlen( $action ) > 0 )
        {
            $actionEsc = $db->escapeString( $action );
            $actionSQL = "AND action != '$actionEsc'";
        }
        $languageSQL = "";
        if ( $languageID !== false )
        {
            $languageSQL = "AND " . $db->bitAnd(  'lang_mask', $languageID ) . ' > 0';
        }
        // Loop until we find a unique name
        while ( true )
        {
            $textEsc = eZURLAliasML::md5( $db, $text . $suffix );
            $query = "SELECT * FROM ezurlalias_ml WHERE parent = $parentElementID $actionSQL $languageSQL AND text_md5 = $textEsc";
            if ( !$linkCheck )
            {
                $query .= " AND is_original = 1";
            }
            if ( $db->databaseName() === 'mysql' )
            {
                $query .= ' LOCK IN SHARE MODE';
            }
            $rows = $db->arrayQuery( $query );
            if ( count( $rows ) == 0 )
            {
                return $text . $suffix;
            }

            ++$uniqueNumber;
            $suffix = $uniqueNumber + 1;
        }
    }

    /*!
     \static
     Updates the lang_mask field for path elements which matches action $actionName and value $actionValue.
     If $langID is false then bit 0 (the *always available* bit) will be removed, otherwise it will set bit 0 for the chosen language and remove it for other languages.
     */
    static public function setLangMaskAlwaysAvailable( $langID, $actionName, $actionValue )
    {
        if ( !$actionName )
        {
            eZDebug::writeError( "ActionName value must not be empty", __METHOD__ );
            return null;
        }
        $db = eZDB::instance();
        if ( is_array( $actionName ) )
        {
            $actions = array();
            foreach ( $actionName as $actionItem )
            {
                $action = $actionItem[0] . ":" . $actionItem[1];
                $actions[] = "'" . $db->escapeString( $action ) . "'";
            }
            $actionSql = "action in (" . implode( ', ', $actions ) . ")";
        }
        else
        {
            $action = $actionName . ":" . $actionValue;
            $actionSql = "action = '" . $db->escapeString( $action ) . "'";
        }
        if ( $langID !== false )
        {
            // Set the 0 bit for chosen language
            $bitOp = $db->bitOr( 'lang_mask', 1 );
            $langWhere = ' AND ' . $db->bitAnd( 'lang_mask' , (int)$langID ) . ' > 0';

            $query = "UPDATE ezurlalias_ml SET lang_mask = $bitOp WHERE $actionSql $langWhere";
            $db->query( $query );

            // Clear the 0 bit for all other languages
            $bitOp = $db->bitAnd( 'lang_mask', ~1 );
            $langWhere = ' AND ' . $db->bitAnd( 'lang_mask' , (int)$langID ) . ' = 0';

            $query = "UPDATE ezurlalias_ml SET lang_mask = $bitOp WHERE $actionSql $langWhere";
            $db->query( $query );
        }
        else
        {
            $bitOp = $db->bitAnd( 'lang_mask', ~1 );
            $query = "UPDATE ezurlalias_ml SET lang_mask = $bitOp WHERE $actionSql";
            $db->query( $query );
        }
    }

    /**
     * Chooses the most prioritized row (based on language) of $rows and returns it.
     * @param array $rows
     * @return array|false The most prioritized row, or false if no match was found
     */
    static public function choosePrioritizedRow( $rows )
    {
        $result = false;
        $score = 0;
        foreach ( $rows as $row )
        {
            if ( $result )
            {
                $newScore = eZURLAliasML::languageScore( $row['lang_mask'] );
                if ( $newScore > $score )
                {
                    $result = $row;
                    $score = $newScore;
                }
            }
            else
            {
                $result = $row;
                $score = eZURLAliasML::languageScore( $row['lang_mask'] );
            }
        }

        // If score is still 0, this means that the objects languages don't
        // match the INI settings, and these should be fix according to the doc.
        if ( $score == 0 )
        {
            eZDebug::writeWarning(
                "None of the available languages are prioritized in the SiteLanguageList setting. An arbitrary language will be used.",
                __METHOD__ );
        }

        return $result;
    }

    /*!
     \static
     \private
     Filters the DB rows $rows by selecting the most prioritized row per
     path element and returns the new row list.
     \param $onlyPrioritized If false all rows are returned, if true filtering is performed.
     */
    static private function filterRows( $rows, $onlyPrioritized )
    {
        if ( !$onlyPrioritized )
        {
            return $rows;
        }
        $idMap = array();
        foreach ( $rows as $row )
        {
            if ( !isset( $idMap[$row['id']] ) )
            {
                $idMap[$row['id']] = array();
            }
            $idMap[$row['id']][] = $row;
        }

        $rows = array();
        foreach ( $idMap as $id => $langRows )
        {
            $rows[] = eZURLAliasML::choosePrioritizedRow( $langRows );
        }

        return $rows;
    }

    /*!
     \static
     \private
     Calculates the score of the language mask $mask based upon the currently
     prioritized languages and returns it.
     \note The higher the value the more the language is prioritized.
     */
    static private function languageScore( $mask )
    {
        $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
        $scores = array();
        $score = 1;
        $mask   = (int)$mask;
        krsort( $prioritizedLanguages );
        foreach ( $prioritizedLanguages as $prioritizedLanguage )
        {
            $id = (int)$prioritizedLanguage->attribute( 'id' );
            if ( $id & $mask )
            {
                $scores[] = $score;
            }
            ++$score;
        }
        if ( count( $scores ) > 0 )
        {
            return max( $scores );
        }
        else
        {
            return 0;
        }
    }

    /*!
     \static
     Decodes the action string $action into an internal path string and returns it.

     The following actions are supported:
     - eznode - argument is node ID, path is 'content/view/full/<nodeID>'
     - module - argument is module/view/args, path is the arguments
     - nop    - a no-op, path is '/'
     */
    static public function actionToUrl( $action )
    {
        if ( !preg_match( "#^([a-zA-Z0-9_]+):(.+)?$#", $action, $matches ) )
        {
            eZDebug::writeError( "Action is not of valid syntax '{$action}'" );
            return false;
        }

        $type = $matches[1];
        $args = '';
        if ( isset( $matches[2] ) )
            $args = $matches[2];
        switch ( $type )
        {
            case 'eznode':
                if ( !is_numeric( $args ) )
                {
                    eZDebug::writeError( "Arguments to eznode action must be an integer, got '{$args}'" );
                    return false;
                }
                $url = 'content/view/full/' . $args;
                break;

            case 'module':
                $url = $args;
                break;

            case 'nop':
                $siteINI = eZINI::instance();
                $url = $siteINI->variable( 'URLTranslator', 'LoadOnPartialAliasPath' );
                break;

            default:
                eZDebug::writeError( "Unknown action type '{$type}', cannot handle it" );
                return false;
        }
        return $url;
    }

    /*!
     \static
     Takes the url string $url and returns the action string for it.

     The following path are supported:
     - content/view/full/<nodeID> => eznode:<nodeID>

     If the url points to an existing module it will return module:<url>

     \return false if the action could not be figured out.
     */
    static public function urlToAction( $url )
    {
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $url, $matches ) )
        {
            return "eznode:" . $matches[1];
        }
        if ( preg_match( "#^([a-zA-Z0-9]+)/#", $url, $matches ) )
        {
            $name = $matches[1];
            $module = eZModule::exists( $name );
            if ( $module !== null )
                return 'module:' . $url;
        }
        return false;
    }

    /*!
     \static
     Makes sure the URL \a $url does not contain leading and trailing slashes (/).
     \return the clean URL
    */
    static public function cleanURL( $url )
    {
        return trim( $url, '/ ' );
    }

    /*!
     \static
     Transform a semi-valid url into one that can be stored in the url-alias system.
     Removes leading/trailing slashes and repeated slashes.

     \code
     echo eZURLAliasML::sanitizeURL( "" ); // Result ""
     echo eZURLAliasML::sanitizeURL( "users//the_dude" ); // Result "users/the_dude"
     echo eZURLAliasML::sanitizeURL( "archive/products/" ); // Result "archive/products"
     \endcode
     \return the sanitized URL
    */
    static public function sanitizeURL( $url )
    {
        $url = preg_replace( "#//+#", "/", trim( $url, '/' ) );
        return $url;
    }

    /*!
     \private
     \static
     Generates partial SELECT part of SQL based on table $table, counter $i and total length $len.
     */
    static private function generateSelect( $table, $i, $len )
    {
        if ( $i == $len - 1 )
        {
            $select = "{$table}.id AS {$table}_id, {$table}.link AS {$table}_link, {$table}.text AS {$table}_text, {$table}.text_md5 AS {$table}_text_md5, {$table}.action AS {$table}_action";
        }
        else
        {
            $select = "{$table}.id AS {$table}_id, {$table}.link AS {$table}_link, {$table}.text AS {$table}_text, {$table}.text_md5 AS {$table}_text_md5";
        }
        return $select;
    }

    /*!
     \private
     \static
     Generates full SELECT part of SQL based on table $table.
     */
    static private function generateFullSelect( $table )
    {
        $select = "{$table}.id AS {$table}_id, {$table}.parent AS {$table}_parent, {$table}.lang_mask AS {$table}_lang_mask, {$table}.text AS {$table}_text, {$table}.text_md5 AS {$table}_text_md5, {$table}.action AS {$table}_action, {$table}.link AS {$table}_link";
        return $select;
    }

    /*!
     \private
     \static
     Generates WHERE part of SQL based on table $table, previous table $prevTable, counter $i, language mask $langMask and text $element.
     */
    static private function generateCond( $table, $prevTable, $i, $langMask, $element )
    {
        $db = eZDB::instance();
        if ( $i == 0 )
        {
            $cond = "{$table}.parent = 0 AND ({$langMask}) AND {$table}.text_md5 = " . eZURLAliasML::md5( $db, $element );
        }
        else
        {
            $cond = "{$table}.parent = {$prevTable}.link AND ({$langMask}) AND {$table}.text_md5 = " . eZURLAliasML::md5( $db, $element );
        }
        return $cond;
    }

    /*!
     \private
     \static
     Generates WHERE part of SQL for a wildcard match based on table $table, previous table $prevTable, counter $i, language mask $langMask and wildcard text $glob.
     \note $glob does not contain the wildcard character * but only the beginning of the matching text.
     */
    static private function generateGlobCond( $table, $prevTable, $i, $langMask, $glob )
    {
        $db = eZDB::instance();
        if ( $i == 0 )
        {
            $cond = "{$table}.parent = 0 AND ({$langMask}) AND {$table}.text LIKE '" . $db->escapeString( $glob ) . "%'";
        }
        else
        {
            $cond = "{$table}.parent = {$prevTable}.link AND ({$langMask}) AND {$table}.text LIKE '" . $db->escapeString( $glob ) . "%'";
        }
        return $cond;
    }

    /*!
     \static
     Converts the path \a $urlElement into a new alias url which only conists of valid characters
     in the URL.
     For non-Unicode setups this means character in the range a-z, numbers and _, for Unicode
     setups it means all characters except space, &, ;, /, :, =, ?, [, ], (, ), -

     Invalid characters are converted to -.
     \return the converted element

     Example with a non-Unicode setup
     \example
     'My car' => 'My-car'
     'What is this?' => 'What-is-this'
     'This & that' => 'This-that'
     'myfile.tpl' => 'Myfile-tpl',
     'øæå' => 'oeaeaa'
     \endexample
    */
    static public function convertToAlias( $urlElement, $defaultValue = false )
    {
        $trans = eZCharTransform::instance();

        $ini = eZINI::instance();
        $group = $ini->variable( 'URLTranslator', 'TransformationGroup' );

        $urlElement = $trans->transformByGroup( $urlElement, $group );
        if ( strlen( $urlElement ) == 0 )
        {
            if ( $defaultValue === false )
                $urlElement = '_1';
            else
            {
                $urlElement = $defaultValue;
                $urlElement = $trans->transformByGroup( $urlElement, $group );
            }
        }
        return $urlElement;
    }

    /*!
     \static
     Converts the path \a $urlElement into a new alias url which only conists of valid characters
     in the URL.
     This means character in the range a-z, numbers and _.

     Invalid characters are converted to -.
     \return the converted element

     \example
     'My car' => 'My-car'
     'What is this?' => 'What-is-this'
     'This & that' => 'This-that'
     'myfile.tpl' => 'Myfile-tpl',
     'øæå' => 'oeaeaa'
     \endexample

     \note Provided for creating url alias as they were before 3.10. Also used to make path_identification_string.
    */
    static public function convertToAliasCompat( $urlElement, $defaultValue = false )
    {
        $trans = eZCharTransform::instance();

        $urlElement = $trans->transformByGroup( $urlElement, "urlalias_compat" );
        if ( strlen( $urlElement ) == 0 )
        {
            if ( $defaultValue === false )
                $urlElement = '_1';
            else
            {
                $urlElement = $defaultValue;
                $urlElement = $trans->transformByGroup( $urlElement, "urlalias_compat" );
            }
        }
        return $urlElement;
    }

    /*!
     \static
     Converts the path \a $pathURL into a new alias path with limited characters.
     For more information on the conversion see convertToAlias().
     \note each element in the path (separated by / (slash) ) is converted separately.
     \return the converted path
    */
    static public function convertPathToAlias( $pathURL )
    {
        $result = array();

        $elements = explode( '/', $pathURL );

        foreach ( $elements as $element )
        {
            $element = eZURLAliasML::convertToAlias( $element );
            $result[] = $element;
        }

        return implode( '/', $result );
    }

    /*!
     \static
     Grabs nodeID from action string.
     \return nodeID on success, \c false otherwise.
     */
    static public function nodeIDFromAction( $action )
    {
        $nodeID = false;
        $pos = strpos( $action, 'eznode:' );
        if ( $pos === 0 ) // make sure $action starts from 'eznode:'
            $nodeID = substr( $action, strlen( 'eznode:' ) );

        return $nodeID;
    }

    /*!
     \static
     Wraps a database md5 call around the string $text and returns the new SQL for it.

     \param $escape If true it will lowercase the text and escape it.
     \note If the database is Oracle and the text is empty the MD5 is computed by PHP
           and returned.
     */
    static private function md5( $db, $text, $escape = true )
    {
        // Special case for Oracle since it cannot calculate MD5 for empty strings
        if ( strlen( $text ) == 0 && $db->databaseName() == 'oracle' )
            return "'" . $db->escapeString( md5( $text ) ) . "'";

        if ( $escape )
            $text = $db->escapeString( eZURLAliasML::strtolower( $text ) );
        return $db->md5( "'" . $text . "'" );
    }

    static function getNewID()
    {
        $db = eZDB::instance();
        if ( $db->supportsDefaultValuesInsertion() )
        {
            $db->query( 'INSERT INTO ezurlalias_ml_incr DEFAULT VALUES' );
        }
        else
        {
            // can not use VALUES(DEFAULT), because of http://bugs.mysql.com/bug.php?id=42270
            $db->query( 'INSERT INTO ezurlalias_ml_incr(id) VALUES(NULL)' );
        }

        return $db->lastSerialID( 'ezurlalias_ml_incr', 'id' );
    }
}

?>

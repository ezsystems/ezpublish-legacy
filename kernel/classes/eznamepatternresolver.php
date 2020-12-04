<?php
/**
 * File containing the eZNamePatternResolver class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 *
 */

/**
 * eZNamePatternResolver is a utility class for resolving object name and url alias patterns.
 * This code supports object name pattern groups.
 *
 * Syntax:
 * <code>
 * &lt;attribute_identifier&gt;
 * &lt;attribute_identifier&gt; &lt;2nd-identifier&gt;
 * User text &lt;attribute_identifier&gt;|(&lt;2nd-identifier&gt;&lt;3rd-identifier&gt;)
 * </code>
 *
 * Example:
 * <code>
 * &lt;nickname|(&lt;firstname&gt; &lt;lastname&gt;)&gt;
 * </code>
 *
 * Tokens are looked up from left to right. If a match is found for the
 * leftmost token, the 2nd token will not be used. Tokens are representations
 * of attributes. So a match means that that the current attribute has data.
 *
 * tokens are the class attribute identifiers which are used in the class
 * edit-interface.
 *
 * @package kernel
 * @version //autogentag//
 */
class eZNamePatternResolver
{
    const FIELD_NAME_MAX_SIZE = 255;

    /**
     * Holds token groups
     *
     * @var array
     */
    private $groupLookupTable;

    /**
     * Contains the original name pattern entered
     *
     * @var string
     */
    private $origNamePattern;

    /**
     * Holds the filtered name pattern where token groups are replaced with
     * meta strings
     *
     * @var string
     */
    private $namePattern;

    /**
     * The content object which holds the attributes used to resolve name pattern.
     *
     * @var eZContentObject
     */
    private $contentObject;

    /**
     * Version number of the content object to fetch attributes from.
     *
     * @var int
     */
    private $version;

    /**
     * Contains the language locale for which to fetch attributes.
     *
     * @var string
     */
    private $translation;

    /**
     * Holds data fetched from content object attributes
     *
     * @var array(string=>string)
     */
    private $attributeArray;


    /**
     * The string to use to signify group tokens.
     *
     * @var string
     */
    private $metaString = 'EZMETAGROUP_';

    /**
     * eZNamePatternResolver constructor.
     * @param string $namePattern
     */
    public function __construct( $namePattern )
    {
        $this->origNamePattern = $namePattern;
        $this->namePattern = $this->filterNamePattern( $namePattern );
    }

    /**
     * Return the real name for an object name pattern. $contentVersion and
     * $contentTranslation specify which version and translation respectively
     * of the content object to use.
     *
     * @param eZContentObject $contentObject
     * @param int|false $contentVersion
     * @param string|false $contentTranslation
     * @param int $limit The limit on the string length, by defaul 0 aka none
     * @param string $sequence End sequence applied to string if limit has been reached
     * @return string
     */
    public function resolveNamePattern( eZContentObject $contentObject, $contentVersion = false, $contentTranslation = false, $limit = 0, $sequence = '' )
    {
        $this->contentObject = $contentObject;
        $this->version = $contentVersion;
        $this->translation = $contentTranslation;

        // Fetch attributes for present identifiers
        $this->fetchContentAttributes();

        // Replace tokens with real values
        $objectName = $this->translatePattern();

        $db = eZDB::instance();

        $limit = $limit ?: self::FIELD_NAME_MAX_SIZE;

        if ( $db->countStringSize( $objectName ) <= $limit )
        {
            return $objectName;
        }

        return preg_replace(
            "/[\pZ\pC]+$/u",
            '',
            $db->truncateString( $objectName, $limit, 'name', $sequence )
        ). $sequence;
    }

    /**
     * Fetches the list of available class-identifiers in the token, and it
     * will only fetch the attributes which appear amongst the identifiers
     * found in tokens.
     *
     * @return void
     */
    private function fetchContentAttributes()
    {
        $returnAttributeArray = array();

        $identifierArray = $this->getIdentifiers( $this->origNamePattern );

        $attributes = $this->contentObject->fetchAttributesByIdentifier( $identifierArray, $this->version, array( $this->translation ) );

        if ( is_array( $attributes ) )
        {
            foreach ( $attributes as $attribute )
            {
                $identifier = $attribute->contentClassAttributeIdentifier();
                $returnAttributeArray[$identifier] = $attribute->title();
            }
        }
        else
        {
            $returnAttributeArray = array();
        }
        $this->attributeArray = $returnAttributeArray;
    }


    /**
     * Replaces tokens in the name pattern with their resolved values.
     *
     * @return string
     */
    private function translatePattern()
    {
        $tokenArray = $this->extractTokens( $this->namePattern );
        $objectName = $this->namePattern;

        foreach( $tokenArray as $token )
        {
            $string = $this->resolveToken( $token );
            $objectName = str_replace( $token, $string, $objectName );
        }

        return $objectName;
    }

    /**
     * Extract all tokens from $namePattern
     *
     * Example:
     * <code>
     * Text &lt;token&gt; more text ==&gt; &lt;token&gt;
     * </code>
     *
     * @param string $namePattern
     * @return array
     */
    private function extractTokens( $namePattern )
    {
        $foundTokens = preg_match_all( "|<([^>]+)>|U", $namePattern,
                                                       $tokenArray );

        return $tokenArray[0];
    }

    /**
     * Looks up the value $token should be replaced with and returns this as
     * a string. Meta strings denothing token groups are automatically
     * inferred.
     *
     * @param string $token
     * @return string
     */
    private function resolveToken( $token )
    {
        $replaceString = "";
        $tokenParts = $this->tokenParts( $token );

        foreach ( $tokenParts as $tokenPart )
        {
            if ( $this->isTokenGroup( $tokenPart ) )
            {
                $groupTokenArray = $this->extractTokens( $this->groupLookupTable[$tokenPart] );
                $replaceString = $this->groupLookupTable[$tokenPart];

                foreach ( $groupTokenArray as $groupToken )
                {
                    $replaceString = str_replace( $groupToken, $this->resolveToken( $groupToken ), $replaceString );
                }
                // We want to stop after the first matching token part / identifier is found
                // <id1|id2> if id1 has a value, id2 will not be used.
                // In this case id1 or id1 is a token group.
                break;
            }
            else
            {
                if ( isset( $this->attributeArray[$tokenPart] ) && $this->attributeArray[$tokenPart] !== '' && $this->attributeArray[$tokenPart] !== false )
                {
                    $replaceString = $this->attributeArray[$tokenPart];
                    // We want to stop after the first matching token part / identifier is found
                    // <id1|id2> if id1 has a value, id2 will not be used.
                    break;
                }
            }
        }
        return $replaceString;
    }


    /**
     * Checks whether $identifier is a placeholder for a token group.
     *
     * @param string $identifier
     * @return void
     */
    private function isTokenGroup( $identifier )
    {
        if ( strpos( $identifier, $this->metaString ) === false )
        {
            return false;
        }
        return true;
    }

    /**
     * Return the different constituents of $token in an array.
     * The normal case here is that the different identifiers within one token
     * will be tokenized and returned.
     *
     * Example:
     * <code>
     * "&lt;title|text&gt;" ==&gt; array( 'title', 'text' )
     * </code>
     *
     * @param string $token
     * @return array
     */
    private function tokenParts( $token )
    {
        $tokenParts = preg_split( '#\W#', $token, -1, PREG_SPLIT_NO_EMPTY );
        return $tokenParts;
    }

    /**
     * Builds a lookup / translation table for groups in the $namePattern.
     * The groups are referenced with a generated meta-token in the original
     * name pattern.
     *
     * Returns intermediate name pattern where groups are replaced with meta-
     * tokens.
     *
     * @param string $namePattern
     * @return string
     */
    private function filterNamePattern( $namePattern )
    {
        $retNamePattern = "";
        $foundGroups = preg_match_all( "/[<|\|](\(.+\))[\||>]/U", $namePattern, $groupArray );

        if ( $foundGroups )
        {
            $i = 0;
            foreach ( $groupArray[1] as $group )
            {
                // Create meta-token for group
                $metaToken = $this->metaString . $i;

                // Insert the group with its placeholder token
                $retNamePattern = str_replace( $group, $metaToken, $namePattern );

                // Remove the pattern "(" ")" from the tokens
                $group = str_replace( array( '(', ')' ), '', $group );

                $this->groupLookupTable[$metaToken] = $group;
                ++$i;
            }
            return $retNamePattern;
        }
        return $namePattern;

    }

    /**
     * Returns all identifiers from all tokens in the name pattern.
     *
     * @param string $patternString
     * @return array
     */
    private function getIdentifiers( $patternString )
    {
        $allTokens = '#<(.*)>#U';
        $identifiers = '#\W#';

        $tmpArray = array();
        preg_match_all( $allTokens, $patternString, $matches );

        foreach ( $matches[1] as $match )
        {
            $tmpArray[] = preg_split( $identifiers, $match, -1, PREG_SPLIT_NO_EMPTY );
        }

        $retArray = array();
        foreach ( $tmpArray as $matchGroup )
        {
            if ( is_array( $matchGroup ) )
            {
                foreach ( $matchGroup as $item )
                {
                    $retArray[] = $item;
                }
            }
            else
            {
                $retArray[] = $matchGroup;
            }
        }
        return $retArray;
    }

    /**
     * Factory
     * @param string $namePattern
     * @return eZNamePatternResolver
     */
    static public function instance( $namePattern )
    {
        if(
            preg_match( '/^{(\w+?)}/', $namePattern, $matches ) &&
            class_exists( $matches[1] ) &&
            is_subclass_of( $matches[1], 'eZNamePatternResolver' )
        )
        {
            // Class name plus 2 curly brackets
            $remainingNamePattern = substr( $namePattern, strlen( $matches[1] ) + 2 );

            $instance = new $matches[1]( $remainingNamePattern );
        }
        else
        {
            $instance = new self( $namePattern );
        }

        return $instance;
    }
}


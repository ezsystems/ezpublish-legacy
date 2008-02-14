<?php
//
// Definition of eZNamePatternResolver class
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/**
 * File containing the eZNamePatternResolver class
 *
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
 * of atttributes. So a match means that that the current attribute has data.
 *
 * tokens are the class attribute identifiers which are used in the class
 * edit-interface.
 *
 * @package kernel
 * @version //autogentag//
 */
class eZNamePatternResolver
{
    /**
     * Holds token groups
     *
     * @var array
     */
    var $groupLookupTable;

    /**
     * Contains the original name pattern entered
     *
     * @var string
     */
    var $origNamePattern;

    /**
     * Holds the filtered name pattern where token groups are replaced with
     * meta strings
     *
     * @var string
     */
    var $namePattern;

    /**
     * The content object which holds the attributes used to resolve name pattern.
     *
     * @var eZContentObject
     */
    var $contentObject;

    /**
     * Version number of the content object to fetch attributes from.
     *
     * @var int
     */
    var $version;

    /**
     * Contains the language locale for which to fetch attributes.
     *
     * @var string
     */
    var $translation;

    /**
     * Holds data fetched from content object attributes
     *
     * @var array(string=>string)
     */
    var $attributeArray;


    /**
     * The string to use to signify group tokens.
     *
     * @var string
     */
    var $metaString = 'EZMETAGROUP_';

    /**
     * Constructs a object to resolve $namePattern. $contentVersion and
     * $contentTranslation specify which version and translation respectively
     * of the content object to use.
     *
     * @param string $namePattern
     * @param eZContentObject $contentObject
     * @param int $contentVersion
     * @param string $contentTranslation
     */
    function eZNamePatternResolver( $namePattern, $contentObject, $contentVersion = false, $contentTranslation = false )
    {
        $this->origNamePattern = $namePattern;
        $this->contentObject = $contentObject;
        $this->version = $contentVersion;
        $this->translation = $contentTranslation;

        $this->namePattern = $this->filterNamePattern( $namePattern);
    }

    /**
     * Return the real name for an object name pattern
     *
     * @param string $namePattern
     * @return string
     */
    function resolveNamePattern()
    {
        // Fetch attributes for present identifiers
        $this->fetchContentAttributes();

        // Replace tokens with real values
        $objectName = $this->translatePattern();

        return $objectName;
    }

    /**
     * Fetches the list of available class-identifiers in the token, and it
     * will only fetch the attributes which appear amongst the identifiers
     * found in tokens.
     *
     * @return void
     */
    function fetchContentAttributes()
    {
        $returnAttributeArray = array();

        $identifierArray = $this->getIdentifiers( $this->origNamePattern );

        $attributes = $this->contentObject->fetchAttributesByIdentifier( $identifierArray, $this->version, $this->translation );

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
    function translatePattern()
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
    function extractTokens( $namePattern )
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
    function resolveToken( $token )
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
                if ( array_key_exists( $tokenPart, $this->attributeArray ) and $this->attributeArray[$tokenPart] !== "" )
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
    function isTokenGroup( $identifier )
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
    function tokenParts( $token )
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
    function filterNamePattern( $namePattern )
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
    function getIdentifiers( $patternString )
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
}
?>

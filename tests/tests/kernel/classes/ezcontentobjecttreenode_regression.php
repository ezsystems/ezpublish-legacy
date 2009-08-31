<?php
/**
 * File containing the eZContentObjectRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectTreeNodeRegression extends ezpDatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
    * Test for regression #13497:
    * attribute operator throws a PHP fatal error on a node without parent in a displayable language
    *
    * Situation:
    *  - siteaccess with one language (fre-FR) and ShowUntranslatedObjects disabled
    *  - parent content node in another language (eng-GB) with always available disabled
    *  - content node in the siteaccess' language (fre-FR)
    *  - fetch this fre-FR node from anywhere, and call attribute() on it
    *
    * Result:
    *  - Fatal error: Call to a member function attribute() on a non-object in
    *    kernel/classes/ezcontentobjecttreenode.php on line 4225
    *
    * Explanation: the error actually comes from the can_remove_location attribute
    **/
    public function testIssue13497()
    {
        // Create a folder in english only
        $folder = new ezpObject( "folder", 2, 14, 1, 'eng-GB' );
        $folder->setAlwaysAvailableLanguageID( false );
        $folder->name = "Parent for " . __FUNCTION__;
        $folder->publish();

        // Create an article in french only, as a subitem of the previously created folder
        $article = new ezpObject( "article", $folder->attribute( 'main_node_id' ), 14, 1, 'fre-FR' );
        $article->title = "Object for " . __FUNCTION__;
        $article->publish();

        $articleNodeID = $article->mainNode->node_id;

        // INi changes: set language to french only, untranslatedobjects disabled
        $this->setINISetting( 'site.ini', 'RegionalSettings', 'ContentObjectLocale', 'fre-FR' );
        $this->setINISetting( 'site.ini', 'RegionalSettings', 'SiteLanguageList', array( 'fre-FR' ) );
        $this->setINISetting( 'site.ini', 'RegionalSettings', 'ShowUntranslatedObjects', 'disabled' );
        eZContentLanguage::expireCache();

        // This should crash
        eZContentObjectTreeNode::fetch( $articleNodeID )->attribute( 'can_remove_location' );

        $this->restoreINISettings();

        // re-expire cache for further tests
        eZContentLanguage::expireCache();
    }

    /**
    * Test for regression #15211
    *
    * The issue was reported as happening when a fetch_alias was called without
    * a second parameter. It turns out that this was just wrong, but led to the
    * following: if eZContentObjectTreeNode::createAttributeFilterSQLStrings
    * is called with the first parameter ($attributeFilter) is a string, a fatal
    * error "Cannot unset string offsets" is thrown.
    *
    * Test: Call this function with a string as the first parameter. Without the
    * fix, a fatal error occurs, while an empty filter is returned once fixed.
    **/
    public function testIssue15211()
    {
        $attributeFilter = "somestring";

        // Without the fix, this is a fatal error
        $filterSQL = eZContentObjectTreeNode::createAttributeFilterSQLStrings(
            $attributeFilter );

        $this->assertType( 'array', $filterSQL );
        $this->assertArrayHasKey( 'from', $filterSQL );
        $this->assertArrayHasKey( 'where', $filterSQL );
    }

    /**
    * Changes an INI setting value
    *
    * @param string $file INI filename
    * @param string $block INI block name
    * @param string $variable INI variable name
    * @param mixed $value The new value
    *
    * @see restoreINISettings() to restore all the modified INI settings
    **/
    protected function setINISetting( $file, $block, $variable, $value )
    {
        $ini = eZINI::instance( $file );

        // backup the value
        $this->modifiedINISettings[] = array( $file, $block, $variable, $ini->variable( $block, $variable ) );

        // change the value
        $ini->setVariable( $block, $variable, $value );
    }

    /**
     * Restores all the INI settings previously modified using setINISetting
     *
     * @return void
     */
    protected function restoreINISettings()
    {
        // restore each changed value
        foreach ( $this->modifiedINISettings as $key => $values )
        {
            list( $file, $block, $variable, $value ) = $values;
            $ini = eZINI::instance( $file );
            $ini->setVariable( $block, $variable, $value );
        }
    }

    /**
    * Modified INI settings, as an array of 4 keys array:
    * file, block, variable, value
    * @var array
    **/
    protected $modifiedINISettings = array();
}
?>
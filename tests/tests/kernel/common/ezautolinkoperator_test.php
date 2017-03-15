<?php
/**
 * File containing the eZAutoLinkOperatorTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

/**
 * Test case for eZAutoLinkOperator class
 */
class eZAutoLinkOperatorTest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZAutoLinkOperator Tests" );
    }

    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    /**
     * test autolink operator with simple http url
     */
    public function testeZAutoLinkOperatorSimpleHttpFullUrlLink()
    {
        $maxChars = 32;
        $argument = 'Heath ( http://example.com/p/10979 )';
        $expectedResult = 'Heath ( <a href="http://example.com/p/10979" title="http://example.com/p/10979">http://example.com/p/10979</a> )';

        $this->runModify( 'autolink', $maxChars, $argument, $expectedResult );
    }

    /**
     * test autolink operator with simple https url
     */
    public function testeZAutoLinkOperatorSimpleHttpsFullUrlLink()
    {
        $maxChars = 32;
        $argument = 'Heath ( https://example.com/p/10979 )';
        $expectedResult = 'Heath ( <a href="https://example.com/p/10979" title="https://example.com/p/10979">https://example.com/p/10979</a> )';

        $this->runModify( 'autolink', $maxChars, $argument, $expectedResult );
    }

    /**
     * test autolink operator with simple email address
     */
    public function testeZAutoLinkOperatorSimpleEmailLink()
    {
        $maxChars = 72;
        $argument = 'Heath ( heath@example.com )';
        $expectedResult = "Heath ( <a href='mailto:heath@example.com'>heath@example.com</a> )";

        $this->runModify( 'autolink', $maxChars, $argument, $expectedResult );
    }

    /**
     * test autolink operator with simple ftp url
     */
    public function testeZAutoLinkOperatorSimpleFtpFullUrlLink()
    {
        $maxChars = 72;
        $argument = 'Heath ( ftp://example.com/pub/mockbinaryfile.tar.gz )';
        $expectedResult = 'Heath ( <a href="ftp://example.com/pub/mockbinaryfile.tar.gz" title="ftp://example.com/pub/mockbinaryfile.tar.gz">ftp://example.com/pub/mockbinaryfile.tar.gz</a> )';

        $this->runModify( 'autolink', $maxChars, $argument, $expectedResult );
    }

    /**
     * test autolink operator function
     */
    private function runModify( $operatorName, $maxChars, $argument, $expectedResult )
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();
        $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        $this->setSiteAccess( $defaultAccess );

        // Make sure to preserve ini settings in case other tests depend on them
        $orgRemoveSiteaccess = $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' );

        // ENABLE RemoveSiteAccessIfDefaultAccess
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', 'enabled' );
        // -------------------------------------------------------------------

        // TEST --------------------------------------------------------------
        $operator = new eZAutoLinkOperator();
        $tpl = eZTemplate::instance();

        $operatorValue = $argument;

        $operatorParameters = array();

        $namedParameters = array(
            'max_chars'  => $maxChars
        );

        $operator->modify(
            $tpl, $operatorName, $operatorParameters, '', '', $operatorValue, $namedParameters, false
        );

        $this->assertEquals( $expectedResult, $operatorValue );
        // -------------------------------------------------------------------
        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', $orgRemoveSiteaccess );
        eZSys::clearAccessPath();
        // -------------------------------------------------------------------
    }

    /* -----------------------------------------------------------------------
     * HELPER FUNCTIONS
     * -----------------------------------------------------------------------
     */
     private function setSiteAccess( $accessName )
     {
         eZSiteAccess::change( array( 'name' => $accessName,
                                      'type' => eZSiteAccess::TYPE_URI,
                                      'uri_part' => array( $accessName ) ) );
     }
}

?>
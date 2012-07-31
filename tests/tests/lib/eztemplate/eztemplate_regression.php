<?php
class eZTemplateRegression extends ezpDatabaseTestCase
{
    /**
     * @var eZTemplate
     */
    private $tpl;

    public function setUp()
    {
        parent::setUp();
        $this->tpl = eZTemplate::factory();
    }

    public function tearDown()
    {
        unset( $this->tpl );
        parent::tearDown();
    }

    /**
     * Test for issue #12285
     * Variables set by functions handled by eZObjectForwarder (like attribute_view_gui) are not scope safe.
     * The patch for this issue ensures that, if requested, a template variable can be backed up in a safe namespace
     * (same var name but different namespace), so that it can't be overridden
     * @param string $varName
     * @param string $val
     * @param string $namespace
     * @link http://issues.ez.no/12285
     * @group issue12285
     */
    public function testSetScopeSafeVariable()
    {
        $namespace = "myNamespace";
        $varName = "foo";
        $aVal = array( "bar", "baz", "biz" ); // Simulate 3 level of nesting
        $previousVal = null;
        $nestingLevel = 0;

        // Try to set different values to a tpl variable to emulate a nested set like described in the issue
        foreach ( $aVal as $val )
        {
            $this->tpl->setVariable( $varName, $val, $namespace, true );
            self::assertEquals( $val, $this->tpl->variable( $varName, $namespace ), "Template variable has not been correctly set" );

            if ( !empty( $previousVal ) )
            {
                $safeNamespace = $namespace . str_repeat( ":safe", $nestingLevel );
                self::assertEquals( $previousVal, $this->tpl->variable( $varName, $safeNamespace ), "Template variable has not been backed up in a safe namespace" );
            }

            $previousVal = $val;
            $nestingLevel++;
        }

        // Now unset the tpl variables and check that they are well restored
        $aUnsetVal = array_reverse( $aVal );
        // Nesting level must decrease to 0, so decrease it first
        $nestingLevel -= 1;
        foreach ( $aUnsetVal as $val )
        {
            $safeNamespace = $namespace . str_repeat( ":safe", $nestingLevel );
            $this->tpl->unsetVariable( $varName, $namespace );
            self::assertNull( $this->tpl->variable( $varName, $safeNamespace ), "Backed up tpl variable has not been deleted" );

            $nestingLevel--;
            // Check that backed up value is correctly restored
            if ( $nestingLevel >= 0 )
            {
                self::assertEquals( $aVal[$nestingLevel], $this->tpl->variable( $varName, $namespace ), "Backed up tpl variable has not been properly restored from safe namespace" );
            }
            else
            {
                self::assertNull( $this->tpl->variable( $varName, $namespace ), "Template variable has not been unset" );
            }
        }
    }
}
?>

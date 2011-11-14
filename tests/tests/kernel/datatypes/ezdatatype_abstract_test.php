<?php
/**
 * File containing the eZDatatypeTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

/**
 * Base class for all datatype implementations
 */
abstract class eZDatatypeAbstractTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /**
     * The tested datatype. Set by children constructor.
     * @var string
     */
    protected $datatype = null;

    /**
     * Registered test data sets
     * @see setDataSet()
     * @var array(ezpDatatypeTestDataSet)
     */
    protected $dataSets = array();

    /**
     * The test attribute identifier
     * @var string
     */
    protected $attributeIdentifier;

    /**
     * Test content class
     * @var ezpClass
     */
    protected $class;

    /**
     * Test content object
     * @var ezpObject
     */
    protected $object;

    /**
     * The currently tested attribute
     * @var eZContentObjectAttribute
     */
    protected $attribute;

    public function __construct( $name = null, array $data = array(), $dataName = '' )
    {
        parent::__construct( $name, $data, $dataName );

        if ( $this->datatype === null )
            throw new ezcBaseValueException( "The test datatype hasn't been set" );
    }

    /**
     * Setup:
     * - creates a new content class with the tested datatype
     * - instanciates a content object of that class with the default dataset
     */
    public function setUp()
    {
        // create a content class with this datatype
        // @todo Add naming pattern handling
        parent::setUp();

        $contentClassName = "Datatype {$this->datatype} test";
        $contentClassIdentifier = "datatype_{$this->datatype}_test";
        $this->attributeIdentifier = "{$this->datatype}_test";
        $this->class = new ezpClass( $contentClassName, $contentClassIdentifier, '<name>' );
        $this->class->add( 'Title', 'title', 'ezstring' );
        $this->class->add( "Test {$this->datatype}", $this->attributeIdentifier, $this->datatype );
        $this->class->store();
    }

    public function tearDown()
    {
        if ( $this->object !== null )
            $this->destroyObject();
        eZContentClassOperations::remove( $this->class->id );
        parent::tearDown();
    }

    public function createObject( ezpDatatypeTestDataSet $dataSet )
    {
        static $counter = 0;

        $this->object = new ezpObject( $this->class->identifier, 2 );
        $this->object->title = "{$this->datatype} test #" . ++$counter;
        $dataMap = $this->object->dataMap();
        $this->attribute = $dataMap[$this->attributeIdentifier];

        try {
            $attribute = $this->attribute->fromString( $dataSet->fromString );
        } catch( PHPUnit_Framework_Error_Notice $e ) {
            self::fail( "A notice has been thrown when calling fromString: " . $e->getMessage() );
        }
        $this->object->publish();
    }

    protected function destroyObject()
    {
        eZContentObjectOperations::remove( $this->object->id );
        $this->object = null;
        $this->attribute = null;
    }

    /**
     * Adds a test dataset
     * @param string $name The set's name
     * @param ezpDatatypeTestDataSet $dataSet
     */
    protected function setDataSet( $name, $dataSet )
    {
        if ( !$dataSet instanceof ezpDatatypeTestDataSet )
            throw new ezcBaseValueException( '\$dataSet argument isn\'t an ezpDatatypeTestDataSet' );

        $this->dataSets[$name] = $dataSet;
    }

    /**
     * Sets the test datatype
     * @param string $datatype The tested datatype: ezstring, ezmatrix...
     */
    protected function setDatatype( $datatype )
    {
        $this->datatype = $datatype;
    }

    /**
     * Returns a dataset
     *
     * @param string $dataSetName The dataset's name. Ex: default.
     * @return ezpDatatypeTestDataSet
     */
    public function dataSet( $dataSetName = 'default' )
    {
        if ( !isset( $this->dataSets[$dataSetName] ) )
            throw new ezcBaseValueException( "No such dataset '$dataSetName'" );

        return $this->dataSets[$dataSetName];
    }

    public function globalDataProvider()
    {
        return array( $this->dataSets );
    }

    /**
     * Tests the attribute's content
     */
    public function testContent()
    {
        $this->createObject( $this->dataSet() );
        self::assertEquals( $this->attribute->content(), $this->dataSet()->content );
        $this->destroyObject();
    }

    /**
     * Tests the attribute's content
     */
    public function testDataText()
    {
        $this->createObject( $this->dataSet() );
        self::assertEquals( $this->attribute->attribute( 'data_text' ), $this->dataSet()->dataText );
        $this->destroyObject();
    }

    /**
     * Tests the attribute's content
     */
    public function testDataInt()
    {
        $this->createObject( $this->dataSet() );
        self::assertEquals( $this->attribute->attribute( 'data_int' ), $this->dataSet()->dataInt );
        $this->destroyObject();
    }

    /**
     * Tests the attribute's content
     */
    public function testDataFloat()
    {
        $this->createObject( $this->dataSet() );
        self::assertEquals( $this->attribute->attribute( 'data_float' ), $this->dataSet()->dataFloat );
        $this->destroyObject();
    }
}
?>

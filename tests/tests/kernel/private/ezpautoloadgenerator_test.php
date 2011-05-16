<?php
/**
 * File containing the ezpAutoloadGeneratorTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezpAutoloadGeneratorTest extends PHPUnit_Framework_TestCase
{
  private $autoload_generator;

  public function setUp()
  {
    $this->autoload_generator = new eZAutoloadGenerator();
  }

  public function testBuildPHPUnitConfigurationFile()
  {
    $autoloadArray = @include 'autoload/ezp_kernel.php';
    
    $this->assertEquals( null, $this->autoload_generator->buildPHPUnitConfigurationFile() );

    $this->autoload_generator->setMode(eZAutoloadGenerator::MODE_KERNEL);

    $dom = $this->autoload_generator->buildPHPUnitConfigurationFile();

    $this->assertInstanceOf('DomDocument', $dom);
    $this->assertTrue($dom->hasChildNodes());

    $elements = $dom->getElementsByTagName('filter');
    $this->assertEquals(1, $elements->length);

    $filter = $elements->item(0);
    $blacklist = $filter->getElementsByTagName('blacklist');
    $whitelist = $filter->getElementsByTagName('whitelist');
    $this->assertEquals(1, $blacklist->length);
    $this->assertEquals(1, $whitelist->length);

    $this->assertContains('tests', $blacklist->item(0)->getElementsByTagName('directory')->item(0)->nodeValue);
    $this->assertEquals(count($autoloadArray), $whitelist->item(0)->getElementsByTagName('file')->length);

    
    //$this->assertTrue($dom->hasChildNodes('filter'));

    //echo $dom->saveXML();
  }
}
?>

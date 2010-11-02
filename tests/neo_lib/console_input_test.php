<?php
include "neo_lib/console_input.php";
class ConsoleInputTest extends PHPUnit_Framework_TestCase
{
     public function testConsole()
     {
         $a = new neoConsoleInput();
         $a->addOption("f", "file");

         list($options, $arguments) = $a->process();
     }

     
     public function testHelp()
     {
         $a = new neoConsoleInput("program [ARGS]");
         $a->exitAfterHelp = false;
         ob_start();
         $a->addOption("f", "file");
         list($options, $arguments) = $a->process(array("myProgram", "-h"));

         $out = ob_get_clean();
         $this->assertEquals("program [ARGS]\n-h / --help  display this help and exit.\n-f / --file  No help available.\n", $out);
     }

     public function testStore()
     {
         $a = new neoConsoleInput("program [ARGS]");
         $a->exitAfterHelp = false;
         $a->addOption("c", "continue", array("action" => "store_true", "dest" => "continue_error", "help" => "Continue on errors.", "default" => false));
         $a->addOption("f", "file", array("action" => "store", "dest" => "file", "help" => "Process the file.", "default" => false));

         list($options, $arguments) = $a->process(array("myProgram", "-c", "--file", "aap", "myarg1", "myarg2"));
 
         $this->assertTrue($options->continue_error);
         $this->assertEquals("aap", $options->file);
         $this->assertEquals("myarg2", $arguments[1]);

     }


     public function testAA()
     {
         $a = new neoConsoleInput("program [ARGS]");
         $a->exitAfterHelp = false;
         $a->addOption("c", "continue", array("action" => "store_true", "dest" => "continue_error", "help" => "Continue on errors.", "default" => false));
         $a->addOption("f", "file", array("action" => "store", "dest" => "file", "help" => "Process the file.", "default" => false));

         list($options, $arguments) = $a->process(array("myProgram",  "-f", "test",  "myarg2"));
         $this->assertFalse($options->continue_error);
         $this->assertEquals("test", $options->file);
         $this->assertEquals("myarg2", $arguments[0]);
     }



#     public function testTemp()
#     {
#         $input = new ezcConsoleInput();
#         $input->registerOption( new ezcConsoleOption( 'i', 'input', ezcConsoleInput::TYPE_STRING));
#         $input->process(array("myProg", "-i", "bla"));
#
#
#         $input->process(array("myProg", "-i", "bla"));
#
#     }


}
?>

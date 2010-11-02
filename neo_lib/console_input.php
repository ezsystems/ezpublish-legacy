<?php

class neoDynamicStruct
{
    protected $properties = array();

    public function __get( $name )
    {
        return $this->properties[$name];
    }

    public function __set( $name, $value )
    {
        $this->properties[$name] = $value;
    }

    public function __isset( $name )
    {
        return isset($this->properties[$name]);
    }
}


class neoConsoleOptions extends neoDynamicStruct
{
}


/**
 * Usage:
 *
 *  $a = new neoConsoleInput("program [ARGS]");
 *  $a->addOption("c", "continue", array("action" => "store_true", "dest" => "continue_error", "help" => "Continue on errors.", "default" => false));
 *  $a->addOption("f", "file", array("action" => "store", "dest" => "file", "help" => "Process the file.", "default" => false));
 *
 *  list($options, $arguments) = $a->process(array("myProgram",  "-f", "test",  "myarg2"));
 *  if( $options->continue_error ) .... 
 */
class neoConsoleInput extends ezcConsoleInput 
{
    protected $helpOption;
    protected $helpUsage;
    protected $consoleOptions;
    public $exitAfterHelp = true;

    public function __construct( $usage = "%prog [ARGS]\n")
    {
        parent::__construct();
        $this->helpOption = $this->registerOption( new ezcConsoleOption( 'h', 'help', self::TYPE_NONE, null, false, "display this help and exit." ) );
        $this->helpUsage = $usage;
        $this->consoleOptions = array();
    }

    public function addOption($short, $long, $namedParameters = array() ) 
    {
        $type = self::TYPE_STRING;
        $action = "store";
        if (isset($namedParameters["action"]) )
        {
            $action = $namedParameters["action"];

            switch( $action )
            {
                #case "store": break;
                case "store_true":
                case "store_false":
                    $type = self::TYPE_NONE;
                    break;

                case "store":
                    $type = self::TYPE_STRING;
                    break;
            }
        }

        $default = null;
        if (isset($namedParameters["default"]))
        {
            $default = $namedParameters["default"];
        }

        $help = null;
        if (isset($namedParameters["help"]))
        {
            $help = $namedParameters["help"];
        }

        $dest = $long;
        if (isset($namedParameters["dest"]))
        {
            $dest = $namedParameters["dest"];
        }


        $co = new ezcConsoleOption($short, $long, $type, $default, false, $help);
        $this->consoleOptions[] = array($co, $dest, $action);
        $this->registerOption($co);
    }


    public function process( array $args = null)
    {
        try
        {
            parent::process($args);
        }
        catch ( ezcConsoleOptionException $e )
        {
            echo $e->getMessage();
            exit( 1 );
        }

        if ( $this->helpOption->value === true )
        {
            $synopsis = ( isset( $argv ) && sizeof( $argv ) > 0 ? $argv[0] : $_SERVER['argv'][0] );
            echo str_replace( "%prog", $synopsis, $this->helpUsage );
            echo "\n";
            echo $this->getMyHelpText();
            if( $this->exitAfterHelp ) exit(0);
            else return; 
        }

        $optObj = new neoConsoleOptions();
        foreach ( $this->consoleOptions as $opt )
        {
            if( $opt[0]->value !== false)
            {
                $optObj->$opt[1] = $this->processAction($opt[2], $opt[0]->value);
            }
            else
            {
                $optObj->$opt[1] = $opt[0]->value;
            }
        }

        return array($optObj, $this->getArguments());
    }

    public function  processAction( $action, $value )
    {
        switch( $action )
        {
            case "store_true": return true;
            case "store_false": return false;
            case "store": 
            default:
                return $value;
        }

    }

    public function getMyHelpText()
    {
        $width = 80;
        $help = $this->getHelp( false, array());
        // Determine max length of first column text.
        $maxLength = 0;
        foreach ( $help as $row )
        {
            $maxLength = max( $maxLength, strlen( $row[0] ) );
        }
        $leftColWidth = $maxLength + 2;
        $rightColWidth = $width - $leftColWidth;

        $res = "";
        foreach ( $help as $row )
        {
            $rowParts = explode( "\n", wordwrap( $row[1], $rightColWidth ) );
            $res .= sprintf( "%-{$leftColWidth}s", $row[0] );
            $res .= $rowParts[0] . PHP_EOL;
            for ( $i = 1; $i < sizeof( $rowParts ); $i++ )
            {
                $res .= str_repeat( ' ', $leftColWidth ) . $rowParts[$i] . PHP_EOL;
            }
        }
        return $res;

   }


}

?>

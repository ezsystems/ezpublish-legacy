<?php

class Runtime
{
    private $file;

    public function __construct( $file )
    {
        if ( basename( realpath( "." ) ) == '.run' )
        {
            $file = "../" . $file;
        }
        $this->file = fopen($file, "a");
    }

    public function add($uniqueName, $entry)
    {
        fwrite( $this->file, "['".$uniqueName ."', " . '"""' . $entry . '"""' . "],\n");
    }
}



?>

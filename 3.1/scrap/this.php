<?php

class p
{
    function p()
    {
    }

    function attribute( $attr )
    {
        $def = $this->definition();
        $fields = $def["fields"];
        $functions = $def["functions"];
        if ( isset( $fields[$attr] ) )
        {
            $varname = $fields[$attr];
            $val = $this->$varname;
            print( "Attribute '$attr'='$val'\n" );
        }
        else if ( isset( $functions[$attr] ) )
        {
            $function = $functions[$attr];
            $val = $this->$function();
        }
        else
            print( "No attribute match '$attr'\n" );
    }
}

class c extends p
{
    function c()
    {
        $this->p();
    }

    function definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "name" => "Name" ),
                      "functions" => array( "work" => "work" ) );
    }

    function attribute( $attr )
    {
        return p::attribute( $attr );
    }

    function work()
    {
        print( "work\n" );
        print( "classname = '" . get_class( $this ) . "'\n" );
        var_dump( $this );
    }

    var $ID = 1;
    var $Name = "abc";
};


class d
{
    function d()
    {
        $this->C = new c();
    }

    function run()
    {
        $this->C->attribute( "id" );
        $this->C->attribute( "name" );
        $this->C->attribute( "work" );
    }
}

$d = new d();
$d->run();

?>

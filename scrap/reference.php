<?php

class node
{
    function node( $name, $children = array() )
    {
        $this->Name = $name;
        $this->Children = $children;
    }

    function name()
    {
        return $this->Name;
    }

    function children()
    {
        return $this->Children;
    }

    var $Name;
    var $Children;
}

// class node
// {
//     function node( $name, &$children )
//     {
//         $this->Name =& $name;
//         $this->Children =& $children;
//     }

//     function &name()
//     {
//         return $this->Name;
//     }

//     function &children()
//     {
//         return $this->Children;
//     }

//     var $Name;
//     var $Children;
// }

$noc = array();

$n12 = new node( "n12", $noc );
$nc11 = array( $n12 );
$n11 = new node( "n11", $nc11 );
$nc10 = array( $n11 );
$n10 = new node( "n10", $nc10 );
$nc9 = array( $n10 );
$n9 = new node( "n9", $nc9 );

$n8 = new node( "n8", $noc );
$nc7 = array( $n8 );
$n7 = new node( "n7", $nc7 );
$n6 = new node( "n6", $noc );
$n5 = new node( "n5", $noc );
$nc4 = array( $n5, $n6, $n7 );
$n4 = new node( "n4", $nc4 );

$n3 = new node( "n3", $noc );
$n2 = new node( "n2", $noc );
$nc1 = array( $n1, $n2 );
$n1 = new node( "n1", $nc1 );

$ncroot = array( $n1, $n4, $n9 );
$root = new node( "root", $ncroot );

$c = array();
$c[0] = $n9->children();

var_dump( $root );
var_dump( $c );


?>

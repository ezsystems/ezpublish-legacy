<?php
 /*
  * T_ML_COMMENT does not exist in PHP 5.
  * The following three lines define it in order to
  * preserve backwards compatibility.
  *
  * The next two lines define the PHP 5 only T_DOC_COMMENT,
  * which we will mask as T_ML_COMMENT for PHP 4.
  */
if (!defined( 'T_ML_COMMENT') )
{
    define( 'T_ML_COMMENT', T_COMMENT );
}
else
{
    define( 'T_DOC_COMMENT', T_ML_COMMENT );
}

$source = file_get_contents( 'example.php' );
$tokens = token_get_all( $source );

$i = 0;
while ( $i < count( $tokens ) )
{
    $token = $tokens[$i];
    if ( is_string( $token ) )
    {
        // simple 1-character token
        echo "'$token'";
    }
    else
    {
        // token array
        list( $id, $text ) = $token;

        switch ( $id )
        {
            case T_COMMENT:
            case T_ML_COMMENT: // we've defined this
            case T_DOC_COMMENT: // and this
            {
//                echo $text;
                // no action on comments
            } break;

            case T_CLASS:
            {
                handleClass( $id, $text, $i, $tokens );
            } break;

            default:
            {
                // anything else -> output "as is"
                echo "[$id=" . token_name( $id ) . "]'$text'";
            } break;
        }
    }
    ++$i;
}

function isToken( $id, $i, $tokens )
{
    return ( isset( $tokens[$i] ) and
             $tokens[$i][0] == $id );
}

function checkClassName( $id, $text )
{
    return true;
}

function checkMethodName( $id, $text )
{
    return true;
}

function checkMemberVariableName( $id, $text )
{
    return true;
}

function checkExpectedTokens( $expectedList, &$i, $tokens )
{
    $istart = $i;
    $offset = 0;
    foreach ( $expectedList as $expected )
    {
        ++$offset;
        if ( !isset( $tokens[$i] ) )
        {
            print( "No more tokens\n" );
            return false;
        }
        $eID = $expected[0];
        if ( is_array( $eID ) )
        {
            $subExpectedList = $eID;
            $subMatched = false;
            $subOffset = 0;
            foreach ( $subExpectedList as $subExpected )
            {
                ++$subOffset;
                print( "Starting subexpected list ($subOffset) for offset $offset\n" );
                $subIndex = $i;
                if ( checkExpectedTokens( $subExpected, $subIndex, $tokens ) )
                {
                    print( "Ended subexpected list ($subOffset) for offset $offset=success\n" );
                    $i = $subIndex;
                    $subMatched = true;
                    break;
                }
                print( "Ended subexpected list ($subOffset) for offset $offset=failed\n" );
            }
            if ( !$subMatched )
            {
                print( "None of the sub expected lists matched at offset $offset\n" );
                return false;
            }
//            ++$i;
            continue;
        }
        else if ( is_string( $eID ) )
        {
            if ( is_string( $tokens[$i] ) )
            {
                $str = $tokens[$i];
                if ( $str == $eID )
                {
                    ++$i;
                    continue;
                }
                else
                {
                    print( "Strings do not match at offset $i ($offset), '$str' != '$eID'\n" );
                    return false;
                }
            }
            else
            {
                list( $id, $text ) = $tokens[$i];
                print( "Expected a string for token offset $i ($offset), got the token '" . token_name( $id ) . "'='$text'\n" );
                return false;
            }
        }
        else
        {
            if ( !is_string( $tokens[$i] ) )
            {
                list( $id, $text ) = $tokens[$i];
                if ( $id == $eID )
                {
                    // Check for strlen or string match
                    if ( isset( $expected[1] ) and
                         is_string( $expected[1] ) )
                    {
                        $match = $expected[1];
                        if ( $match == $text )
                        {
                            ++$i;
                            continue;
                        }
                        else
                        {
                            print( "Token strings do not match at offset $i ($offset), '$text' != '$match'\n" );
                            return false;
                        }
                    }
                    else if ( isset( $expected[1] ) and
                              is_bool( $expected[1] ) )
                    {
                        if ( !isset( $expected[2] ) )
                        {
                            print( "Missing function name (offset 2)\n" );
                            return false;
                        }
                        $function = $expected[2];
                        if ( !function_exists( $function ) )
                        {
                            print( "Function '$function' does not exist\n" );
                            return false;
                        }
                        if ( $function( $id, $text ) )
                        {
                            ++$i;
                            continue;
                        }
                        else
                        {
                            print( "Token string match function '$function' did not succeed at offset $i ($offset)\n" );
                            return false;
                        }
                    }
                    else if ( isset( $expected[1] ) and
                              is_int( $expected[1] ) )
                    {
                        $len = $expected[1];
                        if ( strlen( $text ) == $len )
                        {
                            ++$i;
                            continue;
                        }
                        else
                        {
                            print( "Token string length do not match at offset $i ($offset), strlen( '$text' ) != '$len'\n" );
                            return false;
                        }
                    }
                    else
                    {
                        ++$i;
                        continue;
                    }
                }
                else
                {
                    print( "Tokens do not match at offset $i ($offset), '" . token_name( $id ) . "' != '" . token_name( $eID ) . "'\n" );
                    return false;
                }
            }
            else
            {
                print( "Expected the token '" . token_name( $eID ) . "' for token offset $i ($offset), got the string '" . $tokens[$i] . "'\n" );
                return false;
            }
        }
    }
    return true;
}

function handleClass( $id, $text, &$i, $tokens )
{
//     $expected = array( array( T_WHITESPACE, 1 ),
//                        array( T_STRING, false, 'checkClassName' ),
//                        array( '(' ),
//                        array( T_WHITESPACE, 1 ) );
    $classStart = array( array( T_WHITESPACE, 1 ),
                         array( T_STRING, false, 'checkClassName' ),
                         array( array( array( array( '(' ),
                                              array( T_WHITESPACE, 1 ) ),
                                       array( array( T_WHITESPACE, 1 ),
                                              array( T_EXTENDS ),
                                              array( T_WHITESPACE, 1 ),
                                              array( T_STRING, false, 'checkClassName' ) ) ) ),
                         array( T_WHITESPACE ),
                         array( '{' ) );
    $methodParameters = array();
    $classFunctionEntry = array( array( T_FUNCTION ),
                                 array( T_WHITESPACE, 1 ),
                                 array( T_STRING, false, 'checkMethodName' ),
                                 array( '(' ),
                                 array( array( array( $methodParameters ),
                                               array() ) ),
                                 array( ')' ) );
    $classVariableEntry = array( array( T_VAR ),
                                 array( T_WHITESPACE, 1 ),
                                 array( T_VARIABLE, false, 'checkMemberVariableName' ) );
    $classEntry = array( array( T_WHITESPACE ),
                         array( array( $classFunctionEntry,
                                       $classVariableEntry ) ) );
    ++$i;
    $startI = $i;
    print( "Checking class start\n" );
    if ( !checkExpectedTokens( $classStart, $startI, $tokens ) )
    {
        print( "Class start match failed\n" );
        return false;
    }

    while ( $i < count( $tokens ) )
    {
        print( "Checking class entry\n" );
        if ( !checkExpectedTokens( $classEntry, $startI, $tokens ) )
        {
            print( "Class entry match failed\n" );
            return false;
        }
    }

    $i = $startI;
    return true;
}

?>

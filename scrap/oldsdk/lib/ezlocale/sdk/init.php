<?php
// Standard locale init, included by other examples

// include standard libs
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

include_once( "lib/ezlocale/classes/ezlocale.php" );

function printSelectBox( $langs, $countries, $current_lang, $current_country,
                         $show_lang = true, $show_country = true )
{
    print( '<table>' );
    if ( $show_lang )
    {
        print( '<tr><td><b>Language:</b></td>
<td><select name="Language">' );
        foreach( $langs as $lang )
        {
            print( '<option value="' . $lang . '"' );
            if ( $lang == $current_lang )
                print( ' selected' );
            print( '>' . $lang . '</option>' . "\n" );
        }
        print( '</select></td></tr>' );
    }
    if ( $show_country )
    {
        print( '<tr><td><b>Country:</b></td>
<td><select name="Country">' );
        foreach( $countries as $country )
        {
            print( '<option value="' . $country . '"' );
            if ( $country == $current_country )
                print( ' selected' );
            print( '>' . $country . '</option>' . "\n" );
        }
        print( '</select></td></tr>' );
    }
    print( '<tr><td></td><td><input type="submit" value="Update" /></td></tr></table>' );
}

$ini =& eZINI::instance();

include_once( 'lib/ezutils/classes/ezhttptool.php' );
$http =& eZHTTPTool::instance();

$localeString = false;
if ( $http->hasPostVariable( "Locale" ) )
    $localeString = $http->postVariable( "Locale" );

$locale =& eZLocale::instance( $localeString );

?>

<?php

$redirectURIList = array( '',
                          'index.php',
                          'somedir/index.php/admin/' );

$moduleRedirectURIList = array( '',
                                'list',
                                '/content/view/full' );


foreach ( $redirectURIList as $uri )
{
    foreach ( $moduleRedirectURIList as $moduleRedirectUri )
    {
        $redirectURI = $uri;
        print( "redirectURI='$redirectURI'\n" );
        print( "moduleRedirectUri='$moduleRedirectUri'\n" );

        $leftSlash = false;
        $rightSlash = false;
        if ( strlen( $redirectURI ) > 0 and
             $redirectURI[strlen( $redirectURI ) - 1] == '/' )
            $leftSlash = true;
        if ( strlen( $moduleRedirectUri ) > 0 and
             $moduleRedirectUri[0] == '/' )
            $rightSlash = true;
        if ( !$leftSlash and !$rightSlash )
            $moduleRedirectUri = '/' . $moduleRedirectUri;
        else if ( $leftSlash and $rightSlash )
            $moduleRedirectUri = substr( $moduleRedirectUri, 1 );
        $redirectURI .= $moduleRedirectUri;

        print( "'$leftSlash','$rightSlash'\n" );
        print( "result='$redirectURI'\n" );
        print( "\n" );
    }
}


?>

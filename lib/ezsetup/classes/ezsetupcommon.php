<?php
// This file holds shared functions for the ezsetup files

/*!
    Define the test items. Also define error messages. These should later be internationalized.
*/
function configuration()
{
	$config = eZINI::instance( "setup.ini" );
	$namedArray = $config->getNamedArray();

	// Convert the pseudo array (like "item1;item2;item3") to real arrays 
	// and convert "true" and "false" to real true and false
	foreach( array_keys( $namedArray ) as $mainKey )
	{
		foreach( array_keys( $namedArray[$mainKey] ) as $key )
		{
			if ( preg_match( "/;/", $namedArray[$mainKey][$key] ) )
				$namedArray[$mainKey][$key] = preg_split( "/;/", $namedArray[$mainKey][$key] );
			else if ( $namedArray[$mainKey][$key] == "true" )
			    $namedArray[$mainKey][$key] = true;
			else if ( $namedArray[$mainKey][$key] == "false" )
			    $namedArray[$mainKey][$key] = false;
		}
	}
	return $namedArray;
}


/*!
	Complete the testItems array with the values that we got of the former post form
	Create an array that we can use for the handover in forms
*/
function completeItems( &$testItems, &$http, &$handoverResult )
{
	foreach( array_keys( $testItems ) as $key )
	{
		if ( $http->hasVariable( $key ) )
		{
			// Transform "true" to true and "false" to false
			switch( $http->postVariable( $key ) )
			{
				case "true":
				{
					$testItems[$key]["pass"] = true;
				}break;

				case "false":
				{
					$testItems[$key]["pass"] = false;
				}break;
			}
			$handoverResult[] = array( "name" => $key, "value" => $testItems[$key]["pass"] ? "true" : "false" );
		}
	}   
}


?>

11/3: {mod( 11, 3 )}
11/3: {11|mod( 3 )}

{let foo=11 bar=3}
11/3: {mod( $foo, 3 )}
11/3: {$foo|mod( 3 )}
11/3: {mod( $foo, $bar )}
11/3: {$foo|mod( $bar )}
{/let}

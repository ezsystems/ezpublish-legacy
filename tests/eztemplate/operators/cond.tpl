{let a=4 b=7}
{cond( $a|eq( 5 ), "A is 5",
       $b|eq( 7 ), "B is 7" )}
{cond( $a|eq( 4 ), "A is 4",
       $b|eq( 8 ), "B is 8" )}
{cond( $a|eq( 5 ), "A is 5",
       $b|eq( 8 ), "B is 8",
       "Default value" )}
{/let}

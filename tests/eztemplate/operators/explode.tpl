{let string="1,2,3,4,5,6"}
{$string|explode( ',' )|implode( ',' )}
{$string|explode( 4 )|implode( ',' )}
{/let}

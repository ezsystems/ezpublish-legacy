{section var=somevar loop=array( 8131 )}
Type of $somevar as input is: '{$somevar|get_type}'
Type of $somevar as parameter is: '{get_type($somevar)}'
Equality check as input: '{$somevar|eq(8131)}'
Equality check as parameter: '{eq($somevar,8131)}'
{/section}


{section var=somevar loop=array( 8131 )}
{$somevar|get_type} returns eztemplatesectioniterator instead of array element

{get_type($somevar)} returns the correct thing

{$somevar|eq(8131)}

{eq($somevar,8131)}

{/section}


{let value=false()}
{and( 0, 0, 0, 0 )}
{and( 1, $value, 1, 1 )}
{and( 1, 1, 1, 1 )}

{or( 0, 0, 0, 0 )}
{or( 1, 0, 1, 1 )}
{or( 1, 1, 1, 1 )}
{/let}

{not( 1 )}
{45|not()}

{let indent=4}
{"test"|indent( $indent, 'tab' )}
{"test"|indent( $indent, 'space' )}
{"test"|indent( $indent, 'custom', 'foo ' )}
{/let}

{"test"|indent( 4, 'tab' )}
{"test"|indent( 4, 'space' )}
{"test"|indent( 4, 'custom', 'foo ' )}

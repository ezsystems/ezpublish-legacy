90|dec()='{90|dec()}'
90|dec(89)='{90|dec(89)}'
dec( 89 )='{dec( 89 )}'
inc( 123 )='{inc( 123 )}'

{let value=90}
$value|dec='{$:value|dec}'
$value|inc='{$:value|inc}'
dec( $value )='{dec( $:value )}'
inc( $value )='{inc( $:value )}'
{/let}

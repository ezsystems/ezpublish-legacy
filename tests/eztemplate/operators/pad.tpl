{"foo"|pad( 16 )}
{pad( "foo", 16)}

{"bar"|pad( 16, '<' )}
{pad( "bar", 16, '<' )}

{let str='barbarella'}
{$str|pad( 16 )}
{pad( $str, 16)}

{$str|pad( 16, '<' )}
{pad( $str, 16, '<' )}
{/let}

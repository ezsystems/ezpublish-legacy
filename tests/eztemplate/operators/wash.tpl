{"foo <bar>"|wash}
{"foo <bar>"|wash( 'xhtml' )}

{let str="<a href='http://www.ez.no'>www.ez.no</a>"}
{$str|wash}
{$str|wash( 'xhtml' )}
{/let}

{"	<a href='http://www.ez.no'>www.ez.no</a>"|wash( 'pdf' )}

{let str="	<a href='http://www.ez.no'>www.ez.no</a>"}
{$str|wash( 'pdf' )}
{/let}


{"info@ez.no"|wash( 'email' )}

{let str="info@ez.no"}
{$str|wash( 'email' )}
{/let}

{"foo <bar>"|wash}
{"foo <bar>"|wash( 'xhtml' )}

{let str="<a href='http://www.ez.no'>www.ez.no</a>"}
{$str|wash}
{$str|wash( 'xhtml' )}
{/let}


{*{let attributes=$object.attributes}

{$attributes[0].name}

{attribute_edit_gui attribute=$attributes[0]}

{$attributes[1].name}

{attribute_edit_gui attribute=$attributes[1] title="abc"}

{/let}*}

{*{include name=NS title="abc" attribute=$object.attributes[1] uri="scrap/design/standard/templates/content/datatype/edit/eztext.tpl"}*}

{*{switch name=NS match=2}
{case match=1}
Match: {$:match}
{/case}
{case match=2}
Match: {$:match}
{/case}
{/switch}*}

{*{let name=BS arr=array( 1, 2, 'b', 4, 5, 6, 7, 8 ) times=8 str="12b45678" show=true len=6 mod=2}*}

{*{section show=$:show}
Yes
{section-else}
Nope
{/section}*}


{*{let myvar='bård'}

{append-block name=NS1 scope=root variable=blah}
{switch name=Sw2 var=foobar match=$myvar}
{case match='derick'}
This one matches.
match : {$:match}
{/case}

{case match='amos'}
This one does not match.
{/case}

{case}
Not this one either.
We were trying to find {$:foobar}
{/case}
{/switch}
{/set-block}

{/let}*}


{*{let arr=array(1,2)}
{$arr|array_prepend(3)|implode(',')}
{$arr|array_append(3)|implode(',')}
{$arr|array_merge(3,4)|implode(',')}
{/let}*}

{*{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',1)
         item_count=30
         view_parameters=array()
         item_limit=6}*}

sub:

{let page_count=6 current_page=0}
6={sub( $:page_count, $:current_page )}
5={sub( $:page_count, 1, $:current_page )}
5={sub( $:page_count, $:current_page, 1 )}
4={sub( $:page_count, 1, $:current_page, 1 )}
-5={sub( 1, $:page_count, $:current_page )}
-6={sub( 1, 1, $:page_count, $:current_page )}
-1={sub( 1, 1, 1 )}
1={sub( 1 )}
{/let}


sum:

{let page_count=6 current_page=0}
6={sum( $:page_count, $:current_page )}
7={sum( $:page_count, 1, $:current_page )}
7={sum( $:page_count, $:current_page, 1 )}
8={sum( $:page_count, 1, $:current_page, 1 )}
7={sum( 1, $:page_count, $:current_page )}
8={sum( 1, 1, $:page_count, $:current_page )}
3={sum( 1, 1, 1 )}
1={sum( 1 )}
{/let}


mul:

{let page_count=6 current_page=1}
6={mul( $:page_count, $:current_page )}
12={mul( $:page_count, 2, $:current_page )}
12={mul( $:page_count, $:current_page, 2 )}
24={mul( $:page_count, 2, $:current_page, 2 )}
12={mul( 2, $:page_count, $:current_page )}
24={mul( 2, 2, $:page_count, $:current_page )}
8={mul( 2, 2, 2 )}
2={mul( 2 )}
{/let}


div:

{let page_count=8 current_page=1}
8={div( $:page_count, $:current_page )}
4={div( $:page_count, 2, $:current_page )}
4={div( $:page_count, $:current_page, 2 )}
2={div( $:page_count, 2, $:current_page, 2 )}
2={div( $:page_count, $:current_page, 2, 2 )}
2={div( 64, $:page_count, $:current_page, 2, 2 )}
2={div( 128, 2, $:page_count, $:current_page, 2, 2 )}
0.25={div( 2, $:page_count, $:current_page )}
0.125={div( 2, 2, $:page_count, $:current_page )}
0.5={div( 2, 2, 2 )}
2={div( 2 )}
{/let}



{*{section name=NS var=i loop=$:times offset=2 max=$:len reverse last-value sequence=array(odd,even)}
{section-include match=eq($:i,2)}
{section-include match=ge($:i,7)}
Value<{$:i.sequence}>: {$BS:NS:i}
{section show=eq($:i,2)}
 [Blah({$:i})]
{/section}

last({$:i.last})
last.last({$:i.last.last})
last.last.last({$:i.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}*}


{*{section name=NS loop=$:times offset=2 max=$:len reverse last-value sequence=array(odd,even)}
{section-include match=eq($:item,2)}
{section-include match=ge($:item,7)}
Value<{$:sequence}>: {$BS:NS:item}
{section show=eq($:item,2)}
 [Blah({$:item})]
{/section}

last({$:item.last})
last.last({$:item.last.last})
last.last.last({$:item.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}*}


{*{/let}*}

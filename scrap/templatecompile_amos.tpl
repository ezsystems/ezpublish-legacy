{*{include name=NS uri="design:b.tpl"}*}
{*{attribute_edit_gui attribute=$obj}*}
{*{my_gui attribute=$obj}*}
{*{my_gui_view view=first attribute=$obj}*}

{let a=1}
{sum( 4, sub( 10, sum( 1, mul( 2, 1 ) ) ), $a, 6 )}
{/let}



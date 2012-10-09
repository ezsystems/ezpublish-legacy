<div id="leftmenu">
<div id="leftmenu-design">

{if is_set( $module_result.left_menu )}
    {include uri=$module_result.left_menu}
{else}
    {* 
        Get navigationpart identifier variable depends if the call is an contenobject
        or a custom module 
    *}
    {def $navigation_part_name = $navigation_part.identifier}
    {if $navigation_part_name|eq('')}
        {set $navigation_part_name = $module_result.navigation_part}
    {/if}
    {* 
        Include automatically the menu template for the $navigation_part_name
        ez $part_name navigationpart =>  parts/$part_name/menu.tpl
    *}
    {def $extract_length = sub( count_chars( $navigation_part_name ), '14' )
         $part_name = $navigation_part_name|extract( '2', $extract_length )}

    {include uri=concat( 'design:parts/', $part_name, '/menu.tpl' )}

    {undef $extract_length $part_name $navigation_part_name}
{/if}

</div>
</div>

<hr class="hide" />
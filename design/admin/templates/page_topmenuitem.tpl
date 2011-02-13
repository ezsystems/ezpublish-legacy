{*?template charset=latin1?*}
{let selected=''}
{if eq($navigationpart_identifier,$menu_item.navigationpart_identifier ) }
    {set selected='selected selected-'}
{/if}
<li class="{$selected}{$menu_item.position}"><div>

{if $menu_item.enabled}
<a href={$menu_item.url|ezurl} title="{$menu_item.tooltip}">{$menu_item.name|wash}</a>
 {else}
<span class="disabled">{$menu_item.name|wash}</span>
{/if}
</div></li>
{/let}
{*?template charset=latin1?*}
{let selected=''}
{section show=eq($navigationpart_identifier,$menu_item.navigationpart_identifier ) }
    {set selected='selected selected-'}
{/section}
<li class="{$selected}{$menu_item.position}"><div>

{section show=$menu_item.enabled}
<a href={$menu_item.url|ezurl} title="{$menu_item.tooltip}">{$menu_item.name|wash}</a>
 {section-else}
<span class="disabled">{$menu_item.name|wash}</span>
{/section}
</div></li>
{/let}
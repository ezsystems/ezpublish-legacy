{def $liclass=''}
{if eq( $navigationpart_identifier, $menu_item.navigationpart_identifier ) }
    {set $liclass='selected '}
{/if}
<li class="{$liclass}{$menu_item.position}"><div>
    {if and( $menu_item.enabled, $menu_item.access )}
        <a href={$menu_item.url|ezurl} title="{$menu_item.tooltip}">{$menu_item.name|wash}</a>
    {else}
        <span class="disabled">{$menu_item.name|wash}</span>
    {/if}
</div></li>
{undef $liclass}
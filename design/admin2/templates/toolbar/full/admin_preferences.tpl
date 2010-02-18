{if and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ))}
<div id="currentuserperferences">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'User perferences'|i18n( 'design/admin/pagelayout' )}</h4>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<div class="settings">
<ul>
    <li class="nobullet">{'Locations'|i18n( 'design/admin/parts/my/menu')}:
    {if ezpreference( 'admin_edit_show_locations' )}
        <span class="current">{'on'|i18n( 'design/admin/parts/my/menu' )}</span>&nbsp;|&nbsp;<a href={'/user/preferences/set/admin_edit_show_locations/0'|ezurl} title="{'Disable location window when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {else}
        <a href={'/user/preferences/set/admin_edit_show_locations/1'|ezurl} title="{'Enable location window when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;|&nbsp;<span class="current">{'off'|i18n( 'design/admin/parts/my/menu' )}</span>
    {/if}
    </li>
    <li class="nobullet">{'Preview tab'|i18n( 'design/admin/parts/my/menu')}:
    {if ezpreference( 'admin_navigation_content' )}
        <span class="current">{'on'|i18n( 'design/admin/parts/my/menu' )}</span>&nbsp;|&nbsp;<a href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Disable &quot;Preview tab&quot; while browsing content.'|i18n( 'design/admin/parts/my/menu' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {else}
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Enable &quot;Preview tab&quot; while browsing content.'|i18n( 'design/admin/parts/my/menu' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;|&nbsp;<span class="current">{'off'|i18n( 'design/admin/parts/my/menu' )}</span>
    {/if}
    </li>
</ul>
</div>

{* DESIGN: Content END *}</div></div></div>

</div>
{/if}
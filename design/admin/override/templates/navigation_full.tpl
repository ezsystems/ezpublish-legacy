<div class="menu-block">
<ul>
    {* Content preview *}
    <li>
    {section show=ezpreference( 'admin_navigation_content' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_content/0'|ezurl}>{'Content preview'|i18n( '/design/admin/navigation' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl}>{'Content preview'|i18n( '/design/admin/navigation' )}</a>
    {/section}
    </li>

    {* Additional information *}
    <li>
    {section show=ezpreference( 'admin_navigation_information' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_information/0'|ezurl}>{'Additional information'|i18n( '/design/admin/navigation' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_information/1'|ezurl}>{'Additional information'|i18n( '/design/admin/navigation' )}</a>
    {/section}
    </li>

    {* Locations *}
    <li>
    {section show=ezpreference( 'admin_navigation_locations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_locations/0'|ezurl}>{'Locations'|i18n( '/design/admin/navigation' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl}>{'Locations'|i18n( '/design/admin/navigation' )}</a>
    {/section}
    </li>

    {* Relations *}
    <li>
    {section show=ezpreference( 'admin_navigation_relations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_relations/0'|ezurl}>{'Relations'|i18n( '/design/admin/navigation' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl}>{'Relations'|i18n( '/design/admin/navigation' )}</a>
    {/section}
    </li>
</ul>
</div>

<div class="context-block">

<h2 class="context-title">{$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )} {$node.name} [{$node.object.class_name}]</h2>
<i>{'Last modified:'|i18n( 'design/admin/navigation' )} {$node.object.modified|l10n(shortdatetime)}, {$node.object.current.creator.name}</i>


{* Content preview. *}
{section show=ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-vindow" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/navigation' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/navigation' )}: {$node.object.id}">
    {node_view_gui content_node=$node view=navigation}
</div>
{/section}

{* Edit, move, remove + additional action buttons. *}
{include uri='design:buttons.tpl'}
</div>

{* Information *}
{section show=ezpreference( 'admin_navigation_information'  )}
    {include uri='design:information.tpl'}
{/section}

{* Related objects and reverse related objects *}
{section show=ezpreference( 'admin_navigation_relations'  )}
    {include uri='design:related_objects.tpl'}
{/section}

{* Locations *}
{section show=ezpreference( 'admin_navigation_locations'  )}
    {include uri='design:locations.tpl'}
{/section}

{* Children *}
{section show=$node.object.content_class.is_container}
    <div class="content-view-children">
        {include uri='design:children.tpl'}
    </div>
{/section}

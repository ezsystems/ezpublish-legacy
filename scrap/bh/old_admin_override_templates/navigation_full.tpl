{* Window controls. *}
<div class="menu-block">
<ul>
    {* Content preview. *}
    <li>
    {section show=ezpreference( 'admin_navigation_content' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/content/view' )}">{'Content preview'|i18n( 'design/admin/content/view' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/content/view' )}">{'Content preview'|i18n( 'design/admin/content/view' )}</a>
    {/section}
    </li>

    {* Additional information. *}
    <li>
    {section show=ezpreference( 'admin_navigation_information' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_information/0'|ezurl} title="{'Hide additional information.'|i18n( 'design/admin/content/view' )}">{'Additional information'|i18n( 'design/admin/content/view' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_information/1'|ezurl} title="{'Show additional information.'|i18n( 'design/admin/content/view' )}">{'Additional information'|i18n( 'design/admin/content/view' )}</a>
    {/section}
    </li>

    {* Locations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_locations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/content/view' )}">{'Locations'|i18n( 'design/admin/content/view' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n('design/admin/content/view')}">{'Locations'|i18n( 'design/admin/content/view' )}</a>
    {/section}
    </li>

    {* Relations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_relations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide relation overview.'|i18n( 'design/admin/content/view' )}">{'Relations'|i18n( 'design/admin/content/view' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show relation overview.'|i18n( 'design/admin/content/view' )}">{'Relations'|i18n( 'design/admin/content/view' )}</a>
    {/section}
    </li>
</ul>
</div>

{* Content window. *}
<div class="context-block">
<h2 class="context-title">{$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )} {$node.name} [{$node.object.class_name}]</h2>
<i>{'Last modified:'|i18n( 'design/admin/content/view' )} {$node.object.modified|l10n(shortdatetime)}, {$node.object.current.creator.name}</i>

{* Content preview in content window. *}
{section show=ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-vindow" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/content/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/content/view' )}: {$node.object.id}">
    {node_view_gui content_node=$node view=navigation}
</div>
{/section}

{* Buttonbar for content window. *}
<div class="controlbar">
<div class="block">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

{* Edit button. *}
{section show=$node.can_edit}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view' )}" title="{'Edit this item.'|i18n( 'design/admin/content/view' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view' )}" title="{'You do not have permissions to edit this item.'|i18n( 'design/admin/content/view' )}" disabled="disabled" />
{/section}

{* Move button. *}
{section show=$node.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/content/view' )}" title="{'Move this item to another location.'|i18n( 'design/admin/content/view' )}" />
{section-else}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/content/view' )}" title="{'You do not have permissions to move this item to another location.'|i18n( 'design/admin/content/view' )}" disabled="disabled" />
{/section}

{* Remove button. *}
{section show=$node.can_remove}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/content/view')}" title="{'Remove this item.'|i18n( 'design/admin/content/view' )}" />
{section-else}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/content/view')}" title="{'You do not have permissions to remove this item.'|i18n( 'design/admin/content/view' )}" disabled="disabled" />
{/section}

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/content/view')}" /> *}

</form>
</div>
</div>

</div>

{* Information window. *}
{section show=ezpreference( 'admin_navigation_information'  )}
    {include uri='design:information.tpl'}
{/section}

{* Locations window. *}
{section show=ezpreference( 'admin_navigation_locations'  )}
    {include uri='design:locations.tpl'}
{/section}

{* Related objects window. *}
{section show=ezpreference( 'admin_navigation_relations'  )}
    {include uri='design:related_objects.tpl'}
{/section}

{* Children window.*}
{section show=$node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{/section}

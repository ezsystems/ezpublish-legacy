{* Window controls. *}
<div class="menu-block">
<ul>
    {* Content preview. *}
    <li>
    {section show=ezpreference( 'admin_navigation_content' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Content preview'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Content preview'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Additional information. *}
    <li>
    {section show=ezpreference( 'admin_navigation_information' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_information/0'|ezurl} title="{'Hide additional information.'|i18n( 'design/admin/node/view/full' )}">{'Additional information'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_information/1'|ezurl} title="{'Show additional information.'|i18n( 'design/admin/node/view/full' )}">{'Additional information'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Locations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_locations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n('design/admin/node/view/full')}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Relations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_relations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>
</ul>
</div>

{* Content window. *}
<div class="context-block">
<h2 class="context-title">{$node.class_identifier|class_icon( normal, $node.class_name )} {$node.name} [{$node.class_name}]</h2>

<div class="context-information">
<p>{'Last modified:'|i18n( 'design/admin/node/view/full' )} {$node.object.modified|l10n(shortdatetime)}, {$node.object.current.creator.name}</p>
</div>

{* Content preview in content window. *}
{section show=ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-vindow" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/node/view/full' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/node/view/full' )}: {$node.object.id}">
    {node_view_gui content_node=$node view=admin_preview}
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
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* Move button. *}
{section show=$node.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move this item to another location.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to move this item to another location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* Remove button. *}
{section show=$node.can_remove}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/node/view/full')}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/node/view/full')}" title="{'You do not have permissions to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/node/view/full')}" /> *}

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
{section-else}
    <div class="context-block">
        <h2 class="context-title"><a href={$node.parent.url_alias|ezurl}><img src={'back-button-16x16.gif'|ezimage} alt="{'Up one level'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level'|i18n( 'design/admin/node/view/full' )}" /></a> {'This type of item can not contain any sub items.'|i18n( 'design/admin/layout' )}</h2>
    </div>
{/section}


{let subscribed_nodes=$handler.rules}
<div class="context-block">
<h2 class="context-title">{'My node notifications [%notification_count]'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit',, hash( '%notification_count', $subscribed_nodes|count ) )}</h2>

{section show=$subscribed_nodes}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
	<th>{'Name'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</th>
	<th>{'Type'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</th>
	<th>{'Section'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</th>
</tr>

{section var=Rules loop=$subscribed_nodes sequence=array( bglight, bgdark )}
<tr class="{$Rules.sequence}">
	<td><input type="checkbox" name="SelectedRuleIDArray_{$handler.id_string}[]" value="{$Rules.item.id}" /></td>
    <td>{$Rules.item.node.class_identifier|class_icon( small, $Rules.item.node.class_name )}&nbsp;<a href={concat( '/content/view/full/', $Rules.item.node.node_id, '/' )|ezurl}>{$Rules.item.node.name|wash}</a></td>
	<td>{$Rules.item.node.object.content_class.name|wash}</td>
    <td>{fetch( section, object, hash( section_id, $Rules.item.node.object.section_id ) ).name|wash}</td>
</tr>
{/section}
</table>
{section-else}

<p>{'You have not subscribed to recieve notifications about any nodes.'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</p>

{/section}

<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="RemoveRule_{$handler.id_string}" value="{'Remove selected'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}" {section show=$subscribed_nodes|not}disabled="disabled"{/section} />
        <input class="button" type="submit" name="NewRule_{$handler.id_string}" value="{'Add notifications'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}" />
    </div>
</div>

</div>
{/let}

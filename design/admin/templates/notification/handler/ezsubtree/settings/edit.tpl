{let subscribed_nodes=$handler.rules}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'My node notifications [%notification_count]'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit',, hash( '%notification_count', $subscribed_nodes|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

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
<div class="block">
<p>{'You have not subscribed to receive notifications about any nodes.'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <input class="button" type="submit" name="RemoveRule_{$handler.id_string}" value="{'Remove selected'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}" {section show=$subscribed_nodes|not}disabled="disabled"{/section} />
        <input class="button" type="submit" name="NewRule_{$handler.id_string}" value="{'Add notifications'|i18n( 'design/admin/notification/handler/ezsubtree/settings/edit' )}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>
{/let}


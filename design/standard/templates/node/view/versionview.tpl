{let page_limit=25
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<form method="post" action="/content/action/">

<h2>View {$class.name}</h2>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
{*	{$node.name|texttoimage("hatten",45,0,,,-1,-3)}*}
 	<h1>{$node.name}</h1>
	</td>
</tr>
</table>

{switch match=$node.object.can_edit}
{case match=1}
         <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
	 <input type="hidden" name="ContentObjectVersion" value="{$node.object.version}" />   
         <input type="submit" name="EditButton" value="Edit" />
{/case}
{case match=0}
  You are not allowed to edit this object
{/case}
{/switch}

Current version: {$node.object.current_version}

<br /><b>Path:</b><br />
{include uri="design:content/path.tpl" items=$parents base_uri=$module.functions.view.uri} 
 {$node.object.name}
<br/>
<table width="100%">
{section name=ContentObjectAttribute loop=$versionAttributes}
<tr>
	<td>
	<b>
	{$ContentObjectAttribute:item.contentclass_attribute.name}
	</b><br />
	{attribute_view_gui attribute=$ContentObjectAttribute:item}
	</td>
</tr>
{/section}
</table>

<h2>Related objects</h2>
<table width="100%" cellspacing="0">
{section name=Object loop=$related_contentobject_array show=$related_contentobject_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Object:sequence}">
	{content_view_gui view=line content_object=$Object:item}
	</td>
</tr>
{section-else}
<tr>
	<td class="{$Object:sequence}">
	None
	</td>
</tr>
{/section}
</table>

<h1>Children</h1>
<table width="100%">
{section name=Children loop=$children sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Children:sequence}" >

	<a href={concat('content/view//full/',$Children:item.node_id)|ezurl}>
	{content_view_gui view=line content_object=$Children:item.contentobject} 
	</a>

	<a href={concat('content/edit/',$Children:item.contentobject_id)|ezurl}>[ edit ]</a>
        - {$Children:item.contentobject.class_name}
	</td>
</tr>
{/section}
</table>

{switch match=$node.object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="submit" name="NewButton" value="New" />
         <select name="ClassID">
	      {section name=Classes loop=$classes}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
{/case}
{case match=0}
  You are not allowed to create child objects
{/case}
{/switch}


{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/versionview/',$node.node_id)
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

</form>
{/let}
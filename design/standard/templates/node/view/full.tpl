{let page_limit=25
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}
<form method="post" action={"content/action"|ezurl}>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
{*	{$node.name|texttoimage('archtura')}  *}
 	<h1 class="top">{$node.name}</h1>
	</td>
	<td align="right">
	{switch match=$node.object.can_edit}
	    {case match=1}
	    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input class="button" type="submit" name="EditButton" value="Edit" />
	    {/case}
            {case match=0}
            <p>You are not allowed to edit this object</p>
            {/case}
        {/switch}
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">

    {section name=ContentObjectAttribute loop=$node.object.contentobject_attributes}
    <div class="block">
        <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label>
    	<p class="box">{attribute_view_gui attribute=$ContentObjectAttribute:item}</p>
    </div>
    {/section}

    </td>
    <td width="120" valign="top">
    <h2>Related objects</h2>
    {section name=Object loop=$node.object.related_contentobject_array show=$node.object.related_contentobject_array sequence=array(bglight,bgdark)}

    <div class="block">
    {content_view_gui view=line content_object=$Object:item}
    </div>
    
    {section-else}
    <p>None</p>
    {/section}

    <h2>Content actions</h2>
    {section name=ContentAction loop=$node.object.content_action_list show=$node.object.content_action_list}
    <div class="block">
    <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|i18n}" />
    </div>
    {/section}
    </td>
</tr>
</table>

<h2>Children</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section name=Child loop=fetch('content','list',hash(parent_node_id,$node.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset)) sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Child:sequence}">
        <span class="normal">
        <a href={concat('content/view/full/',$Child:item.node_id)|ezurl}>{node_view_gui view=line content_node=$Child:item}</a>
        </span>
	</td>
        <td class="{$Child:sequence}"><span class="normal">{$Child:item.object.class_name}</span></td>
        <td width="1%">
        <a class="normal" href={concat("content/edit/",$Child:item.contentobject_id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
        </td>
	<td class="{$Child:sequence}" align="right" width="1%">
	{switch name=sw match=$Child:item.object.can_remove}
        {case match=1}
             <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.object.id}" />
	{/case}
        {case} 
        {/case}
        {/switch} 
	</td>
        <td width="1%" class="{$Child:sequence}"><img src={"editdelete.png"|ezimage} border="0"></td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

<div class="buttonblock">

{switch match=$node.object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input class="button" type="submit" name="NewButton" value="New" />
         <select name="ClassID">
	      {section name=Classes loop=$node.object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
{/case}
{case match=0}
 <p>You are not allowed to create child objects</p>
{/case}
{/switch}

<input class="button" type="submit" name="RemoveButton" value="Remove object(s)" />

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
</div>

</form>
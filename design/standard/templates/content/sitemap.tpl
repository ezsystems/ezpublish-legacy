{let page_limit=1}
<h1>{"Site map"|i18n('content/object')}</h1>

<form action="{$module.functions.sitemap.uri}/{$top_object_id}" method="post" >

<table class="list" width="100%" cellpadding="1" cellspacing="0" border="0">
<tr>
	<th class="normal">ID:</th>
	<th class="normal">Object:</th>
	<th class="normal">OwnerID:</th>
	<th class="normal">IsPublished:</th>
	<th class="normal">Version:</th>
	<th class="normal">Section ID:</th>
	<th class="normal">Class:</th>
	<th colspan="2">&nbsp;</th>
</tr>


{section name=Tree loop=fetch('content','tree',hash(parent_node_id,$nodeID,limit,$page_limit,offset,$view_parameters.offset)) sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.id}</span></td>
	<td class="{$Tree:sequence}">
{*	<img src="1x1.gif" width="{$Tree:item.Level}0" height="1" alt="" /> *}
        	<img src="1x1.gif" width="{$Tree:item.depth}0" height="1" alt="" />
	<a class="normal" href="/content/view/full/{$Tree:item.node_id}">

{switch name=sw match=$Tree:item.object.contentclass_id}
{case match=2}
	<img src={"class_2.png"|ezimage} border="0"> 
{/case}
{case match=3}
	<img src={"user_group.png"|ezimage} border="0"> 
{/case}
{case match=4}
	<img src={"class_1.png"|ezimage} border="0"> 
{/case}
{case}
	<img src={"class_1.png"|ezimage} border="0"> 
{/case}
{/switch}

	{$Tree:item.name}</a>
	</td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.owner_id}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.is_published}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.current_version}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.section_id}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.class_name}</span></td>
	<td class="{$Tree:sequence}">
	{switch name=sw1 match=$Tree:item.object.can_edit}
        {case match=1}  
	{switch name=cidsw match=$Tree:item.object.contentclass_id}
	    {case match=4}
	    <a class="normal" href="/user/edit/{$Tree:item.object.id}"><img src={"edit.png"|ezimage} border="0"></a>
	    {/case}
	    {case}
            <a class="normal" href="/content/edit/{$Tree:item.object.id}"><img src={"edit.png"|ezimage} border="0"></a>
	    {/case}
        {/switch}
        {/case}
        {case} 
        {/case}
        {/switch} 
        </td>
        <td class="{$Tree:sequence}">
	{switch name=sw2 match=$Tree:item.object.can_remove}
        {case match=1}  
             <input type="checkbox" name="DeleteIDArray[]" value="{$Tree:item.object.id}" />
             <img src={"editdelete.png"|ezimage} border="0">
	{/case}
        {case} 
        {/case}
        {/switch} 
        </td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat($module.functions.sitemap.uri,'/',$top_object_id)
         module=$module
         item_count=fetch('content','tree_count',hash(parent_node_id,$nodeID))
         view_parameters=$view_parameters
         item_limit=$page_limit}

<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="New" />
<select name="ClassID">
{section name=Classes loop=$classes}
<option value="{$Classes:item.id}">{$Classes:item.name}</option>
{/section}
</select>

<input class="button" type="submit" name="RemoveButton" value="Remove object(s)" />
</div>

</form>
{/let}
<h1>{"Site map"|i18n('content/object')}</h1>

<form action="{$module.functions.sitemap.uri}/{$top_object_id}" method="post" >

<table width="100%" cellspacing="0">
<tr>
	<th align="left">ID</th>
	<th align="left">Object</th>
	<th align="left">OwnerID</th>
	<th align="left">IsPublished</th>
	<th align="left">Version</th>
	<th align="left">Section ID</th>
	<th align="left">Class</th>
	<th align="left"></th>
</tr>


{section name=Tree loop=$tree sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.id}</td>
	<td class="{$Tree:sequence}">
{*	<img src="1x1.gif" width="{$Tree:item.Level}0" height="1" alt="" /> *}
        	<img src="1x1.gif" width="{$Tree:item.depth}0" height="1" alt="" />
	<a href="/content/view/full/{$Tree:item.node_id}">

{switch name=sw match=$Tree:item.contentobject.contentclass_id}
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
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.owner_id}</td>
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.is_published}</td>
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.current_version}</td>
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.section_id}</td>
	<td class="{$Tree:sequence}">{$Tree:item.contentobject.class_name}</td>
        <td class="{$Tree:sequence}">
	{switch name=sw1 match=$Tree:item.contentobject.can_edit}
        {case match=1}  
            <a href="/content/edit/{$Tree:item.contentobject.id}"><img src={"edit.png"|ezimage} border="0"></a>
	{/case}
        {/switch} 
        </td>
        <td class="{$Tree:sequence}">
	{switch name=sw2 match=$Tree:item.contentobject.can_remove}
        {case match=1}  
             <input type="checkbox" name="DeleteIDArray[]" value="{$Tree:item.contentobject.id}" />
             <img src={"editdelete.png"|ezimage} border="0">
	{/case}
        {/switch} 
        </td>

</tr>
{/section}
</table>

<table width="100%">
<tr>
	<td>
	{switch match=$previous|lt(0) }
	  {case match=0}
          <a href="{$module.functions.view.uri}/full/{$nodeID}/offset/{$previous}"> << Previous </a>
	  {/case}
          {case match=1}
	  Previous
	  {/case}
        {/switch}

	</td>
	<td align="right">
	{switch match=$next|lt($tree_count) }
	  {case match=1}
          <a href="{$module.functions.view.uri}/full/{$nodeID}/offset/{$next}"> Next >> </a>
          {/case}
	  {case}
	  Next
          {/case}
        {/switch}

	</td>
</tr>
</table>


<input type="submit" name="NewButton" value="New" />
<select name="ClassID">
{section name=Classes loop=$classes}
<option value="{$Classes:item.id}">{$Classes:item.name}</option>
{/section}
</select>

<input type="submit" name="RemoveButton" value="Remove object(s)" />

</form>

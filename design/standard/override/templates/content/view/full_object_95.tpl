<form method="post" action="/content/action/">

<h2>{$class.name}</h2>
{include uri="design:content/path.tpl" items=$parents base_uri=$module.functions.view.uri} 
 {$object.name}
<br/>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="40">
	 <img src="/design/standard/images/folder_open_large.png" alt="folder"/>
	</td>
	<td>
	{$object.name|texttoimage("hatten",45,0,,,-1,-3)}
	</td>
	<td>
	{switch match=$object.can_edit}
	{case match=1}
	 <input type="hidden" name="ContentObjectID" value="{$object.id}" />
         <input type="submit" name="EditButton" value="Edit" />
	 {/case}
	 {case match=0}
	   You are not allowed to edit this object
	   {/case}
	   {/switch}

&nbsp;
{switch match=$object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$nodeID}" />
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

        </td>
</tr>
</table>

{attribute_view_gui attribute=$object.data_map.description}

<h1>Images</h1>
<table width="100%">
<tr>
{section name=Children loop=$children sequence=array(bglight,bgdark)}
	<td class="{$Children:sequence}" >

	<a href="{$module.functions.view.uri}/full/{$Children:item.node_id}">
	{content_view_gui view=line content_object=$Children:item.contentobject} 
	</a>

	<a href="{$module.functions.edit.uri}/{$Children:item.contentobject_id}">[ edit ]</a>
        - {$Children:item.contentobject.class_name}
	</td>
{/section}
</tr>

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
	{switch match=$next|lt($children_count) }
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

<input type="hidden" name="ContentObjectID" value="{$object.id}" />

</form>
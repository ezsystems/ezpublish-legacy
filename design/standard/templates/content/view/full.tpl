<form method="post" action="/content/action/">

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
	{$node.object.name|texttoimage('archtura')}
{*	{$node.object.name|imagefile('image-6f04edb50d1c35bf54e47ccb585d300a.png')} *}
{*	{image($node.object.name|texttoimage('archtura'),'abc'|texttoimage('archtura'))} *}
{* 	<h1>{$node.object.name}</h1> *}
	</td>
	<td align="rigt">
	{switch match=$node.object.can_edit}
	    {case match=1}
	    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="submit" name="EditButton" value="Edit" />
	    {/case}
            {case match=0}
            You are not allowed to edit this object
            {/case}
        {/switch}
	</td>
</tr>
</table>

<table width="100%">
<tr>
    <td width="80%" valign="top">
    <table width="100%">
    {section name=ContentObjectAttribute loop=$node.object.contentobject_attributes}
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
    </td>
    <td width="20%" valign="top">
    <h2>Related objects</h2>
    <table width="100%" cellspacing="0">
    {section name=Object loop=$node.object.related_contentobject_array show=$node.object.related_contentobject_array sequence=array(bglight,bgdark)}
    <tr>
	<td class="{$node.object:sequence}">
	{content_view_gui view=line content_object=$node.object:item}
	</td>
    </tr>
    {section-else}
    <tr>
	<td class="bglight">
	None
	</td>
    </tr>
    {/section}
   </table>

   <h2>Content actions</h2>
   {section name=ContentAction loop=$node.object.content_action_list show=$node.object.content_action_list}
   <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|i18n}" />
   {delimiter}
   <br /><br />
   {/delimiter}
   {/section}
   </td>
</tr>
</table>

<h1>Children</h1>
<table width="100%">
{section name=Child loop=$node.children sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Child:sequence}">

	<a href="{$module.functions.view.uri}/full/{$Child:item.node_id}">
	{content_view_gui view=line content_object=$Child:item.object}
	</a>

	<a href="{$module.functions.edit.uri}/{$Child:item.object_id}">[ edit ]</a>
        - {$Child:item.object.class_name}
	</td>
	<td class="{$Child:sequence}" align="right">
	{switch name=sw match=$Child:item.object.can_remove}
        {case match=1}  
             <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.object.id}" />
             <img src={"editdelete.png"|ezimage} border="0">
	{/case}
        {case} 
        {/case}
        {/switch} 
	</td>
</tr>
{/section}
</table>

{switch match=$node.object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="submit" name="NewButton" value="New" />
         <select name="ClassID">
	      {section name=Classes loop=$node.object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
{/case}
{case match=0}
  You are not allowed to create child objects
{/case}
{/switch}

<input class="button" type="submit" name="RemoveButton" value="Remove object(s)" />

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

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

</form>
{let page_limit=25
     sitemap_indentation=10
     tree=fetch('content','tree',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))
     tree_count=fetch('content','tree_count',hash(parent_node_id,$node.node_id))}

<form method="post" action={"content/action/"|ezurl}>

<h1>{"Site map"|i18n('content/object')}</h1>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
	{$node.object.name|texttoimage('archtura')}
{* 	<h1>{$node.object.name}</h1> *}
        <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
	</td>
</tr>
</table>

<table width="100%">
{section name=ContentObjectAttribute loop=$node.object.contentobject_attributes}
<tr>
  <td><b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b><br/>
  {attribute_view_gui attribute=$ContentObjectAttribute:item}
  </td>
</tr>
{/section}
</table>

<table class="list" width="100%" cellpadding="1" cellspacing="0" border="0">
<tr>
	<th class="normal">ID:</th>
	<th class="normal">Object:</th>
	<th class="normal">OwnerID:</th>
	<th class="normal">Version:</th>
	<th class="normal">Section ID:</th>
	<th class="normal">Class:</th>
	<th colspan="2">&nbsp;</th>
</tr>


{section name=Tree loop=$tree sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Tree:sequence}"><span class="normal"><a class="normal" href={concat("content/view/sitemap/",$Tree:item.node_id)|ezurl}>{$Tree:item.object.id}</a></span></td>
	<td class="{$Tree:sequence}">
       	<img src="1x1.gif" width="{mul(sub($Tree:item.depth,$node.depth)|dec,$sitemap_indentation)}" height="1" alt="" />
	<a class="normal" href={concat("content/view/full/",$Tree:item.node_id)|ezurl}><img src={"class_1.png"|ezimage} border="0"> &nbsp;{$Tree:item.name}</a>
	</td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.owner_id}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.current_version}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.section_id}</span></td>
	<td class="{$Tree:sequence}"><span class="normal">{$Tree:item.object.class_name}</span></td>
	<td class="{$Tree:sequence}">
	{switch name=sw1 match=$Tree:item.object.can_edit}
        {case match=1}  
	{switch name=cidsw match=$Tree:item.object.contentclass_id}
	    {case match=4}
	    <a class="normal" href={concat("user/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
	    {/case}
	    {case}
            <a class="normal" href={concat("content/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
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
             <input type="checkbox" name="DeleteIDArray[]" value="{$Tree:item.object.id}" align="top" />
             <input type="image" src={"editdelete.png"|ezimage} border="0" name="RemoveObject" value="{$Tree:item.object.id}" onClick="return confirm('Remove {$Tree:item.object.class_name} {$Tree:item.name} ?');">
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
         page_uri=concat('/content/view','/sitemap/',$node.node_id)
         item_count=$tree_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

<div class="buttonblock">
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

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

<input type="hidden" name="ViewMode" value="sitemap" />

</div>

</form>
{/let}
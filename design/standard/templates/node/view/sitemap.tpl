{default with_children=true()
	 is_standalone=true()}
{let page_limit=25
     sitemap_indentation=10
     tree=and($with_children,fetch('content','tree',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)))
     tree_count=and($with_children,fetch('content','tree_count',hash(parent_node_id,$node.node_id)))}
{default content_object=$node.object
         node_name=$node.name}

{section show=$is_standalone}
<form method="post" action={"content/action/"|ezurl}>
{/section}

<div class="maincontentheader">
<h1>{"Site map"|i18n('content/object')}</h1>
</div>

<p>{$node_name|texttoimage('archtura')}</p>
{* 	<h1>{$node_name}</h1> *}

{section name=ContentObjectAttribute loop=$content_object.contentobject_attributes}
<div class="block">
<label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
{attribute_view_gui attribute=$ContentObjectAttribute:item}
</div>
{/section}

{section show=$with_children}

<table class="list" width="100%" cellpadding="1" cellspacing="0" border="0">
<tr>
	<th>ID:</th>
	<th>Object:</th>
	<th>OwnerID:</th>
	<th>Version:</th>
	<th>Section ID:</th>
	<th>Class:</th>
	<th colspan="2">&nbsp;</th>
</tr>

{section name=Tree loop=$tree sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Tree:sequence}"><a href={concat("content/view/sitemap/",$Tree:item.node_id)|ezurl}>{$Tree:item.object.id}</a></td>
	<td class="{$Tree:sequence}">
       	<img src={"1x1-transparent.gif"|ezimage} width="{mul(sub($Tree:item.depth,$node.depth)|dec,$sitemap_indentation)}" height="1" alt="" border="0" />
	<a href={concat("content/view/full/",$Tree:item.node_id)|ezurl}><img src={"class_1.png"|ezimage} border="0"> &nbsp;{$Tree:item.name}</a>
	</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.owner_id}</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.current_version}</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.section_id}</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.class_name}</td>
	<td class="{$Tree:sequence}">
	{switch name=sw1 match=$Tree:item.object.can_edit}
        {case match=1}  
	{switch name=cidsw match=$Tree:item.object.contentclass_id}
	    {case match=4}
	    <a href={concat("user/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} alt="edit" border="0"></a>
	    {/case}
	    {case}
            <a href={concat("content/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} alt="edit" border="0"></a>
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
             <input type="checkbox" name="DeleteIDArray[]" value="{$Tree:item.node_id}" align="top" />
             <input type="image" src={"editdelete.png"|ezimage} border="0" name="RemoveObject" value="{$Tree:item.node_id}" onClick="return confirm('Remove {$Tree:item.object.class_name} {$Tree:item.name} ?');">
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
{switch match=$content_object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input class="button" type="submit" name="NewButton" value="New" />
         <select name="ClassID">
	      {section name=Classes loop=$content_object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
{/case}
{case match=0}
<div class="warning">
<h2>You are not allowed to create child objects</h2>
</div>
{/case}
{/switch}

<input class="button" type="submit" name="RemoveButton" value="Remove object(s)" />

{/section}

<input type="hidden" name="ContentObjectID" value="{$content_object.id}" />

<input type="hidden" name="ViewMode" value="sitemap" />

</div>

{section show=$is_standalone}
</form>
{/section}

{/default}
{/let}
{/default}
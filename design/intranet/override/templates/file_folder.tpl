{default with_children=true()
         is_editable=true()
	 is_standalone=true()}

{section show=$is_standalone}
<form method="post" action={"content/action"|ezurl}>
{/section}
{attribute_view_gui attribute=$node.object.data_map.description}

{section show=$with_children}
{let name=Child
        children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             sort_by, $node.sort_array,
                                             class_filter_type, include,
				             class_filter_array, array( 1,12 ) ) )
        folders=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             sort_by, $node.sort_array,
                                             class_filter_type, include,
				             class_filter_array, array( 1 ) ) )
        files=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             sort_by, $node.sort_array,
                                             class_filter_type, include,
				             class_filter_array, array( 12 ) ) )
        can_remove=false() 
        can_edit=false() 
        can_create=false() 
        can_copy=false()}

{section show=$:children}

        {section loop=$:children}
            {section show=$:item.object.can_remove}
                {set can_remove=true()}
            {/section}
            {section show=$:item.object.can_edit}
                {set can_edit=true()}
            {/section}
            {section show=$:item.object.can_create}
                {set can_create=true()}
            {/section}
        {/section}
	<table width="100%" border="0" cellspacing="0" cellpadding="4" >
	{section loop=$:folders sequence=array( bglight, bgdark )}
	<tr class="{$Child:sequence}">
	<td width="1%">
	<img src={"images/folder.png"|ezdesign} alt="" width="16" height="16" border="0" />
	</td>
	<td width="98%">
	<a href={concat( 'content/view/full/', $:item.node_id )|ezurl}>{$:item.name}</a><br />
	</td>
        
	<td width="1%">
	{section show=$:item.object.can_edit}
            <a href={concat( "content/edit/", $Child:item.contentobject_id )|ezurl}><img src={"images/edit.png"|ezdesign} width="16" height="16" align="top" alt="Edit" /></a>
        {/section}
	</td>
	<td width="1%">
        {section show=$:item.object.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
        {/section}
	</td>
	</tr>
	{/section}
	</table>

<table width="100%" border="0" cellspacing="0" cellpadding="4" >
{section loop=$:files sequence=array( bglight, bgdark )}
<tr class="{$Child:sequence}">
	<td width="1%">
	<img src={"images/file.gif"|ezdesign} alt="" width="16" height="16" border="0" />
	</td>
        <td width="40%">
	<a href={concat("content/download/",$:item.object.data_map.file.contentobject_id,"/",$:item.object.data_map.file.id,"/file/",$:item.object.data_map.file.content.filename)|ezurl}>{$:item.object.data_map.file.content.original_filename|wash(xhtml)}</a><br />
	</td>
        <td width="55%">
	<span class="small">{attribute_view_gui attribute=$:item.object.data_map.description}</span>
	<td width="2%">
	{$:item.object.data_map.file.content.filesize|si(byte)}
	</td>
        <td width="1%">
        <a href={concat("content/download/",$:item.object.data_map.file.contentobject_id,"/",$:item.object.data_map.file.id,"/file/",$:item.object.data_map.file.content.filename)|ezurl} ><img src={"images/download.gif"|ezdesign} width="16" height="16" align="top" alt="Download" /></a><br />
	</td>	
        <td width="1%">
	{section show=$:item.object.can_edit}
            <a href={concat( "content/edit/", $Child:item.contentobject_id )|ezurl}><img src={"images/edit.png"|ezdesign} width="16" height="16" align="top" alt="Edit" /></a><br />
        {/section}
	</td>	
	<td width="1%">
        {section show=$:item.object.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
        {/section}
	</td>
</tr>        
{/section}
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    {section show=$:can_remove}
         <input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/node/view')}" />
    {/section}
    </td>
</tr>
</table>
{/section}

{section show=$node.object.can_create}
<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <select name="ClassID">
         {section name=classList loop=$node.object.can_create_class_list}
              {section show=eq($:item.id,12)}
	      <option value="12">File</option>
	      {/section}
	      {section show=eq($:item.id,1)}
	      <option value="1">Folder</option>
	      {/section}
         {/section}
         </select>
        <input class="button" type="submit" name="NewButton" value="New" />
	</td>
</tr>
</table>
{/section}

{section show=$is_standalone}
</form>
{/section}
{/let}

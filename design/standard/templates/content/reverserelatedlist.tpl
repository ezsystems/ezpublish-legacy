<h2 class="context-title">
{'"%contentObjectName" [%children_count]: Sub items that are used by other objects '|i18n( 'design/admin/content/reverserelatedlist',,
                hash( '%contentObjectName', $content_object_name ,
                      '%children_count', $reverse_list_children_count ) )}
</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
{* Item column *}
    <th colspan="2">{'Item'|i18n( 'design/admin/content/children_reverserelatedlist' )}</th>
    {* Class type column *}
    <th>{'Type'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Objects referring to this item'|i18n( 'design/admin/content/children_reverserelatedlist' )}</th>
</tr>

{section var=children_item loop=$children_list sequence=array( bglight, bgdark )}
<tr class="{$children_item.sequence}">
    {* Object icon. *}
    <td>
         {$children_item.object.class_identifier|class_icon( small, $children_item.object.class_name|wash )}
    </td>

    {* Location. *}
    <td>
	{section var=path_node loop=$children_item.path|append( $children_item )}
              {$path_node.name|wash}
              {delimiter} / {/delimiter}
    {/section}
    </td>

    {* Type. *}
    <td>
    {$children_item.object.class_name|wash}
     </td>

    {* Objects referring to this item. *}
    <td>
      {$reverse_list_count_children_array[$children_item.object.id]}
    </td>

</tr>
{/section}

</table>
	    {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=concat( '/content/reverserelatedlist/', $node_id )
		     item_count=$children_count
                     view_parameters=$view_parameters
                     item_limit=$number_of_items}

{* DESIGN: Content END *}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section name=Relation loop=$attribute.content.relation_list sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td>
            {content_view_gui view=embed content_object=fetch(content,object,hash(object_id,$:item.contentobject_id,
                                                                                  object_version,$:item.contentobject_version))}
    </td>
</tr>
{/section}
</table>

<h2>{$node.name|wash}</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
    <td class="forumhead" width="80%">
    <h2>Topic: {$node.name|wash}</h2>
    </td>
    <td class="forumhead" width="20%">
    Author
    </td>
</tr>
<tr>
    <td class="bglightforum">
    <p>
    {$node.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
    <td class="bglightforum" valign="top">
    <h3>{$node.object.owner.name|wash}</h3>
    <p>
    {$node.object.owner.data_map.title.content|wash}<br /><br />

    {attribute_view_gui attribute=$node.object.owner.data_map.user_image image_class=small}<br />


    Location:{$node.object.owner.data_map.location.content|wash}<br />

    <br />
    {$node.object.published|l10n(datetime)}
    </p>
    <p> 	
    {let owner_id=$node.object.owner.id}
        {section name=Author loop=$node.object.author_array}
            {section  show=eq($owner_id,$Author:item.contentobject_id)|not()}
                Moderated by: {$Author:item.contentobject.name}
             {/section}
         {/section}
    {/let}
    </p>

    {section show$node.object.can_edit}
    <form method="post" action={"content/action/"|ezurl}>

    <br/>
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
    </form>
    {/section}
    <td>
</tr>
</table>



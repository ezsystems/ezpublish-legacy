<h1>Message preview</h1>

<div class="forum_level4">
<table class="forum" cellspacing="0">
<tr>
    <th class="author">
    Author
    </th>
    <th class="message">
    Topic
    </th>
</tr>
<tr class="bglightforum">
    <td class="author">
    <p class="author">{$node.object.owner.name|wash}<br />
    {$node.object.owner.data_map.title.content|wash}</p>
    
    <p class="date">({$node.object.published|l10n(datetime)})</p>

    <div class="authorimage">
    {attribute_view_gui attribute=$node.object.owner.data_map.user_image image_class=small}
    </div>

    <p>Location:{$node.object.owner.data_map.location.content|wash}</p>
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
    </td>
    <td class="message">
    <h3>{$node.name|wash}</h3>
    <p>
    {$node.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
</tr>
</table>
</div>


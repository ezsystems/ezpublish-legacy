<h1>{"Message preview"|i18n("design/base")}</h1>

<div class="forum_level4">
<table class="forum" cellspacing="0">
<tr>
    <th class="author">
    {"Author"|i18n("design/base")}
    </th>
    <th class="message">
    {"Topic"|i18n("design/base")}
    </th>
</tr>
<tr class="bglightforum">
    <td class="author">
    {let owner=$node.object.owner owner_map=$owner.data_map}
        <p class="author">{$owner.name|wash}
        {section show=is_set( $owner_map.title )}
            <br />{$owner_map.title.content|wash}
        {/section}</p>
        {section show=$owner_map.image.has_content}
        <div class="authorimage">
            {attribute_view_gui attribute=$owner_map.image image_class=small}
        </div>
        {/section}

        {section show=is_set( $owner_map.location )}
            <p>{"Location"|i18n("design/base")}:{$owner_map.location.content|wash}</p>
        {/section}
        <p>
        {let owner_id=$node.object.owner.id}
            {section name=Author loop=$node.object.author_array}
                {section  show=eq($owner_id,$Author:item.contentobject_id)|not()}
                    {"Moderated by"|i18n("design/base")}: {$Author:item.contentobject.name|wash}
                 {/section}
             {/section}
        {/let}
        </p>

        {section show=$node.object.can_edit}
        <form method="post" action={"content/action/"|ezurl}>

        <br/>

        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input class="button forum-edit-reply" type="submit" name="EditButton" value="{'Edit'|i18n('design/base')}" />

        </form>
        {/section}

    </td>
    <td class="message">
        <p class="date">{$node.object.published|l10n(datetime)}</p>

        <h3>{$node.name|wash}</h3>

        <p>
            {$node.data_map.message.content|simpletags|wordtoimage|autolink}
        </p>
        {section show=$owner_map.signature.has_content}
            <p class="author-signature">{$owner_map.signature.content|simpletags|autolink}</p>
        {/section}
    {/let}
    </td>
</tr>
</table>
</div>


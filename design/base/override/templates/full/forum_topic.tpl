{let reply_list=fetch('content','list', hash( parent_node_id, $node.node_id,
                                              limit, 20,
                                              offset, $view_parameters.offset,
                                              sort_by, array( array( published, true() ) ) ) )
     reply_count=fetch('content','list_count', hash( parent_node_id, $node.node_id ) ) }


<div class="content-view-full">
    <div class="class-forum">

        <h1>{$node.name|wash}</h1>

        {section show=$node.object.can_create}
        <form method="post" action={"content/action/"|ezurl}>
            <input class="button" type="submit" name="NewButton" value="New reply" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
            <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />
            <input type="hidden" name="NodeID" value="{$node.node_id}" />
            <input type="hidden" name="ClassIdentifier" value="forum_reply" />
        </form>
        {section-else}
           <p>
            {"You need to be logged in to get access to the forums. You can do so"|i18n("design/base")} <a href={"/user/login/"|ezurl}>{"here"|i18n("design/base")}</a>
           </p>
        {/section}

        <div class="content-view-children">
            <table class="list forum" cellspacing="0">
            <tr>
                <th class="author">
                    {"Author"|i18n("design/base")}
                </th>
                <th class="message">
                    {"Message"|i18n("design/base")}
                 </th>
            </tr>
            <tr>
               <td class="author">
                   <p class="author">{$node.object.owner.name|wash}</p>
                   {$node.object.owner.data_map.title.content|wash}

                  <p class="date">({$node.object.published|l10n(datetime)})</p>

                   <div class="authorimage">
                      {attribute_view_gui attribute=$node.object.owner.data_map.user_image image_class=small}
                   </div>

                   <p>
                      {let owner_id=$node.object.owner_id}
                          {section var=author loop=$node.object.author_array}
                          {section show=eq($owner_id,$author.contentobject_id)|not()}
                             {"Moderated by:"|i18n("design/base")} {$author.contentobject.name}
                          {/section}
                          {/section}
                      {/let}
                   </p>

                  {section show=$node.object.can_edit}
                      <form method="post" action={"content/action/"|ezurl}>
                          <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
                          <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
                      </form>
                  {/section}
               </td>
               <td>
                  <h2>{$node.name|wash}</h2>
                  <p>
                    {$node.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
                  </p>
               </td>
           </tr>

           {section var=reply loop=$reply_list sequence=array(bgdark,bglight)}
           <tr class="{$reply.sequence}">
               <td class="author">
                  <p>{$reply.object.owner.name|wash}<br />
                  {$reply.object.owner.data_map.title.content|wash}</p>

                  <p class="date">({$reply.object.published|l10n(datetime)})</p>

                   <div class="authorimage">
                      {attribute_view_gui attribute=$reply.object.owner.data_map.user_image image_class=small}
                  </div>

                  {let owner_id=$reply.object.owner.id}
                       {section var=author loop=$reply.object.author_array}
                            {section show=eq($reply.object.owner_id,$author.contentobject_id)|not()}
                            <p>
                             Moderated by: {$author.contentobject.name}
                            </p>
                            {/section}
                       {/section}
                  {/let}

   {switch match=$reply.object.can_edit}
   {case match=1}
   <form method="post" action={"content/action/"|ezurl}>
   <input type="hidden" name="ContentObjectID" value="{$reply.object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
   </form>
   {/case}
   {case match=0}
   {/case}
   {/switch}

    </td>
    <td class="message">
        <h2 id="msg{$reply.node_id}">{$reply.name|wash}</h2>
        <p>
        {$reply.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
        </p>
    </td>
</tr>
{/section}
            </table>

        </div>

    </div>
</div>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$reply_count
         view_parameters=$view_parameters
         item_limit=20}



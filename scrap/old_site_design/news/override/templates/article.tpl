<div id="article">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{default content_object=$node.object
         content_version=$node.contentobject_version_object}

<h1>{$node.name|wash}</h1>

<div class="byline">
  <p>
   ({$content_object.published|l10n( datetime )} by {attribute_view_gui attribute=$content_version.data_map.author})
  </p>
</div>

{attribute_view_gui attribute=$content_version.data_map.thumbnail image_class=medium alignment=right}

<div class="intro">
{attribute_view_gui attribute=$content_version.data_map.intro}
</div>

<div class="body">
{attribute_view_gui attribute=$content_version.data_map.body}
</div>

{let related_objects=$node.object.related_contentobject_array}
    {section show=$related_objects} 
<div class="relatedarticles">
       <h2>{"Related stories"|i18n("design/news/layout")}</h2>	 
       <ul>
           {section name=ContentObject loop=$related_objects show=$related_objects} 
               <li><a href={$ContentObject:item.main_node.url_alias|ezurl}>{$ContentObject:item.name}</a></li>
           {/section}
       </ul>
</div>
    {/section}
{/let}

<div class="tipafriend">
   <a href={concat('/content/tipafriend/',$node.node_id)|ezurl}>{"Tip a friend"|i18n("design/news/layout")}</a>
</div>


{section show=$node.object.data_map.enable_comments.data_int}
<div class="articlecomments">
    {let message_list=fetch( content, list, hash(
                                                parent_node_id, $node.object.main_node_id,
                                                limit, $page_limit, 
                                                offset, 0,
                                                class_filter_type, include, 
                                                class_filter_array,array( 'comment' ) ) )}

    {section show=$message_list}
        <h2>{"Comments"|i18n("design/news/layout")}</h2>
        {section name=Comment loop=$message_list}
            {node_view_gui view=line content_node=$:item}
        {/section}
    {/section}

    <h2>{"Comment this article!"|i18n("design/news/layout")}</h2>

    <div class="buttonblock">
        <form method="post" action={"content/action"|ezurl}>
        <input type="hidden" name="NodeID" value="{$node.main_node_id}" />
        <input type="hidden" name="ClassID" value="26" />
        <input class="button" type="submit" name="NewButton" value="New comment" />
        </form>
    </div>

    {/let}
</div>
{/section}

{/default}

</form>
</div>

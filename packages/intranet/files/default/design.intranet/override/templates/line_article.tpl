{* Article template *}

{default content_object=$node.object
         content_version=$node.contentobject_version_object}

<div class="listitem">
    <h2>{attribute_view_gui attribute=$content_version.data_map.title}</h2>
    <div class="imageright">
        <a class="small" href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>
        {section show=$node.object.data_map.frontpage_image.data_int}
	{let image=fetch(content,object,hash(object_id,$node.object.data_map.frontpage_image.data_int))}
        {attribute_view_gui attribute=$image.data_map.image image_class=small}
	{/let}
	{/section}
        </a>
    </div>
    <div class="byline">
        <p class="date">({$content_object.published|l10n( datetime )})</p>
    </div>
    <div class="intro">
        {attribute_view_gui attribute=$content_version.data_map.intro}
    </div>
    <p class="readmore"><a href={$node.url_alias|ezurl}>{"Read more"|i18n("design/intranet/layout")}</a></p>
</div>

{/default}

<div class="article_line">

{default content_object=$node.object
         content_version=$node.contentobject_version_object}

{attribute_view_gui attribute=$node.object.data_map.thumbnail image_class=small alignment=right href=$node.url_alias|ezurl}

<h2>{attribute_view_gui attribute=$content_version.data_map.title}</h2>

<div class="byline">
  <p>
  ({$node.object.published|add(21600)|l10n( datetime )})
  </p>
</div>


<div class="object_brief">
  {attribute_view_gui attribute=$content_version.data_map.intro}
</div>


<div class="readmore">
<a href={$node.url_alias|ezurl}>{"Read more"|i18n("design/news/layout")}</a>
</div>

{/default}

</div>

{default content_object=$node.object
         content_version=$node.contentobject_version_object}

<div class="maincontentheader">
<h1>{attribute_view_gui attribute=$content_version.data_map.title}</h1>
</div>

<div class="byline">
<p class="date">({$content_object.published|l10n(datetime)})</p>
</div>
<div class="imageright">
{attribute_view_gui attribute=$content_version.data_map.thumbnail image_class=medium}
</div>
{attribute_view_gui attribute=$content_version.data_map.intro}
{attribute_view_gui attribute=$content_version.data_map.body}

{/default}
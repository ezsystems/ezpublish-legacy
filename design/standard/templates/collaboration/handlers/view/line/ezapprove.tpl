{section show=$item.is_creator}
Awaiting approval of {content_version_view_gui view=text_linked content_version=fetch("content","version",hash("object_id",$item.content.content_object_id,"version_id",$item.content.content_object_version,))}
{section-else}
Awaiting approval for {content_version_view_gui view=text_linked content_version=fetch("content","version",hash("object_id",$item.content.content_object_id,"version_id",$item.content.content_object_version,))}
{/section}

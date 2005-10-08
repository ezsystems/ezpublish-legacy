{let content_version=fetch("content","version",hash("object_id",$item.content.content_object_id,"version_id",$item.content.content_object_version,))
     item_text=""}
{section show=$item.is_creator}

  {switch match=$item.data_int3}
  {case match=0}
    {set item_text="%1 awaits approval by editor"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
  {/case}
  {case match=1}
    {set item_text="%1 was approved for publishing"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
  {/case}
  {case in=array(2,3)}
   {set item_text="%1 was not approved for publishing"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
  {/case}
  {case/}
  {/switch}

{section-else}

  {switch match=$item.data_int3}
  {case match=0}
    {set item_text="%1 awaits your approval"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
  {/case}
  {case match=1}
   {section show=and( is_set( $content_version.name ), $content_version.name )|not() }
       {set item_text="%1 was approved for publishing"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$item.content.content_object_id|wash," [deleted]</i>")))}
   {section-else}
       {set item_text="%1 was approved for publishing"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
   {/section}

  {/case}
  {case in=array(2,3)}
   {set item_text="%1 was not approved for publishing"|i18n('design/admin/collaboration/handler/view/line/ezapprove',,array(concat("<i>",$content_version.name|wash,"</i>")))}
  {/case}
  {case/}
  {/switch}

{/section}
<p class="{$:item_class}"><a class="{$:item_class}" href={concat("collaboration/item/full/",$:item.id)|ezurl}>{$item_text}</a></p>
{/let}
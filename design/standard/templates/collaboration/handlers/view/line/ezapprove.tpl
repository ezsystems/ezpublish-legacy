{let content_version=fetch("content","version",hash("object_id",$item.content.content_object_id,"version_id",$item.content.content_object_version,))}
{section show=$item.is_creator}

  {switch match=$item.data_int3}
  {case match=0}
    <p class="{$:item_class}">{"Awaiting approval of %1"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case match=1}
    <p class="{$:item_class}">{"%1 was approved for publishing"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case match=2}
   <p class="{$:item_class}">{"%1 was not approved for publishing"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case/}
  {/switch}

{section-else}

  {switch match=$item.data_int3}
  {case match=0}
    <p class="{$:item_class}">{"Awaiting approval for %1"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case match=1}
    <p class="{$:item_class}">{"%1 was approved for publishing"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case match=2}
   <p class="{$:item_class}">{"%1 was not approved for publishing"|i18n('design/standard/collaboration',,array(concat("<i>",$content_version.name,"</i>")))}</p>
  {/case}
  {case/}
  {/switch}

{/section}
{/let}
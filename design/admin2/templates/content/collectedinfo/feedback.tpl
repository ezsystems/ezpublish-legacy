{default collection=cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Feedback for %feedbackname'|i18n( 'design/admin/content/collectedinfo/feedback',, hash( '%feedbackname', $node.name ) )|wash}{/set-block}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{$object.name|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

{if $error}

{if $error_existing_data}
<p>{'You have already submitted feedback. The previously submitted data was:'|i18n('design/admin/content/collectedinfo/feedback')}</p>
{/if}

{else}

<p>{'Thanks for your feedback. The following information was collected.'|i18n('design/admin/content/collectedinfo/feedback')}</p>

{/if}

<div class="block">
{section loop=$collection.attributes}

<h3>{$:item.contentclass_attribute_name|wash}</h3>

{attribute_result_gui view=info attribute=$:item}

{/section}
</div>

<p/>

<a href={$node.parent.url|ezurl}>{'Return to site'|i18n('design/admin/content/collectedinfo/feedback')}</a>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/default}

{default collection=cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Feedback for %feedbackname'|i18n('design/standard/content/feedback',,hash('%feedbackname',$node.name))}{/set-block}

<h1>{$object.name}</h1>

{section show=$error}

{section show=$error_existing_data}
<p>{'You have already submitted data to this feedback. The previously submitted data was the following.'|i18n('design/standard/content/feedback')}</p>
{/section}

{section-else}

<p>{'Thanks for your feedback, the following information was collected.'|i18n('design/standard/content/feedback')}</p>

{/section}

<div class="block">
{section loop=$collection.attributes}

<h3>{$:item.contentclass_attribute_name}</h3>

{attribute_result_gui view=info attribute=$:item}

{/section}
</div>

<p/>

<a href={$node.parent.url|ezurl}>{'Return to site'|i18n('design/standard/content/feedback')}</a>

{/default}

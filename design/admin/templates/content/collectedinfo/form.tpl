{default collection=cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Form %formname'|i18n('design/standard/content/form',,hash('%formname',$node.name))}{/set-block}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Collected information'|i18n( 'design/admin/content/collectedinfo/form' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<h2>{$object.name}</h2>

{section show=$error}

{section show=$error_existing_data}
<p>{'You have already submitted data to this form. The previously submitted data was the following.'|i18n('design/admin/content/collectedinfo/form')}</p>
{/section}

{/section}

{section loop=$collection.attributes}

<h3>{$:item.contentclass_attribute_name}</h3>

{attribute_result_gui view=info attribute=$:item}

{/section}

<p/>

<a href={$node.parent.url|ezurl}>{'Return to site'|i18n('design/admin/content/collectedinfo/form')}</a>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/default}

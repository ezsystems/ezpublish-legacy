{set-block scope=global variable=title}{'Poll %pollname'|i18n('design/admin/content/collectedinfo/poll',,hash('%pollname',$node.name))}{/set-block}

{section show=$error}

{section show=$error_anonymous_user}
<div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Anonymous users are not allowed to vote on this poll, please login.'|i18n('design/admin/content/collectedinfo/poll')}</h2>
</div>
{/section}

{section show=$error_existing_data}
<div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'You have already voted for this poll.'|i18n('design/admin/content/collectedinfo/poll')}</h2>
</div>
{/section}

{/section}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Poll results'|i18n( 'design/admin/content/collectedinfo/poll' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<h2>{$node.name|wash}</h2>

{section loop=$object.contentobject_attributes}
    {section show=$:item.contentclass_attribute.is_information_collector}

        <h3>{$:item.contentclass_attribute.name}</h3>
        {attribute_result_gui view=count attribute=$:item}

    {section-else}

        {section show=$attribute_hide_list|contains($:item.contentclass_attribute.identifier)|not}
            <h3>{$:item.contentclass_attribute.name}</h3>
            {attribute_view_gui attribute=$:item}
        {/section}

    {/section}

{/section}

<p>{"%count total votes"|i18n( 'design/admin/content/collectedinfo/poll' ,,
                             hash( '%count', fetch( content, collected_info_count, hash( object_id, $object.id ) ) ) )}</p>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

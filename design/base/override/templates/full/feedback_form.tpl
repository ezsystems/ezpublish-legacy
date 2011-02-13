{* Feedback form - Full view *}

<div class="content-view-full">
    <div class="class-feedback-form">

        <h1>{$node.name|wash()}</h1>

        {include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}

        <div class="attribute-short">
                {attribute_view_gui attribute=$node.data_map.description}
        </div>
        <form method="post" action={"content/action"|ezurl}>

        <h2>{"Your email address"|i18n("design/base")}</h2>
        <div class="attribute-email">
                {attribute_view_gui attribute=$node.data_map.email}
        </div>

        <h2>{"Subject"|i18n("design/base")}</h2>
        <div class="attribute-subject">
                {attribute_view_gui attribute=$node.data_map.subject}
        </div>

        <h2>{"Message"|i18n("design/base")}</h2>
        <div class="attribute-message">
                {attribute_view_gui attribute=$node.data_map.message}
        </div>

        <div class="content-action">
            <input type="submit" class="defaultbutton" name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </div>
        </form>

    </div>
</div>
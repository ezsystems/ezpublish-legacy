{* Feedback form - Admin preview *}

<div class="content-view-full">
    <div class="class-feedback-form">

        <h1>{$node.name|wash()}</h1>

        {include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}

        <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <h2>{"Your E-mail address"|i18n("design/base")}</h2>
        <div class="attribute-email">
                {attribute_view_gui attribute=$node.object.data_map.email}
        </div>

        <h2>{"Subject"|i18n("design/base")}</h2>
        <div class="attribute-subject">
                {attribute_view_gui attribute=$node.object.data_map.subject}
        </div>

        <h2>{"Message"|i18n("design/base")}</h2>
        <div class="attribute-message">
                {attribute_view_gui attribute=$node.object.data_map.message}
        </div>

    </div>
</div>
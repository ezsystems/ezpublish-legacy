{* Feedback form - Admin preview *}
<div class="content-view-full">
    <div class="class-feedback-form">

        <h1>{$node.name|wash()}</h1>

        {include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}

        {* Description. *}
        <div class="attribute-short">
            {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        {* Email address (information collector). *}
        <h2>{'Your E-mail address'|i18n( 'design/admin/preview/feedbackform' )}</h2>
        <div class="attribute-email">
            {attribute_view_gui attribute=$node.object.data_map.email}
        </div>

        {* Subject (information collector). *}
        <h2>{'Subject'|i18n( 'design/admin/preview/feedbackform' )}</h2>
        <div class="attribute-subject">
            {attribute_view_gui attribute=$node.object.data_map.subject}
        </div>

        {* Message (information collector). *}
        <h2>{'Message'|i18n( 'design/admin/preview/feedbackform' )}</h2>
        <div class="attribute-message">
            {attribute_view_gui attribute=$node.object.data_map.message}
        </div>

        {* Recipient. *}
        <h2>{'Recipient'|i18n( 'design/admin/preview/feedbackform' )}</h2>
        <div class="attribute-short">
            {attribute_view_gui attribute=$node.object.data_map.recipient}
        </div>

    </div>
</div>

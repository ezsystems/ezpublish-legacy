{* Feedback form - Admin preview *}
<div class="content-view-full">
    <div class="class-feedback-form">

        <h1>{$node.name|wash()}</h1>

        {* Description. *}
        <div class="attribute-short">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>

        {* Email address (information collector). *}
        <div class="attribute-email">
            <label>{'Your email address'|i18n( 'design/admin/preview/feedbackform' )}:</label>
            {attribute_view_gui attribute=$node.data_map.email}
        </div>

        {* Sender name (information collector). *}
        {if is_set( $node.data_map.sender_name )}
            <div class="attribute-email">
                <label>{'Your name'|i18n( 'design/admin/preview/feedbackform' )}:</label>
                {attribute_view_gui attribute=$node.data_map.sender_name}
            </div>
        {/if}

        {* Subject (information collector). *}
        <div class="attribute-subject">
            <label>{'Subject'|i18n( 'design/admin/preview/feedbackform' )}:</label>
            {attribute_view_gui attribute=$node.data_map.subject}
        </div>

        {* Message (information collector). *}
        <div class="attribute-message">
            <label>{'Message'|i18n( 'design/admin/preview/feedbackform' )}:</label>
            {attribute_view_gui attribute=$node.data_map.message}
        </div>

        {* Recipient. *}
        <div class="content-control">
            <label>{'Recipient'|i18n( 'design/admin/preview/feedbackform' )}:</label>
            {attribute_view_gui attribute=$node.data_map.recipient}
        </div>

    </div>
</div>

{* Article - Admin preview *}
<div class="content-view-full">
    <div class="class-article">

        <h1>{$node.data_map.title.content|wash()}</h1>

        {* Author. *}
        {section show=$node.data_map.author.has_content}
            <div class="attribute-byline">
                <p class="author">{attribute_view_gui attribute=$node.data_map.author}</p>
                <p class="date">{$node.object.published|l10n(date)}</p>
                <div class="break"></div>
            </div>
        {/section}

        {* Image. *}
        {section show=$node.data_map.image.has_content}
            <div class="attribute-image">
                {attribute_view_gui attribute=$node.data_map.image align=right}
            </div>
        {/section}

        {* Intro. *}
        {section show=$node.data_map.intro.has_content}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.data_map.intro}
            </div>
        {/section}

        {* Body. *}
        {section show=$node.data_map.body.has_content}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.data_map.body}
            </div>
        {/section}

        {* Comments. *}
        <div class="content-control">
            <label>{'Comments allowed'|i18n( 'design/admin/preview/article' )}:</label>
            {section show=$node.data_map.enable_comments.content}
                <p>{'Yes'|i18n( 'design/admin/preview/article' )}</p>
                {section-else}
                <p>{'No'|i18n( 'design/admin/preview/article' )}</p>
            {/section}
        </div>

    </div>
</div>

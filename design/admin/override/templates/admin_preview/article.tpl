{* Article - Admin preview *}
<div class="content-view-full">
    <div class="class-article">
        <h1>{$node.object.data_map.title.content|wash()}</h1>

        {* Author. *}
        {section show=$node.object.data_map.author.content.is_empty|not()}
            <div class="attribute-byline">
                <p class="author">{attribute_view_gui attribute=$node.object.data_map.author}</p>
                <p class="date">{$node.object.published|l10n(date)}</p>
            </div>
        {/section}

        {* Image. *}
        {section show=$node.object.data_map.image.content}
            <div class="attribute-image">
                {attribute_view_gui attribute=$node.object.data_map.image align=right}
            </div>
        {/section}

        {* Intro. *}
        {section show=$node.object.data_map.intro.content.is_empty|not}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.intro}
            </div>
        {/section}

        {* Body. *}
        {section show=$node.object.data_map.body.content.is_empty|not}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.object.data_map.body}
            </div>
        {/section}

        {* Comments. *}
        <div class="content-control">
            <label>Comments allowed:</label>
            {section show=$node.object.data_map.enable_comments.content}
                <p>Yes</p>
                {section-else}
                <p>No</p>
            {/section}
        </div>

    </div>
</div>

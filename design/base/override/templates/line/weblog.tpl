{* Weblog - Line view *}

<div class="content-view-line">
    <div class="class-weblog">

        <h2>{$node.name|wash()}</h2>

        <div class="attribute-byline">
            <p class="author">{$node.object.owner.name|wash(xhtml)}</p>
            <p class="date">{$node.object.published|l10n(date)}</p>
            {let assigned_nodes=$node.object.assigned_nodes}
            {section show=$assigned_nodes|count|gt( 1 )}
                <p class="placement">in
            {section var=assigned loop=$assigned_nodes}
                <a href={$assigned.parent.url_alias|ezurl}>{$assigned.parent.name|wash}</a>
            {delimiter}, {/delimiter}
            {/section}
                </p>
            {/section}
            {/let}

           <div class="break"></div>
        </div>

        <div class="attribute-message">
           {attribute_view_gui attribute=$node.object.data_map.message}
        </div>

        {section show=$node.object.data_map.enable_comments.content}
        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>{'Comments'|i18n( 'design/base/weblog' )}</a></p>
        </div>
        {section-else}
        <div class="attribute-link-disabled">
            <p><a href={$node.url_alias|ezurl}>{'Comments off'|i18n( 'design/base/weblog' )}</a></p>
        </div>
        {/section}
   </div>
</div>

{* Weblog - Line view *}

<div class="content-view-line">
    <div class="class-weblog">

        <h2><a href={$node.url_alias|ezurl}>{$node.name|wash()}</a></h2>

        <div class="attribute-byline">
            <p class="author">{$node.object.owner.name|wash(xhtml)}</p>
            <p class="date">{$node.object.published|l10n(date)}</p>
            {let assigned_nodes=$node.object.assigned_nodes}
            {section show=$assigned_nodes|count|gt( 1 )}
                <p class="placement">{'in'|i18n( 'design/base' )}
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
           {attribute_view_gui attribute=$node.data_map.message}
        </div>

        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>{'View comments'|i18n( 'design/base' )}</a></p>
        </div>
   </div>
</div>

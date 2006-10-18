{section show=and( is_set( $validation.processed ), and( $validation.processed, $collection_attributes ) )}
    <div class="message-warning">
        {section show=$validation.attributes}
            <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The information could not be collected.'|i18n( 'design/admin/node/view/full' )}</h2>
            <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/node/view/full' )}:</p>
            <ul>
                {section var=UnvalidatedAttributes loop=$validation.attributes}
                    <li>{$UnvalidatedAttributes.item.name|wash}: {$UnvalidatedAttributes.item.description}</li>
                {/section}
            </ul>
        {/section}
    </div>
{/section}

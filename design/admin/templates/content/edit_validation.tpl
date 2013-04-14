{section show=$validation.processed}
    {section show=or( $validation.attributes, $validation.placement, $validation.custom_rules )}
        <div class="message-error">
            <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The draft could not be stored.'|i18n( 'design/admin/content/edit' )}</h2>

            {section show=$validation.attributes}
                <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/content/edit' )}:</p>
                <ul>
                    {section var=UnvalidatedAttributes loop=$validation.attributes}
                        <li>{$UnvalidatedAttributes.item.name|wash}: {$UnvalidatedAttributes.item.description}</li>
                    {/section}
                </ul>
            {/section}

            {section show=$validation.placement}
                <p>{'The following locations are invalid'|i18n( 'design/admin/content/edit' )}:</p>
                <ul>
                    {section var=UnvalidatedPlacements loop=$validation.placement}
                        <li>{$UnvalidatedPlacements.item.text}</li>
                    {/section}
                </ul>
            {/section}

            {section show=$validation.custom_rules}
                <p>{'The following data is invalid according to the custom validation rules'|i18n( 'design/admin/content/edit' )}:</p>
                <ul>
                    {section var=UnvalidatedCustomRules loop=$validation.custom_rules}
                        <li>{$UnvalidatedCustomRules.item.text}</li>
                    {/section}
                </ul>
            {/section}
        </div>

    {section-else}

        {section show=$validation_log}
            <div class="message-warning">
                <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The draft was only partially stored.'|i18n( 'design/admin/content/edit' )}</h2>
                {section var=ValidationLogs loop=$validation_log}
                    <p>{$ValidationLogs.item.name|wash}:</p>
                    <ul>
                        {section var=LogMessages loop=$ValidationLogs.item.description}
                            <li>{$LogMessages.item}</li>
                        {/section}
                    </ul>
                {/section}
            </div>
            {section-else}
            <div class="message-feedback">
                <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The draft was successfully stored.'|i18n( 'design/admin/content/edit' )}</h2>
            </div>
        {/section}
    {/section}
{/section}

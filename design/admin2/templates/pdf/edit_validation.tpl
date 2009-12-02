{section show=$validation.processed}
    {section show=$validation.placement}
        <div class="message-warning">
            <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The PDF export could not be stored.'|i18n( 'design/admin/pdf/edit' )}</h2>
            <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/pdf/edit' )}:</p>
                <ul>
                    {section var=UnvalidatedPlacements loop=$validation.placement}
                        <li>{$UnvalidatedPlacements.item.text}</li>
                    {/section}
                </ul>
        </div>
    {/section}
{/section}

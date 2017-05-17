<div class="objectinfo">
    <div class="box-header">
        <h4>{'Object information'|i18n( 'design/admin/content/history' )}</h4>
    </div>

    <div class="box-content">
        {* Created *}
        <p>
            <label>{'Created'|i18n( 'design/admin/content/history' )}:</label>
            {if $object.published}
                {$object.published|l10n( shortdatetime )}<br />
                {$object.owner.name|wash}
            {else}
                {'Not yet published'|i18n( 'design/admin/content/history' )}
            {/if}
        </p>
        {* Object ID *}
        <p>
            <label>{'ID'|i18n( 'design/admin/content/history' )}:</label>
            {$object.id}
        </p>
    </div>

    {* Published version*}
    <div class="box-header">
        <h4>{'Published version'|i18n( 'design/admin/content/history' )}</h4>
    </div>

    <div class="box-content">
        {if $object.published}
            <p>
                <label>{'Published'|i18n( 'design/admin/content/history' )}:</label>
                {$object.current.modified|l10n( shortdatetime )}<br />
                {$object.current.creator.name|wash}
            </p>
            <p>
                <label>{'Version'|i18n( 'design/admin/content/history' )}:</label>
                {$object.published_version}
            </p>
        {else}
            <p>{'Not yet published'|i18n( 'design/admin/content/history' )}</p>
        {/if}

        {if and( is_set($manage_version_button), $manage_version_button )}
            {* Manage versions *}
            <div class="block">
                {if $object.versions|count|gt( 1 )}
                    <input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/view/versionview' )}" />
                {else}
                    <input class="button-disabled" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'You cannot manage the versions of this object because there is only one version available (the one that is being displayed).'|i18n( 'design/admin/content/view/versionview' )}" />
                {/if}
            </div>
        {/if}
    </div>
</div>
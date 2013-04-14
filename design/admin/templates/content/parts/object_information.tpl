<div class="objectinfo">

{* DESIGN: Header START *}<div class="box-header">

<h4>{'Object information'|i18n( 'design/admin/content/history' )}</h4>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/history' )}:</label>
{$object.id}
</p>

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

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/history' )}:</label>
{if $object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/history' )}
{/if}
</p>

{* Published version*}
<p>
<label>{'Published version'|i18n( 'design/admin/content/history' )}:</label>
{if $object.published}
{$object.current_version}
{else}
{'Not yet published'|i18n( 'design/admin/content/history' )}
{/if}
</p>

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

{* DESIGN: Content END *}</div>

</div>
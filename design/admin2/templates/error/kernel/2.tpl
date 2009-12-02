<div class="message-error">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (2)'|i18n( 'design/admin/error/kernel' )}</h2>
<p>{'The resource you requested was not found.'|i18n( 'design/admin/error/kernel' )}</p>
<p>{'Possible reasons'|i18n( 'design/admin/error/kernel' )}:</p>
<ul>
    <li>{'The ID number or the name of the resource was misspelled. Try changing the URL.'|i18n( 'design/admin/error/kernel' )}</li>
    <li>{'The resource is no longer available.'|i18n( 'design/admin/error/kernel' )}</li>
</ul>
</div>


{if $embed_content}
    {$embed_content}
{/if}

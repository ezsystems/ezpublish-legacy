{section show=$filter_proccessed}
<div class="message-feedback">
<h2>{'The notification filter proccessed all available notification events.'|i18n( 'design/admin/notification/runfilter' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
</div>
{/section}

{section show=$time_event_created}
<div class="message-feedback">
<h2>{'The notification time event was spawned.'|i18n( 'design/admin/notification/runfilter' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
</div>
{/section}

<form method="post" action={'/notification/runfilter/'|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Notification'|i18n( 'design/admin/notification/runfilter' )}</h2>

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RunFilterButton" value="{'Run notification filer'|i18n( 'design/admin/notification/runfilter' )}" />
    <input class="button" type="submit" name="SpawnTimeEventButton" value="{'Spawn time event'|i18n( 'design/admin/notification/runfilter' )}" />
</div>
</div>

</div>

</form>

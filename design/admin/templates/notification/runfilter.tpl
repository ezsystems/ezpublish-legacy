{if $filter_proccessed}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The notification filter processed all available notification events.'|i18n( 'design/admin/notification/runfilter' )}</h2>
</div>
{/if}

{if $time_event_created}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The notification time event was spawned.'|i18n( 'design/admin/notification/runfilter' )}</h2>
</div>
{/if}

<form method="post" action={'/notification/runfilter/'|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Notification'|i18n( 'design/admin/notification/runfilter' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

&nbsp;

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="RunFilterButton" value="{'Run notification filter'|i18n( 'design/admin/notification/runfilter' )}" />
    <input class="button" type="submit" name="SpawnTimeEventButton" value="{'Spawn time event'|i18n( 'design/admin/notification/runfilter' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>

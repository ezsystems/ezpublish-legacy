{section show=$md5_result}
  {section show=$md5_result|eq('ok')}
    <div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"File consistency check OK."|i18n("design/admin/setup")}</h2>
    </div>
  {section-else}
    <div class="message-warning">
    {section show=and( is_set( $failure_reason ), $failure_reason )}
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$failure_reason}</h2>
    {section-else}
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"Warning: it is not safe to upgrade without checking the modifications done to the following files"|i18n("design/admin/setup")}:</h2>
    <p>
      {section loop=$md5_result}
        {$:item|wash}
        {delimiter}<br />{/delimiter}
      {/section}
    </p>
    {/section}
    </div>
  {/section}
{/section}

{if $upgrade_sql}
  {if $upgrade_sql|eq('ok')}
    <div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"Database check OK."|i18n("design/admin/setup")}</h2>
    </div>
  {else}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"The database is not consistent with the distribution database."|i18n("design/admin/setup")}</h2>
    <p>{"To synchronize your database with the distribution setup, run the following SQL commands"|i18n("design/admin/setup")}:</p>
    <p>
      {$upgrade_sql|wash|break}
    </p>
    </div>
  {/if}
{/if}

<form method="post" action={"/setup/systemupgrade/"|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"System upgrade check"|i18n("design/admin/setup")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>{'Before upgrading eZ Publish to a newer version, it is important to check that the current installation is ready for upgrading.'|i18n('design/admin/setup')}</p>
<p>{'Remember to make a backup of the eZ Publish directory and the database before you upgrade.'|i18n('design/admin/setup')}</p>
<h3>{'File consistency check'|i18n('design/admin/setup')}</h3>
{'The file consistency tool checks if you have altered any of the files that came with the current installation. Altered files may be replaced by new versions that contain bugfixes, new features, etc. Make sure that you backup and then merge your changes into the new versions of the files.'|i18n('design/admin/setup')}
<h3>{'Database consistency check'|i18n('design/admin/setup')}</h3>
{'The database consistency tool checks if the current database is consistent with the database schema that came with the eZ Publish distribution. If there are any inconsistencies, the tool will suggest the necessary SQL statements that should be run in order to bring the database into a consistent state. Please run the suggested SQL statements before upgrading.'|i18n('design/admin/setup')}
<div class="block">
<p>{'The upgrade checking tools require a lot of system resources. They may take some time to run.'|i18n('design/admin/setup')}</p>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
  <input class="button" type="submit" name="MD5CheckButton" value="{'Check file consistency'|i18n("design/admin/setup")}" />
  <input class="button" type="submit" name="DBCheckButton" value="{'Check database consistency'|i18n("design/admin/setup")}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>

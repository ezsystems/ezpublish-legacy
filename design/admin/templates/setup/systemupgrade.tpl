{section show=$md5_result}
  {section show=$md5_result|eq('ok')}
    <div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"File consistency check OK"|i18n("design/admin/setup")}</h2>
    </div>
  {section-else}
    <div class="message-warning">
    {section show=$failure_reason}
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$failure_reason}</h2>
    {section-else}
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"Warning, it is not safe to upgrade without checking the modifications done to the following files :"|i18n("design/admin/setup")}</h2>
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

{section show=$upgrade_sql}
  {section show=$upgrade_sql|eq('ok')}
    <div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"Database check OK"|i18n("design/admin/setup")}</h2>
    </div>
  {section-else}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {"Warning, your database is not consistent with the distribution database."|i18n("design/admin/setup")}</h2>
    <p>{"To synchronize your database with the distribution setup, run the following SQL queries:"|i18n("design/admin/setup")}</p>
    <p>
      {$upgrade_sql|wash|break}
    </p>
    </div>
  {/section}
{/section}

<form method="post" action={"/setup/systemupgrade/"|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"System upgrade check"|i18n("design/admin/setup")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{'Before you upgrade to a newer version of eZ publish it is important to check that your installation is ready. We provide two means of checking your installation.'|i18n('design/admin/setup')}
<h3>{'File consistency'|i18n('design/admin/setup')}</h3>
{'The file consistency tool checks if you have altered any of the files that came with your current installation. Upgrading your installation may cause these files to be overwritten. Also, if a file is replaced this usually means that there are feature improvements or bugfixes. Make sure that you incorporate these changes into your version of the file.'|i18n('design/admin/setup')}
<h3>{'Database consistency'|i18n('design/admin/setup')}</h3>
{'The database consistency tool checks if your database is synchronized with the database schema delivered with your current ez publish installation. If inconsistencies are found, the tool suggests SQL statements that brings your database up to date. Please run these SQL statements on your database prior to upgrading.'|i18n('design/admin/setup')}
<div class="block">
<p><strong>{'Warning: '|i18n('design/admin/setup')}</strong>{'These tools need a lot of resources and may take a while. Always remember to take a backup of your site before upgrading.'|i18n('design/admin/setup')}</p>

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

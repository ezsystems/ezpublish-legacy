<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'System information'|i18n( 'design/admin/setup/info' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="context-attributes">

<table class="list" cellspacing="0">

<tr>
    <th><label>{'eZ Publish'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <div class="block">
        <label>{'Site'|i18n( 'design/admin/setup/info' )}:</label>
        {ezini('SiteSettings','SiteURL')}
    </div>

    <div class="block">
        <label>{'Version'|i18n( 'design/admin/setup/info', 'eZ Publish version' )}:</label>
        {$ezpublish_version}
    </div>

    <div class="block">
        <label>{'SVN revision'|i18n( 'design/admin/setup/info', 'eZ Publish version' )}:</label>
        {$ezpublish_revision}
    </div>

    <div class="block">
        <label>{'Extensions'|i18n( 'design/admin/setup/info', 'eZ Publish extensions' )}:</label>
        {if $ezpublish_extensions}
            {foreach $ezpublish_extensions as $extension}
                {$extension}{delimiter}, {/delimiter}
            {/foreach}
        {else}
            {'Not in use.'|i18n( 'design/admin/setup/info' )}
        {/if}
    </div>

</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
    <th><label>{'PHP'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <div class="block">
        <label>{'Version'|i18n( 'design/admin/setup/info', 'PHP version' )}:</label>
        {$php_version} (<a href={'/setup/info/php'|ezurl}>{'Details'|i18n( 'design/admin/setup/info', 'Detailed PHP information' )}</a>)
    </div>

    <div class="block">
        <label>{'Extensions'|i18n( 'design/admin/setup/info', 'PHP extensions' )}:</label>
        {foreach $php_loaded_extensions as $loadedExtension}{$loadedExtension}{delimiter}, {/delimiter}{/foreach}
    </div>

    <div class="block">
    <label>{'Miscellaneous'|i18n( 'design/admin/setup/info' )}:</label>
        {if $php_ini.safe_mode}
            {'Safe mode is on.'|i18n( 'design/admin/setup/info' )}<br/>
        {else}
            {'Safe mode is off.'|i18n( 'design/admin/setup/info' )}<br/>
        {/if}
        {if $php_ini.open_basedir}
            {'Basedir restriction is on and set to %1.'|i18n( 'design/admin/setup/info',, array( $php_ini.open_basedir ) )}<br/>
        {else}
            {'Basedir restriction is off.'|i18n( 'design/admin/setup/info' )}<br/>
        {/if}
        {if $php_ini.register_globals}
            {'Global variable registration is on.'|i18n( 'design/admin/setup/info' )}<br/>
        {else}
            {'Global variable registration is off.'|i18n( 'design/admin/setup/info' )}<br/>
        {/if}
        {if $php_ini.file_uploads}
            {'File uploading is enabled.'|i18n( 'design/admin/setup/info' )}<br/>
        {else}
            {'File uploading is disabled.'|i18n( 'design/admin/setup/info' )}<br/>
        {/if}
        {'Maximum size of post data (text and files) is %1.'|i18n( 'design/admin/setup/info',, array( $php_ini.post_max_size ) )}<br/>
        {if and( is_set( $php_ini.memory_limit ), $php_ini.memory_limit )}
            {'Script memory limit is %1.'|i18n( 'design/admin/setup/info' ,,array( $php_ini.memory_limit ) )}<br/>
        {else}
            {'Script memory limit is unlimited.'|i18n( 'design/admin/setup/info' )}<br/>
        {/if}
        {'Maximum execution time is %1 seconds.'|i18n( 'design/admin/setup/info',, array( $php_ini.max_execution_time ) )}<br/>
    </div>
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'PHP Accelerator'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
{if $php_accelerator}

<div class="block">
<label>{'Name'|i18n( 'design/admin/setup/info', 'PHP Accelerator name' )}:</label>
{if $php_accelerator.url}<a href="{$php_accelerator.url|wash}">{/if}{$php_accelerator.name|wash}{if $php_accelerator.url}</a>{/if}
</div>

<div class="block">
    <label>{'Version'|i18n( 'design/admin/setup/info', 'PHP Accelerator version' )}:</label>
    {if $php_accelerator.version_string}
        {$php_accelerator.version_string|wash}
        {else}
        {'Version information could not be detected.'|i18n( 'design/admin/setup/info' )}
    {/if}
</div>

<div class="block">
    <label>{'Status'|i18n( 'design/admin/setup/info' ,'PHP Accelerator status')}:</label>
    {if $php_accelerator.enabled}
        {'Enabled.'|i18n( 'design/admin/setup/info' )}
    {else}
        {'Disabled.'|i18n( 'design/admin/setup/info' )}
    {/if}
</div>

{else}
<div class="block">
    {'A known and active PHP Accelerator could not be found.'|i18n( 'design/admin/setup/info' )}
</div>
{/if}
</td>
</tr>
</table>
</table>

<table class="list" cellspacing="0">
<tr>
    <th><label>{'Web server (software)'|i18n( 'design/admin/setup/info', 'Web server title' )}</label></th>
</tr>
<tr>
<td>
    {if $webserver_info}

    <div class="block">
        <label>{'Name'|i18n( 'design/admin/setup/info', 'Web server name')}:</label>
        {$webserver_info.name}
    </div>

    <div class="block">
        <label>{'Version'|i18n( 'design/admin/setup/info', 'Web server version')}:</label>
        {$webserver_info.version}
    </div>

    <div class="block">
    <label>{'Modules'|i18n( 'design/admin/setup/info', 'Web server modules')}:</label>
    {if $webserver_info.modules}
        {section loop=$webserver_info.modules}{$:item}{delimiter}, {/delimiter}{/section}
    {else}
        {'The modules of the web server could not be detected.'|i18n( 'design/admin/setup/info', 'Web server modules')}
    {/if}
    </div>

    {else}
        {'eZ Publish was unable to extract information from the web server.'|i18n( 'design/admin/setup/info' )}
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
    <th><label>{'Web server (hardware)'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <div class="block">
        <label>{'CPU'|i18n( 'design/admin/setup/info', 'CPU info' )}:</label>
        {$system_info.cpu_type} {if $system_info.cpu_speed|is_null|not}{$system_info.cpu_speed} MHz{/if}
    </div>

    <div class="block">
        <label>{'Memory'|i18n( 'design/admin/setup/info', 'Memory info' )}:</label>
        {$system_info.memory_size|si( byte )}
    </div>
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'Database'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
<div class="block">
    <label>{'Type'|i18n( 'design/admin/setup/info', 'Database type' )}:</label>
    {$database_info}
</div>

<div class="block">
    <label>{'Server'|i18n( 'design/admin/setup/info', 'Database server' )}:</label>
    {$database_object.database_server}
</div>

<div class="block">
    <label>{'Socket path'|i18n( 'design/admin/setup/info', 'Database socket path' )}:</label>
    {if $database_object.database_socket_path}
        {$database_object.database_socket_path}
    {else}
        {'Not in use.'|i18n( 'design/admin/setup/info' )}
    {/if}
</div>

<div class="block">
    <label>{'Database name'|i18n( 'design/admin/setup/info', 'Database name' )}:</label>
    {$database_object.database_name}
</div>

<div class="block">
    <label>{'Connection retry count'|i18n( 'design/admin/setup/info', 'Database retry count' )}:</label>
    {$database_object.retry_count}
</div>

<div class="block">
    <label>{'Character set'|i18n( 'design/admin/setup/info', 'Database charset' )}:</label>
    {$database_charset|wash}{if $database_object.is_internal_charset} ({'Internal'|i18n( 'design/admin/setup/info' )}){/if}
</div>

</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
<th><label>{'Slave database (read only)'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
{if $database_object.use_slave_server}

    <div class="block">
        <label>{'Server'|i18n( 'design/admin/setup/info', 'Database server' )}:</label>
        {$database_object.slave_database_server}
    </div>

    <div class="block">
        <label>{'Database'|i18n( 'design/admin/setup/info', 'Database name' )}:</label>
        {$database_object.slave_database_name}
    </div>

{else}

    {'There is no slave database in use.'|i18n( 'design/admin/setup/info' )}

{/if}
</td>
</tr>
</table>


</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

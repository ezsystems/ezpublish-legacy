<div class="message-warning">

<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Class locked'|i18n( 'design/standard/class/edit_locked' )}</h2>

    <p>{'This class has pending modifications defered to cronjob and thus it cannot be edited.'|i18n( 'design/standard/class/edit_locked' )}</p>
    <p>{'Wait until the script is finished. You might see the status in the %urlstart script monitor%urlend</a>.'|i18n( 'design/standard/class/edit_locked', , hash( '%urlstart', concat( '<a href=', 'scriptmonitor/list'|ezurl, '>' ),
                                                                                                                                                                  '%urlend', '</a>' ) )}</p>
    <p>{'To force the modification of the class you may run the following command'|i18n( 'design/standard/class/edit_locked' )}:</p>
    <pre>php extension/ezscriptmonitor/bin/syncobjectattributes.php -s {$access_type.name|wash} --classid={$class.id}</pre>

</div>


<form action={concat( 'class/edit/', $class.id )|ezurl} method="post" name="ClassEdit">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%class_name> [Class]'|i18n( 'design/standard/class/edit_locked',, hash( '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Class'|i18n( 'design/standard/class/edit_locked' )}:</label>
{$class.name|wash}
</div>

<div class="block">
<label>{'Last modifier'|i18n( 'design/standard/class/edit_locked' )}:</label>
<a href={$class.modifier.contentobject.main_node.url_alias|ezurl}>{$class.modifier.contentobject.name|wash}</a>
</div>

<div class="block">
<label>{'Last modified on'|i18n( 'design/standard/class/edit_locked' )}:</label>
{$class.modified|l10n( shortdatetime )}
</div>

<p>{'The class will be available for editing after the script has been run by the cronjob.'|i18n( 'design/standard/class/edit_locked' )}</p>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="RetryButton" value="{'Retry'|i18n( 'design/standard/class/edit_locked' )}" />
<input class="button" type="submit" name="CancelConflictButton" value="{'Cancel'|i18n( 'design/standard/class/edit_locked' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

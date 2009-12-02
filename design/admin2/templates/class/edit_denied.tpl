<div class="message-warning">

<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Class edit conflict'|i18n( 'design/admin/class/edit_denied' )}</h2>

    <p>{'This class is already being edited by someone else.'|i18n( 'design/admin/class/edit_denied' )|wash}</p>
    <p>{'The class is temporarily locked and thus it cannot be edited by you.'|i18n( 'design/admin/class/edit_denied' )}</p>
    <p>{'Possible actions'|i18n( 'design/admin/class/edit_denied' )}:</p>
<ul>
    <li>{'Contact the person who is editing the class.'|i18n( 'design/admin/class/edit_denied' )}</li>
    <li>{'Wait until the lock expires and try again.'|i18n( 'design/admin/class/edit_denied' )}</li>
</ul>

</div>


<form action={concat( 'class/edit/', $class.id )|ezurl} method="post" name="ClassEdit">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%class_name> [Class]'|i18n( 'design/admin/class/edit_denied',, hash( '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Class'|i18n( 'design/admin/class/edit_denied' )}:</label>
{$class.name|wash}
</div>

<div class="block">
<label>{'Current modifier'|i18n( 'design/admin/class/edit_denied' )}:</label>
<a href={$class.modifier.contentobject.main_node.url_alias|ezurl}>{$class.modifier.contentobject.name|wash}</a>
</div>

<div class="block">
<label>{'Unlock time'|i18n( 'design/admin/class/edit_denied' )}:</label>
{sum( $class.modified, $lock_timeout )|l10n( shortdatetime )}
</div>

<p>{'The class will be available for editing after it has been stored by the current modifier or when it is unlocked by the system.'|i18n( 'design/admin/class/edit_denied' )}</p>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="RetryButton" value="{'Retry'|i18n( 'design/admin/class/edit_denied' )}" />
<input class="button" type="submit" name="CancelConflictButton" value="{'Cancel'|i18n( 'design/admin/class/edit_denied' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

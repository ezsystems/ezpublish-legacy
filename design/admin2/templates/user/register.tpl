{* Warning *}
{section show=$validation.processed}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The information could not be stored...'|i18n( 'design/admin/user/register' )}</h2>
<p>{'The following information is either incorrect or missing'|i18n( 'design/admin/user/register' )}:</p>
<ul>
{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<li>{$UnvalidatedAttributes:item.name|wash}: {$UnvalidatedAttributes:item.description}</li>
{/section}
</ul>
<p>{'Please correct the inputs (marked with red labels) and try again.'|i18n( 'design/admin/user/register' )}</p>
</div>
{/section}

{* Register window *}
<div class="context-block">

<form name="registerform" method="post" action={'/user/register/'|ezurl} enctype="multipart/form-data">
{if count($content_attributes)|gt(0)}
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Register new user'|i18n( 'design/admin/user/register' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
    {include uri='design:content/edit_attribute.tpl'}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <input class="button" type="submit" id="PublishButton" name="PublishButton" value="{'OK'|i18n( 'design/admin/user/register' )}" onclick="window.setTimeout( disableButtons, 1 ); return true;" />
        <input class="button" type="submit" id="CancelButton" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/user/register' )}" onclick="window.setTimeout( disableButtons, 1 ); return true;" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

<input type="hidden" name="" value="" />

{else}
{* Warning *}
<div class="message-warning">
<h2>{'Unable to register new user'|i18n( 'design/admin/user/register' )}</h2>
</div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <input class="button" type="submit" id="CancelButton" name="CancelButton" value="{'Back'|i18n( 'design/admin/user/register' )}" onclick="window.setTimeout( disableButtons, 1 ); return true;" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
{/if}

</form>

</div>




{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        with( registerform )
        {
            for( var i=0; i<elements.length; i++ )
            {
                if( elements[i].type == 'text' )
                {
                    elements[i].focus();
                    return;
                }
            }
        }
    }

    function disableButtons()
    {
        document.getElementById( 'PublishButton' ).disabled = true;
        document.getElementById( 'CancelButton' ).disabled = true;
    }
-->
</script>
{/literal}

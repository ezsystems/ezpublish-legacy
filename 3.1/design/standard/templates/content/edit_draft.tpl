{let has_own_drafts=false()
     has_other_drafts=false()
     current_creator=fetch(user,current_user)}
{section loop=$draft_versions}
    {section show=eq($item.creator_id,$current_creator.contentobject_id)}
        {set has_own_drafts=true()}
    {section-else}
        {set has_other_drafts=true()}
    {/section}
{/section}
<form method="post" action={concat('content/edit/',$object.id)|ezurl}>

<div class="objectheader">
<h2>{$object.name|wash}</h2>
</div>

<div class="object">
<p>
The currently published version is {$object.current_version} and was published at {$object.published|l10n(datetime)}.
</p>
<p>
The last modification was done at {$object.modified|l10n(datetime)}.
</p>
<p>
The object is owned by {content_view_gui view=text_linked content_object=$object.owner}.
</p>
</div>

{section show=and($has_own_drafts,$has_other_drafts)}
<p>
    This object is already being edited by someone else including you.
    You can either continue editing one of your drafts or you can create a new draft.
</p>
{section-else}
    {section show=$has_own_drafts}
    <p>
        This object is already being edited by you.
        You can either continue editing one of your drafts or you can create a new draft.
    </p>
    {/section}
    {section show=$has_other_drafts}
    <p>
        This object is already being edited by someone else.
        You should either contact the person about the draft or create a new draft for personal editing.
    </p>
    {/section}
{/section}

<h2>{'Current drafts'}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0">
<tr>
    {section show=$has_own_drafts}
        <th>
            &nbsp;
        </th>
    {/section}
    <th>
        {'Version'}
    </th>
    <th>
        {'Name'}
    </th>
    <th>
        {'Owner'}
    </th>
    <th>
        {'Created'}
    </th>
    <th>
        {'Last modified'}
    </th>
</tr>
{section name=Draft loop=$draft_versions sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    {section show=$has_own_drafts}
        <td width="1">
            {section show=eq($:item.creator_id,$current_creator.contentobject_id)}
                <input type="radio" name="SelectedVersion" value="{$:item.version}"
                    {run-once}
                        checked="checked"
                    {/run-once}
                 />
            {/section}
        </td>
    {/section}
    <td width="1">
        {$:item.version}
    </td>
    <td>
        <a href={concat('content/versionview/',$object.id,'/',$:item.version)|ezurl}>{$:item.version_name|wash}</a>
    </td>
    <td>
        {content_view_gui view=text_linked content_object=$:item.creator}
    </td>
    <td>
        {$:item.created|l10n(shortdatetime)}
    </td>
    <td>
        {$:item.modified|l10n(shortdatetime)}
    </td>
</tr>
{/section}
</table>

{section show=and($has_own_drafts,$has_other_drafts)}
    <input class="defaultbutton" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="NewButton" value="{'New draft'|i18n('design/standard/content/edit')}" />
{section-else}
    {section show=$has_own_drafts}
        <input class="defaultbutton" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/content/edit')}" />
        <input class="button" type="submit" name="NewButton" value="{'New draft'|i18n('design/standard/content/edit')}" />
    {/section}
    {section show=$has_other_drafts}
        <input class="defaultbutton" type="submit" name="NewButton" value="{'New draft'|i18n('design/standard/content/edit')}" />
    {/section}
{/section}

</form>
{/let}

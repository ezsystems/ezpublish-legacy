{* Information on the object *}

<div id="objectinfo">

    <div class="title">
        {"Object info"|i18n("design/standard/content/edit")}
    </div>

    <div id="created">
        <div class="title">
            {"Created"|i18n("design/standard/content/edit")}
        </div>

        <div class="content">
            {section show=$object.published}
                <div class="date">{$object.published|l10n(date)}</div>
            {section-else}
                {"Not yet published"|i18n("design/standard/content/edit")}
            {/section}
        </div>
    </div>

</div>

<div id="versioninfo">

    <div class="title">
        {"Versions"|i18n("design/standard/content/edit")}
    </div>

    <div id="edit">
        <div class="title">
            {"Editing"|i18n("design/standard/content/edit")}
        </div>
        <div class="content">
            {$edit_version}
        </div>
    </div>

    <div id="current">
        <div class="title">
            {"Current"|i18n("design/standard/content/edit")}
        </div>
        <div class="content">
            {$object.current_version}
        </div>
    </div>

    <div class="buttonblock">
        <input class="button" type="submit" name="VersionsButton" value="{'Manage'|i18n('design/standard/content/edit')}" />
        <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
    </div>

</div>

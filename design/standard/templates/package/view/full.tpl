{let package=fetch( package,item,
                    hash( package_name, $package_name ) )}
<div id="package">
<form method="post" action={concat( 'package/view/full/', $package.name )|ezurl}>

<div class="objectheader">
    <h2>{$package.name|wash}-{$package.version-number}-{$package.release-number}{section show=$package.release-timestamp}({$package.release-timestamp|l10n( shortdatetime )}){/section}{section show=$package.type|wash} [{$package.type}]{/section}</h2>
</div>
<div class="object">

    <div class="summary">
        <label>{'Summary'|i18n('design/standard/package')}</label>
        <p>{$package.summary|wash}</p>
    </div>

    <div class="state">
        <label>{'State'|i18n('design/standard/package')}</label>
        <p>{$package.state|wash}</p>
    </div>

    <div class="licence">
        <label>{'Licence'|i18n('design/standard/package')}</label>
        <p>{$package.licence|wash}</p>
    </div>

    <div class="maintainers">
        <label>{'Maintainers'|i18n('design/standard/package')}</label>
        <p>
            {section var=maintainer loop=$package.maintainers}
                <a href="mailto:{$maintainer.item.email}" subject="{"Regarding eZ publish package '%packagename'"|i18n('design/standard/package',,hash( '%packagename', $package.name ) )}" title="{$maintainer.item.name|wash}">{$maintainer.item.name|wash} ({$maintainer.item.role|wash})</a>
            {delimiter}, {/delimiter}
            {/section}
        </p>
    </div>

    <div class="description">
        <label>{'Description'|i18n('design/standard/package')}</label>
        <p>{$package.description}</p>
    </div>

    <div class="documents">
        <label>{'Documents'|i18n('design/standard/package')}</label>
        <p>
            {section var=document loop=$package.documents}
                {$document.item.name|wash}
            {delimiter}, {/delimiter}
            {/section}
        </p>
    </div>

    <div class="changelog">
        <label>{'Changelog'|i18n('design/standard/package')}</label>
        <p>
            {section var=log loop=$package.changelog}
                <h3><a href="mailto:{$log.item.email}" subject="{"Regarding eZ publish package '%packagename'"|i18n('design/standard/package',,hash( '%packagename', $package.name ) )}" title="{$log.item.person|wash}">{$log.item.person|wash} ({$log.item.timestamp|l10n( shortdatetime )})</h3>
                <ul>
                {section var=change loop=$log.item.changes}
                <li>
                    {$change.item|wash}
                </li>
                {/section}
                </ul>
            {delimiter}<hr/> {/delimiter}
            {/section}
        </p>
    </div>

</div>

    <div class="buttonblock">
        <input class="button" type="submit" name="ExportButton" value="{'Export to file'|i18n( 'design/standard/package')}" />
    </div>

</form>
</div>

{/let}

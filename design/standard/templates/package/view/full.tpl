{let package=fetch(package,item,
                   hash(package_name,$package_name))}
<div class="objectheader">
    <h2>{$package.name}</h2>
</div>
<div class="object">
    <p>{$package.summary}</p>
</div>
{/let}

{let depentent_package=fetch(package,dependent_list,
                   hash(package_name,$package_name,parameters,array(name,css2)))}
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{'Name'|i18n('design/standard/package')}</th>
    <th>{'Version'|i18n('design/standard/package')}</th>
    <th>{'Summary'|i18n('design/standard/package')}</th>
    <th>{'Status'|i18n('design/standard/package')}</th>
</tr>
{section name=DependentPackage loop=$depentent_package sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td><a href={concat('package/view/full/',$:item.name)|ezurl}>{$:item.name|wash}</a></td>
    <td>{$:item.version-number}-{$:item.release-number}</td>
    <td>{$:item.summary|wash}</td>
    <td></td>
</tr>
{/section}
</table>

{/let}
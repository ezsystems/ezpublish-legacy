{let page_limit=15
     package_list=fetch(package,list,
                        hash(offset,$view_parameters.offset,
                             limit,$page_limit))}
<form method="post" action={concat('package/list',
                            $view_parameters.offset|gt(0)
                            |choose('',
                                    concat('/offset/',$view_parameters.offset)))|ezurl}>

<h2>{'Packages'|i18n('design/standard/package')}</h2>

<p>{'The following packages are available on this system'|i18n('design/standard/package')}</p>

<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{'Name'|i18n('design/standard/package')}</th>
    <th>{'Version'|i18n('design/standard/package')}</th>
    <th>{'Summary'|i18n('design/standard/package')}</th>
    <th>{'Status'|i18n('design/standard/package')}</th>
</tr>
{section name=Package loop=$package_list sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td><a href={concat('package/view/full/',$:item.name)|ezurl}>{$:item.name|wash}</a></td>
    <td>{$:item.version-number}-{$:item.release-number}</td>
    <td>{$:item.summary|wash}</td>
    <td></td>
</tr>
{/section}
</table>

<div class="buttonblock">
    <input class="button" type="submit" name="InstallPackageButton" value="{'Upload package'|i18n('design/standard/package')}" />
</div>

</form>

{/let}

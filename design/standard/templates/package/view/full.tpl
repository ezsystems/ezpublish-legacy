{let package=fetch(package,item,
                   hash(package_name,$package_name))}
<div class="objectheader">
    <h2>{$package.name}</h2>
</div>
<div class="object">
    <p>{$package.summary}</p>
</div>

{/let}
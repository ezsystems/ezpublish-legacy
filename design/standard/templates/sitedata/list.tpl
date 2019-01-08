<h1>Site Data</h1>

<label>Search by key</label>

<form method="post">
    <input name="filter" value="{$filter|wash()}" />
    <input type="submit" value="Filter" /><br />
    <small>Supports '*' place holder.</small>
</form>

<p style="text-align: right;">Found {$count|wash()} entrie(s).</p>

{if $entries}
    <table class="list" style="margin-top: 2rem;">
        <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        {foreach $entries as $entry}
            <tr>
                <td>
                    {$entry.name|wash()}
                </td>
                <td>
                    {$entry.value|wash()}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    <div>
        {include
            name='navigator'
            uri='design:navigator/google.tpl'
            page_uri='/sitedata/list/'
            item_count=$count
            view_parameters=$view_parameters
            item_limit=$limit
        }
    </div>
{/if}
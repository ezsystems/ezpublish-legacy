<hr />
<h3>Hello world from an eZ Publish 4.7 template !</h3>
<p><u>The new kernel says:</u> <cite>{$message|wordtoimage}</cite></p>

{if is_set($konamiCode)}
<p>
    It also provides a Konami Code as an array:
    <cite>
    {foreach $konamiCode as $step}
        {delimiter}, {/delimiter}
        {$step}
    {/foreach}
    </cite>
</p>
{/if}

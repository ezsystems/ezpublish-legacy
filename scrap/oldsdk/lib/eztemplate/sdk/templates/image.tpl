{*?template charset="iso-8859-1"?*}
<table>
<tr><td>{$myvar|texttoimage(,,,,,-10,)}</td></tr>
{* Change point size *}
<tr><td>{"The Quick Brown Fox Jumps Over The Lazy Dog"|texttoimage("smartie",26,,,,2,-2)}</td></tr>
{* Change font family and colors *}
<tr><td>{"abcdef"|upcase|texttoimage("a_d_mono",,,"#2050a0","#ffffff")}</td></tr>
{* Do not store unique cache, instead always reuse cache using a new cache-id *}
<tr><td>{$curdate|l10n(date)|texttoimage("sketchy",,,,,,,"curdate")}</td></tr>
</table>

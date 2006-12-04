<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'eZ Publish information'|i18n( 'design/admin/ezinfo/about' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="context-attributes">

<table class="list" cellspacing="0">

<tr>
    <th><label>{'What is eZ Publish?'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $what_is_ez_publish )}
       <p>{$what_is_ez_publish}</p>
    {/if}
</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
    <th><label>{'Licence'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $license )}
       <p>{$license}</p>
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'Contributors'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <p>The following is a list of eZ Publish contributors who have licensed their work for use by eZ systems AS under the terms and conditions of
       the eZ Contributor Licensing Agreement. As permitted by this agreement with the contributors, eZ systems AS is redistributing the
       contribution under the same license as the file that the contribution is included in. The list of contributors includes the contributors's
       name, optional contact info and a list of files that they have either contributed or contributed work to.</p>

    {if and( is_set( $contributors ), is_array( $contributors ) )}
        <ul>
        {foreach $contributors as $contributor}
             <li>{$contributor['name']} : {$contributor['files']}</li>
        {/foreach}
        </ul>
    {/if}
</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
    <th><label>{'Copyright Notice'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <p>Copyright &copy; 1999-2006 eZ systems AS, with portions copyright by other parties. A complete list of all contributors and third-party software follows.</p>
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
    <th><label>{'Third-Party Software'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <p>The following is a list of the third-party software that is distributed with this copy of eZ Publish. The list of third party
       software includes the license for the software in question and the directory or files that contain the third-party software.</p>

    {if and( is_set( $third_party_software ), is_array( $third_party_software ) )}
        <ul>
	{foreach $third_party_software as $software_key => $software}
          <li>{$software}</li>
        {/foreach}
        </ul>
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'Extensions'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    <p>The following is a list of the extensions that have been loaded at run-time by this copy of eZ Publish. </p>
    {if is_set( $extensions )}
       {foreach $extensions as $ext_name => $extension}
          <ul>
              <li>
              {foreach $extension as $ext_key => $ext_info}
                {$ext_key}:
		{if not( is_array( $ext_info ))}
                     {$ext_info}<br>
                {else}
                     <ul>
                         <li>
                             {foreach $ext_info as $ext_info_key => $ext_info_value}
                                  {$ext_info_key} : {$ext_info_value}<br>
                             {/foreach}
                         </li>
                    </ul>
                {/if}
              {/foreach}
              </li>
          </ul>
       {/foreach}
    {/if}
</td>
</tr>
</table>


</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

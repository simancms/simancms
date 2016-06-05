{if $modules[$index].mode eq "view"}
{include file="block_begin.tpl"}
<table width="100%" cellspacing="2" cellpadding="2" border="0" class="download-table">
{section name=i loop=$modules[$index].files}
<tr>
    <td width="25%">
	<a href="{$modules[$index].files[i].download_url}">{$modules[$index].files[i].file}</a><br>
	 <em>[{$modules[$index].files[i].size_label}]</em>
	</td>
    <td width="75%">{$modules[$index].files[i].description}</td>
</tr>
{/section}
</table>
{include file="block_end.tpl"}
{/if}
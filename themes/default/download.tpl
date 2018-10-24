{if $m.mode eq "view"}
{include file="block_begin.tpl"}
<table width="100%" cellspacing="2" cellpadding="2" border="0" class="download-table">
{section name=i loop=$m.files}
<tr>
    <td width="25%">
	<a href="{$m.files[i].download_url}">{$m.files[i].file}</a><br>
	 <em>[{$m.files[i].size_label}]</em>
	</td>
    <td width="75%">{$m.files[i].description}</td>
</tr>
{/section}
</table>
{include file="block_end.tpl"}
{/if}
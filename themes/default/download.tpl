{if $modules[$index].mode eq "view"}
{include file="block_begin.tpl"}
<table width="100%" cellspacing="2" cellpadding="2" border="0" class="download-table">
{section name=i loop=$modules[$index].files}
<tr>
    <td width="25%">
	<a href="files/download/{$modules[$index].files[i].file}">{$modules[$index].files[i].file}</a><br>
	 <em>[{$modules[$index].files[i].sizeK} K]</em>
	</td>
    <td width="75%">{$modules[$index].files[i].description}</td>
</tr>
{/section}
</table>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "edit"}
{include file="block_begin.tpl"}
<form action="index.php?m=download&d=postedit&did={$modules[$index].iddownl}" method="post">
{$lang.module_download.short_description_download} <textarea cols="50" rows="5" name="p_shortdesc">{$modules[$index].short_desc}</textarea>
{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.submit}">
{include file="block_end.tpl"}
{/if}
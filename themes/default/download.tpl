{if $modules[$index].mode eq "admin"}
{include file="block_begin.tpl"}
<a href="{$modules[$index].downloads_url}">{$lang.module_download.downloads}</a><br />
<br />
<a href="index.php?m=download&d=upload">{$lang.module_download.upload_file}</a><br />
<form action="index.php?m=menu&d=addouter" method="post">
<input type="hidden" name="p_url" value="{$modules[$index].downloads_url}">
<input type="hidden" name="p_caption" value="{$lang.module_download.downloads}">
<input type="submit" value="{$lang.add_to_menu} - {$lang.module_download.downloads}">
</form><br>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "view"}
{include file="block_begin.tpl"}
<table width="100%" cellspacing="2" cellpadding="2" border="0">
{section name=i loop=$modules[$index].files}
<tr>
    <td width="25%">
	<a href="files/download/{$modules[$index].files[i].file}">{$modules[$index].files[i].file}</a><br>
	 <em>[{$modules[$index].files[i].sizeK} K]</em>
	{if $userinfo.level eq "3"}<br><a href="index.php?m=download&d=delete&did={$modules[$index].files[i].id}">{$lang.delete}</a><br><a href="index.php?m=download&d=edit&did={$modules[$index].files[i].id}">{$lang.edit}</a>{/if}
	</td>
    <td width="75%">{$modules[$index].files[i].description}</td>
</tr>
{/section}
</table>
{if $userinfo.level eq "3"}<br><br><a href="index.php?m=download&d=upload">{$lang.module_download.upload_file}</a>{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "upload"}
{include file="block_begin.tpl"}
<form action="index.php?m=download&d=postupload" method="post" enctype="multipart/form-data">
<table width="100%">
	<tr>
		<td width="30%">
			<input TYPE="hidden" name="MAX_FILE_SIZE" value="{$_settings.max_upload_filesize}">
			{$lang.file_name}:
		</td>
		<td width="70%">
			<input NAME="userfile" TYPE="file">
		</td>
	</tr>
	<tr><td>{$lang.optional_file_name}:</td><td><input type="text" name="p_optional"></td></tr>
	<tr><td>{$lang.module_download.short_description_download}</td><td><textarea cols="50" rows="5" name="p_shortdesc"></textarea></td></tr>
	{$modules[$index].formadditionalhtml}
	<tr><td colspan="2"><input type="submit" value="{$lang.upload}"></td></tr>
</table>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "errorupload"}
{include file="block_begin.tpl"}
{$lang.module_download.error_file_upload_message}<br>
<a href="index.php?m=admin">{$lang.control_panel}</a><br>
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

{if $modules[$index].mode eq "postupload"}
{include file="block_begin.tpl"}
{$lang.operation_complete}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}
	  
{if $modules[$index].mode eq "postdelete" or $modules[$index].mode eq "postdeleteattachment"}
{include file="block_begin.tpl"}
{$lang.module_download.delete_file_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}
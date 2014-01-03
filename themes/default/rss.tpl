{if $modules[$index].mode eq "admin"}
{include file="block_begin.tpl"}
<form action="index.php?m={$modules[$index].module}&d=postsettings" method="post">
<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td colspan="2"><input type="checkbox" name="rss_showfulltext" value="1" {if $_settings.rss_showfulltext eq "1"}checked{/if}> {$lang.module_rss.settings.rss_showfulltext}</td>
</tr>
<tr>
	<td colspan="2"><input type="checkbox" name="rss_shownewsctgs" value="1" {if $_settings.rss_shownewsctgs eq "1"}checked{/if}> {$lang.module_rss.settings.rss_shownewsctgs}</td>
</tr>
<tr>
	<td>{$lang.module_rss.settings.rss_itemscount}</td>
	<td><input type="text" name="rss_itemscount" value="{$_settings.rss_itemscount}" /></td>
</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{$lang.save}"></td>
</tr>
</table>
</form>
{include file="block_end.tpl"}
{/if}
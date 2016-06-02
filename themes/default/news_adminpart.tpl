{if $modules[$index].mode eq "list"}
{include file="block_begin.tpl"}
<div align="center"><form action="index.php">
<input type="hidden" name="m" value="news">
<input type="hidden" name="d" value="list">
<select name="ctg" size="1">
	<option value=""{if $modules[$index].ctg_id eq ""} SELECTED{/if}>{$lang.all_categories}</option>
	{section name=i loop=$modules[$index].ctg}
	<option value="{$modules[$index].ctg[i].id}"{if $modules[$index].ctg_id eq $modules[$index].ctg[i].id} SELECTED{/if}>{$modules[$index].ctg[i].title}</option>
	{/section}
</select><input type="submit" value="{$lang.show}">
</form></div>
{include file="common_admintable.tpl" table=$modules[$index].table}
<br>
<a href="index.php?m=news&d=add&ctg={$sm.g.ctg}">{$lang.common.add}</a>
(<a href="index.php?m=news&d=add&ctg={$sm.g.ctg}&exteditor=off">{$lang.common.html}</a>)<br>
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_news_lisnews&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="pagebar.tpl"}
{include file="block_end.tpl"}
{/if}

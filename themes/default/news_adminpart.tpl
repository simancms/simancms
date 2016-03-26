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

{if $modules[$index].mode eq "addctg"}
{include file="block_begin.tpl"}
<form action="index.php?m=news&d=postaddctg" method="post" name="post">															
{$lang.caption_category}: <input type="text" name="p_title_category" size="40" value="">
<br />{$lang.common.url}: <input type="text" name="p_filename" value="" size="50" maxlength="255">
<div align="right"><a href="javascript:;" onClick="set_visibility('extended_params')">{$lang.common.extended_parameters}</a></div>
<div style="display:none; width:100%;" id="extended_params">
{$lang.common.groups_can_modify}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].modify_groups_category var="p_groups_modify"}
{if $_settings.allow_alike_news eq 1}
<div style="width:100%;"><input type="checkbox" name="p_no_alike_news" value="1"> {$lang.module_content.dont_show_alike_news}</div>
{/if}
</div>
{$modules[$index].formadditionalhtml}
<br><div align="center"><input type="submit" value="{$lang.submit}"></div>
</form>
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_news_addeditcategory&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "editctg"}
{include file="block_begin.tpl"}
<form action="index.php?m=news&d=posteditctg&ctgid={$modules[$index].id_ctg}" method="post" name="post">															
{$lang.caption_category}: <input type="text" name="p_title_category" size="40" value="{$modules[$index].title_category}">
<br />{$lang.common.url}: <input type="text" name="p_filename" value="{$modules[$index].filename_category}" size="50" maxlength="255"> <a href="http://{$_settings.help_resource}/index.php?m=help&q=content_edit_category_url&lang={$_settings.default_language}" target="_blank">[?]</a>
<div align="right"><a href="javascript:;" onClick="set_visibility('extended_params')">{$lang.common.extended_parameters}</a></div>
<div style="display:none; width:100%;" id="extended_params">
{$lang.common.groups_can_modify}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].modify_groups_category var="p_groups_modify"}
{if $_settings.allow_alike_news eq 1}
<div style="width:100%;"><input type="checkbox" name="p_no_alike_news" value="1"{if $modules[$index].category_no_alike_news eq "1"} checked{/if}> {$lang.module_content.dont_show_alike_news}</div>
{/if}
</div>
{$modules[$index].formadditionalhtml}
<br /><div align="center"><input type="submit" value="{$lang.submit}"></div>
</form>
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_news_addeditcategory&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "admin"}
{include file="block_begin.tpl"}
<a href="index.php?m=news&d=add">{$lang.add_news}</a> (<a href="index.php?m=news&d=add&exteditor=off">{$lang.common.html}</a>)<br>
<br>
<a href="index.php?m=news&d=list">{$lang.list_news}</a><br>
<br>
<a href="index.php?m=news&d=listctg">{$lang.list_news_categories}</a><br>
<br>
<a href="index.php?m=news&d=addctg">{$lang.add_category}</a><br>
<br>
<a href="index.php?m=blocks&d=add&b=news&id=1&c={$lang.short_news_block}">{$lang.set_as_block} "{$lang.short_news_block}"</a>
<br>
<form action="index.php?m=menu&d=addouter" method="post">
<input type="hidden" name="p_url" value="index.php?m=news&d=listnews">
<input type="hidden" name="p_caption" value="{$lang.news}">
<input type="submit" value="{$lang.add_to_menu} - {$lang.news}">
</form>
{include file="block_end.tpl"}
{/if}

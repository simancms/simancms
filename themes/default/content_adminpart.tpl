{if $modules[$index].mode eq "list"}
{include file="block_begin.tpl"}
<div align="center"><form action="index.php">
<input type="hidden" name="m" value="content">
<input type="hidden" name="d" value="list">
<select name="ctg" size="1">
	<option value=""{if $modules[$index].ctg_id eq ""} SELECTED{/if}>{$lang.all_categories}</option>
	{section name=i loop=$modules[$index].ctg}
	<option value="{$modules[$index].ctg[i].id}"{if $modules[$index].ctg_id eq $modules[$index].ctg[i].id} SELECTED{/if}>{section name=j loop=$modules[$index].ctg[i].level start=1} - {/section}{$modules[$index].ctg[i].title}</option>
	{/section}
</select><input type="submit" value="{$lang.show}">
</form></div>
{include file="common_admintable.tpl" table=$modules[$index].table}
{if $modules[$index].showall neq "1" and $modules[$index].pages.pages neq "0" and $modules[$index].pages.pages neq ""}
<div align="right"><a href="index.php?m=content&d=list&ctg={$modules[$index].ctg_id}&showall=1">{$lang.common.show_all}</a></div>
{include file="pagebar.tpl"}
{/if}
<br />
<a href="index.php?m=content&d=add{if $modules[$index].ctg_id neq ""}&ctg={$modules[$index].ctg_id}{/if}">{$lang.common.add}</a>
(<a href="index.php?m=content&d=add{if $modules[$index].ctg_id neq ""}&ctg={$modules[$index].ctg_id}{/if}&exteditor=off">{$lang.common.html}</a>)
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=content_list_content&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "addctg"}
{include file="block_begin.tpl"}
<form action="index.php?m=content&d=postaddctg" method="post" name="post">															
{$lang.caption_category}: <input type="text" name="p_title_category" size="40" value=""><br />
{$lang.module_content.main_category}: 
	<select name="p_mainctg" size="1">
	<option value="0" SELECTED>[{$lang.module_content.root_category}]</option>
	{section name=i loop=$modules[$index].ctg}
	<option value="{$modules[$index].ctg[i].id}">{section name=j loop=$modules[$index].ctg[i].level} - {/section}{$modules[$index].ctg[i].title}</option>
	{/section}
	</select>
<br />
{$lang.can_view}: 
	<select name="p_can_view" size="1">
	<option value="0" SELECTED>{$lang.all_users}</option>
	<option value="1">{$lang.logged_users}</option>
	<option value="2">{$lang.power_users}</option>
	<option value="3">{$lang.administrators}</option>
	</select>
<br />
{if $special.ext_editor_on eq "1"}
<br /><a href="index.php?m=content&d=addctg&exteditor=off">{$lang.ext.editors.switch_to_standard_editor}</a>
{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="content_ctg"}
{else}
{if $_settings.ext_editor neq ""}<br /><a href="index.php?m=content&d=addctg&exteditor=on">{$lang.ext.editors.switch_to_ext_editor}</a>{/if}
<br /><a href="index.php?m=geturl" target="_simangeturl">{$lang.add_url}</a>
&nbsp;<a href="index.php?m=geturl&d=2" target="_simangeturl">{$lang.add_image}</a>
<br />{$lang.module_content.preview_category}:<br /> <textarea cols="50" rows="10" name="p_preview_ctg" wrap="off"></textarea>
<br />
{/if}
<br />{$lang.common.url}: <input type="text" name="p_filename" value="" size="50" maxlength="255"> <a href="http://{$_settings.help_resource}/index.php?m=help&q=content_add_category_text_url&lang={$_settings.default_language}" target="_blank">[?]</a>
<br />
{$lang.common.sorting}: 
	<select name="p_sorting_category" size="1">
	<option value="0" SELECTED>{$lang.common.title} / {$lang.common.sortingtypes.asc}</option>
	<option value="1">{$lang.common.title} / {$lang.common.sortingtypes.desc}</option>
	<option value="2">{$lang.common.priority} / {$lang.common.sortingtypes.asc}</option>
	<option value="3">{$lang.common.priority} / {$lang.common.sortingtypes.desc}</option>
	</select>
<br />
<div align="right"><a href="javascript:;" onClick="set_visibility('extended_params')">{$lang.common.extended_parameters}</a></div>
<div style="display:none; width:100%;" id="extended_params">
<div style="float:left; width: 200px;">
{$lang.common.groups_can_view}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].view_groups_category var="p_groups_view"}
</div>
<div style="float:left; width: 200px;">
{$lang.common.groups_can_modify}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].modify_groups_category var="p_groups_modify"}
</div>
<div style="clear:both;"></div>
{if $_settings.allow_alike_content eq 1}
<input type="checkbox" name="p_no_alike_content" value="1"> {$lang.module_content.dont_show_alike_content}<br />
{/if}
{if $_settings.content_use_path eq 1}
<input type="checkbox" name="p_no_use_path" value="1"> {$lang.module_content.no_use_path}<br />
{/if}
</div>
{$modules[$index].formadditionalhtml}
<div align="center" style="width:100%"><input type="submit" value="{$lang.submit}"></div>
</form>
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=content_add_category&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "editctg"}
{include file="block_begin.tpl"}
<form action="index.php?m=content&d=posteditctg&ctgid={$modules[$index].id_ctg}" method="post" name="post">															
{$lang.caption_category}: <input type="text" name="p_title_category" size="40" value="{$modules[$index].title_category}"><br />
{$lang.module_content.main_category}: 
	<select name="p_mainctg" size="1">
	<option value="0"{if $modules[$index].category_can_view eq "0"} SELECTED{/if}>[{$lang.module_content.root_category}]</option>
	{section name=i loop=$modules[$index].ctg}
	<option value="{$modules[$index].ctg[i].id}" {if $modules[$index].main_ctg eq $modules[$index].ctg[i].id} SELECTED{/if}>{section name=j loop=$modules[$index].ctg[i].level} - {/section}{$modules[$index].ctg[i].title}</option>
	{/section}
	</select>
<br />
{$lang.can_view}: 
	<select name="p_can_view" size="1">
	<option value="0"{if $modules[$index].category_can_view eq "0"} SELECTED{/if}>{$lang.all_users}</option>
	<option value="1"{if $modules[$index].category_can_view eq "1"} SELECTED{/if}>{$lang.logged_users}</option>
	<option value="2"{if $modules[$index].category_can_view eq "2"} SELECTED{/if}>{$lang.power_users}</option>
	<option value="3"{if $modules[$index].category_can_view eq "3"} SELECTED{/if}>{$lang.administrators}</option>
	</select>
{if $special.ext_editor_on eq "1"}
<br /><a href="index.php?m=content&d=editctg&ctgid={$modules[$index].id_ctg}&exteditor=off">{$lang.ext.editors.switch_to_standard_editor}</a>
{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="content_ctg"}
{else}
{if $_settings.ext_editor neq ""}<br /><a href="index.php?m=content&d=editctg&ctgid={$modules[$index].id_ctg}&exteditor=on">{$lang.ext.editors.switch_to_ext_editor}</a>{/if}
<br /><a href="index.php?m=geturl" target="_simangeturl">{$lang.add_url}</a>
&nbsp;<a href="index.php?m=geturl&d=2" target="_simangeturl">{$lang.add_image}</a>
<br />{$lang.module_content.preview_category}:<br /> <textarea cols="50" rows="10" name="p_preview_ctg" wrap="off">{$modules[$index].preview_ctg}</textarea>
<br />
{/if}
<br />{$lang.common.url}: <input type="text" name="p_filename" value="{$modules[$index].filename_category}" size="50" maxlength="255"> <a href="http://{$_settings.help_resource}/index.php?m=help&q=content_edit_category_url&lang={$_settings.default_language}" target="_blank">[?]</a>
<br />
{$lang.common.sorting}:
	<select name="p_sorting_category" size="1">
	<option value="0"{if $modules[$index].sorting_category eq "0"} SELECTED{/if}>{$lang.common.title} / {$lang.common.sortingtypes.asc}</option>
	<option value="1"{if $modules[$index].sorting_category eq "1"} SELECTED{/if}>{$lang.common.title} / {$lang.common.sortingtypes.desc}</option>
	<option value="2"{if $modules[$index].sorting_category eq "2"} SELECTED{/if}>{$lang.common.priority} / {$lang.common.sortingtypes.asc}</option>
	<option value="3"{if $modules[$index].sorting_category eq "3"} SELECTED{/if}>{$lang.common.priority} / {$lang.common.sortingtypes.desc}</option>
	</select>
<br />
<div align="right"><a href="javascript:;" onClick="set_visibility('extended_params')">{$lang.common.extended_parameters}</a></div>
<div style="display:none; width:100%;" id="extended_params">
<div style="float:left; width: 200px;">
{$lang.common.groups_can_view}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].view_groups_category var="p_groups_view"}
</div>
<div style="float:left; width: 200px;">
{$lang.common.groups_can_modify}<br />
{include file="common_groupselector.tpl" groups=$modules[$index].groups_list selgroups=$modules[$index].modify_groups_category var="p_groups_modify"}
</div>
<div style="clear:both;"></div>
{if $_settings.allow_alike_content eq 1}
<input type="checkbox" name="p_no_alike_content" value="1"{if $modules[$index].category_no_alike_content eq "1"} checked{/if}> {$lang.module_content.dont_show_alike_content}<br />
{/if}
{if $_settings.content_use_path eq 1}
<input type="checkbox" name="p_no_use_path" value="1"{if $modules[$index].category_no_use_path eq "1"} checked{/if}> {$lang.module_content.no_use_path}<br />
{/if}
</div>
{$modules[$index].formadditionalhtml}
<div align="center" style="width:100%;"><input type="submit" value="{$lang.submit}"></div>
</form>
<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=content_edit_category&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>
{include file="block_end.tpl"}
{/if}

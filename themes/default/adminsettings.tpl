{if $modules[$index].mode eq "admin"}
{include file="block_begin.tpl"}
{include file="common_admintable.tpl" table=$modules[$index].table}
<br />
<a href="index.php?m=adminsettings&d=addeditor">{$lang.common.add}</a> (<a href="index.php?m=adminsettings&d=addhtml">{$lang.common.html}</a>)
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "install" or $modules[$index].mode eq "uninstall" or $modules[$index].mode eq "postedit" or $modules[$index].mode eq "postadd" or $modules[$index].mode eq "postdelete"}
{include file="block_begin.tpl"}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}


{if $modules[$index].mode eq "edit" or $modules[$index].mode eq "html"}
{include file="block_begin.tpl"}
<form action="index.php?m=adminsettings&d=postedit&param={$modules[$index].name_settings}" method="post">
<div align="center" style="width:100%;"><input type="text" style="width:100%" name="p_name" value="{$modules[$index].name_settings}"><br />
{if $modules[$index].mode eq "edit"}
{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="common" var="p_value" value=$modules[$index].value_settings_ext}
{else}
<textarea cols="50" rows="10" name="p_value" wrap="off" style="width:100%;">{$modules[$index].value_settings_html}</textarea>
{/if}
<input type="submit" value="{$lang.submit}"></div>
</form>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "addeditor" or $modules[$index].mode eq "addhtml"}
{include file="block_begin.tpl"}
<form action="index.php?m=adminsettings&d=postadd" method="post">
<div align="center" style="width:100%;"><input type="text" style="width:100%" name="p_name" value="{$modules[$index].name_settings}"><br />
{if $modules[$index].mode eq "addeditor"}
{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="common" var="p_value" value=''}
{else}
<textarea cols="50" rows="10" name="p_value" wrap="off" style="width:100%;"></textarea>
{/if}
<input type="submit" value="{$lang.submit}"></div>
</form>
{include file="block_end.tpl"}
{/if}
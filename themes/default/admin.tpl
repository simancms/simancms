{if $modules[$index].mode eq 'view'}
{include file="block_begin.tpl"}
<a href="index.php?m=admin&d=modules">{$lang.modules_mamagement}</a><br />
<a href="index.php?m=blocks">{$lang.blocks_mamagement}</a><br />
<a href="index.php?m=admin&d=filesystem">{$lang.module_admin.virtual_filesystem}</a><br />
{* <a href="index.php?m=admin&d=filesystemexp">{$lang.module_admin.virtual_filesystem_regexp}</a><br /> [U	nsupported]  *}
{* <a href="index.php?m=admin&d=listmodes">{$lang.module_admin.modes_management}</a><br /> [Temporary unsupported]  *}
<a href="index.php?m=admin&d=listimg">{$lang.module_admin.images_list}</a><br />
<a href="index.php?m=admin&d=uplimg">{$lang.upload_image}</a><br />
<a href="index.php?m=account&d=register">{$lang.register_user}</a><br />
<a href="index.php?m=account&d=usrlist">{$lang.user_list}</a><br />
<a href="index.php?m=account&d=listgroups">{$lang.module_account.groups_management}</a><br />
<a href="index.php?m=admin&d=massemail">{$lang.module_admin.mass_email}</a><br />
<a href="index.php?m=admin&d=tstatus">{$lang.module_admin.optimize_database}</a><br />
{if $_settings.log_type gt 0}<a href="index.php?m=admin&d=viewlog">{$lang.module_admin.view_log}</a><br />{/if}
{if $modules[$index].can_use_package eq 1}
<a href="index.php?m=admin&d=package">{$lang.module_admin.upload_package}</a><br />
{/if}
<a href="index.php?m=admin&d=settings">{$lang.settings}</a>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'chgttl'}
{include file="block_begin.tpl"}
<form action="index.php?m=admin&d=postchgttl&mid={$modules[$index].mod.id}" method="post">
<input type="text" name="p_title" value="{$modules[$index].mod.title}">
{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.submit}">
</form>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'uplimg'}
{include file="block_begin.tpl"}
<form action="index.php?m=admin&d=postuplimg" method="post" enctype="multipart/form-data">
<input TYPE="hidden" name="MAX_FILE_SIZE" value="{$_settings.max_upload_filesize}">
{$lang.file_name}: <input NAME="userfile" TYPE="file"><br />
{$lang.optional_file_name}: <input type="text" name="p_optional"><br />
{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.upload}">
</form>
{if $_settings.show_help eq "on"}<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_upload_image&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'errorupload'}
{include file="block_begin.tpl"}
{$lang.error_file_upload_message}<br />
{$modules[$index].error_message}<br />
<a href="index.php?m=admin">{$lang.control_panel}</a><br />
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'settings'}
{include file="block_begin.tpl"}
{$lang.common.profile}: 
	{if $modules[$index].mode_settings neq "default"}<a href="index.php?m=admin&d=settings">{else}<b>{/if}{$lang.common.general}{if $modules[$index].mode_settings neq "default"}</a>{else}</b>{/if}
	{section name=tmpextctrls loop=$modules[$index].list_modes}
		  | 
		{if $modules[$index].mode_settings neq $modules[$index].list_modes[tmpextctrls].mode}<a href="index.php?m=admin&d=settings&viewmode={$modules[$index].list_modes[tmpextctrls].mode}">{else}<b>{/if}{$modules[$index].list_modes[tmpextctrls].profile}{if $modules[$index].mode_settings neq $modules[$index].list_modes[tmpextctrls].mode}</a>{else}</b>{/if}
	{/section}
<br /><br />
<form action="index.php?m=admin&d=postsettings&viewmode={$modules[$index].mode_settings}" method="post">
<input type="hidden" name="marker" value="1" />
<table width="100%" border="0">
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.common_settings}</td>
</tr>
	{if $modules[$index].show_settings.resource_title eq 1}
		<tr>
		    <td width="50%">{$lang.settings_resource_title}:</td>
			<td><input type="text" name="p_title" value="{$modules[$index].edit_settings.resource_title|htmlspecialchars}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='resource_title'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_resource_title&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.resource_url eq 1}
		<tr>
			<td>{$lang.settings_resource_url}:</td>
			<td>http://<input type="text" name="p_url" value="{$modules[$index].edit_settings.resource_url}"></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_resource_url&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.resource_url_mobile eq 1}
		<tr>
			<td>{$lang.settings_resource_url_mobile}:</td>
			<td>http://<input type="text" name="resource_url_mobile" value="{$modules[$index].edit_settings.resource_url_mobile}"></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_resource_url_mobile&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.resource_url_tablet eq 1}
		<tr>
			<td>{$lang.settings_resource_url_tablet}:</td>
			<td>http://<input type="text" name="resource_url_tablet" value="{$modules[$index].edit_settings.resource_url_tablet}"></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=resource_url_tablet&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.resource_url_rewrite eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="resource_url_rewrite" value="1" {if $modules[$index].edit_settings.resource_url_rewrite eq "1"}checked{/if}>{$lang.settings_resource_url_rewrite}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=resource_url_rewrite&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.logo_text eq 1}
		<tr>
			<td>{$lang.settings_logo_text}:</td>
			<td><input type="text" name="p_logo" value="{$modules[$index].edit_settings.logo_text|htmlspecialchars}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='logo_text'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_logo_text&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.copyright_text eq 1}
		<tr>
			<td>{$lang.settings_copyright_text}:</td>
			<td><input type="text" name="p_copyright" value="{$modules[$index].edit_settings.copyright_text|htmlspecialchars}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='copyright_text'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_copyright_text&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.meta_description eq 1}
		<tr>
			<td>{$lang.settings_meta_description}:</td>
			<td><input type="text" name="p_description" value="{$modules[$index].edit_settings.meta_description|htmlspecialchars}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='meta_description'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_meta_description&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.meta_keywords eq 1}
		<tr>
			<td>{$lang.settings_meta_keywords}:</td>
			<td><input type="text" name="p_keywords" value="{$modules[$index].edit_settings.meta_keywords|htmlspecialchars}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='meta_keywords'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_meta_keywords&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.default_language eq 1}
		<tr>
			<td>{$lang.settings_default_language}: </td>
			<td><select name="p_lang" size="1">
			  {section name=i loop=$modules[$index].lang}
			  <option value="{$modules[$index].lang[i]}"{if $modules[$index].lang[i] eq $modules[$index].edit_settings.default_language} SELECTED{/if}>{$modules[$index].lang[i]}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='default_language'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_default_language&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.default_theme eq 1}
		<tr>
			<td>{$lang.settings_default_theme}:</td>
			<td><select name="p_theme" size="1">
			  {section name=i loop=$modules[$index].themes}
			  <option value="{$modules[$index].themes[i]}"{if $modules[$index].themes[i] eq $modules[$index].edit_settings.default_theme} SELECTED{/if}>{$modules[$index].themes[i]}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='default_theme'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_default_theme&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.sidepanel_count eq 1}
		<tr>
			<td>{$lang.settings_sidepanel_count}:</td>
			<td><select name="p_sidepanel_count" size="1">
			  {section name=i loop=16 start=1}
			  <option value="{$smarty.section.i.index}"{if $smarty.section.i.index eq $modules[$index].edit_settings.sidepanel_count} SELECTED{/if}>{$smarty.section.i.index}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='sidepanel_count'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_sidepanel_count&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.default_module eq 1}
		<tr>
			<td>{$lang.settings_default_module}:</td>
			<td><select name="p_module" size="1">
			  {section name=i loop=$modules[$index].modules}
			  <option value="{$modules[$index].modules[i].name}"{if $modules[$index].modules[i].name eq $modules[$index].edit_settings.default_module} SELECTED{/if}>{$modules[$index].modules[i].title}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='default_module'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_default_module&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.cookprefix eq 1}
		<tr>
			<td>{$lang.settings_cookprefix}:</td>
			<td><input type="text" name="p_cook" value="{$modules[$index].edit_settings.cookprefix}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='cookprefix'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_cookprefix&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.max_upload_filesize eq 1}
		<tr>
			<td>{$lang.settings_max_upload_filesize}:</td>
			<td><input type="text" name="p_maxfsize" value="{$modules[$index].edit_settings.max_upload_filesize}"> ({$lang.bytes})</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='max_upload_filesize'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_max_upload_filesize&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.admin_items_by_page eq 1}
		<tr>
			<td>{$lang.settings_admin_items_by_page}:</td>
			<td><select name="p_adminitems_per_page" size="1">
			  <option value="5"{if $modules[$index].edit_settings.admin_items_by_page eq 5} SELECTED{/if}>5</option>
			  <option value="10"{if $modules[$index].edit_settings.admin_items_by_page eq 10} SELECTED{/if}>10</option>
			  <option value="15"{if $modules[$index].edit_settings.admin_items_by_page eq 15} SELECTED{/if}>15</option>
			  <option value="20"{if $modules[$index].edit_settings.admin_items_by_page eq 20} SELECTED{/if}>20</option>
			  <option value="30"{if $modules[$index].edit_settings.admin_items_by_page eq 30} SELECTED{/if}>30</option>
			  <option value="40"{if $modules[$index].edit_settings.admin_items_by_page eq 40} SELECTED{/if}>40</option>
			  <option value="50"{if $modules[$index].edit_settings.admin_items_by_page eq 50} SELECTED{/if}>50</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='admin_items_by_page'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_admin_items_by_page&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.search_items_by_page eq 1}
		<tr>
			<td>{$lang.settings_search_items_by_page}:</td>
			<td><select name="p_searchitems_per_page" size="1">
			  <option value="5"{if $modules[$index].edit_settings.search_items_by_page eq 5} SELECTED{/if}>5</option>
			  <option value="10"{if $modules[$index].edit_settings.search_items_by_page eq 10} SELECTED{/if}>10</option>
			  <option value="15"{if $modules[$index].edit_settings.search_items_by_page eq 15} SELECTED{/if}>15</option>
			  <option value="20"{if $modules[$index].edit_settings.search_items_by_page eq 20} SELECTED{/if}>20</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='search_items_by_page'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_search_items_by_page&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.ext_editor eq 1}
		<tr>
			<td>{$lang.settings_extern_editor}:</td>
			<td><select name="p_exteditor" size="1">
			  <option value=""{if $modules[$index].edit_settings.ext_editor eq ""} SELECTED{/if}>[---------]</option>
			  {section name=i loop=$modules[$index].exteditors}
			  <option value="{$modules[$index].exteditors[i]}"{if $modules[$index].exteditors[i] eq $modules[$index].edit_settings.ext_editor} SELECTED{/if}>{$modules[$index].exteditors[i]}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='ext_editor'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_extern_editor&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.noflood_time eq 1}
		<tr>
			<td>{$lang.settings_noflood_time}:</td>
			<td><input type="text" name="p_floodtime" value="{$modules[$index].edit_settings.noflood_time}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='noflood_time'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_noflood_time&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.blocks_use_image eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_blocks_use_image" value="1" {if $modules[$index].edit_settings.blocks_use_image eq "1"}checked{/if}>{$lang.settings_blocks_use_image}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='blocks_use_image'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_blocks_use_image&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.rewrite_index_title eq 1}
		<tr>
		    <td width="50%">{$lang.settings_rewrite_index_title}:</td>
			<td><input type="text" name="p_rewrite_index_title" value="{$modules[$index].edit_settings.rewrite_index_title}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='rewrite_index_title'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_rewrite_index_title&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.log_type eq 1}
		<tr>
			<td>{$lang.settings_log_type}:</td>
			<td><select name="p_log_type" size="1">
			  <option value="0"{if $modules[$index].edit_settings.log_type eq 0} SELECTED{/if}>{$lang.module_admin.log_types[0]}</option>
			  <option value="1"{if $modules[$index].edit_settings.log_type eq 1} SELECTED{/if}>{$lang.module_admin.log_types[1]}</option>
			  <option value="10"{if $modules[$index].edit_settings.log_type eq 10} SELECTED{/if}>{$lang.module_admin.log_types[10]}</option>
			  <option value="20"{if $modules[$index].edit_settings.log_type eq 20} SELECTED{/if}>{$lang.module_admin.log_types[20]}</option>
			  <option value="30"{if $modules[$index].edit_settings.log_type eq 30} SELECTED{/if}>{$lang.module_admin.log_types[30]}</option>
			  <option value="100"{if $modules[$index].edit_settings.log_type eq 100} SELECTED{/if}>{$lang.module_admin.log_types[100]}</option>
			  <option value="120"{if $modules[$index].edit_settings.log_type eq 120} SELECTED{/if}>{$lang.module_admin.log_types[120]}</option>
			</select></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_log_type&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.log_store_days eq 1}
		<tr>
			<td>{$lang.settings_log_store_days}:</td>
			<td><input type="text" name="p_log_store_days" value="{$modules[$index].edit_settings.log_store_days}"></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_log_store_days&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.image_generation_type eq 1}
		<tr>
			<td>{$lang.settings_image_generation_type}:</td>
			<td><select name="p_image_generation_type" size="1">
			  <option value="dynamic"{if $modules[$index].edit_settings.image_generation_type neq "static"} SELECTED{/if}>{$lang.common.dynamic}</option>
			  <option value="static"{if $modules[$index].edit_settings.image_generation_type eq "static"} SELECTED{/if}>{$lang.common.static}</option>
			</select></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_image_generation_type&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.title_delimiter eq 1}
		<tr>
			<td>{$lang.settings_title_delimiter}:</td>
			<td><input type="text" name="p_title_delimiter" value="{$modules[$index].edit_settings.title_delimiter}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='title_delimiter'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_title_delimiter&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.meta_resource_title_position eq 1}
		<tr>
			<td>{$lang.settings_meta_resource_title_position}:</td>
			<td><select name="p_meta_resource_title_position" size="1">
			  <option value="0"{if $modules[$index].edit_settings.meta_resource_title_position eq "0"} SELECTED{/if}>{$lang.module_admin.meta_resource_title_position[0]}</option>
			  <option value="1"{if $modules[$index].edit_settings.meta_resource_title_position eq "1"} SELECTED{/if}>{$lang.module_admin.meta_resource_title_position[1]}</option>
			  <option value="2"{if $modules[$index].edit_settings.meta_resource_title_position eq "2"} SELECTED{/if}>{$lang.module_admin.meta_resource_title_position[2]}</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='meta_resource_title_position'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_meta_resource_title_position&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.menu_settings}</td>
</tr>
	{if $modules[$index].show_settings.upper_menu_id eq 1}
		<tr>
			<td>{$lang.settings_upper_menu_id}:</td>
			<td><select name="p_uppermenu" size="1">
			  <option value=""{if $modules[$index].edit_settings.upper_menu_id eq ""} SELECTED{/if}>[---------]</option>
			  {section name=i loop=$modules[$index].menus}
			  <option value="{$modules[$index].menus[i].id}"{if $modules[$index].menus[i].id eq $modules[$index].edit_settings.upper_menu_id} SELECTED{/if}>{$modules[$index].menus[i].title}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='upper_menu_id'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_upper_menu_id&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.bottom_menu_id eq 1}
		<tr>
			<td>{$lang.settings_bottom_menu_id}:</td>
			<td><select name="p_bottommenu" size="1">
			  <option value=""{if $modules[$index].edit_settings.bottom_menu_id eq ""} SELECTED{/if}>[---------]</option>
			  {section name=i loop=$modules[$index].menus}
			  <option value="{$modules[$index].menus[i].id}"{if $modules[$index].menus[i].id eq $modules[$index].edit_settings.bottom_menu_id} SELECTED{/if}>{$modules[$index].menus[i].title}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='bottom_menu_id'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_bottom_menu_id&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.users_menu_id eq 1}
		<tr>
			<td>{$lang.settings_users_menu_id}:</td>
			<td><select name="p_usersmenu" size="1">
			  <option value=""{if $modules[$index].edit_settings.users_menu_id eq ""} SELECTED{/if}>[---------]</option>
			  {section name=i loop=$modules[$index].menus}
			  <option value="{$modules[$index].menus[i].id}"{if $modules[$index].menus[i].id eq $modules[$index].edit_settings.users_menu_id} SELECTED{/if}>{$modules[$index].menus[i].title}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='users_menu_id'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_users_menu_id&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.menus_use_image eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_menus_use_image" value="1" {if $modules[$index].edit_settings.menus_use_image eq "1"}checked{/if}>{$lang.settings_menus_use_image}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='menus_use_image'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_menus_use_image&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.menuitems_use_image eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_menuitems_use_image" value="1" {if $modules[$index].edit_settings.menuitems_use_image eq "1"}checked{/if}>{$lang.settings_menuitems_use_image}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='menuitems_use_image'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_menuitems_use_image&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.content_settings}</td>
</tr>
	{if $modules[$index].show_settings.content_use_preview eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_content_use_preview" value="1" {if $modules[$index].edit_settings.content_use_preview eq "1"}checked{/if}>{$lang.settings_content_use_preview}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_content_use_preview&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_per_page_multiview eq 1}
		<tr>
			<td>{$lang.settings_content_per_page_multiview}:</td>
			<td><select name="p_multiviewperpage" size="1">
			  <option value="5"{if $modules[$index].edit_settings.content_per_page_multiview eq 5} SELECTED{/if}>5</option>
			  <option value="10"{if $modules[$index].edit_settings.content_per_page_multiview eq 10} SELECTED{/if}>10</option>
			  <option value="15"{if $modules[$index].edit_settings.content_per_page_multiview eq 15} SELECTED{/if}>15</option>
			  <option value="20"{if $modules[$index].edit_settings.content_per_page_multiview eq 20} SELECTED{/if}>20</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_per_page_multiview'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_content_per_page_multiview&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.allow_alike_content eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_allow_alike_content" value="1" {if $modules[$index].edit_settings.allow_alike_content eq "1"}checked{/if}>{$lang.settings_allow_alike_content}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_allow_alike_content&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.alike_content_count eq 1}
		<tr>
			<td>{$lang.settings_alike_content_count}:</td>
			<td><input type="text" name="p_alike_content_count" value="{$modules[$index].edit_settings.alike_content_count}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='alike_content_count'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_alike_content_count&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_use_path eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_content_use_path" value="1" {if $modules[$index].edit_settings.content_use_path eq "1"}checked{/if}>{$lang.settings_content_use_path}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_use_path'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_content_use_path&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_attachments_count eq 1}
		<tr>
			<td>{$lang.settings_content_attachments_count}:</td>
			<td><select name="p_content_attachments_count" size="1">
			  <option value="0"{if $modules[$index].edit_settings.content_content_attachments_count eq 0} SELECTED{/if}>-</option>
			  {section name=i loop=16 start=1}
			  <option value="{$smarty.section.i.index}"{if $modules[$index].edit_settings.content_attachments_count eq $smarty.section.i.index} SELECTED{/if}>{$smarty.section.i.index}</option>
			  {/section}
			</select></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_content_attachments_count&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_use_image eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_content_use_image" value="1" {if $modules[$index].edit_settings.content_use_image eq "1"}checked{/if}>{$lang.settings_content_use_image}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_use_image'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_content_use_image&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_image_preview_width eq 1}
		<tr>
			<td>{$lang.common.image_width} ({$lang.common.preview|lower}):</td>
			<td><input type="text" name="p_content_image_preview_width" value="{$modules[$index].edit_settings.content_image_preview_width}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_image_preview_width'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=content_image_preview_width&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_image_preview_height eq 1}
		<tr>
			<td>{$lang.common.image_height} ({$lang.common.preview|lower}):</td>
			<td><input type="text" name="p_content_image_preview_height" value="{$modules[$index].edit_settings.content_image_preview_height}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_image_preview_height'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=content_image_preview_height&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_image_fulltext_width eq 1}
		<tr>
			<td>{$lang.common.image_width} ({$lang.common.full_text|lower}):</td>
			<td><input type="text" name="p_content_image_fulltext_width" value="{$modules[$index].edit_settings.content_image_fulltext_width}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_image_fulltext_width'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=content_image_fulltext_width&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.content_image_fulltext_height eq 1}
		<tr>
			<td>{$lang.common.image_height} ({$lang.common.full_text|lower}):</td>
			<td><input type="text" name="p_content_image_fulltext_height" value="{$modules[$index].edit_settings.content_image_fulltext_height}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='content_image_fulltext_height'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=content_image_fulltext_height&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.news_settings}</td>
</tr>
	{if $modules[$index].show_settings.news_use_title eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_news_use_title" value="1" {if $modules[$index].edit_settings.news_use_title eq "1"}checked{/if}>{$lang.settings_news_use_title}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_use_title&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_use_image eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_news_use_image" value="1" {if $modules[$index].edit_settings.news_use_image eq "1"}checked{/if}>{$lang.settings_news_use_image}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_use_image'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_use_image&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_image_preview_width eq 1}
		<tr>
			<td>{$lang.common.image_width} ({$lang.common.preview|lower}):</td>
			<td><input type="text" name="p_news_image_preview_width" value="{$modules[$index].edit_settings.news_image_preview_width}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_image_preview_width'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=news_image_preview_width&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_image_preview_height eq 1}
		<tr>
			<td>{$lang.common.image_height} ({$lang.common.preview|lower}):</td>
			<td><input type="text" name="p_news_image_preview_height" value="{$modules[$index].edit_settings.news_image_preview_height}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_image_preview_height'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=news_image_preview_height&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_image_fulltext_width eq 1}
		<tr>
			<td>{$lang.common.image_width} ({$lang.common.full_text|lower}):</td>
			<td><input type="text" name="p_news_image_fulltext_width" value="{$modules[$index].edit_settings.news_image_fulltext_width}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_image_fulltext_width'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=news_image_fulltext_width&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_image_fulltext_height eq 1}
		<tr>
			<td>{$lang.common.image_height} ({$lang.common.full_text|lower}):</td>
			<td><input type="text" name="p_news_image_fulltext_height" value="{$modules[$index].edit_settings.news_image_fulltext_height}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_image_fulltext_height'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=news_image_fulltext_height&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_use_preview eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_news_use_preview" value="1" {if $modules[$index].edit_settings.news_use_preview eq "1"}checked{/if}>{$lang.settings_news_use_preview}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_use_preview&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_by_page eq 1}
		<tr>
			<td>{$lang.settings_news_per_page}:</td>
			<td><select name="p_news_per_page" size="1">
			  <option value="5"{if $modules[$index].edit_settings.news_by_page eq 5} SELECTED{/if}>5</option>
			  <option value="10"{if $modules[$index].edit_settings.news_by_page eq 10} SELECTED{/if}>10</option>
			  <option value="15"{if $modules[$index].edit_settings.news_by_page eq 15} SELECTED{/if}>15</option>
			  <option value="20"{if $modules[$index].edit_settings.news_by_page eq 20} SELECTED{/if}>20</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_by_page'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_per_page&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_anounce_cut eq 1}
		<tr>				 
			<td>{$lang.settings_news_anounce_cut}:</td>
			<td><select name="p_news_cut" size="1">
			  <option value="50"{if $modules[$index].edit_settings.news_anounce_cut eq 50} SELECTED{/if}>50</option>
			  <option value="100"{if $modules[$index].edit_settings.news_anounce_cut eq 100} SELECTED{/if}>100</option>
			  <option value="150"{if $modules[$index].edit_settings.news_anounce_cut eq 150} SELECTED{/if}>150</option>
			  <option value="200"{if $modules[$index].edit_settings.news_anounce_cut eq 200} SELECTED{/if}>200</option>
			  <option value="300"{if $modules[$index].edit_settings.news_anounce_cut eq 300} SELECTED{/if}>300</option>
			  <option value="400"{if $modules[$index].edit_settings.news_anounce_cut eq 400} SELECTED{/if}>400</option>
			  <option value="500"{if $modules[$index].edit_settings.news_anounce_cut eq 500} SELECTED{/if}>500</option>
			  <option value="500"{if $modules[$index].edit_settings.news_anounce_cut eq 700} SELECTED{/if}>700</option>
			  <option value="1000"{if $modules[$index].edit_settings.news_anounce_cut eq 1000} SELECTED{/if}>1000</option>
			  <option value="1000"{if $modules[$index].edit_settings.news_anounce_cut eq 2000} SELECTED{/if}>2000</option>
			  <option value="1000"{if $modules[$index].edit_settings.news_anounce_cut eq 10000} SELECTED{/if}>10000</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_anounce_cut'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_anounce_cut&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.short_news_count eq 1}
		<tr>
			<td>{$lang.settings_count_short_news}:</td>
			<td><select name="p_news_short" size="1">
			  {section name=i loop=20 start=1}
				  <option value="{$smarty.section.i.index}"{if $modules[$index].edit_settings.short_news_count eq $smarty.section.i.index} SELECTED{/if}>{$smarty.section.i.index}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='short_news_count'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_count_short_news&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.short_news_cut eq 1}
		<tr>				 
			<td>{$lang.settings_short_news_cut}:</td>
			<td><select name="p_short_news_cut" size="1">
			  <option value="50"{if $modules[$index].edit_settings.short_news_cut eq 50} SELECTED{/if}>50</option>
			  <option value="100"{if $modules[$index].edit_settings.short_news_cut eq 100} SELECTED{/if}>100</option>
			  <option value="150"{if $modules[$index].edit_settings.short_news_cut eq 150} SELECTED{/if}>150</option>
			  <option value="200"{if $modules[$index].edit_settings.short_news_cut eq 200} SELECTED{/if}>200</option>
			  <option value="300"{if $modules[$index].edit_settings.short_news_cut eq 300} SELECTED{/if}>300</option>
			  <option value="400"{if $modules[$index].edit_settings.short_news_cut eq 400} SELECTED{/if}>400</option>
			  <option value="500"{if $modules[$index].edit_settings.short_news_cut eq 500} SELECTED{/if}>500</option>
			  <option value="700"{if $modules[$index].edit_settings.short_news_cut eq 600} SELECTED{/if}>700</option>
			  <option value="1000"{if $modules[$index].edit_settings.short_news_cut eq 1000} SELECTED{/if}>1000</option>
			  <option value="10000"{if $modules[$index].edit_settings.short_news_cut eq 10000} SELECTED{/if}>10000</option>
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='short_news_cut'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_short_news_cut&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_use_time eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_news_use_time" value="1" {if $modules[$index].edit_settings.news_use_time eq "1"}checked{/if}>{$lang.settings_news_use_time}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=news_use_time&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.allow_alike_news eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_allow_alike_news" value="1" {if $modules[$index].edit_settings.allow_alike_news eq "1"}checked{/if}>{$lang.settings_allow_alike_news}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_allow_alike_news&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.alike_news_count eq 1}
		<tr>
			<td>{$lang.settings_alike_news_count}:</td>
			<td><input type="text" name="p_alike_news_count" value="{$modules[$index].edit_settings.alike_news_count}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='alike_news_count'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_alike_news_count&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.news_news_attachments_count eq 1}
		<tr>
			<td>{$lang.settings_news_attachments_count}:</td>
			<td><select name="p_news_attachments_count" size="1">
			  <option value="0"{if $modules[$index].edit_settings.news_news_attachments_count eq 0} SELECTED{/if}>-</option>
			  {section name=i loop=16 start=1}
			  <option value="{$smarty.section.i.index}"{if $modules[$index].edit_settings.news_attachments_count eq $smarty.section.i.index} SELECTED{/if}>{$smarty.section.i.index}</option>
			  {/section}
			</select></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='news_news_attachments_count'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_news_attachments_count&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.user_settings}</td>
</tr>
	{if $modules[$index].show_settings.allow_register eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_allowregister" value="1" {if $modules[$index].edit_settings.allow_register eq "1"}checked{/if}>{$lang.settings_allow_register}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_allow_register&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.allow_forgot_password eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_allowforgotpass" value="1" {if $modules[$index].edit_settings.allow_forgot_password eq "1"}checked{/if}>{$lang.settings_allow_forgot_password}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_allow_forgot_password&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.user_activating_by_admin eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_adminactivating" value="1" {if $modules[$index].edit_settings.user_activating_by_admin eq "1"}checked{/if}>{$lang.settings_user_activating_by_admin}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_user_activating_by_admin&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.return_after_login eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_return_after_login" value="1" {if $modules[$index].edit_settings.return_after_login eq "1"}checked{/if}>{$lang.settings_return_after_login}</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='return_after_login'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_return_after_login&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.allow_private_messages eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_allow_private_messages" value="1" {if $modules[$index].edit_settings.allow_private_messages eq "1"}checked{/if}>{$lang.settings_allow_private_messages}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_allow_private_messages&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.use_email_as_login eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="p_use_email_as_login" value="1" {if $modules[$index].edit_settings.use_email_as_login eq "1"}checked{/if}>{$lang.settings_use_email_as_login}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_use_email_as_login&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.signinwithloginandemail eq 1}
		<tr>
			<td colspan="2"><input type="checkbox" name="signinwithloginandemail" value="1" {if $modules[$index].edit_settings.signinwithloginandemail eq "1"}checked{/if}>{$lang.settings_signinwithloginandemail}</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=signinwithloginandemail&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_after_login_1 eq 1}
		<tr>
			<td>{$lang.settings_redirect_after_login} ({$lang.logged_users}):</td>
			<td><input type="text" name="p_redirect_after_login_1" value="{$modules[$index].edit_settings.redirect_after_login_1}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_after_login_1'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_after_login_1&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_after_login_2 eq 1}
		<tr>
			<td>{$lang.settings_redirect_after_login} ({$lang.power_users}):</td>
			<td><input type="text" name="p_redirect_after_login_2" value="{$modules[$index].edit_settings.redirect_after_login_2}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_after_login_2'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_after_login_2&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_after_login_3 eq 1}
		<tr>
			<td>{$lang.settings_redirect_after_login} ({$lang.administrators}):</td>
			<td><input type="text" name="p_redirect_after_login_3" value="{$modules[$index].edit_settings.redirect_after_login_3}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_after_login_3'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_after_login_3&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_after_register eq 1}
		<tr>
			<td>{$lang.settings_redirect_after_register}:</td>
			<td><input type="text" name="p_redirect_after_register" value="{$modules[$index].edit_settings.redirect_after_register}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_after_register'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_after_register&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_after_logout eq 1}
		<tr>
			<td>{$lang.settings_redirect_after_logout}:</td>
			<td><input type="text" name="p_redirect_after_logout" value="{$modules[$index].edit_settings.redirect_after_logout}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_after_logout'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_after_logout&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.redirect_on_success_change_usrdata eq 1}
		<tr>
			<td>{$lang.settings_redirect_on_success_change_usrdata}:</td>
			<td><input type="text" name="redirect_on_success_change_usrdata" value="{$modules[$index].edit_settings.redirect_on_success_change_usrdata}"></td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='redirect_on_success_change_usrdata'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=redirect_on_success_change_usrdata&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.security_settings}</td>
</tr>
	{if $modules[$index].show_settings.banned_ip eq 1}
		<tr>
			<td colspan="2">{$lang.settings_banned_ip}<br /><div align="center">
				<textarea cols="40" rows="3" name="p_banned_ip">{$modules[$index].edit_settings.banned_ip}</textarea>
				</div>
			</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_banned_ip&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.static_text_settings}</td>
</tr>
	{if $modules[$index].show_settings.meta_header_text eq 1}
		<tr>
			<td colspan="2">{$lang.settings_meta_header_text}<br /><div align="center">
				<textarea cols="40" rows="3" name="p_meta_header_text" wrap="off">{$modules[$index].edit_settings.meta_header_text}</textarea>
				</div>
			</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='meta_header_text'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_meta_header_text&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.header_static_text eq 1}
		<tr>
			<td colspan="2">{$lang.settings_header_static_text}<br /><div align="center">
				<textarea cols="40" rows="3" name="p_htext" wrap="off">{$modules[$index].edit_settings.header_static_text}</textarea>
				</div>
			</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='header_static_text'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_header_static_text&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.footer_static_text eq 1}
		<tr>
			<td colspan="2">{$lang.settings_footer_static_text}<br /><div align="center">
				<textarea cols="40" rows="3" name="p_ftext" wrap="off">{$modules[$index].edit_settings.footer_static_text}</textarea>
				</div>
			</td>
			<td>{include file='admin_settings_extctrls.tpl' name_settings='footer_static_text'}</td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_footer_static_text&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
<tr>
	<td colspan="4" bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.mass_email_settings}</td>
</tr>
	{if $modules[$index].show_settings.administrators_email eq 1}
		<tr>
			<td>{$lang.settings_administrators_email}</td>
			<td><input type="text" name="p_admemail" value="{$modules[$index].edit_settings.administrators_email}"></td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_administrators_email&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
	{if $modules[$index].show_settings.email_signature eq 1}
		<tr>
			<td colspan="2">{$lang.settings_email_signature}<br /><div align="center">
				<textarea cols="40" rows="3" name="p_esignature">{$modules[$index].edit_settings.email_signature}</textarea>
				</div>
			</td>
			<td></td>
			<td>{if $_settings.show_help eq "on"}<a target="_blank" href="http://{$modules[$index].edit_settings.help_resource}/index.php?m=help&q=settings_email_signature&lang={$modules[$index].edit_settings.default_language}">[?]</a>{/if}</td>
		</tr>
	{/if}
{$modules[$index].formadditionalhtml}
<tr>
	<td colspan="4" style="text-align: center"><input type="submit" value="{$lang.save}"></td>
</tr>
</table>
</form>
{include file="block_end.tpl"}
{/if}


{if $modules[$index].mode eq "postsettings"}
{include file="block_begin.tpl"}
{$lang.settings_saved_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postuplimg"}
{include file="block_begin.tpl"}
{$lang.operation_complete}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'tstatus'}
{include file="block_begin.tpl"}
{if $modules[$index].table_count gt "0"}
<form action="index.php?m=admin&d=optimize" method="post">
<input type="hidden" name="p_table_count" value="{$modules[$index].table_count}">
<a href="index.php?m=admin">{$lang.control_panel}</a><br /><br />
<table width="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.table_name}</td>
    <td bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.table_rows}</td>
    <td bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.table_size}</td>
    <td bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.table_not_optimized}</td>
    <td bgcolor="{#cl_admintabletitle_bgcolor#}">{$lang.module_admin.table_optimize}</td>
</tr>
{section name=i loop=$modules[$index].tables}
<tr>
    <td>{$modules[$index].tables[i].name}</td>
    <td>{$modules[$index].tables[i].rows}</td>
    <td>{$modules[$index].tables[i].data_length}</td>
    <td>{$modules[$index].tables[i].need_opt}</td>
    <td><input type="checkbox" name="p_opt_{$smarty.section.i.index}" value="{$modules[$index].tables[i].name}"{if $modules[$index].tables[i].need_opt gt "0"} checked{/if}></td>
</tr>
{/section}
</table>
{$modules[$index].formadditionalhtml}
<div align="center"><input type="submit" value="{$lang.module_admin.optimize_tables}"></div>
</form>
{if $_settings.show_help eq "on"}<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_optimize_database&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>{/if}
{else}
{$lang.module_admin.message_no_tables_in_DB}
{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'optimize'}
{include file="block_begin.tpl"}
{$lang.module_admin.message_optimize_successfull}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'listimg'}
{include file="block_begin.tpl"}
<form action="index.php">
	<input type="hidden" name="m" value="admin">
	<input type="hidden" name="d" value="listimg">
	{$lang.search}: <input type="text" name="filter" value="{$sm.g.filter}">
	<input type="submit" value="{$lang.common.ok}">
</form>
{include file="common_admintable.tpl" table=$modules[$index].table}
<br /><br />
<a href="index.php?m=admin&d=uplimg">{$lang.upload_image}</a><br />
<br />
{if $_settings.show_help eq "on"}<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_list_images&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postdelimg"}
{include file="block_begin.tpl"}
{$lang.module_admin.message_delete_image_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "renimg"}
{include file="block_begin.tpl"}
<!--<a href="index.php?m=admin">{$lang.control_panel}</a> :: <a href="index.php?m=admin&m=listimg">{$lang.module_admin.images_list}</a><br /><br />-->
{if $modules[$index].error_message neq ""}
<div class="errormessage">{$modules[$index].error_message}</div>
<br />
{/if}
<form action="index.php">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="d" value="postrenimg">
<input type="hidden" name="on" value="{$modules[$index].image.old_name}">
<input type="text" name="nn" value="{if $modules[$index].image.new_name eq ""}{$modules[$index].image.old_name}{else}{$modules[$index].image.new_name}{/if}">
{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.rename}">
</form>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postrenimg"}
{include file="block_begin.tpl"}
{$lang.module_admin.message_rename_image_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'massemail'}
{include file="block_begin.tpl"}
<form action="index.php?m=admin&d=postmassemail" method="post">
{$lang.module_admin.mass_email_theme}: <input type="text" name="p_theme" value=""><br />
{$lang.module_admin.mass_email_message}:<br />
{if $special.ext_editor_on eq "1"}
{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="common" var='p_body' value=$modules[$index].email.text}
{else}
<textarea cols="50" rows="20" name="p_body">{$modules[$index].email.text}</textarea>
{/if}
{$modules[$index].formadditionalhtml}
<div align="center"><input type="submit" value="{$lang.submit}"></div>
</form>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postmassemail"}
{include file="block_begin.tpl"}
{$lang.module_admin.message_mass_email_successfull}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postremmodule"}
{include file="block_begin.tpl"}
{$lang.module_admin.message_remove_module_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'addmodule'}
{include file="block_begin.tpl"}
<table width="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td width="50%">{$lang.module}</td>
    <td width="50%">{$lang.doing}</td>
</tr>
{section name=i loop=$modules[$index].addmodules}
<tr>
    <td>{$modules[$index].addmodules[i]}</td>
    <td><a href="index.php?m={$modules[$index].addmodules[i]}&d=install">{$lang.module_admin.add_module}</a></td>
</tr>
{/section}
</table>
{if $_settings.show_help eq "on"}<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_add_module&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postaddmodule"}
{include file="block_begin.tpl"}
{$lang.module_admin.message_add_module_successful}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'filesystem'}
{include file="block_begin.tpl"}
<a href="index.php?m=admin">{$lang.control_panel}</a><br />
{include file="common_admintable.tpl" table=$modules[$index].table}
<br />
<a href="index.php?m=admin&d=addfilesystem">{$lang.common.add}</a>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "editfilesystem" or $modules[$index].mode eq "addfilesystem"}
{include file="block_begin.tpl"}
<!--<a href="index.php?m=admin">{$lang.control_panel}</a> :: <a href="index.php?m=admin&d=filesystem">{$lang.module_admin.virtual_filesystem}</a><br /><br />-->
<form action="{if $modules[$index].mode eq "editfilesystem"}index.php?m=admin&d=posteditfilesystem&id={$modules[$index].row.id}{else}index.php?m=admin&d=postaddfilesystem{/if}" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td width="50%">{$lang.common.url}</td>
    <td width="50%"><input type="text" name="p_filename" value="{$modules[$index].row.filename}" style="width:100%"></td>
</tr>
<tr>
    <td width="50%">{$lang.module_admin.true_url}</td>
    <td width="50%"><input type="text" name="p_url" value="{$modules[$index].row.url}" style="width:100%"></td>
</tr>
<tr>
    <td width="50%">{$lang.common.comment}</td>
    <td width="50%"><input type="text" name="p_comment" value="{$modules[$index].row.comment}" style="width:100%"></td>
</tr>
{$modules[$index].formadditionalhtml}
<tr>
    <td width="50%" colspan="2" align="center"><input type="submit" value="{$lang.submit}"></td>
</tr>
</table>
</form>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'viewimg'}
{include file="block_begin.tpl"}
<div align="center">
<img src="files/img/{$modules[$index].viewed_img_name}" width="400">
<br />
<br />
<a href="index.php?m=admin&d=delimg&imgn={$modules[$index].viewed_img_name}">{$lang.common.delete}</a>
</div>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'filesystemexp'}
{include file="block_begin.tpl"}
<a href="index.php?m=admin">{$lang.control_panel}</a><br />
{include file="common_admintable.tpl" table=$modules[$index].table}
<br />
<a href="index.php?m=admin&d=addfilesystemexp">{$lang.common.add}</a>
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "addfilesystemexp" or $modules[$index].mode eq "editfilesystemexp"}
{include file="block_begin.tpl"}
<!--<a href="index.php?m=admin">{$lang.control_panel}</a> :: <a href="index.php?m=admin&d=filesystemexp">{$lang.module_admin.virtual_filesystem_regexp}</a><br />
<br />-->
{include file="common_adminform.tpl" form=$modules[$index].form}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'viewlog'}
{include file="block_begin.tpl"}
{include file="common_admintable.tpl" table=$modules[$index].table}
<br />
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'package'}
{include file="block_begin.tpl"}
<form action="index.php?m=admin&d=postpackage" method="post" enctype="multipart/form-data" class="pkgfrm">
<input TYPE="hidden" name="MAX_FILE_SIZE" value="{$_settings.max_upload_filesize}">
{$lang.file_name}: <input NAME="userfile" TYPE="file">
{$modules[$index].formadditionalhtml1}
<input type="submit" value="{$lang.upload}">
</form>
{if $modules[$index].can_upload_from_server eq "1"}
<br />
<form action="index.php?m=admin&d=postpackage&typeupload=url" method="post" enctype="multipart/form-data" class="pkgfrm">
{$lang.common.url}: <input NAME="urlupload" TYPE="text">
{$modules[$index].formadditionalhtml2}
<input type="submit" value="{$lang.upload}">
</form>
{/if}
{if $_settings.show_help eq "on"}<p align="right"><a href="http://{$_settings.help_resource}/index.php?m=help&q=admin_upload_image&lang={$_settings.default_language}" target="_blank">[? {$lang.help}]</a></p>{/if}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq "postdeletefilesystem" or $modules[$index].mode eq "posteditfilesystem" or $modules[$index].mode eq "postaddfilesystem" or $modules[$index].mode eq "postdeletefilesystemexp" or $modules[$index].mode eq "posteditfilesystemexp" or $modules[$index].mode eq "postaddfilesystemexp" or $modules[$index].mode eq "postpackage"}
{include file="block_begin.tpl"}
{include file="refresh.tpl"}
{include file="block_end.tpl"}
{/if}
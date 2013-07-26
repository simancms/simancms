<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>{if $inst.title eq ""}SiMan CMS{else}{$inst.title}{/if}</title>
<META content="text/html; charset={$lang.charset}" http-equiv=Content-Type>
<style type="text/css">
{literal}
body
	{
		background: #e5e5e5;
		color:#000000;
		font-family: sans-serif;
	}
a
	{
		color:#000080;
	}
.area
	{
	}
.container
	{
		margin: 0 auto;
		width:800;
	}
.header
	{
		color:#ffffff;
		padding:10px;
		background: #1e5799; /* Old browsers */
		background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1e5799), color-stop(50%,#2989d8), color-stop(51%,#207cca), color-stop(100%,#7db9e8)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* IE10+ */
		background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
		-webkit-border-top-left-radius: 10px;
		-webkit-border-top-right-radius: 10px;
		-moz-border-radius-topleft: 10px;
		-moz-border-radius-topright: 10px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
	}
.content
	{
		-webkit-border-bottom-right-radius: 10px;
		-webkit-border-bottom-left-radius: 10px;
		-moz-border-radius-bottomright: 10px;
		-moz-border-radius-bottomleft: 10px;
		border-bottom-right-radius: 10px;
		border-bottom-left-radius: 10px;
		background:#ffffff;
		padding:10px;
	}
{/literal}
</style>
</head>

<body>
	<div class="area">
		<div class="container">
			<div class="header">
				<span style="font-weight:bold;">SiMan CMS Installation</span>
				{if $inst.title neq ""} - {$inst.title}{/if}
			</div>
			
			<div class="content">
				{if $inst.step eq ""}
				<center>
				Choose instalation language:<br>
				<form action="install.php?s=1" method="post">
				<select name="p_lang" size="1">
				{section name=i loop=$inst.lang}
					<option value="{$inst.lang[i]}"{if $smarty.section.i.index eq "0"} SELECTED{/if}>{$inst.lang[i]}</option>
				{/section}
				</select>
				<input type="submit" value="OK &gt;&gt;">
				</form>
				</center>
				{elseif $inst.step eq "1"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{else}
				<div align="right">
				<form action="install.php?s=2" method="post">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</form>
				</div>
				{/if}
				{elseif $inst.step eq "2"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{else}
				<div align="right">
				<form action="install.php?s=3" method="post">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</form>
				</div>
				{/if}
				{elseif $inst.step eq "3"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{else}
				<div align="right">
				<form action="install.php?s=4" method="post">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</form>
				</div>
				{/if}
				
				{elseif $inst.step eq "4"}
				
				<form action="install.php?s=5" method="post">
				
				
				<table width="100%" border="0">
				<tr>
					<td width="20%">{$lang.settings_resource_title}:</td><td><input type="text" name="p_title" value="{$lang.siman_powered_site}" size="50"></td>
				</tr>
				<tr>
					<td>{$lang.settings_resource_url}:</td><td>http://<input type="text" name="p_url" value="{$inst.resource_url}" size="45"></td>
				</tr>
				<tr>
					<td>{$lang.settings_logo_text}:</td><td><input type="text" name="p_logo" value="{$lang.use_siman}" size="50"></td>
				</tr>
				<tr>
					<td>{$lang.settings_copyright_text}:</td><td><input type="text" name="p_copyright" value="&amp;copy; {$lang.your_company}" size="50"></td>
				</tr>
				<tr>
					<td>{$lang.settings_default_language}: </td><td><select name="p_lang" size="1">
					  {section name=i loop=$inst.langs}
					  <option value="{$inst.langs[i]}"{if $inst.langs[i] eq $inst.lang} SELECTED{/if}>{$inst.langs[i]}</option>
					  {/section}
					</select></td>
				</tr>
				<tr>
					<td>{$lang.settings_default_theme}:</td><td><select name="p_theme" size="1">
					  {section name=i loop=$inst.themes}
					  <option value="{$inst.themes[i]}"{if $inst.themes[i] eq "bootstrap"} SELECTED{/if}>{$inst.themes[i]}</option>
					  {/section}
					</select></td>
				</tr>
				<tr>
					<td>{$lang.settings_default_module}:</td><td><select name="default_module" size="1">
					  <option value="content">{$lang.content_texts}</option>
					  <option value="news">{$lang.content_news}</option>
					</select></td>
				</tr>
				</table>
				
				<div align="right">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</div>
				</form>
				
				{elseif $inst.step eq "5"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{else}
				<div align="right">
				<form action="install.php?s=6" method="post">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</form>
				</div>
				{/if}
				
				
				{elseif $inst.step eq "6"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{/if}
				<form action="install.php?s=7" method="post">
				
				
				<table width="100%" border="0">
				<tr>
				<td width="50%">{$lang.addadm.login_str}<sup>*</sup></td><td width="50%"><input type="text" name="p_login" value="{if $inst.addadmin.user_login eq ""}admin{else}{$inst.addadmin.user_login}{/if}"></td>
				</tr>
				<tr>
				<td>{$lang.addadm.password}<sup>*</sup></td><td><input type="text" name="p_password" value=""></td>
				</tr>
				<tr>
				<td>{$lang.addadm.email}<sup>*</sup></td><td><input type="text" name="p_email" value="{$inst.addadmin.user_email}"><td>
				</tr>
				<tr>
				<td>{$lang.addadm.secret_question}</td><td><input type="text" name="p_question" value="{$inst.addadmin.user_question}"></td>
				</tr>
				<tr>
				<td>{$lang.addadm.secret_answer_question}</td><td><input type="text" name="p_answer" value="{$inst.addadmin.user_answer}"></td>
				</tr>
				</table>
				
				
				
				<div align="right">
				<input type="submit" value="{$lang.next_step} &gt;&gt;">
				</div>
				</form>
				
				{elseif $inst.step eq "7"}
				{include file="listmsg.tpl"}
				{if $inst.error eq "1"}{include file="listerrors.tpl"}{else}
				<div align="right">
				<form action="install.php?s=finish" method="post">
				<input type="submit" value="{$lang.finish} &gt;&gt;">
				</form>
				</div>
				{/if}
				
				
				{elseif $inst.step eq "finish"}
				
				<div align="center">
				{$lang.finished.gratulations}
				<br>
				<br>
				{$lang.finished.apserver_message}:<br>
				{$lang.finished.official_site} <a href="http://simancms.org">simancms.org</a><br>
				{$lang.finished.developers_portal} <a href="http://dev.simancms.org">dev.simancms.org</a><br>
				<br>
				<font color="#FF0000">{$lang.finished.dont_forget_erase}</font>
				<br>
				<br>
				<a href="../">{$lang.finished.begin_work}</a>
				</div>
				
				{else}
				
				{/if}
			</div>
		</div>
	</div>
</body>
</html>
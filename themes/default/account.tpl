{if $modules[$index].mode eq "show" or $modules[$index].mode eq "wronglogin"}
{if $modules[$index].panel eq "center"}
  {* main panel *}
{include file="block_begin.tpl"}
{if $modules[$index].mode eq "show" or $modules[$index].mode eq "wronglogin"}
{if $modules[$index].mode eq "wronglogin"}<div align="center" style="color:#ff0000;">{$lang.message_wrong_login}</div>{/if}
<form action="index.php?m=account&d=login" method="POST" class="loginFormCenter">
<div align="center">
<table width="50%" cellspacing="0">
	<tr>
		<td>{if $_settings.use_email_as_login neq "1"}{$lang.login_str}{else}{$lang.common.email}{/if}:</td>
		<td><input name="login_d" id="login_d" type="text" size="10"></td>
	</tr>
	<tr>
		<td>{$lang.password}:</td>
		<td><input type="Password" name="passwd_d" type="password" type="text" size="10"></td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="autologin_d" value="1"> {$lang.common.auto_login}
		</td>
		<td>
			<div align="left"><input type="submit" value="{$lang.login}"></div>
		</td>
	</tr>
</table>
</div>
</form>
{if $_settings.allow_register eq "1" or $userinfo.id neq ""}
<div align="center"><a href="index.php?m=account&d=register">{$lang.register}</a></div>
{/if}
{if $_settings.allow_forgot_password eq "1"}
<div align="center"><a href="index.php?m=account&d=getpasswd">{$lang.forgot_password_question}</a></div>
{/if}
{include file="block_end.tpl"}
{/if}
{else}
  {* side panels *}								   
{include file="block_begin.tpl"}
{if $userinfo.id eq ""}
<form action="index.php?m=account&d=login" method="POST" class="loginForm">

<table width="100%" cellspacing="0">
	<tr>
		<td>
			{if $_settings.use_email_as_login neq "1"}{$lang.login_str}{else}{$lang.common.email}{/if}:
		</td>
		<td><input name="login_d" type="text" size="10" style="width:100%;"></td>
	</tr>
	<tr>
		<td>{$lang.password}:</td>
		<td><input type="password" name="passwd_d" size="10" style="width:100%;"></td>
	<tr>
		<td colspan="2">
			<input type="checkbox" name="autologin_d" value="1"> {$lang.common.auto_login}
		</td>
	</tr>
	<tr>
		<td align="center">
			{if $_settings.allow_register eq "1" or $userinfo.id neq ""}
				<div align="center"><a href="index.php?m=account&d=register">{$lang.register}</a></div>
			{/if}
		</td>
		<td align="center"><input class="loginbutton" type="submit" value="{$lang.login}"></td>
	</tr>
</table>
<input type="hidden" name="p_goto_url" value="{$modules[$index].goto_url}">
</form>
{else}
<strong>{$userinfo.login}</strong>
<br />
{if $_settings.allow_private_messages eq "1"}
<a href="index.php?m=account&d=viewprivmsg&folder=inbox">{$lang.module_account.inbox}</a><br />
{/if}
{if $userinfo.status eq "admin"}
<a href="index.php?m=admin">{$lang.control_panel}</a><br />
{/if}
<a href="index.php?m=account&d=cabinet">{$lang.my_cabinet}</a><br />
<a href="index.php?m=account&d=logout">{$lang.logout}</a>
{/if}
{include file="block_end.tpl"}
{/if}
{/if}

{if $modules[$index].mode eq "cabinet"}
{include file="block_begin.tpl"}
{$lang.wellcome}, <strong>{$userinfo.login}</strong>!
<br />
{if $_settings.allow_private_messages eq "1"}
<br />
<a href="index.php?m=account&d=sendprivmsg">{$lang.module_account.send_message}</a> :: <a href="index.php?m=account&d=viewprivmsg&folder=inbox">{$lang.module_account.inbox}{if $modules[$index].privmsgdata.inbox.unread gt 0} ({$modules[$index].privmsgdata.inbox.unread}/{$modules[$index].privmsgdata.inbox.all}){/if}</a> :: <a href="index.php?m=account&d=viewprivmsg&folder=outbox">{$lang.module_account.outbox}</a><br />
{/if}
{if $modules[$index].userlinkcount gt "0"}
<br />
{section name=i loop=$modules[$index].userlinks}
<a href="{$modules[$index].userlinks[i].url}">{$modules[$index].userlinks[i].title}</a><br />
{/section}
{/if}
<br />
<form action="index.php?m=account&d=savenbook" method="post">
<strong>{$lang.module_account.notebook}:</strong>
<div align="center">
<textarea cols="50" rows="10" name="p_notebook">{$modules[$index].notebook_text}</textarea>
<br />
<input type="submit" value="{$lang.save}">
</div>
</form>
<br />
{if $userinfo.status eq "admin"}
<a href="index.php?m=admin">{$lang.control_panel}</a><br />
{/if}
<a href="index.php?m=account&d=change">{$lang.module_account.change_personal_info}</a><br />
<a href="index.php?m=account&d=logout">{$lang.logout}</a>
{include file="block_end.tpl"}
{/if}




{include file="account_additional.tpl"}
{include file="account_adminpart.tpl"}
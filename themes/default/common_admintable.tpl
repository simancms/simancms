{strip}{if $postfix eq ""}
	{assign var=postfix value=$table.postfix}
{/if}
<script type="text/javascript">
{literal}
function  admintable_msgbox{/literal}{$postfix}{literal}(question, url)
	{
		if (confirm(question+(question.indexOf('?', 0)>=0?'':'?')))
			{
				setTimeout(function() { document.location.href = url; }, 30);
			}
	}

function  admintable_goto{/literal}{$postfix}{literal}(url)
	{
		setTimeout(function() { document.location.href = url; }, 30);
	}

function  admintable_to_menu{/literal}{$postfix}{literal}(url, caption)
	{
		var menustr='<form action="index.php?m=menu&d=addouter" method="post" id="menuform{/literal}{$postfix}{literal}" name="menuform{/literal}{$postfix}{literal}">'+
			'<input type="hidden" name="p_url" id="menuform_url{/literal}{$postfix}{literal}" value="">'+
			'<input type="hidden" name="p_caption" id="menuform_caption{/literal}{$postfix}{literal}" value="">'+
			'</form>';
		f=document.getElementById('menuform{/literal}{$postfix}{literal}');
		if (!f)
			{
				var df = document.createElement('div');
				df.style.display='none';
				df.innerHTML=menustr;
				document.body.appendChild(df);
				f=document.getElementById('menuform{/literal}{$postfix}{literal}');
			}
		f_u=document.getElementById('menuform_url{/literal}{$postfix}{literal}');
		f_c=document.getElementById('menuform_caption{/literal}{$postfix}{literal}');
		f_u.value=url;
		f_c.value=caption;
		f.submit();
		//alert(f_c.value);
		//document.getElementById('menuform').submit();
	}

function  admintable_ajax_load{/literal}{$postfix}{literal}(url, id)
	{
		if (!$("#"+id).is(':visible'))
			return;
		$('#'+id).html('{/literal}<div class="at-expander-loading">{$lang.common.loading|addslashes}</div>{literal}');
		$("#"+id).load(
				url,
				function(response, status, xhr)
					{
						if (status == "error")
							{
								var msg = '{/literal}{$lang.error}{literal}';
								$("#"+id).html(msg + ' ' + xhr.status + " " + xhr.statusText);
							}
					}
			);
	}
{/literal}

var atdropdowntimeout{$postfix}	= 1000;
var atdropdownclosetimer{$postfix}	= 0;
var atdropdownddmenuitem{$postfix}	= 0;
function atdropdownopen{$postfix}(id)
	{ldelim}
		atdropdowncancelclosetime{$postfix}();
		atdropdownclosetime{$postfix}(1500);
		if(atdropdownddmenuitem{$postfix}) atdropdownddmenuitem{$postfix}.style.visibility = 'hidden';
		atdropdownddmenuitem{$postfix} = document.getElementById(id);
		atdropdownddmenuitem{$postfix}.style.visibility = 'visible';
	{rdelim}
function atdropdownclose{$postfix}()
	{ldelim}
		if(atdropdownddmenuitem{$postfix}) atdropdownddmenuitem{$postfix}.style.visibility = 'hidden';
	{rdelim}
function atdropdownclosetime{$postfix}(timeout)
	{ldelim}
		if (!timeout)
			timeout=atdropdowntimeout{$postfix};
		atdropdownclosetimer{$postfix} = window.setTimeout(atdropdownclose{$postfix}, timeout);
	{rdelim}
function atdropdowncancelclosetime{$postfix}()
	{ldelim}
		if(atdropdownclosetimer{$postfix})
		{ldelim}
			window.clearTimeout(atdropdownclosetimer{$postfix});
			atdropdownclosetimer{$postfix} = null;
		{rdelim}
	{rdelim}

</script>
{assign var=default_column value=$table.default_column}
<table{foreach name=table_attr_index from=$table.attrs item=attr key=attr_name} {$attr_name}="{$attr}"{/foreach}>
	{if $table.hideheader neq 1}{$table.head_html_start}{strip}{if $table.display_thead}<thead>{/if}<tr{foreach name=table_header_attr_index from=$table.header_attrs item=attr key=attr_name} {$attr_name}="{$attr}"{/foreach}>
		{foreach name=table_column_index from=$table.columns item=column key=column_name}
		{if $column.hide neq 1 and $column.hideheader neq 1}
		<{$table.header_tag}{foreach name=table_th_attr_index from=$column.header_attr key=table_th_attr_name item=table_th_attr_val} {$table_th_attr_name}="{$table_th_attr_val}"{/foreach}>{$column.th_html_start}
			{if $column.headerurl neq "" or $column.onclick neq ""}<a{if $column.headerurl neq ""} href="{$column.headerurl}"{/if}{if $column.onclick neq ""} onclick="{$column.onclick}"{/if}>{/if}
				{$column.caption}{$column.html}
			{if $column.headerurl neq "" or $column.onclick neq ""}</a>{/if}
			{if $column.dropdown eq 1}
				<div class="atdropdownitems" id="atdropdown-{$column_name}-{$postfix}" onmouseover="atdropdowncancelclosetime{$postfix}()" onmouseout="atdropdownclosetime{$postfix}()">
				{section name=table_row_index_dropdown loop=$column.dropdownitems}
				<a {if $column.dropdownitems[table_row_index_dropdown].confirm_message neq ""}
						href="javascript:void(0);" onclick="admintable_msgbox{$postfix}('{$column.dropdownitems[table_row_index_dropdown].confirm_message}', '{$column.dropdownitems[table_row_index_dropdown].url}')"
					{else}
						href="{$column.dropdownitems[table_row_index_dropdown].url}"
					{/if}{if $column.dropdownitems[table_row_index_dropdown].selected eq 1} style="font-weight:bold;"{/if}>{$column.dropdownitems[table_row_index_dropdown].title}</a>
				{/section}
				</div>
			{/if}
		{$column.th_html_end}</{$table.header_tag}>
		{/if}
		{/foreach}
	</tr>{/strip}{$table.head_html_end}{/if}
	{$table.body_html_start}{section name=table_row_index loop=$table.rows}
	{strip}{$table.rowparams[table_row_index].html_start}<tr class="admintable_row{$table.rowparams[table_row_index].class}"{if $table.rows[table_row_index].$default_column.url neq ""} onDblClick="admintable_goto{$postfix}('{$table.rows[table_row_index].$default_column.url}')"{/if}{if $table.rowparams[table_row_index].style neq ""} style="{$table.rowparams[table_row_index].style}"{/if}>
		{foreach name=table_column_index from=$table.columns item=column key=column_name}
		{if $column.hide neq 1 and $table.rows[table_row_index].$column_name.hide neq 1}
		<td{foreach name=table_td_attr_index from=$table.rows[table_row_index].$column_name.attrs key=table_td_attr_name item=table_td_attr_val} {$table_td_attr_name}="{$table_td_attr_val}"{/foreach}>
			{$table.rows[table_row_index].$column_name.html_start}
			{if $column.nobr eq "1"}
				<nobr>
			{/if}
			{if $column.to_menu eq "1"}
				<a href="javascript:admintable_to_menu{$postfix}('{$table.rows[table_row_index].$column_name.menu_url}', '{$table.rows[table_row_index].$column_name.menu_caption}');"><nobr>
			{else}
				{if $table.rows[table_row_index].$column_name.url neq ""}
					{if $column.messagebox eq "1" or $table.rows[table_row_index].$column_name.messagebox_text neq ""}
						<a href="javascript:void(0);" onclick="admintable_msgbox{$postfix}('{if $table.rows[table_row_index].$column_name.messagebox_text neq ""}{$table.rows[table_row_index].$column_name.messagebox_text}{else}{$column.messagebox_text}{/if}', '{$table.rows[table_row_index].$column_name.url}')">
					{else}
						<a href="{$table.rows[table_row_index].$column_name.url}"{if $column.url_target neq ""} target="{$column.url_target}"{/if}{if $table.rows[table_row_index].$column_name.onclick neq ""} onclick="{$table.rows[table_row_index].$column_name.onclick}"{/if}{if $table.rows[table_row_index].$column_name.new_window} target="_blank"{/if}>
					{/if}
				{/if}
			{/if}
			{if $table.rows[table_row_index].$column_name.image neq ""}
				<img src="{$table.rows[table_row_index].$column_name.image}" border="0">
			{elseif $table.rows[table_row_index].$column_name.element eq "select"}
				<select{foreach name=table_ctrlattr_index from=$table.rows[table_row_index].$column_name.control_attr key=control_attr_name item=control_attrval} {$control_attr_name}="{$control_attrval}"{/foreach}>
					{section name=table_row_index_section loop=$table.rows[table_row_index].$column_name.values}
						<option value="{$table.rows[table_row_index].$column_name.values[table_row_index_section]}"{if $table.rows[table_row_index].$column_name.values[table_row_index_section] eq $table.rows[table_row_index].$column_name.data} SELECTED{/if}>{$table.rows[table_row_index].$column_name.labels[table_row_index_section]}</option>
					{/section}
				</select>
			{elseif $table.rows[table_row_index].$column_name.element neq ""}
				<input{foreach name=table_ctrlattr_index from=$table.rows[table_row_index].$column_name.control_attr key=control_attr_name item=control_attrval} {$control_attr_name}="{$control_attrval}"{/foreach} />{if $table.rows[table_row_index].$column_name.element eq "storedlabel"} {$table.rows[table_row_index].$column_name.data}{/if}
			{elseif $table.rows[table_row_index].$column_name.data|strlen > 0}
				{$table.rows[table_row_index].$column_name.data}
			{elseif $column.default_image neq ""}
				<img src="{$column.default_image}" border="0">
			{elseif $column.default_text neq ""}
				{$column.default_text}
			{/if}
			{if $column.to_menu eq "1"}
				</nobr></a>
			{else}
				{if $table.rows[table_row_index].$column_name.url neq ""}</a>{/if}
			{/if}
			{if $column.nobr eq "1"}
				</nobr>
			{/if}
			{$table.rows[table_row_index].$column_name.html_content_end}
			{if $table.rows[table_row_index].$column_name.dropdown eq 1}
				<div class="atdropdownitems" id="atdropdown-{$column_name}-{$smarty.section.table_row_index.index}-{$postfix}" onmouseover="atdropdowncancelclosetime{$postfix}()" onmouseout="atdropdownclosetime{$postfix}()">
				{section name=table_row_index_dropdown loop=$table.rows[table_row_index].$column_name.dropdownitems}
				<a {if $table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].tomenutitle neq ""}
						 href="javascript:admintable_to_menu{$postfix}('{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].url}', '{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].tomenutitle}');"
					{elseif $table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].confirm_message neq ""}
						href="javascript:void(0);" onclick="admintable_msgbox{$postfix}('{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].confirm_message}', '{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].url}')"
					{else}
						href="{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].url}"
					{/if}{if $table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].selected eq 1} style="font-weight:bold;"{/if}>{$table.rows[table_row_index].$column_name.dropdownitems[table_row_index_dropdown].title}</a>
				{/section}
				</div>
			{/if}
			{$table.rows[table_row_index].$column_name.html_end}
		</td>
		{/if}
		{/foreach}
	</tr>{$table.rowparams[table_row_index].html_end}{/strip}
	{if $table.expanders[table_row_index].enabled}
	<tr style="display:none;" id="admintable-expander-{$smarty.section.table_row_index.index}-{$postfix}" class="at-expander">
		<td colspan="{$table.colcount}" id="admintable-expanderarea-{$smarty.section.table_row_index.index}-{$postfix}" class="at-expanderarea">
			{$table.expanders[table_row_index].html}
		</td>
	</tr>
	{/if}
	{/section}{$table.body_html_end}
</table>
{/strip}
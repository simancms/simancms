<script type="text/javascript">
{literal}
function set_adminform_style{/literal}{$postfix}{literal}(element, r1, r1h, r2, r2h)
{
 while (element && element.nodeName != "TR")
  element = element.childNodes.length ? element.childNodes[0] : element.nextSibling;
 var n = 0;
 while (element)
 {
  if (element.nodeName == "TR" && element.className!='adminform-separator')
  {
//   if (element.id!="admintable_header{/literal}{$postfix}{literal}")
   if (n % 2)
   {
    element.style.background = r1;
    if (r1h)
    {
     element.onmouseover = function() { this.style.background = r1h; }
     element.onmouseout = function() { this.style.background = r1; }
    }
   } else
   {
    element.style.background = r2;
    if (r2h)
    {
     element.onmouseover = function() { this.style.background = r2h; }
     element.onmouseout = function() { this.style.background = r2; }
    }
   }
   n++;
  }
  element = element.nextSibling;
 }
} 

function show_admintable_tab(num)
	{
		{/literal}
		{section name=tabsectionindex loop=$form.tabs start=1}{* tab titles *}
		document.getElementById('adminform-tab-{$smarty.section.tabsectionindex.index}').style.display=({$smarty.section.tabsectionindex.index}==num)?'':'none';
		document.getElementById('adminform-tabtitle-{$smarty.section.tabsectionindex.index}').style.fontWeight=({$smarty.section.tabsectionindex.index}==num)?'bold':'normal';
		{/section}
		{literal}
	}
{/literal}
</script>

{if $form.dont_use_form_tag neq 1}
<form action="{$form.action}" method="{if $form.method eq ""}post{else}{$form.method}{/if}" class="adminform_form" enctype="multipart/form-data">
{/if}
{if $form.method neq "get"}
	<input type="hidden" name="adminform_updates_nllist" value="{$form.updates}">
	<input type="hidden" name="adminform_files_nllist" value="{$form.files}">
{/if}
{section name=tabsectionindex loop=$form.tabs}
{if $smarty.section.tabsectionindex.index eq 1}{* tab definitions *}
<div id="adminform-tabs-elements" class="adminform-tabs-elements">
<div id="adminform-tabs-titles">
	{section name=tabsectionindex2 loop=$form.tabs start=1}{* tab titles *}
	{if $smarty.section.tabsectionindex2.index gt 1} | {/if}
		<a id="adminform-tabtitle-{$smarty.section.tabsectionindex2.index}" href="javascript:;" onclick="show_admintable_tab('{$smarty.section.tabsectionindex2.index}')">{$form.tabs[tabsectionindex2].title}</a>
	{/section}
</div>
{/if}
<div id="adminform-tab-{$smarty.section.tabsectionindex.index}">
<table width="100%" cellspacing="2" cellpadding="2" id="adminform_table{$smarty.section.tabsectionindex.index}" class="adminform_table">
	{foreach name=form_field_index from=$form.fields item=field key=field_name}
	{if $field.tab eq $smarty.section.tabsectionindex.index}
		{if $field.type neq "hidden" and  $field.type neq "separator" and $field.mergecolumns neq 1 and $field.hidedefinition neq 1}
		<tr>
		<td width="{if $form.options.width1 neq ""}{$form.options.width1}{else}30%{/if}">{$field.caption}{$field.column[0]}</td>
		<td width="{if $form.options.width2 neq ""}{$form.options.width2}{else}67%{/if}">
		{elseif $field.mergecolumns eq 1}
		<tr>
		<td colspan="3">
			{$field.caption}
		{elseif $field.type eq "separator"}
		<tr class="adminform-separator">
		<td colspan="2"><strong>{$field.caption}</strong>
		{/if}
		{assign var=field_db value=$field.name}
		{if $field.toptext neq ""}{$field.toptext}<br />{/if}
		{$field.begintext}
		{if $field.type eq "label"}
			{$field.labeltext}
		{elseif $field.type eq "hidden"}
			<input type="hidden" name="{$form.prefix}{$field.name}" value="{$form.data.$field_db}" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrname key=attrval} {$attrname}="{$attrval}"{/foreach} />
		{elseif $field.type eq "statictext"}
			<input type="hidden" name="{$form.prefix}{$field.name}" value="{$form.data.$field_db}" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrname key=attrval} {$attrname}="{$attrval}"{/foreach} /> {$form.data.$field_db}
		{elseif $field.type eq "text"}
			<input type="text" name="{$form.prefix}{$field.name}" value="{$form.data.$field_db}" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrname key=attrval} {$attrname}="{$attrval}"{/foreach} />
		{elseif $field.type eq "file"}
			<input type="hidden" name="MAX_FILE_SIZE" value="{$_settings.max_upload_filesize}" />
			<input type="file" name="{$form.prefix}{$field.name}" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrname key=attrval} {$attrname}="{$attrval}"{/foreach}  />
		{elseif $field.type eq "textarea"}
			<textarea name="{$form.prefix}{$field.name}" cols="30" rows="5" name="1" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrname key=attrval} {$attrname}="{$attrval}"{/foreach}>{$form.data.$field_db}</textarea>
		{elseif $field.type eq "select"}
			<select name="{$form.prefix}{$field.name}" size="1" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{foreach name=form_field_attr_index from=$field.attrs item=attrval key=attrname} {$attrname}="{$attrval}"{/foreach}>
				{section name=form_vaule_index loop=$field.values}
				<option value="{$field.values[form_vaule_index]}"{if $field.values[form_vaule_index] eq $form.data.$field_db or $form.data.$field_db eq "" and $smarty.section.form_vaule_index.index eq 0} SELECTED{/if}>{if $field.labels[form_vaule_index] eq ""}{$field.values[form_vaule_index]}{else}{$field.labels[form_vaule_index]}{/if}</option>
				{/section}
			</select>
		{elseif $field.type eq "editor"}
			{include file="editors_`$_settings.ext_editor`.tpl" editor_doing="common" var=`$form.prefix``$field.name` value=$form.data.$field_db noninit=$field.noinit}
		{elseif $field.type eq "checkbox"}
			<input type="checkbox" name="{$form.prefix}{$field.name}" value="{$field.checkedvalue}" id="{if $field.id neq ""}{$field.id}{else}{$form.prefix}{$field.name}{/if}"{if $form.data.$field_db eq $field.checkedvalue} checked{/if} />
		{/if}
		{$field.endtext}
		{if $field.image.href neq ""}<a href="{$field.image.href}" target="_blank">{/if}{if $field.image.src neq ""}<img src="{$field.image.src}" border="0" align="middle" />{/if}{if $field.image.href neq ""}</a>{/if}
		{if $field.bottomtext neq ""}<br />{$field.bottomtext}{/if}
		{if $field.type neq "hidden" and $field.hideencloser neq 1}
		{$field.column[1]}
		{if $field.mergecolumns neq 1}
			<td width="{if $form.options.width3 neq ""}{$form.options.width2}{else}3%{/if}">
				{if $field.tooltip neq ""}<div class="tooltip" title="{$field.tooltip}"></div>{/if}
				{$field.column[2]}
			</td>
			<td>
		{/if}
		</td>
		</tr>
		{/if}
	{/if}
	{/foreach}

</table>
</div>
{/section}
{if $form.tabscount gt 1}
</div>{* closed element for tabs div *}
<div class="clear"></div>
{/if}
{if $form.dont_use_form_tag neq 1}
<div align="right"><input type="submit" value="{if $form.savetitle neq ""}{$form.savetitle}{else}{$lang.save}{/if}"></div>
</form>
{/if}

<script type="text/javascript">
{if $form.no_highlight neq 1}
{section name=tabsectionindex2 loop=$form.tabs}{* tab titles *}
	set_adminform_style(document.getElementById('adminform_table{$smarty.section.tabsectionindex2.index}'), '#efefef', '#e5e5e5', '#dbdbdb', '#e5e5e5');
{/section}
{/if}
{if $form.tabscount gt 1}
	show_admintable_tab(1);
{/if}
</script>
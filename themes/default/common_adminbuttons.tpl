{strip}{$bar.htmlbegin}<div class="adminbuttons{if $bar.class neq ""} {$bar.class}{/if}"{if $bar.style neq ""} style="{$bar.style}"{/if}>{if $bar.buttonbar_title neq ""}<span class="buttonbar_title">{$bar.buttonbar_title}</span>{/if}
{foreach name=adminbuttons_index from=$bar.buttons item=button key=button_name}
	{if not $smarty.foreach.adminbuttons_index.first}{$bar.buttons_separator}{/if}{$button.htmlbegin}<{$button.htmlelement}{foreach name=adminbuttonsattrs_index from=$button.attrs item=abattr key=abattr_name}{if $abattr neq ""} {$abattr_name}="{$abattr}"{/if}{/foreach}>{$button.html}</{$button.htmlelement}>{$button.htmlend}
{/foreach}
</div>{$bar.htmlend}{/strip}
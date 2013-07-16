<script type="text/javascript">
{literal}
function  button_msgbox(url, message)
	{
		if (confirm(message))
			{
				setTimeout(function() { document.location.href = url; }, 30);
			}
	}
{/literal}
</script>

<div class="adminbuttons">{if $bar.buttonbar_title neq ""}<span class="buttonbar_title">{$bar.buttonbar_title}</span>{/if}
{foreach name=adminbuttons_index from=$bar.buttons item=button key=button_name}
{if $button.type eq "separator"}
	<span{if $button.style neq ""} style="{$button.style}"{/if}>{if $button.bold eq 1}<b>{/if}{if $button.image neq ""}<img src="{$button.image}" />{/if}{$button.caption}{if $button.bold eq 1}</b>{/if}</span>
{else}
	<button{if $button.url neq "" or $button.javascript neq ""} onclick="{if $button.type eq "messagebox"}button_msgbox('{$button.url}', '{$button.message}'){elseif $button.javascript neq ""}{$button.javascript}{else}location.href='{$button.url}'{/if}"{/if}{if $button.style neq "" or $button.width neq "" or $button.height neq ""} style="{$button.style}{if $button.width neq ""}width:{$button.width};{/if}{if $button.height neq ""}height:{$button.height};{/if}"{/if}>{if $button.bold eq 1}<b>{/if}{if $button.image neq ""}<img src="{$button.image}" />{/if}{$button.caption}{if $button.bold eq 1}</b>{/if}</button>
{/if}
{/foreach}	
</div>
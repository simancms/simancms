{if $tmp.block[$index].blockend neq 1}{$special.document.block[$index].blockend}{assign var=tmp.block[$index].blockend value=1}{/if}
{if $modules[$index].borders_off neq "1"}
{if $modules[$index].panel eq "center"}
  {* якщо центральна панель *}
	</div>
</div>
{elseif $modules[$index].panel eq "1"}
  {* якщо ліва панель *}
	</div>
</div>
{else}
  {* якщо права панель *}
</div>
{/if}
{/if}
{if $tmp.block[$index].blockend neq 1}{$special.document.block[$index].blockend}{assign var=tmp.block[$index].blockend value=1}{/if}
{if $modules[$index].borders_off neq "1"}
{if $modules[$index].panel eq "center"}
  {* ���� ���������� ������ *}
	</div>
</div>
{elseif $modules[$index].panel eq "1"}
  {* ���� ��� ������ *}
	</div>
</div>
{else}
  {* ���� ����� ������ *}
</div>
{/if}
{/if}
{if $special.pathcount gt 0}
<ul class="breadcrumb">
{section name=path_index loop=$special.path}
  <li>
    <a href="{$special.path[path_index].url}">{$special.path[path_index].title}</a>{if not $smarty.section.path_index.last} <span class="divider">/{/if}</span>
  </li>
{/section}
</ul>
{/if}

{if $special.pathcount gt 0}
<ol class="breadcrumb">
{section name=path_index loop=$special.path}
  <li>
    <a href="{$special.path[path_index].url}">{$special.path[path_index].title}</a>
  </li>
{/section}
</ol>
{/if}

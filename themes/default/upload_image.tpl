<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="{$_settings.max_upload_filesize}">
{if $no_use_foto_lang neq "1"}{if $upload_image_lang_foto neq ""}{$upload_image_lang_foto}{else}{$lang.common.image}{/if}: {/if}<INPUT NAME="userfile{$userfile_modif}" TYPE="file">
{if $img_path neq ""}<a href="{$img_path}&width=300&height=300" target="_blank"><img src="{$img_path}&width=30&height=30" border="0" /></a>{/if}
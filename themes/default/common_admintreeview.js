function atw_collapser(id)
	{
		if ($('#atw-'+id).hasClass('atw-expanded'))
			{
				$('.treeview-childof-atw-'+id).removeClass('atw-visible');
				$('.treeview-childof-atw-'+id).addClass('atw-invisible');
				$('#atw-'+id).removeClass('atw-expanded');
			}
		else
			{
				$('.treeview-childof-atw-'+id).removeClass('atw-invisible');
				$('.treeview-childof-atw-'+id).addClass('atw-visible');
				$('#atw-'+id).addClass('atw-expanded');
			}
	}

function atw_mass_checkboxes(items)
	{
		var ids=items.split(',');
		for (var i = 0; i < ids.length; i++)
			{
				$('#atw-checkbox-'+ids[i]).prop('checked', true);
			}
	}
var sm_ls_listener_page_id = '';
var sm_ls_listener_keys = {};

function sm_ls_listen()
	{
		var $listener = localStorage.getItem('sm_listener-' + sm_ls_listener_page_id);
		if (!$listener)
			$listener = {};
		else
			$listener = JSON.parse($listener);
		localStorage.setItem('sm_listener-' + sm_ls_listener_page_id, JSON.stringify({}));
		for (var key in $listener)
			{
				if ($listener.hasOwnProperty(key) && sm_ls_listener_keys[key])
					{
						$element=document.getElementById(key);
						if ($element)
							{
								$element.value = $listener[key];
							}
					}
			}
		setTimeout(sm_ls_listen, 100);
	}

function sm_ls_add_listen_key(key)
	{
		sm_ls_listener_keys[key]=true;
	}

function sm_ls_init_listener(page_id)
	{
		sm_ls_listener_page_id = page_id;
		setTimeout(sm_ls_listen, 250);
	}

function sm_ls_send_to_listener(listener_page_id, key, val)
	{
		var $listener = localStorage.getItem('sm_listener-' + listener_page_id);
		if (!$listener)
			$listener = {};
		else
			$listener = JSON.parse($listener);
		console.log($listener);
		$listener[key] = val;
		$listener = JSON.stringify($listener);
		console.log($listener);
		localStorage.setItem('sm_listener-' + listener_page_id, $listener);
	}
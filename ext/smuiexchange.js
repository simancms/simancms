var sm_ls_listener_timer;
var sm_ls_listeners=[];

function sm_ls_listen()
	{
		for (var listenerindex in sm_ls_listeners)
			{
				var sm_ls_listener_page_id=sm_ls_listeners[listenerindex].id;
				var sm_ls_listener_keys=sm_ls_listeners[listenerindex].keys;
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
								$element = document.getElementById(key);
								if ($element)
									{
										$element.value = $listener[key];
									}
							}
					}
			}
		sm_ls_listener_timer=setTimeout(sm_ls_listen, 100);
	}

function sm_ls_add_listen_key(listener_id, key)
	{
		var index=sm_ls_listener_index(listener_id);
		if (index)
			{
				sm_ls_listeners[index].keys[key] = true;
			}
	}

function sm_ls_listener_index(listener_id)
	{
		for (var index in sm_ls_listeners)
			{
				if (sm_ls_listeners[index].id == listener_id)
					return index;
			}
		return false;
	}

function sm_ls_init_listener(listener_id)
	{
		var listenerinfo={"id":listener_id, "keys":[]}
		sm_ls_listeners.push(listenerinfo);
		clearTimeout(sm_ls_listener_timer);
		sm_ls_listener_timer=setTimeout(sm_ls_listen, 0);
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
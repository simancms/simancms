<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2015-06-18
	//==============================================================================


	if (!class_exists('TGooglemapLoader'))
		{
			class TGooglemapLoader
				{
					var $centerXY;
					var $markers;
					var $type;
					var $usegeocoder;
					var $canvas;
					var $zoom;
					var $suffix;
					var $usenumbermarkers;
					var $numbermarkerssize;
					var $numbermarkersanchor;
					var $numbermarkersfileprefix;
					var $savegeoposition;
					var $savegeopositionaddr;
					var $useroaddirections;
					var $roaddirectionsfromid;
					var $roaddirectionstoid;
					var $roaddirectionsout;

					function TGooglemapLoader($initcanv = 'map_canvas', $initzoom = '13', $inittype = 'ROADMAP')
						{
							$this->type = $inittype;
							$this->canvas = $initcanv;
							$this->usegeocoder = 0;
							$this->savegeoposition = 0;
							$this->zoom = $initzoom;
							$this->usenumbermarkers = 0;
							$this->useroaddirections = 0;
							$this->suffix = '';
						}

					function SetRoadDirections($useRoadDir = 1, $fromId = 'from', $toId = 'to')
						{
							$this->useroaddirections = $useRoadDir;
							$this->roaddirectionsfromid = $fromId;
							$this->roaddirectionstoid = $toId;
						}

					function SetRoadDirectionsOut($idOutTo)
						{
							$this->roaddirectionsout = $idOutTo;
						}

					function SetNumberMarkers($useNumbers = 1, $defImageFilePrefix = 'marker', $defSize = '20, 34', $defAnchor = '9, 0')
						{
							$this->usenumbermarkers = $useNumbers;
							$this->numbermarkerssize = $defSize;
							$this->numbermarkersfileprefix = $defImageFilePrefix;
							$this->numbermarkersanchor = $defAnchor;
						}

					function SetSaveGeoPosition($ajaxurl, $address)
						{
							$this->savegeoposition = $ajaxurl;
							$this->savegeopositionaddr = $address;
						}

					function SetCenter($lat, $lng)
						{
							$this->centerXY = $lat.', '.$lng;
						}

					function SetCenterMarkers()
						{
							$maxlat = $this->markers[0]['lat'];
							$minlat = $this->markers[0]['lat'];
							$maxlng = $this->markers[0]['lng'];
							$minlng = $this->markers[0]['lng'];
							for ($i = 0; $i < count($this->markers); $i++)
								{
									if ($this->markers[$i]['lat'] > $maxlat)
										$maxlat = $this->markers[$i]['lat'];
									if ($this->markers[$i]['lng'] > $maxlng)
										$maxlng = $this->markers[$i]['lng'];
									if ($this->markers[$i]['lat'] < $minlat)
										$minlat = $this->markers[$i]['lat'];
									if ($this->markers[$i]['lng'] < $minlng)
										$minlng = $this->markers[$i]['lng'];
								}
							//$lat=$minlat+($maxlat-$minlat)/2;
							//$lng=$minlng+($maxlng-$minlng)/2;
							$lat = ($maxlat + $minlat) / 2;
							$lng = ($maxlng + $minlng) / 2;
							$this->SetCenter($lat, $lng);
						}

					function SetZoomMarkers()
						{
							$maxlat = $this->markers[0]['lat'];
							$minlat = $this->markers[0]['lat'];
							$maxlng = $this->markers[0]['lng'];
							$minlng = $this->markers[0]['lng'];
							for ($i = 0; $i < count($this->markers); $i++)
								{
									if ($this->markers[$i]['lat'] > $maxlat)
										$maxlat = $this->markers[$i]['lat'];
									if ($this->markers[$i]['lng'] > $maxlng)
										$maxlng = $this->markers[$i]['lng'];
									if ($this->markers[$i]['lat'] < $minlat)
										$minlat = $this->markers[$i]['lat'];
									if ($this->markers[$i]['lng'] < $minlng)
										$minlng = $this->markers[$i]['lng'];
								}
							$miles = (3958.75 * acos(sin($minlat / 57.2958) * sin($maxlat / 57.2958) + cos($minlat / 57.2958) * cos($maxlat / 57.2958) * cos($maxlng / 57.2958 - $minlng / 57.2958)));
							if ($miles < 0.2) $this->SetZoom(16);
							elseif ($miles < 0.5) $this->SetZoom(15);
							elseif ($miles < 1) $this->SetZoom(14);
							elseif ($miles < 2) $this->SetZoom(13);
							elseif ($miles < 3) $this->SetZoom(12);
							elseif ($miles < 7) $this->SetZoom(11);
							elseif ($miles < 15) $this->SetZoom(10);
							else $this->SetZoom(9);
						}

					function SetZoom($initzoom)
						{
							$this->zoom = $initzoom;
						}

					function SetGeocoder($use = 1)
						{
							$this->usegeocoder = $use;
						}

					function AddMarker($lat, $lng)
						{
							$i = count($this->markers);
							$this->markers[$i]['lat'] = $lat;
							$this->markers[$i]['lng'] = $lng;
						}

					function AddNumMarker($lat, $lng, $number)
						{
							$this->AddMarker($lat, $lng);
							$this->markers[count($this->markers) - 1]['number'] = $number;
						}

					function GenerateCode($outputtostr = false)
						{
							global $special;
							$special['body_onload'] .= 'GMInitialise'.$this->suffix.'();';
							$special['use_googlemap'] = 1;
							$s = '
				<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key='.sm_settings('googlemap_api_key').'" type="text/javascript"></script>
				<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
				<script type="text/javascript">
					var map'.$this->suffix.';';
							if ($this->useroaddirections == 1)
								$s .= "
					 var directionDisplay".$this->suffix.";
					";
							if ($this->usegeocoder == 1)
								$s .= "
					var geocoder".$this->suffix.";";
							if (!empty($this->savegeopositionaddr))
								$s .= "
					var address".$this->suffix."=\"".addslashes($this->savegeopositionaddr)."\";";
							$s .= "
					function GMInitialise".$this->suffix."()
						{
							var latlng = new google.maps.LatLng(".$this->centerXY.");
							var myOptions = {
								zoom: ".$this->zoom.",
								center: latlng,
								mapTypeId: google.maps.MapTypeId.ROADMAP
							};";
							if ($this->usegeocoder == 1)
								$s .= "
							geocoder".$this->suffix." = new google.maps.Geocoder();";
							$s .= "
							map".$this->suffix." = new google.maps.Map(document.getElementById('".$this->canvas."'), myOptions);";
							if ($this->usenumbermarkers == 1)
								$s .= "
							var iconSize = new google.maps.Size(".$this->numbermarkerssize.");
							var iconPosition = new google.maps.Point(0, 0);
							var iconHotSpotOffset = new google.maps.Point(".$this->numbermarkersanchor.");";
							for ($i = 0; $i < count($this->markers); $i++)
								{
									//var markerImage = new google.maps.MarkerImage(iconImageUrl, iconSize, iconPosition, iconHotSpotOffset);
									if ($this->usenumbermarkers == 1)
										$s .= "
							var markerImage".$i." = new google.maps.MarkerImage('http://".sm_settings('resource_url')."themes/default/markers/".$this->numbermarkersfileprefix.((empty($this->markers[$i]['number'])) ? $i + 1 : $this->markers[$i]['number']).".png', iconSize, iconPosition, iconHotSpotOffset);";
									$s .= "
							var markerlatlng".$i." = new google.maps.LatLng(".$this->markers[$i]['lat'].", ".$this->markers[$i]['lng'].");
							marker".$i." = new google.maps.Marker({
									      position: markerlatlng".$i.",
										  visible: true,";
									if ($this->usenumbermarkers == 1)
										$s .= "
										  icon: markerImage".$i.",";
									$s .= "
										  map: map".$this->suffix."
									    });";
								}
							if ($this->useroaddirections == 1)
								{
									$s .= "
							directionService".$this->suffix." = new google.maps.DirectionsService();
							directionDisplay".$this->suffix." = new google.maps.DirectionsRenderer({ map: map".$this->suffix." });
						";
									if (!empty($this->roaddirectionsout))
										$s .= "
							directionDisplay".$this->suffix.".setPanel(document.getElementById('".$this->roaddirectionsout."'));
							";
								}
							if (!empty($this->savegeoposition))
								{
									$s .= "
							geocoder.geocode( { 'address': address}, function(results, status)
										  	{
										        if (status == google.maps.GeocoderStatus.OK) 
													{
												      map".$this->suffix.".setCenter(results[0].geometry.location);
													    var tmplatlng = results[0].geometry.location;
													    markerAdmin = new google.maps.Marker({
															      position: tmplatlng,
																  visible: true,
																  map: map".$this->suffix." 
															    });
													  	var x = [
															    	tmplatlng.lat(),
																	tmplatlng.lng()
																].join(', ');
													  map".$this->suffix.".setZoom(15);
													  $.ajax({
														  type:'get',
														  url: '".$this->savegeoposition."',
														  data: ({lat: tmplatlng.lat(), lng: tmplatlng.lng()}),
														  success: function(msg){
																 //alert( msg );
															   }
														});
											        } 
												else 
													{
											          //alert('Geocode was not successful for the following reason: ' + status);
											        }
									       });";
								}

							$s .= "
						}
					";
							if ($this->useroaddirections == 1)
								$s .= "
					function route".$this->suffix."()
						{
						  var request =
							{
								origin: document.getElementById('".$this->roaddirectionsfromid."').value,
								destination: document.getElementById('".$this->roaddirectionstoid."').value,
								travelMode: google.maps.DirectionsTravelMode.DRIVING
							}

						  // Make the directions request
						  directionService".$this->suffix.".route(request, function(result, status) {
							if (status == google.maps.DirectionsStatus.OK)
								{
									directionDisplay".$this->suffix.".setDirections(result);
								}
							else
								{
									alert('Directions query failed');
								}
						  });
						}
				";
							$s .= "
				</script>";
							if ($outputtostr) return $s;
							sm_html_headend($s);
						}
				//End of class definiotion
				}
		}

?>
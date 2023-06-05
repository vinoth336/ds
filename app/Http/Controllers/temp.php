<?php //$sqlCond = $this->generateCondtion($_POST);

		$sqlCond = 'WHERE 1 '.$this->getAreaCondition($_POST['search_city']);
		$status = $this->getRoomAvailStatusCondition($_POST['start'],$_POST['end']);
		$this->data = array_merge($this->data,$_POST);

		$subQueryCond = '';
		$this->data['room_count'] = $this->getRoomNumbers($_POST);
		$this->data['member_count'] = $this->getMemberCounts($_POST);
		$subQueryCond .= ' WHERE `rm`.`adults_count` >= "'.$this->data['member_count'].'" AND `rm`.`room_count` >= "'.$this->data['room_count'].'"';
		$subQueryCond .= ' AND "Available" = '.$status;
		$mainConnection = "`h`.`hotel_id`";
		$countquery = $this->getCountQuery($subQueryCond,$mainConnection);
		$minquery = $this->getMinValQuery($subQueryCond,$mainConnection);
		$query = "SELECT `h`.*,".$countquery.",".$minquery." FROM `abserve_hotels` as `h`";

		//Default Condition
		$aReslts = $this->model->getData($query,$sqlCond);

		foreach ($aReslts as $key => $aValue) {
			if($aValue->avail_room_count == 0)
				unset($aReslts[$key]);
		}

		$this->data['aHotelRooms'] = !empty($aReslts) ? $aReslts : '';
		$this->data['img_path'] = \URL::to('').'/';

		$this->data['pageTitle'] 			= 'Hotels';
		$this->data['pageNote'] 			= 'Welcome To our Hotels Booking page';
		$this->data['breadcrumb'] 			= 'inactive';	
		$this->data['pageMetakey'] 			= 'Niresh' ;
		$this->data['pageMetadesc'] 		= 'Hello' ;
		$this->data['hotel_list_page'] 		= 'hotelroom.list';
		$this->data['pages'] 				= 'hotelroom.results';
		$this->data['search_action_url'] 	= '';
		$this->data['search_form'] 			= 'hotel.search_form';

		$this->data['search_title'] = count($aReslts) . ' hotel(s) in '.$this->data['city_name'].' on '.$_POST['start'].' - '.$_POST['end'].' for '.$this->data['member_count'].' adult';
		$page = 'layouts.'.CNF_THEME.'.index';
		return view($page, $this->data);
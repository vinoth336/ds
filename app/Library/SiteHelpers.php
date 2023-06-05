<?php
use App\Models\Restaurant;
use App\Models\Fooditems;
use App\Models\Foodcategories;
use App\Models\Customerorder;
use App\Models\Usercart;
class SiteHelpers
{
	public static function menus( $position ='top',$active = '1')
	{
		$data = array();  
		$menu = self::nestedMenu(0,$position ,$active);		
		foreach ($menu as $row) 
		{
			$child_level = array();
			$p = json_decode($row->access_data,true);
			
			
			if($row->allow_guest == 1)
			{
				$is_allow = 1;
			} else {
				$is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
			}
			if($is_allow ==1) 
			{
				
				$menus2 = self::nestedMenu($row->menu_id , $position ,$active );
				if(count($menus2) > 0 )
				{	 
					$level2 = array();							 
					foreach ($menus2 as $row2) 
					{
						$p = json_decode($row2->access_data,true);
						if($row2->allow_guest == 1)
						{
							$is_allow = 1;
						} else {
							$is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
						}						
									
						if($is_allow ==1)  
						{						
					
							$menu2 = array(
									'menu_id'		=> $row2->menu_id,
									'module'		=> $row2->module,
									'menu_type'		=> $row2->menu_type,
									'url'			=> $row2->url,
									'menu_name'		=> $row2->menu_name,
									'menu_lang'		=> json_decode($row2->menu_lang,true),
									'menu_icons'	=> $row2->menu_icons,
									'childs'		=> array()
								);	
												
							$menus3 = self::nestedMenu($row2->menu_id , $position , $active);
							if(count($menus3) > 0 )
							{
								$child_level_3 = array();
								foreach ($menus3 as $row3) 
								{
									$p = json_decode($row3->access_data,true);
									if($row3->allow_guest == 1)
									{
										$is_allow = 1;
									} else {
										$is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
									}										
									if($is_allow ==1)  
									{								
										$menu3 = array(
												'menu_id'		=> $row3->menu_id,
												'module'		=> $row3->module,
												'menu_type'		=> $row3->menu_type,
												'url'			=> $row3->url,												
												'menu_name'		=> $row3->menu_name,
												'menu_lang'		=> json_decode($row3->menu_lang,true),
												'menu_icons'	=> $row3->menu_icons,
												'childs'		=> array()
											);	
										$child_level_3[] = $menu3;	
									}					
								}
								$menu2['childs'] = $child_level_3;
							}
							$level2[] = $menu2 ;
						}	
					
					}
					$child_level = $level2;
						
				}
				
				$level = array(
						'menu_id'		=> $row->menu_id,
						'module'		=> $row->module,
						'menu_type'		=> $row->menu_type,
						'url'			=> $row->url,						
						'menu_name'		=> $row->menu_name,
						'menu_lang'		=> json_decode($row->menu_lang,true),
						'menu_icons'	=> $row->menu_icons,
						'childs'		=> $child_level
					);			
				
				$data[] = $level;	
			}	
				
		}	
		//echo '<pre>';print_r($data); echo '</pre>'; exit;
		return $data;
	}
	
	public static function nestedMenu($parent=0,$position ='top',$active = '1')
	{
		$group_sql = " AND tb_menu_access.group_id ='".Session::get('gid')."' ";
		$active 	=  ($active =='all' ? "" : "AND active ='1' ");
		$Q = DB::select("
		SELECT 
			tb_menu.*
		FROM tb_menu WHERE parent_id ='". $parent ."' ".$active." AND position ='{$position}'
		GROUP BY tb_menu.menu_id ORDER BY ordering			
		");		
		//print_r($Q);exit;			
		return $Q;					
	}
	
	public static function CF_encode_json($arr) {
	  //$arr = Array ( [table_db] => 'tb_users', [primary_key] => 'id', [sql_select] => 'SELECT tb_users.* FROM tb_users', [sql_where] => 'WHERE tb_users.id IS NOT NULL AND tb_users.group_id = 4', [sql_group] => [forms] => Array ( [0] => Array ( [field] => 'id', [alias] => 'tb_users', [language] => Array ( ) [label] => 'Id', [form_group] => , [required] => 0 ,[view] => 1, [add] => 1, [size] => 0, [edit] => 1, [search] => 0, [sortlist] => 0, [limited] =>, [option] => Array ( [opt_type] =>, [lookup_query] =>, [lookup_table] =>,[lookup_key] =>, [lookup_value] =>, [is_dependency] =>, [select_multiple] => 0 ,[image_multiple] => 0, [lookup_dependency_key] =>, [path_to_upload] =>, [resize_width] =>, [resize_height] =>, [upload_type] =>, [tooltip] =>, [attribute] =>, [extend_class] => ) ) [1] => Array ( [field] => group_id [alias] => tb_users [language] => Array ( ) [label] => Group Id [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 1 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [2] => Array ( [field] => username [alias] => tb_users [language] => Array ( ) [label] => Username [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 2 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [3] => Array ( [field] => password [alias] => tb_users [language] => Array ( ) [label] => Password [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 3 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [4] => Array ( [field] => email [alias] => tb_users [language] => Array ( ) [label] => Email [form_group] => [required] => 0 [view] => 1 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 1 [sortlist] => 4 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [5] => Array ( [field] => first_name [alias] => tb_users [language] => Array ( ) [label] => First Name [form_group] => [required] => 0 [view] => 1 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 1 [sortlist] => 5 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [6] => Array ( [field] => last_name [alias] => tb_users [language] => Array ( ) [label] => Last Name [form_group] => [required] => 0 [view] => 1 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 6 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [7] => Array ( [field] => avatar [alias] => tb_users [language] => Array ( ) [label] => Avatar [form_group] => [required] => 0 [view] => 1 [type] => file [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 7 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => image [tooltip] => [attribute] => [extend_class] => ) ) [8] => Array ( [field] => res_name [alias] => tb_users [language] => Array ( ) [label] => Res Name [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 7 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [9] => Array ( [field] => active [alias] => tb_users [language] => Array ( ) [label] => Active [form_group] => [required] => 0 [view] => 1 [type] => radio [add] => 1 [size] => 0 [edit] => 1 [search] => 1 [sortlist] => 8 [limited] => [option] => Array ( [opt_type] => datalist [lookup_query] => 1:Active|0:Inactive|2:Block [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [10] => Array ( [field] => login_attempt [alias] => tb_users [language] => Array ( ) [label] => Login Attempt [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 9 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [11] => Array ( [field] => last_login [alias] => tb_users [language] => Array ( ) [label] => Last Login [form_group] => [required] => 0 [view] => 0 [type] => text_datetime [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 10 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [12] => Array ( [field] => created_at [alias] => tb_users [language] => Array ( ) [label] => Created At [form_group] => [required] => 0 [view] => 0 [type] => text_datetime [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 11 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [13] => Array ( [field] => updated_at [alias] => tb_users [language] => Array ( ) [label] => Updated At [form_group] => [required] => 0 [view] => 0 [type] => text_datetime [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 12 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [14] => Array ( [field] => reminder [alias] => tb_users [language] => Array ( ) [label] => Reminder [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 13 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [15] => Array ( [field] => activation [alias] => tb_users [language] => Array ( ) [label] => Activation [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 14 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [16] => Array ( [field] => remember_token [alias] => tb_users [language] => Array ( ) [label] => Remember Token [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 15 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [17] => Array ( [field] => last_activity [alias] => tb_users [language] => Array ( ) [label] => Last Activity [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 16 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [18] => Array ( [field] => phone_number [alias] => tb_users [language] => Array ( ) [label] => Phone Number [form_group] => [required] => 0 [view] => 1 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 1 [sortlist] => 17 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [19] => Array ( [field] => phone_verified [alias] => tb_users [language] => Array ( ) [label] => Phone Verified [form_group] => [required] => 0 [view] => 1 [type] => radio [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 18 [limited] => [option] => Array ( [opt_type] => datalist [lookup_query] => 1:Yes|2:No [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [20] => Array ( [field] => phone_otp [alias] => tb_users [language] => Array ( ) [label] => Phone Otp [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 19 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [21] => Array ( [field] => address [alias] => tb_users [language] => Array ( ) [label] => Address [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 19 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [22] => Array ( [field] => city [alias] => tb_users [language] => Array ( ) [label] => City [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 20 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [23] => Array ( [field] => state [alias] => tb_users [language] => Array ( ) [label] => State [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 21 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [24] => Array ( [field] => phone_code [alias] => tb_users [language] => Array ( ) [label] => Phone Code [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 21 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [25] => Array ( [field] => zip_code [alias] => tb_users [language] => Array ( ) [label] => Zip Code [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 22 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [26] => Array ( [field] => lat [alias] => tb_users [language] => Array ( ) [label] => Lat [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 23 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [27] => Array ( [field] => country [alias] => tb_users [language] => Array ( ) [label] => Country [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 23 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [28] => Array ( [field] => entry_by [alias] => tb_users [language] => Array ( ) [label] => Entry By [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 24 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [29] => Array ( [field] => lang [alias] => tb_users [language] => Array ( ) [label] => Lang [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 24 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [30] => Array ( [field] => mobile_token [alias] => tb_users [language] => Array ( ) [label] => Mobile Token [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 25 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [31] => Array ( [field] => commission [alias] => tb_users [language] => Array ( ) [label] => Commission [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 26 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [32] => Array ( [field] => device [alias] => tb_users [language] => Array ( ) [label] => Device [form_group] => [required] => 0 [view] => 0 [type] => text [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 27 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) [33] => Array ( [field] => ext_acc_id [alias] => tb_users [language] => Array ( ) [label] => Ext Acc Id [form_group] => [required] => 0 [view] => 1 [type] => textarea [add] => 1 [size] => 0 [edit] => 1 [search] => 0 [sortlist] => 33 [limited] => [option] => Array ( [opt_type] => [lookup_query] => [lookup_table] => [lookup_key] => [lookup_value] => [is_dependency] => [select_multiple] => 0 [image_multiple] => 0 [lookup_dependency_key] => [path_to_upload] => [resize_width] => [resize_height] => [upload_type] => [tooltip] => [attribute] => [extend_class] => ) ) ) [grid] => Array ( [0] => Array ( [field] => id [alias] => tb_users [language] => Array ( ) [label] => Customer Id [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 1 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [1] => Array ( [field] => group_id [alias] => tb_users [language] => Array ( ) [label] => Group Id [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 2 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [2] => Array ( [field] => first_name [alias] => tb_users [language] => Array ( ) [label] => First Name [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 3 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [3] => Array ( [field] => last_name [alias] => tb_users [language] => Array ( ) [label] => Last Name [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 4 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [4] => Array ( [field] => email [alias] => tb_users [language] => Array ( ) [label] => Email [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 5 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [5] => Array ( [field] => username [alias] => tb_users [language] => Array ( ) [label] => Username [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 6 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [6] => Array ( [field] => password [alias] => tb_users [language] => Array ( ) [label] => Password [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 7 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [7] => Array ( [field] => res_name [alias] => tb_users [language] => Array ( ) [label] => Res Name [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 8 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [8] => Array ( [field] => avatar [alias] => tb_users [language] => Array ( ) [label] => Avatar [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 9 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [9] => Array ( [field] => login_attempt [alias] => tb_users [language] => Array ( ) [label] => Login Attempt [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 10 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [10] => Array ( [field] => last_login [alias] => tb_users [language] => Array ( ) [label] => Last Login [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 11 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [11] => Array ( [field] => created_at [alias] => tb_users [language] => Array ( ) [label] => Created At [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 12 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [12] => Array ( [field] => updated_at [alias] => tb_users [language] => Array ( ) [label] => Updated At [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 13 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [13] => Array ( [field] => reminder [alias] => tb_users [language] => Array ( ) [label] => Reminder [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 14 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [14] => Array ( [field] => activation [alias] => tb_users [language] => Array ( ) [label] => Activation [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 15 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [15] => Array ( [field] => remember_token [alias] => tb_users [language] => Array ( ) [label] => Remember Token [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 16 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [16] => Array ( [field] => last_activity [alias] => tb_users [language] => Array ( ) [label] => Last Activity [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 17 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [17] => Array ( [field] => address [alias] => tb_users [language] => Array ( ) [label] => Address [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 18 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [18] => Array ( [field] => phone_number [alias] => tb_users [language] => Array ( ) [label] => Phone Number [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 19 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [19] => Array ( [field] => phone_otp [alias] => tb_users [language] => Array ( ) [label] => Phone Otp [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 20 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [20] => Array ( [field] => phone_verified [alias] => tb_users [language] => Array ( ) [label] => Phone Verified [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 21 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [21] => Array ( [field] => city [alias] => tb_users [language] => Array ( ) [label] => City [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 22 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [22] => Array ( [field] => phone_code [alias] => tb_users [language] => Array ( ) [label] => Phone Code [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 23 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [23] => Array ( [field] => state [alias] => tb_users [language] => Array ( ) [label] => State [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 24 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [24] => Array ( [field] => zip_code [alias] => tb_users [language] => Array ( ) [label] => Zip Code [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 25 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [25] => Array ( [field] => lat [alias] => tb_users [language] => Array ( ) [label] => Lat [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 26 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [26] => Array ( [field] => country [alias] => tb_users [language] => Array ( ) [label] => Country [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 27 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [27] => Array ( [field] => lang [alias] => tb_users [language] => Array ( ) [label] => Lang [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 28 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [28] => Array ( [field] => entry_by [alias] => tb_users [language] => Array ( ) [label] => Entry By [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 29 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [29] => Array ( [field] => mobile_token [alias] => tb_users [language] => Array ( ) [label] => Mobile Token [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 30 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [30] => Array ( [field] => commission [alias] => tb_users [language] => Array ( ) [label] => Commission [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 31 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [31] => Array ( [field] => device [alias] => tb_users [language] => Array ( ) [label] => Device [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 32 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [32] => Array ( [field] => ext_acc_id [alias] => tb_users [language] => Array ( ) [label] => Ext Acc Id [view] => 0 [detail] => 0 [sortable] => 0 [search] => 1 [download] => 0 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 33 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) ) [33] => Array ( [field] => active [alias] => tb_users [language] => Array ( ) [label] => Active [view] => 1 [detail] => 1 [sortable] => 1 [search] => 1 [download] => 1 [frozen] => 1 [limited] => [width] => 100 [align] => left [sortlist] => 34 [conn] => Array ( [valid] => 0 [db] => [key] => [display] => ) [attribute] => Array ( [hyperlink] => Array ( [active] => 1 [link] => [target] => [html] => ) [image] => Array ( [active] => 0 [path] => [size_x] => [size_y] => [html] => ) ) [34] => Array ([field] => cod_status  [alias] => tb_users [language] => Array ( ) [label] => COD Status [form_group] => [required] => 0 [view] => 1 [type] => radio [add] => 1 [size] => 0 [edit] => 1 [search] => 1 [limited] => [sortlist] => 35 [option] => array ([opt_type] => datalist [lookup_query] => 1:Active|0:Inactive [lookup_table] => [lookup_key] => [lookup_value] =>, [is_dependency] =>, [select_multiple] => 0, [image_multiple] => 0, [lookup_dependency_key] =>, [path_to_upload] =>, [resize_width] =>, [resize_height] =>, [upload_type] =>, [tooltip] =>, [attribute] =>, [extend_class] => ) )) ) );
	  //print "<pre>";
	  //print_r($arr);
	  //print "</pre>";
	  
	  $str = json_encode( $arr );
	  $enc = base64_encode($str );
	  $enc = strtr( $enc, 'poligamI123456', '123456poligamI');
	  //print $enc;
	  //exit();
	  return $enc;
	}
	
	public static function CF_decode_json($str) {
	  $dec = strtr( $str , '123456poligamI', 'poligamI123456');
	  $dec = base64_decode( $dec );
	  $obj = json_decode( $dec ,true);
	  return $obj;
	}	
	
	
	public static function columnTable( $table )
	{	  
        $columns = array();
	    foreach(DB::select("SHOW COLUMNS FROM $table") as $column)
        {
           //print_r($column);
		    $columns[] = $column->Field;
        }
	  

        return $columns;
	}	
	
	public static function encryptID($id,$decript=false,$pass='',$separator='-', & $data=array()) {
		$pass = $pass?$pass:Config::get('app.key');
		$pass2 = Config::get('app.url');;
		$bignum = 200000000;
		$multi1 = 500;
		$multi2 = 50;
		$saltnum = 10000000;
		if($decript==false){
			$strA = self::alphaid(($bignum+($id*$multi1)),0,0,$pass);
			$strB = self::alphaid(($saltnum+($id*$multi2)),0,0,$pass2);
			$out = $strA.$separator.$strB;
		} else {
			$pid = explode($separator,$id);
			
			
		//    trace($pid);
			$idA = (self::alphaid($pid[0],1,0,$pass)-$bignum)/$multi1;
			$idB = (self::alphaid($pid[1],1,0,$pass2)-$saltnum)/$multi2;
			$data['id A'] = $idA;
			$data['id B'] = $idB;
			$out = ($idA==$idB)?$idA:false;
		}
		return $out;
	}
	
public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
{
    $index = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
    if ($passKey !== null) {
        // Although this function's purpose is to just make the
        // ID short - and not so much secure,
        // with this patch by Simon Franz (http://blog.snaky.org/)
        // you can optionally supply a password to make it harder
        // to calculate the corresponding numeric ID
 
        for ($n = 0; $n<strlen($index); $n++) {
            $i[] = substr( $index,$n ,1);
        }
 
        $passhash = hash('sha256',$passKey);
        $passhash = (strlen($passhash) < strlen($index))
            ? hash('sha512',$passKey)
            : $passhash;
 
        for ($n=0; $n < strlen($index); $n++) {
            $p[] =    substr($passhash, $n ,1);
        }
 
        array_multisort($p,    SORT_DESC, $i);
        $index = implode($i);
    }
 
    $base    = strlen($index);
 
    if ($to_num) {
        // Digital number    <<--    alphabet letter code
        $in    = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out     = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }
 
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
        // Digital number    -->>    alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }
 
        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a     = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in    = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }
 
    return $out;
}	
		
	
	public static function toForm($forms,$layout)
	{
		$f = '';
	//	echo '<pre>'; print_r($forms);echo '</pre>';
		//usort($forms,"_sort"); 
		$block = $layout['column'];
		$format = $layout['format'];
		$display = $layout['display'];
		$title = explode(",",$layout['title']);
		
		if($format =='tab')
		{
			$f .='<ul class="nav nav-tabs">';
			
			for($i=0;$i<$block;$i++)
			{
				$active = ($i==0 ? 'active' : '');
				$tit = (isset($title[$i]) ? $title[$i] : 'None');	
				$f .= '<li class="'.$active.'"><a href="#'.trim(str_replace(" ","",$tit)).'" data-toggle="tab">'.$tit.'</a></li>
				';	
			}
			$f .= '</ul>';		
		}

		if($format =='tab') $f .= '<div class="tab-content">';
		for($i=0;$i<$block;$i++)
		{		
			if($block == 4) {
				$class = 'col-md-3';
			}  elseif( $block ==3 ) {
				$class = 'col-md-4';
			}  elseif( $block ==2 ) {
				$class = 'col-md-6';
			} else {
				$class = 'col-md-12';
			}	
			
			$tit = (isset($title[$i]) ? $title[$i] : 'None');	
			// Grid format 
			if($format == 'grid')
			{
				$f .= '<div class="'.$class.'">
						<fieldset><legend> '.$tit.'</legend>
				';
			} else {
				$active = ($i==0 ? 'active' : '');
				$f .= '<div class="tab-pane m-t '.$active.'" id="'.trim(str_replace(" ","",$tit)).'"> 
				';			
			}	
			
			
			
			$group = array(); 
			
			foreach($forms as $form)
			{
				$tooltip =''; $required = ($form['required'] != '0' ? '<span class="asterix"> * </span>' : '');
				if($form['view'] != 0)
				{
					if($form['field'] !='entry_by')
					{
						if(isset($form['option']['tooltip']) && $form['option']['tooltip'] !='') 	
						$tooltip = '<a href="#" data-toggle="tooltip" placement="left" class="tips" title="'. $form['option']['tooltip'] .'"><i class="icon-question2"></i></a>';	
						$hidethis = ""; if($form['type'] =='hidden') $hidethis ='hidethis';
						$inhide = ''; if(count($group) >1) $inhide ='inhide';
						//$ebutton = ($form['type'] =='radio' || $form['option'] =='checkbox') ? "ebutton-radio" : "";
						$show = '';
						if($form['type'] =='hidden') $show = 'style="display:none;"';	
						if(isset($form['limited']) && $form['limited'] !='')
						{
							$limited_start = 
							'
				<?php 
				$limited = isset($fields[\''.$form['field'].'\'][\'limited\']) ? $fields[\''.$form['field'].'\'][\'limited\'] :\'\';
				if(SiteHelpers::filterColumn($limited )) { ?>
							';
							$limited_end = '
				<?php } ?>'; 
						} else {
							$limited_start = '';
							$limited_end = ''; 
						}

						if($form['form_group'] == $i)
						{	
							if($display == 'horizontal')
							{			
								$f .= $limited_start;
								$f .= '					
								  <div class="form-group '.$hidethis.' '.$inhide.'" '.$show .'>
									<label for="'.$form['label'].'" class=" control-label col-md-4 text-left"> '.$form['label'].' '.$required.'</label>
									<div class="col-md-6">
									  '.self::formShow($form['type'],$form['field'],$form['required'],$form['option']).' 
									 </div> 
									 <div class="col-md-2">
									 	'.$tooltip.'
									 </div>
								  </div> '; 
								  $f .= $limited_end;
							} else {
								$f .= $limited_start;
								$f .= '					
								  <div class="form-group '.$hidethis.' '.$inhide.'" '.$show .'>
									<label for="ipt" class=" control-label "> '.$form['label'].'  '.$required.' '.$tooltip.' </label>									
									  '.self::formShow($form['type'],$form['field'],$form['required'],$form['option']).' 						
								  </div> '; 
								 $f .= $limited_end;  							
							
							}	  
						}	  
					}	  
					
				}					
			}
			if($format == 'grid') $f .='</fieldset>';
			$f .= '
			</div>
			
			';
		} 		
		
		//echo '<pre>'; print_r($f);echo '</pre>'; exit;
		return $f;
	
	}
	public static function gridClass( $layout )
	{
		$column = $layout['column'];
		$format = $layout['format'];

		if($block == 4) {
			$class = 'col-md-3';
		}  elseif( $block ==3 ) {
			$class = 'col-md-4';
		}  elseif( $block ==2 ) {
			$class = 'col-md-6';
		} else {
			$class = 'col-md-12';
		}	
				
		
		if(format == 'tab')
		{
			$tag_open = '<div class="col-md-">';
			$tag_close = '<div class="col-md-">';
			
		}  elseif($layout['format'] == 'accordion'){
		
		} else {					
			$tag_open = '<div class="col-md-">';
			$tag_close = '</div>';
		}		
		

		return $class;	
	}
	
	
	public static function formShow( $type , $field , $required ,$option = array()){
		//print_r($option);
		$mandatory = '';$attribute = ''; $extend_class ='';
		if(isset($option['attribute']) && $option['attribute'] !='') {
				$attribute = $option['attribute']; }
		if(isset($option['extend_class']) && $option['extend_class'] !='') {
			$extend_class = $option['extend_class']; 
		}				
				
		$show = '';
		if($type =='hidden') $show = 'style="display:none;"';	
				
		if($required =='required') {
			$mandatory = "'required'=>'true'";
		} else if($required =='email') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'email' ";
		} else if($required =='url') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'url' ";
		} else if($required =='date') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'dateIso' ";
		} else if($required =='numeric') {
			$mandatory = "'required'=>'true', 'parsley-type'=>'number' ";
		} else {
			$mandatory = '';
		}		
		
		switch($type)
		{
			default;
				$form = "{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control', 'placeholder'=>'', {$mandatory}  )) !!}";
				break;
				
			case 'textarea';
				if($required !='0') { $mandatory = 'required'; }
				$form = "<textarea name='{$field}' rows='5' id='{$field}' class='form-control {$extend_class}'  
				         {$mandatory} {$attribute} >{{ \$row['{$field}'] }}</textarea>";
				break;

			case 'textarea_editor';
				if($required !='0') { $mandatory = 'required'; }
				$form = "<textarea name='{$field}' rows='5' id='editor' class='form-control editor {$extend_class}'  
						{$mandatory}{$attribute} >{{ \$row['{$field}'] }}</textarea>";
				break;				
				

			case 'text_date';
				$form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control date')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>";
				break;
				
			case 'text_time';
				$form = "
					<div class=\"input-group m-b\" style=\"width:150px !important;\">
						input  type='text' name='{$field}' id='{$field}' value='{{ \$row['{$field}'] }}' 
						{$mandatory}  {$attribute}   class='form-control {$extend_class}'
						data-date-format='yyyy-mm-dd'
						 />
						 <span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
						 </div>
						 ";
				break;				

			case 'text_datetime';
				if($required !='0') { $mandatory = 'required'; }
				$form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>
				";
				break;				

			case 'select';
				if($required !='0') { $mandatory = 'required'; }
				if($option['opt_type'] =='datalist')
				{
					$optList ='';
					$opt = explode("|",$option['lookup_query']);
					for($i=0; $i<count($opt);$i++) 
					{							
						$row =  explode(":",$opt[$i]);
						for($i=0; $i<count($opt);$i++) 
						{					
							
							$row =  explode(":",$opt[$i]);
							$optList .= " '".trim($row[0])."' => '".trim($row[1])."' , ";
							
						}							
					}	
					$form  = "
					<?php \$".$field." = explode(',',\$row['".$field."']);
					";
					$form  .= 
					"\$".$field."_opt = array(".$optList."); ?>
					";	
					
					if(isset($option['select_multiple']) && $option['select_multiple'] ==1)
					{
					 
						$form  .= "<select name='{$field}[]' rows='5' {$mandatory} multiple  class='select2 '  > ";
						$form  .= "
						<?php 
						foreach(\$".$field."_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(in_array(\$key,\$".$field.") ? \" selected='selected' \" : '' ).\">\$val</option>\"; 						
						}						
						?>";
						$form .= "</select>";
					} else {
						
						$form  .= "<select name='{$field}' rows='5' {$mandatory}  class='select2 '  > ";
						$form  .= "
						<?php 
						foreach(\$".$field."_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(\$row['".$field."'] == \$key ? \" selected='selected' \" : '' ).\">\$val</option>\"; 						
						}						
						?>";
						$form .= "</select>";				
					
					}
					
				} else {
					
					if(isset($option['select_multiple']) && $option['select_multiple'] ==1)
					{
						$named ="name='{$field}[]' multiple";
					} else {
						$named ="name='{$field}'";

					}
					$form = "<select ".$named." rows='5' id='{$field}' class='select2 {$extend_class}' {$mandatory} {$attribute} ></select>";


				}
				break;	
				
			case 'file';
				if($required !='0') { $mandatory = 'required'; }

				if(isset($option['image_multiple']) && $option['image_multiple'] ==1)
				{
					$form = '
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles(\''.$field.'\')"><i class="fa fa-plus"></i></a>
					<div class="'.$field.'Upl">	
					 	<input  type=\'file\' name=\''.$field.'[]\'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row[\''.$field.'\'] = explode(",",$row[\''.$field.'\']);
					?>
					@foreach($row[\''.$field.'\'] as $files)
						@if(file_exists(\'.'.$option['path_to_upload'].'\'.$files) && $files !=\'\')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ Url::to(\''.$option['path_to_upload'].'/\'.$files) }}" target="_blank" >{{ $files }}</a> 
							<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="curr'.$field.'[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					';

				} else {
					$form = "<input  type='file' name='{$field}' id='{$field}' ";
					$form .= "@if(\$row['$field'] =='') class='required' @endif "; 
					$form .= "style='width:150px !important;' {$attribute} />
					 	<div >
						{!! SiteHelpers::showUploadedFile(\$row['{$field}'],'$option[path_to_upload]') !!}
						
						</div>					
					";

				}
				break;						
				
			case 'radio';
				if($required !='0') { $mandatory = 'required'; }
				$opt = explode("|",$option['lookup_query']);
				$form = '';
				for($i=0; $i<count($opt);$i++) 
				{
					$checked = '';
					$row =  explode(":",$opt[$i]); 
					$form .= "
					<label class='radio radio-inline'>
					<input type='radio' name='{$field}' value ='".ltrim(rtrim($row[0]))."' {$mandatory} {$attribute}";
					$form .= "@if(\$row['".$field."'] == '".ltrim(rtrim($row[0]))."') checked=\"checked\" @endif";
					$form .= " > ".$row[1]." </label>";
				}
				break;
				
			case 'checkbox';
				if($required !='0') { $mandatory = 'required'; }
				$opt = explode("|",$option['lookup_query']);
				$form = "<?php \$".$field." = explode(\",\",\$row['".$field."']); ?>";
				for($i=0; $i<count($opt);$i++) 
				{
					
					$checked = '';
					$row =  explode(":",$opt[$i]);					
					 $form .= "
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='{$field}[]' value ='".ltrim(rtrim($row[0]))."' {$mandatory} {$attribute} class='{$extend_class}' ";
					$form .= "
					@if(in_array('".trim($row[0])."',\$".$field."))checked @endif 
					";
					$form .= " /> ".$row[1]." </label> ";					
				}
				break;				
			
		}
		
		return $form;		
	}
	
	public static function toMasterDetail( $info )
	{

		 if(count($info)>=1)
		 {
		 	$module = ucwords($info['module']);
		 	//$data['masterdetailmodel'] 	= '$this->modelview = new  \App\Models\''.$module.'();';
		 	
		 	$data['masterdetailinfo'] 	= "\$this->data['subgrid']	= (isset(\$this->info['config']['subgrid']) ? \$this->info['config']['subgrid'][0] : array()); ";
		 	$data['masterdetailgrid'] 	= "\$this->data['subgrid'] = \$this->detailview(\$this->modelview ,  \$this->data['subgrid'] ,\$id );";	
		 	$data['masterdetailsave'] 	= "\$this->detailviewsave( \$this->modelview , \$request->all() , \$this->data['subgrid'] , \$id) ;";

		 	$tpl = array();
		 	require_once('../resources/views/abserve/module/template/native/masterdetailform.php');
		 	$data['masterdetailview'] 	= $tpl['masterdetailview'];
		 	$data['masterdetailform'] 	= $tpl['masterdetailform'];
		 	$data['masterdetailjs'] 	= $tpl['masterdetailjs'];
		 	$data['masterdetaildelete']	= $tpl['masterdetaildelete'];
		 	$data['masterdetailmodel'] 	= $tpl['masterdetailmodel'];
		 }
		 return $data;

	}

	public static function filterColumn( $limit )
	{
		if($limit !='')
		{
			$limited = explode(',',$limit);	
			if(in_array( \Session::get('uid'),$limited) )
			{
				return  true;
			} else {
				return false;	
			}
		} else {
			return true;
		}
	}

	public static function toView( $grids )
	{
		$f = '';
		foreach($grids as $grid)
		{
			if(isset($grid['conn']) && is_array($grid['conn']))
			{
				$conn = $grid['conn'];
				//print_r($conn);exit;
			} else {
				$conn = array('valid'=>0,'db'=>'','key'=>'','display'=>'');
			}
			
			if($grid['detail'] =='1')  
			{
				if($grid['attribute']['image']['active'] =='1')
				{	
					$val = "{!! SiteHelpers::showUploadedFile(\$row->".$grid['field'].",'".$grid['attribute']['image']['path']."') !!}";	
				} elseif($conn['valid'] ==1)  {
					$arr = implode(':',$conn);
					$val = "{!! SiteHelpers::gridDisplayView(\$row->".$grid['field'].",'".$grid['field']."','".$arr."') !!}";
				} else {
					$val = "{{ \$row->".$grid['field']." }}"; 
				}

					if(isset($grid['limited']) && $grid['limited'] !='')
					{
						$limited_start = 
						'
			<?php 
			$limited = isset($fields[\''.$grid['field'].'\'][\'limited\']) ? $fields[\''.$grid['field'].'\'][\'limited\'] :\'\';
			if(SiteHelpers::filterColumn($limited )) { ?>
						';
						$limited_end = '
			<?php } ?>'; 
					} else {
						$limited_start = '';
						$limited_end = ''; 
					}

				$f .= $limited_start;
				$f .= "
					<tr>
						<td width='30%' class='label-view text-right'>".$grid['label']."</td>
						<td>".$val." </td>
						
					</tr>
				";
				$f .= $limited_end;
			}
		}
		return $f;
	}
	
	public static  function transForm( $field, $forms = array(),$bulk=false , $value ='')
	{
		$type = ''; 
		$bulk = ($bulk == true ? '[]' : '');
		$mandatory = '';
		foreach($forms as $f)
		{
			if($f['field'] == $field && $f['search'] ==1)
			{
				$type = ($f['type'] !='file' ? $f['type'] : ''); 
				$option = $f['option'];
				$required = $f['required'];
				
				if($required =='required') {
					$mandatory = "data-parsley-required='true'";
				} else if($required =='email') {
					$mandatory = "data-parsley-type'='email' ";
				} else if($required =='date') {
					$mandatory = "data-parsley-required='true'";
				} else if($required =='numeric') {
					$mandatory = "data-parsley-type='number' ";
				} else {
					$mandatory = '';
				}				
			}	
		}
		
		switch($type)
		{
			default;
				$form ='';
				break;
			
			case 'text';			
				$form = "<input  type='text' name='".$field."{$bulk}' class='form-control input-sm' $mandatory value='{$value}'/>";
				break;

			case 'text_date';
				$form = "<input  type='text' name='$field{$bulk}' class='date form-control input-sm' $mandatory value='{$value}'/> ";
				break;

			case 'text_datetime';
				$form = "<input  type='text' name='$field{$bulk}'  class='date form-control input-sm'  $mandatory value='{$value}'/> ";
				break;				

			case 'select';
				
			
				if($option['opt_type'] =='external')
				{
					
					$data = DB::table($option['lookup_table'])->get();
					$opts = '';
					foreach($data as $row):
						$selected = '';
						if($value == $row->$option['lookup_key']) $selected ='selected="selected"';
						$fields = explode("|",$option['lookup_value']);
						//print_r($fields);exit;
						$val = "";
						foreach($fields as $item=>$v)
						{
							if($v !="") $val .= $row->$v." " ;
						}
						$opts .= "<option $selected value='".$row->$option['lookup_key']."' $mandatory > ".$val." </option> ";
					endforeach;
						
				} else {
					$opt = explode("|",$option['lookup_query']);
					$opts = '';
					for($i=0; $i<count($opt);$i++) 
					{
						$selected = ''; 
						if($value == ltrim(rtrim($opt[0]))) $selected ='selected="selected"';
						$row =  explode(":",$opt[$i]); 
						$opts .= "<option $selected value ='".trim($row[0])."' > ".$row[1]." </option> ";
					}				
					
				}
				$form = "<select name='$field{$bulk}'  class='form-control' $mandatory >
							<option value=''> -- Select  -- </option>
							$opts
						</select>";
				break;	

			case 'radio';
			
				$opt = explode("|",$option['lookup_query']);
				$opts = '';
				for($i=0; $i<count($opt);$i++) 
				{
					$checked = '';
					$row =  explode(":",$opt[$i]);
					$opts .= "<option value ='".$row[0]."' > ".$row[1]." </option> ";
				}
				$form = "<select name='$field{$bulk}' class='form-control' $mandatory ><option value=''> -- Select  -- </option>$opts</select>";
				break;												
			
		}
		
		return $form;	
	}
	
	public static  function bulkForm( $field, $forms = array(), $value ='')
	{
		$type = ''; 
		$bulk ='true';
		$bulk = ($bulk == true ? '[]' : '');
		$mandatory = '';
		foreach($forms as $f)
		{
			if($f['field'] == $field && $f['search'] ==1)
			{
				$type = ($f['type'] !='file' ? $f['type'] : ''); 
				$option = $f['option'];
				$required = $f['required'];
				
				if($required =='required') {
					$mandatory = "data-parsley-required='true'";
				} else if($required =='email') {
					$mandatory = "data-parsley-type'='email' ";
				} else if($required =='date') {
					$mandatory = "data-parsley-required='true'";
				} else if($required =='numeric') {
					$mandatory = "data-parsley-type='number' ";
				} else {
					$mandatory = '';
				}				
			}	
		}
		$field = 'bulk_'.$field;
		
		switch($type)
		{
			default;
				$form ='';
				break;
			
			case 'text';			
				$form = "<input  type='text' name='".$field."{$bulk}' class='form-control input-sm' $mandatory value='{$value}'/>";
				break;

			case 'text_date';
				$form = "<input  type='text' name='$field{$bulk}' class='date form-control input-sm' $mandatory value='{$value}'/> ";
				break;

			case 'text_datetime';
				$form = "<input  type='text' name='$field{$bulk}'  class='date form-control input-sm'  $mandatory value='{$value}'/> ";
				break;				

			case 'select';
				
			
				if($option['opt_type'] =='external')
				{
					
					$data = DB::table($option['lookup_table'])->get();
					$opts = '';
					foreach($data as $row):
						$selected = '';
						if($value == $row->$option['lookup_key']) $selected ='selected="selected"';
						$fields = explode("|",$option['lookup_value']);
						//print_r($fields);exit;
						$val = "";
						foreach($fields as $item=>$v)
						{
							if($v !="") $val .= $row->$v." " ;
						}
						$opts .= "<option $selected value='".$row->$option['lookup_key']."' $mandatory > ".$val." </option> ";
					endforeach;
						
				} else {
					$opt = explode("|",$option['lookup_query']);
					$opts = '';
					for($i=0; $i<count($opt);$i++) 
					{
						$selected = ''; 
						if($value == ltrim(rtrim($opt[0]))) $selected ='selected="selected"';
						$row =  explode(":",$opt[$i]); 
						$opts .= "<option $selected value ='".trim($row[0])."' > ".$row[1]." </option> ";
					}				
					
				}
				$form = "<select name='$field{$bulk}'  class='form-control' $mandatory >
							<option value=''> -- Select  -- </option>
							$opts
						</select>";
				break;	

			case 'radio';
			
				$opt = explode("|",$option['lookup_query']);
				$opts = '';
				for($i=0; $i<count($opt);$i++) 
				{
					$checked = '';
					$row =  explode(":",$opt[$i]);
					$opts .= "<option value ='".$row[0]."' > ".$row[1]." </option> ";
				}
				$form = "<select name='$field{$bulk}' class='form-control' $mandatory ><option value=''> -- Select  -- </option>$opts</select>";
				break;												
			
		}
		
		return $form;	
	}

	public static function viewColSpan( $grid )
	{
		$i =0;
		foreach ($grid as $t):
			if($t['view'] =='1') ++$i;
		endforeach;
		return $i;	
	}
	
	public static function blend($str,$data) {
		$src = $rep = array();
		
		foreach($data as $k=>$v){
			$src[] = "{".$k."}";
			$rep[] = $v;
		}
		
		if(is_array($str)){
			foreach($str as $st ){
				$res[] = trim(str_ireplace($src,$rep,$st));
			}
		} else {
			$res = str_ireplace($src,$rep,$str);
		}
		
		return $res;
		
	}			
		
	public static function toJavascript( $forms , $app , $class )
	{
		$f = '';
		foreach($forms as $form){
			if($form['view'] != 0)
			{			
				if(preg_match('/(select)/',$form['type'])) 
				{
					if($form['option']['opt_type'] == 'external') 
					{
						$table 	=  $form['option']['lookup_table'] ;
						$val 	=  $form['option']['lookup_value'];
						$key 	=  $form['option']['lookup_key'];
						$lookey = '';
						if($form['option']['is_dependency']) $lookey .= $form['option']['lookup_dependency_key'] ;
						$f .= self::createPreCombo( $form['field'] , $table , $key , $val ,$app, $class , $lookey  );
							
					}
									
				}
				
			}	
		
		}
		return $f;	
	
	}
	
	public static function createPreCombo( $field , $table , $key ,  $val ,$app ,$class ,$lookey = null)
	{


		
		$parent = null;
		$parent_field = null;
		if($lookey != null)  
		{	
			$parent = " parent: '#".$lookey."',";
			$parent_field =  "&parent={$lookey}:";
		}	
		$pre_jCombo = "
		\$(\"#{$field}\").jCombo(\"{{ URL::to('{$class}/comboselect?filter={$table}:{$key}:{$val}') }}$parent_field\",
		{ ".$parent." selected_value : '{{ \$row[\"{$field}\"] }}' });
		";	
		return $pre_jCombo;
	}	

	static public function showNotification()
	{
		$status = Session::get('msgstatus');
		if(Session::has('msgstatus')): ?>	  
		<script type="text/javascript">
            $(document).ready(function(){
			toastr.<?php echo $status;?>("success", "<?php echo Session::get('messagetext');?>");
			toastr.options = {
				  "closeButton": true,
				  "debug": false,
				  "positionClass": "toast-bottom-right",
				  "onclick": null,
				  "showDuration": "300",
				  "hideDuration": "1000",
				  "timeOut": "5000",
				  "extendedTimeOut": "1000",
				  "showEasing": "swing",
				  "hideEasing": "linear",
				  "showMethod": "fadeIn",
				  "hideMethod": "fadeOut"

				}
			});
		</script>		
		<?php endif;	
	}

	public static function alert( $task , $message)
	{
		if($task =='error') {
			$alert ='
			<div class="alert alert-danger  fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-cancel-circle"></i> '. $message.' </div>
			';			
		} elseif ($task =='success') {
			$alert ='
			<div class="alert alert-success fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-checkmark-circle"></i> '. $message.' </div>
			';			
		} elseif ($task =='warning') {
			$alert ='
			<div class="alert alert-warning fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-warning"></i> '. $message.' </div>
			';			
		} else {
			$alert ='
			<div class="alert alert-info  fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-info"></i> '. $message.' </div>
			';			
		}
		return $alert;
	
	} 		

	public static function _sort($a, $b) {
	 
		if ($a['sortlist'] == $a['sortlist']) {
		return strnatcmp($a['sortlist'], $b['sortlist']);
		}
		return strnatcmp($a['sortlist'], $b['sortlist']);
	}

	
	static public function cropImage($nw, $nh, $source, $stype, $dest) 
	{
		$size = getimagesize($source); // ukuran gambar
		$w = $size[0];
		$h = $size[1];
		switch($stype) 
		{ // format gambar
			default :
				$simg = imagecreatefromjpeg($source);
				break;

			case 'gif':
				$simg = imagecreatefromgif($source);
				break;
			
			case 'png':
				$simg = imagecreatefrompng($source);
				break;
		}
		$dimg = imagecreatetruecolor($nw, $nh); // menciptakan image baru
		$wm = $w/$nw;
		$hm = $h/$nh;
		$h_height = $nh/2;
		$w_height = $nw/2;
		if($w> $h) 
		{
			$adjusted_width = $w / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
		}
		elseif(($w <$h) || ($w == $h)) 
		{
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
		}
		else
		{
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
		imagejpeg($dimg,$dest,100);
	}	
		
	
	public static function showUploadedFile($file,$path , $width = 50) {
		/*print_r($file);
		print_r($path);
		exit;*/
	//print_r(base_path());exit;
		$files =  base_path(). $path . $file ;		
		if(file_exists($files ) && $file !="") {
		//	echo $files ;
			$info = pathinfo($files);	
			if($info['extension'] == "jpg" || $info['extension'] == "jpeg" ||  $info['extension'] == "png" || $info['extension'] == "gif" || $info['extension'] == "JPG") 
			{
				$path_file = str_replace("./","",$path);
				return '<p><a href="'.url( $path_file . $file).'" target="_blank" class="previewImage">
				<img src="'.asset( $path_file . $file ).'" border="0" width="'. $width .'" class="img-circle" /></a></p>';
			} else {
				$path_file = str_replace("./","",$path);
				return '<p> <a href="'.url($path_file . $file).'" target="_blank"> '.$file.' </a>';
			}
	
		} else {
			
			return "<img src='".asset('/uploads/images/no-image.png')."' border='0' width='".$width."' class='img-circle' /></a>";
				
		}

	}	

	public static function globalXssClean()
	{
	    // Recursive cleaning for array [] inputs, not just strings.
	    $sanitized = static::arrayStripTags(Input::get());
	    Input::merge($sanitized);
	}
	 
	public static function arrayStripTags($array)
	{
	    $result = array();
	 
	    foreach ($array as $key => $value) {
	        // Don't allow tags on key either, maybe useful for dynamic forms.
	        $key = strip_tags($key);
	 
	        // If the value is an array, we will just recurse back into the
	        // function to keep stripping the tags out of the array,
	        // otherwise we will set the stripped value.
	        if (is_array($value)) {
	            $result[$key] = static::arrayStripTags($value);
	        } else {
	            // I am using strip_tags(), you may use htmlentities(),
	            // also I am doing trim() here, you may remove it, if you wish.
	            $result[$key] = trim(strip_tags($value));
	        }
	    }
	 
	    return $result;
	}
	
	public static function writeEncoder($val) {
		return base64_encode($val);
	}
	
	public static function readEncoder($val) {
		return base64_decode($val);
	}
	
	public static function gridDisplay($val , $field, $arr) {
		
		if(isset($arr['valid']) && $arr['valid'] ==1)
		{
			$fields = str_replace("|",",",$arr['display']);
			$Q = DB::select(" SELECT ".$fields." FROM ".$arr['db']." WHERE ".$arr['key']." = '".$val."' ");
			if(count($Q) >= 1 )
			{
				$row = $Q[0];
				$fields = explode("|",$arr['display']);
				$v= '';
				$v .= (isset($fields[0]) && $fields[0] !='' ?  $row->{$fields[0]}.' ' : '');
				$v .= (isset($fields[1]) && $fields[1] !=''  ? $row-> {$fields[1]}.' ' : '');
				$v .= (isset($fields[2]) && $fields[2] !=''  ? $row->{$fields[2]}.' ' : '');
				
				
				return $v;
			} else {
				return '';
			}
		} else {
			return $val;
		}	
	}
	public static function gridDisplayView($val , $field, $arr) {
		$arr = explode(':',$arr);
		
		if(isset($arr['0']) && $arr['0'] ==1)
		{
			$Q = DB::select(" SELECT ".str_replace("|",",",$arr['3'])." FROM ".$arr['1']." WHERE ".$arr['2']." = '".$val."' ");
			if(count($Q) >= 1 )
			{
				$row = $Q[0];
				$fields = explode("|",$arr['3']);
				$v= '';
				$v .= (isset($fields[0]) && $fields[0] !='' ?  $row->{$fields[0]}.' ' : '');
				$v .= (isset($fields[1]) && $fields[1] !=''  ? $row-> {$fields[1]}.' ' : '');
				$v .= (isset($fields[2]) && $fields[2] !=''  ? $row->{$fields[2]}.' ' : '');
				return $v;
			} else {
				return '';
			}
		} else {
			return $val;
		}	
	}	
	
	public static function langOption()
	{
		$path = base_path().'/resources/lang/';
		$lang = scandir($path);

		$t = array();
		foreach($lang as $value) {
			if($value === '.' || $value === '..') {continue;} 
				if(is_dir($path . $value))
				{
					$fp = file_get_contents($path . $value.'/info.json');
					$fp = json_decode($fp,true);
					$t[] =  $fp ;
				}	
			
		}	
		return $t;
	}
	
	
	public static function themeOption()
	{
		
		$path = base_path().'/resources/views/layouts/';
		$lang = scandir($path);
		$t = array();
		foreach($lang as $value) {
			if($value === '.' || $value === '..') {continue;} 
				if(is_dir($path . $value))
				{
					$fp = file_get_contents($path .$value.'/info.json');
					$fp = json_decode($fp,true);
					$t[] =  $fp ;
				}	
			
		}	
		return $t;
	}
		
	public static function avatar( $width =75)
	{
		$avatar = '<img alt="" src="http://www.gravatar.com/avatar/'.md5(Session::get('email')).'" class="img-circle" width="'.$width.'" />';
		$Q = DB::table("tb_users")->where("id",'=',Session::get('uid'))->get();
		if(count($Q)>=1) 
		{
			$row = $Q[0];
			$files =  './uploads/users/'.$row->avatar ;
			if($row->avatar !='' ) 	
			{
				if( file_exists($files))
				{
					return  '<img src="'.asset('uploads/users').'/'.$row->avatar.'" border="0" width="'.$width.'" class="img-circle" />';
				} else {
					return $avatar;
				}	
			} else {
				return $avatar;
			}
		}	
	}

	
	public static function BBCode2Html($text) {
	
		$emotion =  URL::to('abserve/js/plugins/markitup/images/emoticons/');
		
		$text = trim($text);
	
		// BBCode [code]
		if (!function_exists('escape')) {
			function escape($s) {
				global $text;
				$text = strip_tags($text);
				$code = $s[1];
				$code = htmlspecialchars($code);
				$code = str_replace("[", "&#91;", $code);
				$code = str_replace("]", "&#93;", $code);
				return '<pre class="prettyprint linenums"><code>'.$code.'</code></pre>';
			}	
		}
		$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);
	
		// Smileys to find...
		$in = array( 	 ':)', 	
						 ':D',
						 ':o',
						 ':p',
						 ':(',
						 ';)'
		);
		// And replace them by...
		$out = array(	 '<img alt=":)" src="'.$emotion.'emoticon-happy.png" />',
						 '<img alt=":D" src="'.$emotion.'emoticon-smile.png" />',
						 '<img alt=":o" src="'.$emotion.'emoticon-surprised.png" />',
						 '<img alt=":p" src="'.$emotion.'emoticon-tongue.png" />',
						 '<img alt=":(" src="'.$emotion.'emoticon-unhappy.png" />',
						 '<img alt=";)" src="'.$emotion.'emoticon-wink.png" />'
		);
		$text = str_replace($in, $out, $text);
		
		// BBCode to find...
		$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
						 '/\[div\="?(.*?)"?](.*?)\[\/div\]/ms',
						 '/\[i\](.*?)\[\/i\]/ms',
						 '/\[u\](.*?)\[\/u\]/ms',
						 '/\[img\](.*?)\[\/img\]/ms',
						 '/\[email\](.*?)\[\/email\]/ms',
						 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
						 '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
						 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
						 '/\[quote](.*?)\[\/quote\]/ms',
						 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
						 '/\[list\](.*?)\[\/list\]/ms',
						 '/\[\*\]\s?(.*?)\n/ms'
		);
		// And replace them by...
		$out = array(	 '<strong>\1</strong>',
						 '<div class="\1">\2</div>',
						 '<em>\1</em>',
						 '<u>\1</u>',
						 '<img src="\1" alt="\1" />',
						 '<a href="mailto:\1">\1</a>',
						 '<a href="\1">\2</a>',
						 '<span style="font-size:\1%">\2</span>',
						 '<span style="color:\1">\2</span>',
						 '<blockquote>\1</blockquote>',
						 '<ol start="\1">\2</ol>',
						 '<ul>\1</ul>',
						 '<li>\1</li>'
		);
		$text = preg_replace($in, $out, $text);
			
		// paragraphs
		$text = str_replace("\r", "", $text);
		$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
		$text = nl2br($text);
		
		// clean some tags to remain strict
		// not very elegant, but it works. No time to do better ;)
		if (!function_exists('removeBr')) {
			function removeBr($s) {
				return str_replace("<br />", "", $s[0]);
			}
		}	
		$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
		$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
		
		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
		$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
		
		return $text;
	}	
	
	public static function seoUrl($str, $separator = 'dash', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}
	
		$trans = array(
					'&\#\d+?;'				=> '',
					'&\S+?;'				=> '',
					'\s+'					=> $replace,
					'[^a-z0-9\-\._]'		=> '',
					$replace.'+'			=> $replace,
					$replace.'$'			=> $replace,
					'^'.$replace			=> $replace,
					'\.+$'					=> ''
			  );
	
		$str = strip_tags($str);
	
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}
	
		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}
		
		return trim(stripslashes(strtolower($str)));
	}	
	
	
	static function renderHtml( $html )
	{
	
		$html = preg_replace( '/(\.+\/)+uploads/Usi' , URL::to('uploads') ,  $html );
	//	$content = str_replace($pattern , URL::to('').'/', $content );
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_opened = count ( $openedtags );
        # all tags are closed
        if( count ( $closedtags ) == $len_opened )
        {
       	 return $html;
        }
        $openedtags = array_reverse ( $openedtags );
        # close tags
        for( $i = 0; $i < $len_opened; $i++ )
        {
            if ( !in_array ( $openedtags[$i], $closedtags ) )
            {
            $html .= "</" . $openedtags[$i] . ">";
            }
            else
            {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
            }
        }
        return $html;
	
  
	
	} 

	public static function activeLang( $label , $l )
	{
		$activeLang = Session::get('lang');
		$lang = (isset($l[$activeLang]) ? $l[$activeLang] : $label );
		return $lang; 
		
	}

	static public function fieldLang( $fields ) 
	{ 
		$l = array();
		foreach($fields as $fs)
		{			
			foreach($fs as $f)
				$l[$fs['field']] = $fs; 									
		}
		return $l;	
	}	
	
	public static function infoLang( $label , $l , $t = 'title' )
	{
		$activeLang = Session::get('lang');
		$lang = (isset($l[$t][$activeLang]) ? $l[$t][$activeLang] : $label );
		return $lang; 
		
	}	

	public static function auditTrail( $request , $note )
	{
		$data = array(
			'module'	=> $request->segment(1),
			'task'		=> $request->segment(2),
			'user_id'	=> \Session::get('uid'),
			'ipaddress'	=> $request->getClientIp(),
			'note'		=> $note
		);
		
		\DB::table( 'tb_logs')->insert($data);		

	}
	 	

  static function storeNote( $args )
  {
      $args =  array_merge( array(
        'url'       => '#' ,
        'userid'    => '0' ,
        'title'     => '' ,
        'note'      => '' ,
        'created'   => date("Y-m-d H:i:s") ,
        'icon'      => 'fa fa-envelope',
        'is_read'   => 0   
        ), $args ); 


        \DB::table('tb_notification')->insert($args);   
  }	 
  static function getuname($id){
  		$tb_users = DB::select("SELECT first_name,last_name FROM tb_users WHERE id ='". $id ."'");	
  		return $tb_users[0]->first_name." ".$tb_users[0]->last_name;
  }	
  static function randres(){
  			$getval='';
  			$abserve_restaurants = DB::select("SELECT id,name FROM abserve_restaurants ORDER BY RAND() LIMIT 6 ");
		  			// foreach($abserve_restaurants as $value){
		  			// 	$getval.='<li><a href="">'.$value->name.'</a></li>';

		  			// }
		  			foreach($abserve_restaurants as $value){
		  				$getval.=$value->id."~".$value->name."|";

		  			}
		  			return $getval;
  }	 
  static function foodcheck($food_id){
  	if(\Auth::check()){
  		$userid = Auth::user()->id;
  		$q = "SELECT quantity FROM abserve_user_cart WHERE user_id = ".$userid." AND food_id = ".$food_id;
  		$items = \DB::table('abserve_user_cart')->select(DB::raw('SUM(quantity) as total_quants'))->where('user_id',$userid)->where('food_id',$food_id)->get();
  		$quantity = $items[0]->total_quants;
  		if(count($items)>0)
  		{
  			if($quantity != '')
  				return $quantity;
  			else 
  				return 0;
  		}
  		else
  		{
  			return 0;
  		}
  	} else
  	{
		return 0;
  	}
  }
/*
  public static function nearest_restaurants($lat='',$lang='')
   	{
   		$restaurant='';
  			$abserve_restaurants = DB::select("SELECT * FROM abserve_restaurants WHERE  ");
		  			foreach($abserve_restaurants as $value){
		  				$restaurant.=$value->id."~".$value->name."|";

		  			}
		  			return $restaurant;
   	} */	



  public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		$ip = $_SERVER["REMOTE_ADDR"];
		//print_r($ip);exit;
		if ($deep_detect) {
		    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
		        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
		        $ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		}

		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
		$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
		$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America"
		);
		if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
			$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?" . $ip));
			if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			    if ($purpose) {
			        if( $purpose == "location"){
			            $output = array(
			                "city"           => @$ipdat->geoplugin_city,
			                "state"          => @$ipdat->geoplugin_regionName,
			                "country"        => @$ipdat->geoplugin_countryName,
			                "country_code"   => @$ipdat->geoplugin_countryCode,
			                "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
			                "continent_code" => @$ipdat->geoplugin_continentCode,
			                "latitude"		 => @$ipdat->geoplugin_latitude,
			                "longitude"		 => @$ipdat->geoplugin_longitude
			            );
			            // break;
			        } else if( $purpose == "address"){
			            $address = array($ipdat->geoplugin_countryName);
			            if (@strlen($ipdat->geoplugin_regionName) >= 1)
			                $address[] = $ipdat->geoplugin_regionName;
			            if (@strlen($ipdat->geoplugin_city) >= 1)
			                $address[] = $ipdat->geoplugin_city;
			            $output = implode(", ", array_reverse($address));
			            // break;
			        } elseif( $purpose == "city"){
			            $output = @$ipdat->geoplugin_city;
			            // break;
			        } elseif( $purpose == "state"){
			            $output = @$ipdat->geoplugin_regionName;
			            // break;
			        } elseif( $purpose == "region"){
			            $output = @$ipdat->geoplugin_regionName;
			            // break;
			        } elseif( $purpose == "country"){
			            $output = @$ipdat->geoplugin_countryName;
			            // break;
			        } elseif( $purpose == "countrycode"){
			            $output = @$ipdat->geoplugin_countryCode;
			            // break;
			        }
			    }
			}
		}
		return $output;
	}	

	public static function getOverallRating($res_id){
		$star_1 = \DB::select("SELECT count(rating)as rating1 FROM `abserve_rating` WHERE `res_id` = ".$res_id." AND `rating` = 1");
		$star_2 = \DB::select("SELECT count(rating)as rating2 FROM `abserve_rating` WHERE `res_id` = ".$res_id." AND `rating` = 2");
		$star_3 = \DB::select("SELECT count(rating)as rating3 FROM `abserve_rating` WHERE `res_id` = ".$res_id." AND `rating` = 3");
		$star_4 = \DB::select("SELECT count(rating)as rating4 FROM `abserve_rating` WHERE `res_id` = ".$res_id." AND `rating` = 4");
		$star_5 = \DB::select("SELECT count(rating)as rating5 FROM `abserve_rating` WHERE `res_id` = ".$res_id." AND `rating` = 5");

		$str_1 = $star_1[0]->rating1;
		$str_2 = $star_2[0]->rating2;
		$str_3 = $star_3[0]->rating3;
		$str_4 = $star_4[0]->rating4;
		$str_5 = $star_5[0]->rating5;

		$total_count = $str_5 + $str_4 + $str_3 + $str_2 + $str_1;

		$Rating = (($str_5 * 5) + ($str_4 * 4) + ($str_3 * 3) + ($str_2 * 2) + ($str_1 * 1));
		if($total_count == 0 || $Rating == 0) {
			$tot = 0;
		}
		else{
		$tot = ($Rating/$total_count);
		}
		$round_overall	= round($tot);
		return $round_overall;
	}

	public static function gettimeval($res_id){
		$res_timeValid = 0;
		$resInfo = Restaurant::find($res_id);
		$date = new \DateTime();
		$timeval1=date("H:i ", time());
		$timeval2=date("H:i ", strtotime($resInfo->opening_time));
		$timeval3=date("H:i ", strtotime($resInfo->closing_time));
		if ($timeval1 > $timeval2 && $timeval1 < $timeval3)
		{
			$res_timeValid = 1;
		} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3){
			if($timeval3 >= 0 && $timeval3 < $timeval2){
				$res_timeValid = 1;
			} else {
				$res_timeValid = 0;
			}
		} else {
			$res_timeValid = 0;
		}
		return $res_timeValid;
	}
	
	public static function getrestimeval($res_id){
		$res_timeValid = 0;
		$resInfo = Restaurant::find($res_id);
		$date = new \DateTime();		
		$timeval=date("H:i ", time());
		$timeval1=date("H:i ", strtotime($resInfo->opening_time));
		$timeval2=date("H:i ", strtotime($resInfo->closing_time));
		$timeval3=date("H:i ", strtotime($resInfo->breakfast_opening_time));
		$timeval4=date("H:i ", strtotime($resInfo->breakfast_closing_time));
		$timeval5=date("H:i ", strtotime($resInfo->lunch_opening_time));
		$timeval6=date("H:i ", strtotime($resInfo->lunch_closing_time));
		$timeval7=date("H:i ", strtotime($resInfo->dinner_opening_time));
		$timeval8=date("H:i ", strtotime($resInfo->dinner_closing_time));
		
		if(($resInfo->opening_time !='' && $resInfo->closing_time !='')){
			if ($timeval >= $timeval1 && $timeval <= $timeval2){
				$res_timeValid = 1;
			} else {
				$res_timeValid = 0;
			}
			
		} else {
			if ($timeval >= $timeval3 && $timeval <= $timeval4){
				$res_timeValid = 1;
			} else {
				if ($timeval >= $timeval5 && $timeval <= $timeval6){
					$res_timeValid = 1;
				} else {
					if ($timeval >= $timeval7 && $timeval <= $timeval8){
						$res_timeValid = 1;
					} else {
						$res_timeValid = 0;
					}
				}
			}
		}
		
		return $res_timeValid;
	}

	public static function getItemTimeValid($hid){
		$itemtimevalid = 0;
		if($hid != ''){
			$iteminfo = Fooditems::find($hid);
			if($iteminfo->item_status == 1){
				$date = new DateTime();
				$timeval1=date("H:i ", time());
				$timeval2=date("H:i ", strtotime($iteminfo->available_from));
				$timeval3=date("H:i ", strtotime($iteminfo->available_to));
				if ($timeval1 > $timeval2 && $timeval1 < $timeval3) {
					$itemtimevalid = 1;
				} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3) {
					if($timeval3 >= 0 && $timeval3 < $timeval2){
						$itemtimevalid = 1;
					} else {
						$itemtimevalid = 0;
					}
				} else {
					$itemtimevalid = 0;
				}
			} else {
				$itemtimevalid = 0;
			}
		} 
		return $itemtimevalid;
	}

	public static function getOrderDetails($oid){
		$order_details = '';
		$order_details = \DB::table('abserve_order_details')->where('id','=',$oid)->first();
		return $order_details;
	}

	public static function getMainCatName($rootid) {
		$mainCatName = '';
		$food_cat = Foodcategories::where('id',$rootid)->first();
		if(($food_cat) > 0){
			if($food_cat->cat_name != '') 
				$mainCatName = $food_cat->cat_name;
		}
		return $mainCatName;
	}

	public static function getRestaurantDetails($rid) {
		$gid = \Auth::user()->group_id;
		$res_call = 'false';
		if($rid != ''){
			$resinfo = Restaurant::find($rid);
			//print $resinfo;
			//$cnt = sizeof($resinfo);
			if($resinfo > 0){
				$res_call_val = $resinfo->call_handling;
				if($gid == 1){
					if($res_call_val == 1)
						$res_call = 'true';
					else
						$res_call = 'false';
				} else {
					if($res_call_val == 1)
						$res_call = 'false';
					else
						$res_call = 'true';
				}
			}
		}
		return $res_call;
	}

	public static function getOrderValues($oid) {
		$orderValue = '';
		if($oid != ''){
			$orderInfo = Customerorder::where('orderid',$oid)->first();
			if(($orderInfo) > 0){
				$orderValue = $orderInfo->order_details;
			}
		}
		return $orderValue;
	}
	
	public static function getCustomerPhone($cid) {
		$CustomerPhone = '';
		if($cid != ''){
			$customer_details = \DB::table('tb_users')->select('phone_number')->where('id',$cid)->first();
			if($customer_details > 0){
				$CustomerPhone = $customer_details->phone_number;
			}
		}
		return $CustomerPhone;
	}
	
	public static function getBoyid($oid) {
		$bid = '';
		if($oid != ''){
			$boyInfo = \DB::table('abserve_boyorderstatus')->where('oid',$oid)->first();
			if($boyInfo > 0){
				$bid = $boyInfo->bid;
			}
		}
		return $bid;
	}
	
	public static function getRestaurantPhone($rid) {
		$resphone = '';
		if($rid != ''){
			$resInfo = \DB::table('abserve_restaurants')->select('phone')->where('id',$rid)->first();
			if($resInfo > 0){
				$resphone = $resInfo->phone;
			}
		}
		return $resphone;
	}
	
	public static function getRestaurantName($rid) {
		$resname = '';
		if($rid != ''){
			$resInfo = \DB::table('abserve_restaurants')->select('name')->where('id',$rid)->first();
			if($resInfo > 0){
				$resname = $resInfo->name;
			}
		}
		return $resname;
	}
	
	public static function getRegionKeyword($rid) {
		$resname = '';
		if($rid != ''){
			$resInfo = \DB::table('abserve_restaurants')->select('region')->where('id',$rid)->first();
			if($resInfo > 0){
				$resname = $resInfo->region;
			}
		}
		return $resname;
	}
	
	public static function getRegionName($rid) {
		$region = '';
		if($rid != ''){
			$regionInfo = \DB::table('region')->select('region_name')->where('id',$rid)->first();
			if(($regionInfo) > 0){
				$region = $regionInfo->region_name;
			}
		}
		return $region;
	}
	
	public static function getBoyname($bid) {
		$boyname = '';
		if($bid != ''){
			$boyInfo = \DB::table('abserve_deliveryboys')->where('id',$bid)->first();
			if($boyInfo > 0){
				$boyname = $boyInfo->username;
			}
		}
		return $boyname;
	}
	
	public static function getOfferPrice($rid,$totalprice,$orderdate) {
		$offer_price = 0;
		if($rid != ''){
			$restInfo = \DB::table('abserve_restaurants')->where('id',$rid)->where('offer_from', '<=', ($orderdate))->where('offer_to', '>=', ($orderdate))->first();
			if($restInfo > 0){
			  if($restInfo->min_order_value <= $totalprice){
				$offer_price = $totalprice*($restInfo->offer/100);
				if($restInfo->max_value < $offer_price){
					$offer_price = $restInfo->max_value;
				}
			  }
			}
		}
		return $offer_price;
	}
	
	public static function getResproducts($rid,$main_cat) {
		
		if($rid != ''){
			$resproducts = \DB::table('abserve_hotel_items')->select('*')->where('restaurant_id',$rid)->where('main_cat',$main_cat)->orderBy('display_order')->get();
			
		}
		return $resproducts;
	}

	public static function CartCookieItem($cookieID) {
		$message = '';
		$authid = \Auth::user()->id;
		$cartInfo = Usercart::where('cookie_id',$cookieID)->get();
		if(count($cartInfo) > 0 ){
			foreach ($cartInfo as $value) {
				$cookieCustomInfo = \DB::table('abserve_customitems')->where('ucart_id',$value->id)->get();
				$cntCookieCust = count($cookieCustomInfo);
				$userCartDetails  = Usercart::where('user_id',$authid)->where('res_id','=',$value->res_id)->where('food_id',$value->food_id)->get();
				if($cntCookieCust == 0) {
					if(count($userCartDetails > 0)) {
						foreach ($userCartDetails as $usercart) {
							$userCustInfo = \DB::table('abserve_customitems')->where('ucart_id',$usercart->id)->get();
							if(count($userCustInfo) > 0) {
								$updateCart = DB::table('abserve_user_cart')->where('id','=',$value->id)->update(array('user_id'=>$authid));
							} else {
								$newQuantity = $value->quantity + $usercart->quantity;
								$updateCart = DB::table('abserve_user_cart')->where('id','=',$usercart->id)->update(array('quantity'=>$newQuantity));
							}
						}
					} else {
						$updateCart = DB::table('abserve_user_cart')->where('id','=',$value->id)->update(array('user_id'=>$authid));
					}
				} else {
					$custIds = '';
					foreach ($cookieCustomInfo as $custIems) {
						$custIds .=  $custIems->item_id.',';
					}
					$custidVal = rtrim($custIds,",");
					$arrayCustid = explode(',',$custidVal);
					if(count($userCartDetails > 0)) {
						$j = 0;
						foreach ($userCartDetails as $usercart) {
							$exists = 'No';
							$userCustInfo = \DB::table('abserve_customitems')->where('ucart_id',$usercart->id)->get();
							if(count($userCustInfo) > 0) {
								$i=0;
								foreach ($userCustInfo as $userCustom) {
									if(in_array($userCustom->item_id, $arrayCustid)){
										$i++;
									}
								}
								if($i ==  $cntCookieCust && $i == count($userCustInfo)){
									$exists = 'Yes';
									$cartDet = \DB::table('abserve_user_cart')->where('id',$usercart->id)->first();
									$newQuantity = $value->quantity + $cartDet->quantity;
									$updateCart = DB::table('abserve_user_cart')->where('id','=',$usercart->id)->update(array('quantity'=>$newQuantity));
									break;
								} else {
									$j++;
								}
							} else {
								$j++;
							}
							if($exists == 'Yes'){
								\DB::table('abserve_customitems')->where('ucart_id',$value->id)->delete();
								break;
							}
							if($j == count($userCartDetails)) {
								$updateCart = DB::table('abserve_user_cart')->where('id','=',$value->id)->update(array('user_id'=>$authid));
							}
						}
					} else {
						$updateCart = DB::table('abserve_user_cart')->where('id','=',$value->id)->update(array('user_id'=>$authid));
					}
				}

				/*if(count($userCartDetails) > 0) {
					$newQuantity = $value->quantity + $userCartDetails->quantity;
					$userCart = DB::table('abserve_user_cart')->where('user_id','=',$authid)->where('res_id','=',$value->res_id)->where('food_id',$value->food_id)->update(array('quantity'=>$newQuantity));
				} else {
					$userCart = DB::table('abserve_user_cart')->where('id','=',$value->id)->update(array('user_id'=>$authid));
				}*/
			}
			if($updateCart)
				$rmessage = 'success';
			else
				$message = 'fail';
		} else {
			$message = 'No such cookie id';
		}
		return $message;
	}

	public static function getResTimeValid ($resid) {
		$res_timeValid = 0;
		$resInfo = Restaurant::find($resid);
		$date = new \DateTime();
		$timeval1=date("H:i ", time());
		$timeval2=date("H:i ", strtotime($resInfo->opening_time));
		$timeval3=date("H:i ", strtotime($resInfo->closing_time));
		if ($timeval1 > $timeval2 && $timeval1 < $timeval3) {
			$res_timeValid = 1;
		} elseif($timeval1 > $timeval2 && $timeval1 > $timeval3) {
			if($timeval3 >= 0 && $timeval3 < $timeval2) {
				$res_timeValid = 1;
			} else {
				$res_timeValid = 0;
			}
		} else {
			$res_timeValid = 0;
		}
		return $res_timeValid;
	}

	public static function getOrderRating($rid) {
		$rating = 0;
		$authid = \Auth::user()->id;
		$ratingInfo = DB::table('abserve_rating')->where('res_id','=',$rid)->where('cust_id','=',$authid)->first();
		if(($ratingInfo) > 0) {
			$rating = $ratingInfo->rating;
		}
		return $rating;
	}

	public static function CurrencyValue($value) {
		if(\Session::has('currency')){
			return ($value * \Session::get('currency_value'));
		} else {
			return ($value * 1);
		}
	}

	public static function getCustItems($food_id,$cat_id) {
		return \DB::table('abserve_item_customization')->select('*')->where('food_id',$food_id)->where('cat_id',$cat_id)->get();
	}

	public static function setconfig($name,$val) {
		$setInfo = \DB::table('tb_settings')->where('name',$name)->first();
		if(($setInfo) > 0){
			$updated = \DB::table('tb_settings')->where('name',$name)->update(array('value'=>$val)); 
		} else {
			$updated = \DB::table('tb_settings')->insert(array('name'=>$name,'value'=>$val));
		}
		return $updated;
	}

	public static function getConfig($name) {
		$value = 0;
		if($name != ''){
			$setInfo = \DB::table('tb_settings')->where('name',$name)->first();
			if(($setInfo) > 0) {
				$value = $setInfo->value;
			}
		}
		return $value;
	}

	public static function country(){
		return \DB::table('abserve_countries')->select('*')->get();
	}	

	public static function allcurreny() {
		return \DB::table('abserve_currency')->select('*')->get();
	}

	public static function getCustomizeName($cus_id) {
		$cus_name = array();
		$cus_name = \DB::table('abserve_food_customization')->where('id','=',$cus_id)->first();
		return $cus_name->cat_name;
	}

	public static function customItems($user_id,$cookie_id,$food_id,$cart_id) {
		$return = array();
		if($user_id){
			$customItems = \DB::table('abserve_customitems')->where('user_id',$user_id)->where('food_id',$food_id)->where('ucart_id',$cart_id)->get();
			if(count($customItems) > 0){
				$return = $customItems;
			}
		} else {
			$customItems = \DB::table('abserve_customitems')->where('cookie_id',$cookie_id)->where('food_id',$food_id)->where('ucart_id',$cart_id)->get();
			if(count($customItems) > 0){
				$return = $customItems;
			}
		}
		return $return;
	}

	public static function FoodCustItemCheck($user_id,$food_id,$item_id,$ucartid){
		$ufield		=  (\Auth::check()) ? 'user_id' : 'cookie_id';
		$ufieldopp	=  (!\Auth::check()) ? 'user_id' : 'cookie_id';
		return \DB::table('abserve_customitems')->where($ufield,$user_id)->where('ucart_id',$ucartid)->where('food_id',$food_id)->where('item_id',$item_id)->exists();
	}

	public static function walletdetails(){
		$abserve_host_transfer=\DB::table('abserve_host_transfer')->select('id','host_id','amount','created')->where('status','Requested')->get();
		return $abserve_host_transfer;
	}

	public static function Image($url='',$type='',$value='') {
		$abserve_list = \DB::select("SELECT avatar FROM `tb_users` WHERE id='".$id."' ");
		if($url != ''){
			if($type == 'user') {
				if(\File::exists(base_path($url.'/'.$abserve_list[0]->first_name))){
					return URL::to($url.'/'.$abserve_list[0]->first_name);
				} else {
					return URL::to('/uploads/images/40x40.png');
				}
			}
		} 
		else {
			if($type == 'user') {
				return URL::to('/uploads/images/40x40.png');
			} else if ($type == 'rooms') {
				return URL::to('/uploads/images/no-image.png');
			}
		}
	}

	public static function hostname($id){
    	$abserve_list = \DB::select("SELECT first_name FROM `tb_users` WHERE id='".$id."' ");
       return ($abserve_list[0]->first_name);
    }
	
	public static function cate_serv_list($cid, $level){
       $serve_list = \DB::table('service_categories')->select('*')->where('cat_id','=',$cid)->where('level','=',$level)->get();
       return $serve_list;
    }
	
	
	
}
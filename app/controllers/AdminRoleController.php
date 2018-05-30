<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles user roles
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles user role

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AdminRoleController extends BaseController
{
	/**
	 * Admin role Listing, Searching, Sorting Pagination 
	 *
	 * @method GET for displaying form 
	 * @method AJAX GET for displaying role datatable  
	 * @return GET -> role Listing 
	 * @return AJAX GET -> JSON response role 
	 */
	public function index(){

		if(Request::ajax()){

			$where_str 	  = "1 = ?";
            $where_params = array(1);  

            if (Input::has('sSearch'))
            {
                $search     = Input::get('sSearch');

                $where_str .= " and ( name like '%{$search}%'"
                            . ")";
            }

			$columns = array('id','name');

            $role_count = AclGroup::select('id')
							->whereRaw($where_str, $where_params)
							->count();

			$role = AclGroup::select($columns)
	               		->whereRaw($where_str, $where_params);
		
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1'){
                $role = $role->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0')){
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $columns[Input::get('iSortCol_' . $i)];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $role = $role->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $role = $role->get();
            
            $response['iTotalDisplayRecords'] = $role_count;
            $response['iTotalRecords'] = $role_count;

            $response['sEcho'] = intval(Input::get('sEcho'));

            $response['aaData'] = $role->toArray();
            
            return $response;
		}
		return View::make('admin.role.index');
	}

	/**
	 * Admin role create form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn role Form  
	 */
	public function create()
	{
		$consultperm = AclPermission::where('main_module','=','consultant')->get();
		$awardedperm = AclPermission::where('main_module','=','awarded')->get();
		$akdnperm = AclPermission::where('main_module','=','akdn')->get();
		$languageperm = AclPermission::where('main_module','=','language')->get();
		$skillperm = AclPermission::where('main_module','=','skill')->get();
		$roleperm = AclPermission::where('main_module','=','role')->get();
		$specperm = AclPermission::where('main_module','=','specialization')->get();
		$userperm = AclPermission::where('main_module','=','adminuser')->get();
		$firewallperm = AclPermission::where('main_module','=','firewall')->get();
		$agency = AclPermission::where('main_module','=','agency')->get();
		$mails = AclPermission::where('main_module','=','mails')->get();
		return View::make('admin.role.create',compact('mails','agency','consultperm','awardedperm','akdnperm','languageperm','skillperm','roleperm','userperm','specperm','firewallperm'));
	}

	/**
	 * Admin role validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to role create form with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function store()
	{
		$data = Input::all();

		$rules = [
			'role_name' => 'required',
			'permission' => 'required',
		];
		$message = [
			'role_name.required' => 'Role Name is Required',
			'permission.required' => 'Select at least One Permission',
		];

		$validator = Validator::make($data,$rules,$message);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to add details.')
							   ->with('message_type', 'danger');
		}

		$acl_group = new AclGroup();
		$acl_group->name = $data['role_name'];
		$acl_group->save();

		foreach ($data['permission'] as $value) {
			$acl_group_permission = new AclGroupPermission();
			$acl_group_permission->group_id = $acl_group->id;
			$acl_group_permission->permission_id = $value;
			$acl_group_permission->save();
		}
		return Redirect::back()->with('message', 'Role Successfully Added.')
							   ->with('message_type', 'success');
	}

	/**
	 * Admin role Edit form 
	 * 
	 * @method GET 
	 * @param  string  $role_id => role id
	 * @return HTML -> Role Edit Form  
	 */
	public function edit($role_id)
	{
		$consultperm = AclPermission::where('main_module','=','consultant')->get();
		$awardedperm = AclPermission::where('main_module','=','awarded')->get();
		$akdnperm = AclPermission::where('main_module','=','akdn')->get();
		$languageperm = AclPermission::where('main_module','=','language')->get();
		$skillperm = AclPermission::where('main_module','=','skill')->get();
		$roleperm = AclPermission::where('main_module','=','role')->get();
		$specperm = AclPermission::where('main_module','=','specialization')->get();
		$userperm = AclPermission::where('main_module','=','adminuser')->get();
		$firewallperm = AclPermission::where('main_module','=','firewall')->get();
		$agency = AclPermission::where('main_module','=','agency')->get();
		$mails = AclPermission::where('main_module','=','mails')->get();

		$selected = AclGroupPermission::where('group_id',$role_id)->lists('group_id','permission_id');
		$role_name = AclGroup::where('id',$role_id)->first();

		return View::make('admin.role.edit',compact('mails','agency','consultperm','awardedperm','akdnperm','languageperm','skillperm','roleperm','selected','role_name','specperm','userperm','firewallperm'));
	}

	/**
	 * Admin role validates, update record in database
	 * 
	 * @method POST 
	 * @param  $role_id => role id
	 * @return if failed -> redirect back to role edit form with error messages 
	 * @return if successful -> redirect back to role edit form with successful confirmation 
	 */
	public function update($role_id)
	{
		$data = Input::all();
		
		$rules = [
			'role_name' => 'required',
			'permission' => 'required',
		];
		$message = [
			'role_name.required' => 'Role Name is Required',
			'permission.required' => 'Select at least One Permission',
		];

		$validator = Validator::make($data,$rules,$message);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to edit details.')
							   ->with('message_type', 'danger');
		}

		$acl_group_permission_delete = AclGroupPermission::where('group_id',$role_id)->delete();

		$acl_group = AclGroup::find($role_id);
		$acl_group->name = $data['role_name'];
		$acl_group->save();

		foreach ($data['permission'] as $value) {
			$acl_group_permission = new AclGroupPermission();
			$acl_group_permission->group_id = $acl_group->id;
			$acl_group_permission->permission_id = $value;
			$acl_group_permission->save();
		}

		return Redirect::back()->with('message', 'Role Successfully Updated.')
							   ->with('message_type', 'success');
	}

	 /**
	 * Admin role  single / multiple role delete
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			$role_id = Input::get('id');
			$acl_group_permission_delete = AclGroupPermission::where('group_id',$role_id)->delete();
			AclGroup::where('id','=',$role_id)->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			AclGroup::whereIn('id',$ids)->forceDelete();
			foreach ($ids as $id) {
				$acl_group_permission_delete = AclGroupPermission::where('group_id',$id)->delete();
			}
		}

		return Response::json(array('msg' => 'AkdnUser deleted permanently','success'=>true), 200);
	}

	 /**
	 * Admin role export filtered records to excel 
	 * 
	 * @method GET 
	 * @return Excel File 
	 */
	public function excelExport()
   	{
        $where_str    = "1 = ?";
        $where_params = array(1);

        if (Input::has('sSearch'))
        {
            $search     = Input::get('sSearch');

            $where_str .= " and ( name like '%{$search}%'"
                            . ")";
        }

		$role = AclGroup::select('name')
               		->whereRaw($where_str, $where_params);
                        
        $role = $role->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'Role Name' => 'name',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        $link_style_array = [
            'font'  => [
                'color' => ['rgb' => '0000FF'],
                'underline' => 'single'
            ]
        ];
        $rowNumber = 2;
        $col = 'A';
        foreach ($role as $role_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {

                $insert_data[$column_name] = $role_data[$column_name];
            }
            
            $objPHPExcel->getActiveSheet()->fromArray($role_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'Role_export_' . date('d_m_Y') . '.xlsx';
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=$filename");
        header("Content-Transfer-Encoding: binary ");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
        exit();
   }
}
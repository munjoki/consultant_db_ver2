<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles user registration and logins
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles user registration and logins

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 
class AdminUserController extends \BaseController {

	/**
	 * Admin user Listing, Searching, Sorting Pagination 
	 *
	 * @method GET for displaying form 
	 * @method AJAX GET for displaying user datatable  
	 * @return GET -> AKDN Listing 
	 * @return AJAX GET -> JSON response user 
	 */
	public function index(){

		if(Request::ajax()){

			$where_str 	  = "1 = ?";
            $where_params = array(1);  

            if (Input::has('sSearch'))
            {
                $search     = Input::get('sSearch');

                $where_str .= " and ( name like '%{$search}%'"
                			. " or email like '%{$search}%'"
                            . ")";
            }

			$columns = array('id','name','email');

            $role_count = Admin::select('id')
							->whereRaw($where_str, $where_params)
							->count();

			$role = Admin::select($columns)
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
		return View::make('admin.adminuser.index');
	}

	 /**
	 * Admin user create form 
	 * 
	 * @method GET 
	 * @return HTML -> user Create Form  
	 */
	public function create()
	{
		$roles = AclGroup::lists('name','id');

		return View::make('admin.adminuser.create',compact('roles'));
	}

	/**
	 * Admin user validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to user create form with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */

	public function store()
	{
		$data = Input::all();
		$validator = Validator::make($data,Admin::$rules,Admin::$message);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to add details.')
							   ->with('message_type', 'danger');
		}

		$admin = new Admin($data);
		$admin->password = Hash::make(Input::get('password'));
		$admin->save();

		if (isset($data['group_id'])) {
			$aclusergroup = new AclUserGroup();
			$aclusergroup->group_id = $data['group_id'];
			$aclusergroup->user_id = $admin->id;
			$aclusergroup->save();
		}

		return Redirect::back()->with('message', 'User Successfully Added.')
							   ->with('message_type', 'success');
	}

	/**
	 * Admin user Edit form 
	 * 
	 * @method GET 
	 * @param $id => user id
	 * @return HTML -> user Edit Form  
	 */
	public function edit($id)
	{
		$admin = Admin::where('id',$id)->first();
		$roles = AclGroup::lists('name','id');
		$aclusergroup = AclUserGroup::where('user_id',$id)->first();

		return view::make('admin.adminuser.edit',compact('admin','roles','aclusergroup'));
	}

	/**
	 * Admin user validates, update record in database
	 * 
	 * @method POST 
	 * @param  $id => user id
	 * @return if failed -> redirect back to user edit form with error messages 
	 * @return if successful -> redirect back to user edit form with successful confirmation 
	 */
	public function update($id)
	{
		$data = Input::all();

		$rules = Admin::$rules;
		unset($rules['password']);

		$validator = Validator::make($data,$rules,Admin::$message);

		if($validator->fails()){
			return Redirect::back()->withInput()
								   ->withErrors($validator->errors())
								   ->with('message','user data not updated')
								   ->with('message_type','danger');
		}

		$admin = Admin::find($id);
		$admin->fill($data);
		$aclusergroup = AclUserGroup::where('user_id',$id)->delete();

		if (isset($data['group_id'])) {
			$aclusergroup = new AclUserGroup();
			$aclusergroup->group_id = $data['group_id'];
			$aclusergroup->user_id = $id;
			$aclusergroup->save();
		}
		$admin->save();
		return Redirect::back()->with('message','admin user updated successfully')
							   ->with('message_type','success');
	}

	/**
	 * Admin user  single / multiple users delete
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			$id = Input::get('id');
			Admin::where('id','=',$id)->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			Admin::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'deleted permanently','success'=>true), 200);
	}

	 /**
	 * Admin user change password form 
	 * 
	 * @method GET 
	 * @return HTML -> user change password form 
	 */
	public function getChangePassword()
	{
		return View::make('admin.user.changepassword');
	}

	/**
	 * Admin user change password validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function postChangePassword()
	{
		$admin_user = Admin::find(Auth::admin()->get()->id);

		Validator::extend('strong_password', function($attribute, $value, $parameters) {
			$r1 = '/[A-Z]/';
			$r2 = '/[a-z]/';
			$r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/';
			$r4 = '/[0-9]/';

			if(preg_match_all($r1,$value, $o)<1) return false;

			if(preg_match_all($r2,$value, $o)<1) return false;

			if(preg_match_all($r3,$value, $o)<1) return false;

			if(preg_match_all($r4,$value, $o)<1) return false;

			if(strlen($value)<8) return false;

			return true;
		});

		Validator::extend('check_password', function($attribute, $value, $parameters) 
		{	
			$user   = Admin::find( Auth::admin()->get()->id );
			$old_password = Hash::check($value, $user->password);
			
			if($old_password == true) return true;

			return false;
		});

		$rules = array(
			'old_password' 			=> 'required|check_password',
			'password' 				=> 'required|strong_password|different:old_password',
			'password_confirmation' => 'required|same:password',
		);

		$messages = array(
			'old_password.check_password' => 'Please enter correct password',
			'password.strong_password'    => 'Passwords must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and one special character.',
			'password.different'		  => 'new password must be different',
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if($validator->passes())
		{
			$admin_user->password = Hash::make(Input::get('password'));
			$admin_user->save();

			return Redirect::back()->with('message', 'Password updated successfully.')
								   ->with('message_type', 'success');
		} 

		return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Could not update password.')
							   ->with('message_type', 'danger');
	}

	/**
	 * Admin user export filtered records to excel 
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
                			. " or email like '%{$search}%'"
                            . ")";
        }

        $columns = array('name','email');

        $role_count = Admin::select('id')
							->whereRaw($where_str, $where_params)
							->count();

		$role = Admin::select($columns)
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
            'Admin user Name' => 'name',
            'Email Address' => 'email',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);

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
           	
           	if($insert_data['email'] != ""){

			    $objPHPExcel->getActiveSheet()->getCell('B' . $rowNumber)->getHyperlink()->setUrl(strip_tags("mailto:".$insert_data['email']));
			    $objPHPExcel->getActiveSheet()->getStyle('B' . $rowNumber)->applyFromArray($link_style_array);
			}

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'AdminUser_export_' . date('d_m_Y') . '.xlsx';
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
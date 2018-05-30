<?php

class LoginLogsController extends BaseController
{
	
    /**
     * Consultant login logs Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying Consultant login logs datatable  
     * @return GET -> AKDN Listing 
     * @return AJAX GET -> JSON response Consultant login logs 
     */
    public function user()
	{
		if( Request::ajax() ) {

            $where_str 	  = "1 = ?";
            $where_params = array(1);

            if (Input::has('sSearch')) {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. " ip_address like '%{$search}%'"
                			. " or country like '%{$search}%'"
                            . " or login_time like '%{$search}%'"
                 			. ")";
            }

			$loginLogCount = LoginLog::select('user.id')
									->where('type','user')
						  			->whereRaw($where_str,$where_params)
						  			->count();

			$columns = array('login_logs.id','consultants.other_names as username','ip_address','country','login_time','logout_time');

			$loginLog = LoginLog::select($columns)
								->where('type','user')
								->leftJoin('consultants','consultants.id','=','login_logs.user_id')
								->whereRaw($where_str, $where_params);

            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $loginLog->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0'))
            {
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $columns[Input::get('iSortCol_' . $i)];

                    if(false !== ($index = strpos($column, ' as ')))
                    {
                        $column = substr($column, 0, $index);
                    }

                    $loginLog->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $loginLog = $loginLog->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $loginLogCount,
				'iTotalRecords'			=> $loginLogCount,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $loginLog
			);
			
			return Response::json($response, 200);
		}
		
		return View::make('admin.loginlogs.user');
	}

	/**
     * akdn login logs Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying akdn login logs datatable  
     * @return GET -> AKDN Listing 
     * @return AJAX GET -> JSON response akdn login logs 
     */
    public function akdn()
	{
		if( Request::ajax() ) {

            $where_str 	  = "1 = ?";
            $where_params = array(1);

            /* Common Search Filter */
            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. " ip_address like '%{$search}%'"
                			. " or country like '%{$search}%'"
                            . " or login_time like '%{$search}%'"
                 			. ")";
            }

			$loginLogCount = LoginLog::select('user.id')
									->where('type','akdn')
						  			->whereRaw($where_str,$where_params)
						  			->count();

			$columns = array('login_logs.id','akdn.other_name as username','ip_address','country','login_time','logout_time');

			$loginLog = LoginLog::select($columns)
								->where('type','akdn')
								->leftJoin('akdn','akdn.id','=','login_logs.user_id')
								->whereRaw($where_str, $where_params);

            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $loginLog->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0'))
            {
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $columns[Input::get('iSortCol_' . $i)];

                    if(false !== ($index = strpos($column, ' as ')))
                    {
                        $column = substr($column, 0, $index);
                    }

                    $loginLog->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $loginLog = $loginLog->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $loginLogCount,
				'iTotalRecords'			=> $loginLogCount,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $loginLog
			);
			
			return Response::json($response, 200);
		}
		
		return View::make('admin.loginlogs.akdn');
	}

	
    /**
     * admin login logs Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying admin login logs datatable  
     * @return GET -> AKDN Listing 
     * @return AJAX GET -> JSON response admin login logs 
     */
    public function admin()
	{
		if( Request::ajax() ) {

            $where_str 	  = "1 = ?";
            $where_params = array(1);

            /* Common Search Filter */
            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. " ip_address like '%{$search}%'"
                			. " or country like '%{$search}%'"
                            . " or login_time like '%{$search}%'"
                 			. ")";
            }

			$loginLogCount = LoginLog::select('user.id')
									->where('type','admin')
						  			->whereRaw($where_str,$where_params)
						  			->count();

			$columns = array('login_logs.id','admin.name as username','ip_address','country','login_time','logout_time');

			$loginLog = LoginLog::select($columns)
								->where('type','admin')
								->leftJoin('admin','admin.id','=','login_logs.user_id')
								->whereRaw($where_str, $where_params);

            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $loginLog->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0'))
            {
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $columns[Input::get('iSortCol_' . $i)];

                    if(false !== ($index = strpos($column, ' as ')))
                    {
                        $column = substr($column, 0, $index);
                    }

                    $loginLog->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $loginLog = $loginLog->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $loginLogCount,
				'iTotalRecords'			=> $loginLogCount,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $loginLog
			);
			
			return Response::json($response, 200);
		}
		
		return View::make('admin.loginlogs.admin');
	}

     /**
     * Login logs export filtered records to excel 
     * 
     * @method GET 
     * @return Excel File 
     */
    public function excelExport()
    {
        $user_type = Input::get('user_type');
        $where_str    = "1 = ?";
        $where_params = array(1);
        if (Input::has('sSearch'))
        {
            $search     = Input::get('sSearch');

            $where_str  .= " and ( "
                            . " ip_address like '%{$search}%'"
                            . " or country like '%{$search}%'"
                            . " or login_time like '%{$search}%'"
                            . ")";
        }

        if(Input::get('user_type') == 'admin')
        {
            $columns = array('admin.name as name','ip_address','country','login_time','logout_time');

            $loginLog = LoginLog::select($columns)
                                ->where('type','admin')
                                ->leftJoin('admin','admin.id','=','login_logs.user_id')
                                ->whereRaw($where_str, $where_params);                        
        }

        if(Input::get('user_type') == 'akdn')
        {
            $columns = array('akdn.other_name as name','ip_address','country','login_time','logout_time');

            $loginLog = LoginLog::select($columns)
                                ->where('type','akdn')
                                ->leftJoin('akdn','akdn.id','=','login_logs.user_id')
                                ->whereRaw($where_str, $where_params);
        }

        if(Input::get('user_type') == 'user')
        {
            $columns = array('consultants.other_names as name','ip_address','country','login_time','logout_time');

            $loginLog = LoginLog::select($columns)
                                ->where('type','user')
                                ->leftJoin('consultants','consultants.id','=','login_logs.user_id')
                                ->whereRaw($where_str, $where_params);
        }

        $loginLog = $loginLog->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'User Name' => 'name',
            'IP Address' => 'ip_address',
            'Country' => 'country',
            'Login Time' => 'login_time',
            'Logout Time' => 'logout_time',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

        $link_style_array = [
            'font'  => [
                'color' => ['rgb' => '0000FF'],
                'underline' => 'single'
            ]
        ];
        $rowNumber = 2;
        $col = 'A';
        foreach ($loginLog as $loginLog_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
                // echo "<pre>";
                // print_r($header);
                // print_r($column_name);

                $insert_data[$column_name] = $loginLog_data[$column_name];
            }
            // echo "<pre>";
            //     print_r($insert_data);
            //     exit();
            $objPHPExcel->getActiveSheet()->fromArray($loginLog_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'loginlogs_'.$user_type.'_export_' . date('d_m_Y') . '.xlsx';
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
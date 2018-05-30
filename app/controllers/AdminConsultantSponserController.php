<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles the ConsultantSponser updating and deletion
------------------------------------------------------------------------------------------------------------------------------------------*/ 

/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles the ConsultantSponser updating and deletion

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AdminConsultantSponserController extends BaseController{

    /**
     * Admin Consultant Sponser Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying Consultant Sponser datatable  
     * @return GET -> Consultant Sponser Listing 
     * @return AJAX GET -> JSON response Consultant Sponser
     */
    public function index()
	{
		if(Request::ajax()){

			$where_str 	  = "1 = ?";
            $where_params = array(1);  

            if (Input::has('sSearch'))
            {
                $search     = Input::get('sSearch');

                $where_str .= " and ( name like '%{$search}%'"
                			. " or consultant_sponsors.email like '%{$search}%'"
                            . " or akdn.other_name like '%{$search}%'"
                            . " or akdn.surname like '%{$search}%'"
                            . ")";
            }

			$columns = array('consultant_sponsors.id',DB::raw('CONCAT(akdn.other_name," ", akdn.surname) AS full_name'),'consultant_sponsors.invited_on_behalf','name','consultant_sponsors.email','consultant_sponsors.created_at', 'users.id as user_id');

            $consultantSponsor_count = ConsultantSponsor::select('id')
            				->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
							->leftjoin('users','users.email','=','consultant_sponsors.email')
							->whereRaw($where_str, $where_params)
							->count();

			$consultantSponsor = ConsultantSponsor::select($columns)
									->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
									->leftjoin('users','users.email','=','consultant_sponsors.email')
	               					->whereRaw($where_str, $where_params);
		
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1'){
                $consultantSponsor = $consultantSponsor->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0')){
                $oColumns = ['consultant_sponsors.id','name','consultant_sponsors.email','consultant_sponsors.invited_on_behalf','consultant_sponsors.created_at', 'user_id'];
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $oColumns[Input::get('iSortCol_' . $i)];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $consultantSponsor = $consultantSponsor->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $consultantSponsor = $consultantSponsor->get();

            $response['iTotalDisplayRecords'] = $consultantSponsor_count;
            $response['iTotalRecords'] = $consultantSponsor_count;

            $response['sEcho'] = intval(Input::get('sEcho'));

            $response['aaData'] = $consultantSponsor->toArray();
            
            return $response;
		}
		return View::make('admin.consultant.consultantsponsors');
	}

     /**
     * Admin consultant Sponsor Edit form 
     * 
     * @method GET 
     * @param  string  $id => Consultant Sponsor id
     * @return HTML -> ConsultantSponsor Edit Form  
     */
    public function edit($id)
    {
    
        $consultantSponsor = ConsultantSponsor::find($id);

        $all_akdn = [' '=>'Select Akdn'] + Akdn::select(DB::raw('CONCAT(akdn.other_name," ", akdn.surname) AS name'),'id')->lists('name','id');
       
        $akdn =  ConsultantSponsor::select(DB::raw('CONCAT(akdn.other_name," ", akdn.surname) AS name'),'consultant_sponsors.akdn_id as akdn_id')
                                    ->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                                    ->leftjoin('users','users.email','=','consultant_sponsors.email')
                                    ->where('consultant_sponsors.id',$id)->lists('akdn_id','name');
          
       // $columns = array('consultant_sponsors.id',DB::raw('CONCAT(akdn.other_name," ", akdn.surname) AS full_name'),'consultant_sponsors.invited_on_behalf','name','consultant_sponsors.email','consultant_sponsors.created_at', 'users.id as user_id');

        // $consultantsponsors_status = ConsultantSponsor::select( 'users.id as user_id')
        //                         ->leftjoin('users','users.email','=','consultant_sponsors.email')
        //                         ->where('consultant_sponsors.id',$id)->first()->toArray();

        $consultant_emails = [''=>'Select Consultant email'] + Consultant::lists('email','email');


        //$pending_consultant = Consultant::lists('email','email') + ConsultantSponsor::select('consultant_sponsors.email as email,users.id as user_id')->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')->leftjoin('users','users.email','=','consultant_sponsors.email')->where('users.id',null)->lists('email','email');
        
        //ConsultantSponsor::lists('email','email')
        // if (empty($consultantsponsors_status['user_id'])) 
        // {
        //     $status = 'Pending';
        // }
        // else
        // {
        //     $status = 'Joined';
        // }

        return View::make('admin.consultant.sponsorsedit',compact('id','consultantSponsor','akdn','all_akdn','consultant_emails'));
    }

    /**
     * Admin consultant Sponsor validates, update record in database
     * 
     * @method POST 
     * @param  string  $id => consultant Sponsor id
     * @return if failed -> redirect back to consultant Sponsor edit form with error messages 
     * @return if successful -> redirect to consultant Sponsor edit form back with successful confirmation 
     */
    public function update($id)
    {
        $data = Input::all();
        $rules = [
            'email' => 'required',
            'name' => 'required',
        ];
        
        $message = [
            'email.required' => 'Email is Required',
            'name.required' => 'Name is Required',
        ];

        $validator = Validator::make($data,$rules,$message);

        if ($validator->fails()) {
            return Redirect::back()->withInput()
                               ->withErrors($validator->errors())
                               ->with('message', 'Unable to edit details.')
                               ->with('message_type', 'danger');
        }

        $consultantsponsors = ConsultantSponsor::find($id);
        $consultantsponsors->fill($data);
        
        if(!empty(trim($data['registeremail'])))
        {   
            $consultantsponsors->email= $data['registeremail'];
        }
        $consultantsponsors->save();

        return Redirect::back()->with('message', 'ConsultantSponsor Successfully Updated.')
                               ->with('message_type', 'success');
    }

	/**
     * Admin Consultant Sponsor  single / multiple Consultant Sponsor delete
     * 
     * @method POST 
     * @return JSON
     */
    public function destroy()
	{
		$data = Input::all();
		
		$id = Input::get('id');

		$consultant_sponsors = ConsultantSponsor::where('id',$id)->delete();
		return Response::json(array('message' => 'deleted permanently','success'=>true), 200);
	}


    /**
     * Admin Consultant Sponsor export filtered records to excel 
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
                        . " or consultant_sponsors.email like '%{$search}%'"
                        . " or akdn.other_name like '%{$search}%'"
                        . " or akdn.surname like '%{$search}%'"
                        . ")";
        }

        // $columns = array(DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'),'name','consultant_sponsors.email','consultant_sponsors.created_at', 'users.id as user_id');
        $columns = array('name','consultant_sponsors.email','consultant_sponsors.created_at',DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'), 'users.id as user_id');

        $consultantSponsor = ConsultantSponsor::select($columns)
                                ->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                                ->leftjoin('users','users.email','=','consultant_sponsors.email')
                                ->whereRaw($where_str, $where_params);
                        

        $consultantSponsor = $consultantSponsor->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'Consultant Name' => 'name',
            'Email Address' => 'email',
            'Date of Invitation' => 'created_at',
            'Invited By' => 'full_name',
            'status' => 'user_id',
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
        foreach ($consultantSponsor as $consultantSponsor_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
                
                $insert_data[$column_name] = $consultantSponsor_data[$column_name];
            }
           
            $objPHPExcel->getActiveSheet()->fromArray($consultantSponsor_data, NULL, 'A' . $rowNumber );

            if($insert_data['email'] != ""){

                $objPHPExcel->getActiveSheet()->getCell('B' . $rowNumber)->getHyperlink()->setUrl(strip_tags("mailto:".$insert_data['email']));
                $objPHPExcel->getActiveSheet()->getStyle('B' . $rowNumber)->applyFromArray($link_style_array);
            }

            if($insert_data['user_id'] != "" && $insert_data['user_id'] != null){

                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowNumber,'Joined');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowNumber,'Pending');
            }

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'InvitedConsultants_export_' . date('d_m_Y') . '.xlsx';
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
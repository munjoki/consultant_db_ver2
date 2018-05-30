<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this controller file handles the AKDN user managing the registering of consultancies
------------------------------------------------------------------------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles Add, edit, delete and listing of consultancies

    * @author     thinkTANKER
    * @version    AKDN MER Consultant Database Version 1.0
    * @email      hello@thinktanker.in
------------------------------------------------------------------------------------------------------------------------------------------*/
class AdminAwardedConsultancyController extends BaseController
{
	/**
     * Admin consultancy Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying consultancy datatable  
     * @return GET -> consultancy Listing 
     * @return AJAX GET -> JSON response consultancy 
     */
    public function index()
    {
        if( Request::ajax() ) {

            $where_str    = "1 = ?";
            $where_params = array(1);

            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. "title_of_consultancy like '%{$search}%'"
                			. " or consultants.surname like '%{$search}%'"
                			. " or consultants.title like '%{$search}%'"
                			. " or akdn_manager_email like '%{$search}%'"
                 			. ")";
            }

            if (Input::get('sSearch_0') != ""){
                $search = Input::get('sSearch_0');

                $where_str .= " and title_of_consultancy like '%{$search}%'";             
         	}
         	if (Input::get('sSearch_1') != ""){
                $search = Input::get('sSearch_1');

                $where_str .= " and akdn_manager_name like '%{$search}%'";             
            }
            if (Input::get('sSearch_2') != ""){
                $search = Input::get('sSearch_2');

                $where_str .= " and akdn_manager_email like '%{$search}%'";             
            }
            if (Input::get('sSearch_3') != ""){
                $search = Input::get('sSearch_3');

                $where_str .= " and start_date like '%{$search}%'";             
            }
            if (Input::get('sSearch_4') != ""){
                $search = Input::get('sSearch_4');

                $where_str .= " and end_date like '%{$search}%'";             
            }


            $total_count = ConsultantAssignment::select('consultant_assignments.id')->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
                          ->whereRaw($where_str,$where_params)
                          ->count();
            
            $columns = array('consultant_assignments.id','consultant_id' ,'title_of_consultancy',DB::raw('CONCAT(consultants.other_names, " ",consultants.surname) AS consultant_full_name'),DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'),'akdn.id as akdn_id', 'akdn_manager_name', 'akdn_manager_email', DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date_dmy'), DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date_dmy'),'status');

            $user = ConsultantAssignment::select($columns)
                    ->leftJoin('akdn','akdn.id','=','consultant_assignments.akdn_id')
            		->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
            		->whereRaw($where_str, $where_params);
                 

            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $user->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }        

            if(Input::has('iSortCol_0'))
            {
                $oColumns = ['consultant_assignments.id','consultant_id' ,'title_of_consultancy','consultant_full_name','full_name','akdn_manager_name', 'akdn_manager_email','start_date_dmy','end_date_dmy'];
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $oColumns[Input::get('iSortCol_' . $i)];

                    if(false !== ($index = strpos($column, ' as ')))
                    {
                        $column = substr($column, 0, $index);
                    }

                    $user->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $user = $user->get()->toArray();

            $response   = array(

                'iTotalDisplayRecords'  => $total_count,
                'iTotalRecords'         => $total_count,
                'sEcho'                 => intval(Input::get('sEcho')),
                'aaData'                => $user
            );

            return Response::json($response, 200);
        }

        return View::make('admin.awarded.index');
    } 

    /**
     * Admin consultancy create form 
     * 
     * @method GET 
     * @return HTML -> consultancy Create Form  
     */
    public function create(){

        $consultant = Consultant::select(DB::raw('concat(consultants.title," ",consultants.surname) as name'),'id')->lists('name','id');
        $akdn       = Akdn::lists('other_name','id');
        return View::make('admin.awarded.create',compact('consultant','akdn'));
    }

    /**
     * Admin consultancy validates, stores record in database and sends email
     * 
     * @method POST 
     * @return if failed -> redirect back to consultancy create form with error messages 
     * @return if successful -> redirect back to consultancy create form with successful confirmation 
     */
    public function store(){
            
            $rules = ConsultantAssignment::getAddRules();

            $id = Input::get('consultant_id');
            $akdn_id = Input::get('akdn_id');
            $validator = Validator::make(Input::all(), $rules, ConsultantAssignment::$messages);

            if( $validator->fails() ){

                return Redirect::back()
                ->withInput()
                ->withErrors($validator)
                ->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
                ->with('message_type', 'danger');
            }

            $consultant_assignment = new ConsultantAssignment(Input::all());
            $consultant_assignment->akdn_id = $akdn_id;
            if($consultant_assignment->save()){

                $akdn = new Akdn();
                $now = New Datetime();
                $array['last_award_activity'] = $now;
                $akdn->where('id',$id)->update($array); 
            
            }
            
            $akdn = Akdn::where('id',$akdn_id)->first()->toArray();
            $akdn_email = $akdn['email'];
            $akdn_name = $akdn['other_name'].' '.$akdn['surname'];
            

            $consultant = Consultant::select('consultants.other_names','consultants.surname')
                                            ->leftJoin('consultant_assignments','consultant_assignments.consultant_id','=','consultants.id')
                                            ->where('consultants.id',$id)->first()->toArray();
            $consultant_name = $consultant['other_names'].' '.$consultant['surname'];

            $start_date = date('d-m-Y', strtotime($consultant_assignment->start_date));
            $end_date = date('d-m-Y', strtotime($consultant_assignment->end_date));
            $consultant_assignment_title = $consultant_assignment->title_of_consultancy;

            $mail_data = array(
                'akdn_name' => $akdn_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'consultant_name' => $consultant_name,
                'consultant_assignment_title' => $consultant_assignment_title,
            );
            
            Mail::send('emails.user.consultantawarded', $mail_data, function($message) use($akdn_email) {
                        
                    $message->to($akdn_email)
                            ->subject("AKDN’s MER Consultancy Registration Confirmation");
            });

            return Redirect::back()->with('message', 'Registered successfully..!')
                                   ->with('message_type', 'success');

    }

    public function show(){

    }
    
    /**
     * Admin consultancy validates, stores record in database
     * 
     * @method POST 
     * @return JSON
     */
    public function update()
	{
		if( Request::ajax() ){
            
			$rules = ConsultantAssignment::getAddRules();
			
			$all = Input::all();
            
            $consultant_name = $all['consultant_full_name'];
			$all['start_date'] =$all['start_date_dmy'];
			$all['end_date'] = $all['end_date_dmy'];
			$all['id'] = $all['id'];
            $akdn_id = $all['akdn_id'];
		    $akdn_name = $all['full_name'];
			$validator = Validator::make($all, $rules, ConsultantAssignment::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$consultant_assignment = ConsultantAssignment::find(Input::get('id'));
			
			$consultant_assignment->update($all);

			$akdn = Akdn::where('id',$akdn_id)->first()->toArray();
            $akdn_email = $akdn['email'];
   //          $akdn_name = $akdn['other_name'].' '.$akdn['surname'];
            

   //          $consultant = Consultant::select('consultants.other_names','consultants.surname')
   //                                          ->leftJoin('consultant_assignments','consultant_assignments.consultant_id','=','consultants.id')
   //                                          ->where('consultants.id',$id)->first()->toArray();
            //$consultant_name = $consultant['other_names'].' '.$consultant['surname'];

			$start_date = date('d-m-Y', strtotime($all['start_date']));
			$end_date = date('d-m-Y', strtotime($all['end_date']));
			$consultant_assignment_title = $all['title_of_consultancy'];
			$mail_data = array(
				'akdn_name' => $akdn_name,
				'start_date' => $all['start_date'],
				'end_date' => $all['end_date'],
				'consultant_name' => $consultant_name,
				'consultant_assignment_title' => $consultant_assignment_title,
			);

			Mail::send('emails.user.consultantawarded', $mail_data, function($message) use($akdn_email) {
					$message->to($akdn_email)
		            		->subject("AKDN’s Consultancy Registration Confirmation");
		    });

			return Response::json(array('success' => true));
		}
	}

    /**
     * Admin consultancy  single / multiple consultancy delete
     * 
     * @method POST 
     * @return JSON
     */
    public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			ConsultantAssignment::where('id','=',Input::get('id'))->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			ConsultantAssignment::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'Registered consultancy deleted permanently','success'=>true), 200);
	}

    /**
     * Admin consultancy export filtered records to excel 
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

            $where_str  .= " and ( "
                            . "title_of_consultancy like '%{$search}%'"
                            . " or consultants.surname like '%{$search}%'"
                            . " or consultants.title like '%{$search}%'"
                            . " or akdn_manager_email like '%{$search}%'"
                            . ")";
        }

        $columns = array('consultants.surname','other_names','title_of_consultancy',DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'), 'akdn_manager_email', DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date_dmy'), DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date_dmy'),'akdn_manager_name');

        $consultantSponsor = ConsultantAssignment::select($columns)
                ->leftJoin('akdn','akdn.id','=','consultant_assignments.akdn_id')
                ->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
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
            'Surname' => 'surname',
            'Other Names' => 'other_names',
            'Title of Project' => 'title_of_consultancy',
            'AKDN Contact' => 'full_name',
            'AKDN Contact Email' => 'akdn_manager_email',
            'Start Date' => 'start_date_dmy',
            'End Date' => 'end_date_dmy',
            'Registered By' => 'akdn_manager_name',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

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

            if($insert_data['akdn_manager_email'] != ""){

            $objPHPExcel->getActiveSheet()->getCell('E' . $rowNumber)->getHyperlink()->setUrl(strip_tags("mailto:".$insert_data['akdn_manager_email']));
            $objPHPExcel->getActiveSheet()->getStyle('E' . $rowNumber)->applyFromArray($link_style_array);
        }
            
            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'RegisteredConsultancies_export_' . date('d_m_Y') . '.xlsx';
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
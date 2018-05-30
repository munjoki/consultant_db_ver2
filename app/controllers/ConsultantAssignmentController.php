<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this controller file handles the consultant's assignments
------------------------------------------------------------------------------------------------------------------------------------------*/ 
/*----------------------------------------------------------------------------------------------------------------------------------------
	Akdn Module: 
        This controller file handles the consultant's assignments

    * @author     thinkTANKER
    * @version    AKDN MER Consultant Database Version 1.0
    * @email      hello@thinktanker.in
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class ConsultantAssignmentController extends BaseController{
	
	/**
	 * Consultant Assignment Listing, Searching, Sorting Pagination 
	 *
	 * @method GET for displaying form 
	 * @method AJAX GET for displaying Consultant Assignment datatable  
	 * @return GET -> Consultant Assignment Listing 
	 * @return AJAX GET -> JSON response Consultant Assignment
	 */
	public function index()
    {
        if( Request::ajax() ) {

            $where_str    = "1 = ?";
            $where_params = array(1);
            $consultant_ids = [];

            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');

                $where_str  .= " and ( "
                			. "title_of_consultancy like '%{$search}%'"
                			. " or consultants.surname like '%{$search}%'"
                			. " or consultants.title like '%{$search}%'"
                			. " or akdn_manager_email like '%{$search}%'"
                			. " or ".DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y")')." like '%{$search}%'"
                			. " or ".DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y")')." like '%{$search}%'"
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


            $total_count = ConsultantAssignment::select('consultant_assignments.id')
            									->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
						                        ->where('consultant_assignments.akdn_id',Auth::akdn()->get()->id)
						                        ->whereRaw($where_str,$where_params)
						                        ->count();
            
            $columns = array('consultant_assignments.id','consultant_id','status','title_of_consultancy', 'surname', 'other_names', 'akdn_manager_name', 'akdn_manager_email', DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date_dmy'), DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date_dmy'));

            $user = ConsultantAssignment::select($columns)
            		->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
            		->where('consultant_assignments.akdn_id',Auth::akdn()
            		->get()->id)
            		->whereRaw($where_str, $where_params);
                 

            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $user->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
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

        return View::make('akdn.consultant.awarded');
    } 

    /**
	 * akdn ConsultantAssignment validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function store(){
		
		if( Request::ajax() )
		{
			$id = Input::get('consultant_id');
			$rules = ConsultantAssignment::getAddRules();
			
			$validator = Validator::make(Input::all(), $rules, ConsultantAssignment::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$consultant_assignment = new ConsultantAssignment(Input::all());
			$consultant_assignment->akdn_id = Auth::akdn()->get()->id;
			if($consultant_assignment->save()){

				$akdn = new Akdn();
				$now = New Datetime();
				$array['last_award_activity'] = $now;
				$akdn->where('id',Auth::akdn()->get()->id)->update($array);	
			
			}
			$akdn_email = Auth::akdn()->get()->email;
			$akdn_name = Auth::akdn()->get()->other_name.' '.Auth::akdn()->get()->surname;
			
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

			return Response::json(array('success' => true));
		}
	}

	/**
	 * Akdn ConsultantAssignment validates, update record in database and send mail
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function update()
	{
		if( Request::ajax() ){

			$rules = ConsultantAssignment::getAddRules();
			
			$all = Input::all();


			$all['start_date'] =$all['start_date_dmy'];
			$all['end_date'] = $all['end_date_dmy'];
			$all['id'] = $all['id'];
			
			$validator = Validator::make($all, $rules, ConsultantAssignment::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$consultant_assignment = ConsultantAssignment::find(Input::get('id'));
			$consultant_assignment->akdn_id = Auth::akdn()->get()->id;
			$consultant_assignment->update($all);

			$akdn_email = Auth::akdn()->get()->email;
			$consultant_name = Input::get('name');

			$start_date = date('d-m-Y', strtotime($all['start_date']));
			$end_date = date('d-m-Y', strtotime($all['end_date']));
			$consultant_assignment_title = $all['title_of_consultancy'];
			$mail_data = array(
				'akdn_name' => Auth::akdn()->get()->other_name,
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
	 * Akdn Consultant Assignment single / multiple Consultant Assignment delete
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function destroy()
    {
        ConsultantAssignment::where('id','=',Input::get('id'))->delete();

        return Response::json(array('msg' => 'Registered consultancy deleted','success'=>true), 200);
    }
}
<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this controller file handles the consultant details
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AkdnConsultantController extends BaseController{
	
	 /**
     * Admin akdn home page 
     * 
     * @method GET 
     * @return HTML -> Akdn home page  
     */
    public function home(){

		$skills         = Skill::lists('skills_des','id');
		$specialization = Specialization::lists('spec_des','id');
		$countries_worked = Country::orderBy('country_des','asc')->lists('country_des','id');
		$languages       = Language::lists('lang_acr','id');
        //$consultant_type = Consultant::lists('consultant_type','id');

		return View::make('akdn.home', compact('skills','specialization','countries_worked','languages'));
	}

	/**
     * Akdn consultant Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying akdn consultant datatable  
     * @return GET -> AKDN consultant Listing 
     * @return AJAX GET -> JSON response AKDN consultant 
     */
    public function consultantSearch() {
        
		if( Request::ajax() ) {

			$search_params_keys = array('name','skill','specialization','languages','countries_worked','gender','consultant_type','last_consultancy');
            //dd(Input::all());
			$search_params = array();
			foreach ($search_params_keys as $search_key) {
				
				if( Input::has($search_key) ){
					$search_params[$search_key] = Input::get($search_key);
				}
			}

            $where_str 	  = "1 = ?";
            $where_params = array(1);
            $consultant_ids = [];

            if ( array_key_exists('name', $search_params))
            {
                $search 	= Input::get('name');
                $where_str  .= " and ( "
                			. "consultants.other_names like '%{$search}%'"
                			. " or consultants.surname like '%{$search}%'"
                 			. ")";
            }

            if ( array_key_exists('gender', $search_params))
            {
                $where_str  .= " and consultants.gender=?";
                $where_params[] = Input::get('gender');
            }

            // if ( array_key_exists('ratings', $search_params))
            // {
            //     $where_str  .= " and consultants.ratings>=?";
            //     $where_params[] = Input::get('ratings');
            // }
            $skills_consultants = $specialization_consultants = $consultant_type = $languages_consultants = $countries_worked_consultants = [];
            if ( array_key_exists('skill', $search_params))
            {
                $search 	= Input::get('skill');
                $skill_count = count($search);
                $skills_consultants = ConsultantSkill::select('consultant_id')->whereIn('skill_id', Input::get('skill'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $skill_count)->lists('consultant_id');
               
                //$consultant_ids = array_merge( $consultant_ids, $skills_consultants);
                $consultant_ids[] = $skills_consultants;
            }

            if ( array_key_exists('specialization', $search_params))
            {
                $search 	= Input::get('specialization');
                $specialization_count = count($search);
                $specialization_consultants = ConsultantSpecialization::select('consultant_id')->whereIn('specialization_id', Input::get('specialization'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $specialization_count)->lists('consultant_id');
                // $consultant_ids = array_merge( $consultant_ids, $specialization_consultants);
                $consultant_ids[] = $specialization_consultants;
            }

            if ( array_key_exists('languages', $search_params))
            {
                $search 	= Input::get('languages');
                $language_count = count($search);
                $languages_consultants = ConsultantLanguage::select('consultant_id')->whereIn('language_id', Input::get('languages'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $language_count)->lists('consultant_id');
                // $consultant_ids = array_merge( $consultantstant_ids, $languages_consultants);
                $consultant_ids[] = $languages_consultants;
            }
            if ( array_key_exists('countries_worked', $search_params))
            {
                $search 	= Input::get('countries_worked');
                $countries_worked_count = count($search);
                $countries_worked_consultants = ConsultantWorkedCountry::select('consultant_id')->whereIn('country_id', Input::get('countries_worked'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $countries_worked_count)->lists('consultant_id');
                
                // $consultant_ids = array_merge( $consultant_ids, $countries_worked_consultants);
                $consultant_ids[] = $countries_worked_consultants;
                
            }
            if ( array_key_exists('consultant_type', $search_params))
            {
                $search     = Input::get('consultant_type');
                //dd($search);
                if ($search == 'Both independent and Institution-affiliated')
                {   
                    $consultant_type = Consultant::select('id')
                                                        ->where('consultant_type','Both independent and Institution-affiliated')
                                                        ->where('consultant_type','Institution-affiliated')
                                                        ->lists('id');    
                }
                $consultant_type = Consultant::select('id')->where('consultant_type',$search)->lists('id');
                // $consultant_ids = array_merge( $consultant_ids, $countries_worked_consultants);
                $consultant_ids[] = $consultant_type;
            }
            if ( array_key_exists('last_consultancy', $search_params))
            {
                $search = Input::get('last_consultancy');

                $date = date('Y-m-d');
                $qry_raw = 'consultant_assignments.end_date >= DATE_SUB(' . $date . ',INTERVAL '. $search .' YEAR)';
                $ca_ids = ConsultantAssignment::where(DB::raw($qry_raw))->distinct('consultant_id')->lists('consultant_id');
                
                $consultant_ids[] = $ca_ids;
            }

            /* Consultant ids is the array in which each entry cotains the list if consutlant ids
            For exmple 
            $consultant_ids[0] = [1,3,5]
            $consultant_ids[1] = [1,3,8]

            So as the seach result was "and" condition result we need id 1,3 only and not 5 and 8 
            so using the following loop we are extracting such ids. 
            */
            //print_r($consultant_ids);
            //$finalArray = [];
            if( ! empty($consultant_ids) ){

                $consultant_ids = array_values(array_filter($consultant_ids));
                if (count($consultant_ids) <= 1) {
                    $consultant_ids = isset($consultant_ids[0]) ? $consultant_ids[0] : ['0'];
                } else {

                    for ($i = 0; $i < count($consultant_ids); $i++) { 

                        $finalArray[] = $consultant_ids[$i];
                    }

                    //$consultant_ids = $finalArray;
                    $consultant_ids = array_values(call_user_func_array('array_intersect',$finalArray));
                }
                // $consultant_ids = array_unique($consultant_ids);
                $consultant_ids = ( count($consultant_ids) ) ? implode(',', $consultant_ids) : 0;
               
                $where_str .= " and consultants.id in ({$consultant_ids})";
                // dd($consultant_ids);
            }
            elseif( empty($consultant_ids) && ( array_key_exists('languages', $search_params) || array_key_exists('countries_worked', $search_params)   || array_key_exists('specialization', $search_params)|| array_key_exists('skill', $search_params) ) ) {
                $consultant_id = 0;
                $where_str .= " and consultants.id in ({$consultant_id})";
            }

			$total_count = User::select('users.id')
                            ->leftJoin('consultants','consultants.id','=','users.id')
                            ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                            ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                            ->whereRaw($where_str, $where_params)
                            ->orderBy('consultants.created_at','desc')
                            ->count();
			
			$columns = array('users.id as id','consultants.surname','consultants.other_names','users.email','consultant_assignments.id as c',
				'consultants.telno','consultants.resume','consultants.linkedin_url','consultants.website_url','consultants.gender',
                'akdn.other_name as akdn_name','consultant_sponsors.invited_on_behalf',DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'));

			$user = User::select($columns)
                ->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
                ->leftJoin('consultants','users.id','=','consultants.id')
                ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                ->whereRaw($where_str, $where_params)
                ->orderBy('consultants.created_at','desc');
                // ->get()->toArray();
                // echo "<pre>";
                // dd($user);
                //->leftJoin('consultant_sponsors','consultants.invited_by','=','consultant_sponsors.akdn_id')

                // $total_count = User::select($columns)
                // ->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
                // ->leftJoin('consultants','users.id','=','consultants.id')
                // ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                // ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                // ->whereRaw($where_str, $where_params)
                // ->orderBy('consultants.created_at','desc')->count();

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

            $user = $user->groupBy('users.id')->get()->toArray();
          
            $resume_folder = public_path('upload/resume');

            foreach ($user as $key => $user_row) {
                
                $file_path = $resume_folder . '/' . $user_row['resume'];
                if ( !File::isFile( $file_path ) ) {

                    $user[$key]['resume'] = '';
                }
            }

            if( Input::has('name') || Input::has('skill') || Input::has('gender') || Input::has('specialization') || Input::has('languages') || Input::has('countries_worked') || Input::has('ratings') )
            {
                $akdn = Akdn::find( Auth::akdn()->get()->id );
                $akdn->last_activity = date('Y-m-d h:i:s');
                $akdn->save();
            }

            $response   = array(

				'iTotalDisplayRecords'	=> $total_count,
				'iTotalRecords'			=> $total_count,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $user
			);

			return Response::json($response, 200);
		}
	}

	 /**
     * Akdn consultant Show form => show all records of consultant
     * 
     * @method GET 
     * @param  $id => consultant id
     * @return HTML -> consultant show Page  
     */
    public function show($id)
	{
		$user = User::with('consultant')->find($id);
 
		$nationalities = $user->nationalities();
		$specialization = $user->specialization();
		$workedcountries = $user->workedcountries();
		$skills = $user->skills();
		$languages = $user->languages();
		$agencies = $user->agencies();

        $avg_ratings = $user->consultant()->getRatingLabel($id);

		return View::make('akdn.consultant.show', compact('user', 'nationalities','specialization','workedcountries','skills','languages','agencies'));
	}

     /**
     * Akdn consultant Show form => show all records of consultant
     * 
     * @method GET 
     * @return HTML -> consultant show modal  
     */
    public function quickview()
    {   
        if( Request::ajax() ){

            $id = Input::get('id');
    
            $user = User::with('consultant')->find($id);
            
            $nationalities = $user->nationalities();
            $specialization = $user->specialization();
            $workedcountries = $user->workedcountries();
            $skills = $user->skills();
            $languages = $user->languages();
            $agencies = $user->agencies();

            // $consultant = Consultant::select('consultant_assignments.id as c')
            //                             ->leftJoin('consultant_assignments','consultants.id','=','consultant_assignments.consultant_id')
            //                             ->where('consultants.id',$id)->first();

            $consultant = ConsultantAssignment::select('id as c')->where('consultant_id',$id)->first();
            
            // $avg_ratings = $user->consultant->getRatingLabel($id);

            // $ratings_commnets = ConsultantAssignment::select('comments', 'future_repeat', DB::raw('DATE_FORMAT(review_timestamp,"%d-%m-%Y %h:%i:%s %p") as review_date'))
            //                     ->whereNotNull('review_timestamp')
            //                     ->where('consultant_id', $id)
            //                     ->orderBy('review_timestamp','desc')
            //                     ->take(5)->skip(0)
            //                     ->get(); 
            return View::make('akdn.consultant.quickview', compact('id','user','consultant', 'nationalities','specialization','workedcountries','skills','languages','agencies'));
        }
    }

    
     /**
     * Akdn consultant profile download pdf
     * 
     * @method GET 
     * @return PDF file 
     */

    public function downloadPdf($id)
    {
        $user = User::with('consultant')->find($id);
            
            $nationalities = $user->nationalities();
            $specialization = $user->specialization();
            $workedcountries = $user->workedcountries();
            $skills = $user->skills();
            $languages = $user->languages();
            $agencies = $user->agencies();

            $user_name = $user->consultant->other_names.'_'.$user->consultant->surname;
            $consultant = ConsultantAssignment::select('id as c')->where('consultant_id',$id)->first();
            $date = date('d-m-y_h:i:s');
            $delivery_challan_pdf = View::make('akdn.consultant.profilepdf',compact('id','user','consultant', 'nationalities','specialization','workedcountries','skills','languages','agencies'))->render();
            //return $delivery_challan_pdf;
            $mpdf = new mPDF('','A4');
            $mpdf->WriteHTML($delivery_challan_pdf);

            $filename = $user_name.'_'.$date.'.pdf';
            
            $mpdf->Output($filename,'D');
        //return View::make('akdn.consultant.profilepdf',compact('id','user','consultant', 'nationalities','specialization','workedcountries','skills','languages','agencies'));
    }

    /**
     * Akdn Invite consultant preview mail 
     * 
     * @method POST 
     * @return JSON
     */
    public function preview()
    {
        $data = Input::all();

        $message_by = nl2br($data['message_by']);
        $name = $data['consultant_name'];
        if (!empty($data['invited_behalf'])) {
            $akdn_name = $data['invited_behalf'];
        }
        else
        {
            $akdn = Auth::akdn()->get();
            $akdn_name = $akdn['other_name'].' '.$akdn['surname'];
        }

        $html_response = View::make('akdn.preview',compact('message_by','name','akdn_name'))->render();
        
        return Response::json($html_response, 200);
    }

     
     /**
     * AKDN Consultant export filtered records to excel 
     * 
     * @method GET 
     * @return Excel File 
     */
    public function excelExport(){
 
        if(Auth::akdn()->check()){
            $auth = new Akdn();
            $auth->updateLastActivity(Auth::akdn()->get()->id);
        }

        $search_params_keys = array('name','skill','specialization','languages','countries_worked','gender','consultant_type','last_consultancy');
            //dd(Input::all());
            $search_params = array();
            foreach ($search_params_keys as $search_key) {
                
                if( Input::has($search_key) ){
                    $search_params[$search_key] = Input::get($search_key);
                }
            }

            $where_str    = "1 = ?";
            $where_params = array(1);
            $consultant_ids = [];

            if ( array_key_exists('name', $search_params))
            {
                $search     = Input::get('name');
                $where_str  .= " and ( "
                            . "consultants.other_names like '%{$search}%'"
                            . " or consultants.surname like '%{$search}%'"
                            . ")";
            }

            if ( array_key_exists('gender', $search_params))
            {
                $where_str  .= " and consultants.gender=?";
                $where_params[] = Input::get('gender');
            }

            // if ( array_key_exists('ratings', $search_params))
            // {
            //     $where_str  .= " and consultants.ratings>=?";
            //     $where_params[] = Input::get('ratings');
            // }
            $skills_consultants = $specialization_consultants = $consultant_type = $languages_consultants = $countries_worked_consultants = [];
            if ( array_key_exists('skill', $search_params))
            {
                $search     = Input::get('skill');
                $skill_count = count($search);
                $skills_consultants = ConsultantSkill::select('consultant_id')->whereIn('skill_id', Input::get('skill'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $skill_count)->lists('consultant_id');
               
                //$consultant_ids = array_merge( $consultant_ids, $skills_consultants);
                $consultant_ids[] = $skills_consultants;
            }

            if ( array_key_exists('specialization', $search_params))
            {
                $search     = Input::get('specialization');
                $specialization_count = count($search);
                $specialization_consultants = ConsultantSpecialization::select('consultant_id')->whereIn('specialization_id', Input::get('specialization'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $specialization_count)->lists('consultant_id');
                // $consultant_ids = array_merge( $consultant_ids, $specialization_consultants);
                $consultant_ids[] = $specialization_consultants;
            }

            if ( array_key_exists('languages', $search_params))
            {
                $search     = Input::get('languages');
                $language_count = count($search);
                $languages_consultants = ConsultantLanguage::select('consultant_id')->whereIn('language_id', Input::get('languages'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $language_count)->lists('consultant_id');
                // $consultant_ids = array_merge( $consultantstant_ids, $languages_consultants);
                $consultant_ids[] = $languages_consultants;
            }
            if ( array_key_exists('countries_worked', $search_params))
            {
                $search     = Input::get('countries_worked');
                $countries_worked_count = count($search);
                $countries_worked_consultants = ConsultantWorkedCountry::select('consultant_id')->whereIn('country_id', Input::get('countries_worked'))->groupBy('consultant_id')->havingRaw('COUNT(consultant_id) = '. $countries_worked_count)->lists('consultant_id');
                
                // $consultant_ids = array_merge( $consultant_ids, $countries_worked_consultants);
                $consultant_ids[] = $countries_worked_consultants;
                
            }
            if ( array_key_exists('consultant_type', $search_params))
            {
                $search     = Input::get('consultant_type');
                //dd($search);
                if ($search == 'Both independent and Institution-affiliated')
                {   
                    $consultant_type = Consultant::select('id')
                                                        ->where('consultant_type','Both independent and Institution-affiliated')
                                                        ->where('consultant_type','Institution-affiliated')
                                                        ->lists('id');    
                }
                $consultant_type = Consultant::select('id')->where('consultant_type',$search)->lists('id');
                // $consultant_ids = array_merge( $consultant_ids, $countries_worked_consultants);
                $consultant_ids[] = $consultant_type;
            }
            if ( array_key_exists('last_consultancy', $search_params))
            {
                $search = Input::get('last_consultancy');

                $date = date('Y-m-d');
                $qry_raw = 'consultant_assignments.end_date >= DATE_SUB(' . $date . ',INTERVAL '. $search .' YEAR)';
                $ca_ids = ConsultantAssignment::where(DB::raw($qry_raw))->distinct('consultant_id')->lists('consultant_id');
                
                $consultant_ids[] = $ca_ids;
            }

            /* Consultant ids is the array in which each entry cotains the list if consutlant ids
            For exmple 
            $consultant_ids[0] = [1,3,5]
            $consultant_ids[1] = [1,3,8]

            So as the seach result was "and" condition result we need id 1,3 only and not 5 and 8 
            so using the following loop we are extracting such ids. 
            */
            //print_r($consultant_ids);
            //$finalArray = [];
            if( ! empty($consultant_ids) ){

                $consultant_ids = array_values(array_filter($consultant_ids));
                if (count($consultant_ids) <= 1) {
                    $consultant_ids = isset($consultant_ids[0]) ? $consultant_ids[0] : ['0'];
                } else {

                    for ($i = 0; $i < count($consultant_ids); $i++) { 

                        $finalArray[] = $consultant_ids[$i];
                    }

                    //$consultant_ids = $finalArray;
                    $consultant_ids = array_values(call_user_func_array('array_intersect',$finalArray));
                }
                // $consultant_ids = array_unique($consultant_ids);
                $consultant_ids = ( count($consultant_ids) ) ? implode(',', $consultant_ids) : 0;
               
                $where_str .= " and consultants.id in ({$consultant_ids})";
                // dd($consultant_ids);
            }
            elseif( empty($consultant_ids) && ( array_key_exists('languages', $search_params) || array_key_exists('countries_worked', $search_params)   || array_key_exists('specialization', $search_params)|| array_key_exists('skill', $search_params) ) ) {
                $consultant_id = 0;
                $where_str .= " and consultants.id in ({$consultant_id})";
            }

            $total_count = User::select('users.id')
                            ->leftJoin('consultants','consultants.id','=','users.id')
                            ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                            ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                            ->whereRaw($where_str, $where_params)
                            ->orderBy('consultants.created_at','desc')
                            ->count();
            
            $columns = array(
                'users.id','consultants.surname','consultants.other_names','consultants.ratings','users.email',
                'consultants.alternate_email','consultants.skypeid','consultant_assignments.id as c',
                'consultants.telno','consultants.resume','consultants.linkedin_url','consultants.website_url','consultants.gender',DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'));

            $user = User::select($columns)
                ->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
                ->leftJoin('consultants','users.id','=','consultants.id')
                ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                ->whereRaw($where_str, $where_params)
                ->orderBy('consultants.created_at','desc');
                // ->get()->toArray();
                // echo "<pre>";
                // dd($user);
                //->leftJoin('consultant_sponsors','consultants.invited_by','=','consultant_sponsors.akdn_id')

                // $total_count = User::select($columns)
                // ->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
                // ->leftJoin('consultants','users.id','=','consultants.id')
                // ->leftJoin('consultant_sponsors','users.email' ,'=' ,'consultant_sponsors.email')
                // ->leftJoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
                // ->whereRaw($where_str, $where_params)
                // ->orderBy('consultants.created_at','desc')->count();

            $users = $user->groupBy('users.id')->get()->toArray();
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
                'Gender' => 'gender',
                'Email' => 'email',
                'Alternate Email' => 'alternate_email',
                'Tel No' => 'telno',
                'Skype Id' => 'skypeid',
                'LinkedIn URL' => 'linkedin_url',
                'Website/blog URL' => 'website_url',
                'Previous Consultancies' =>'c',
                'Invited By' => 'full_name',
            );

            $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:k1')->getFont()->setBold(true);

            $link_style_array = [
                'font'  => [
                    'color' => ['rgb' => '0000FF'],
                    'underline' => 'single'
                ]
            ];
            // adding user data
            $rowNumber = 2;
            $col = 'A';
            foreach ($users as $user_data) {
            
                $user_data['gender'] = ('1' == $user_data['gender']) ? "Male" : "Female";
                
                $insert_data = array();
                foreach ($header as $column_name) {
                    
                    $insert_data[$column_name] = $user_data[$column_name];
                }

                // echo "<pre>";
                // print_r($insert_data);
                // print_r($insert_data);
                // exit;

                $objPHPExcel->getActiveSheet()->fromArray($insert_data, NULL, 'A' . $rowNumber );

                if($insert_data['email'] != ""){

                    $objPHPExcel->getActiveSheet()->getCell('D' . $rowNumber)->getHyperlink()->setUrl(strip_tags('mailto:' . $insert_data['email']));
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $rowNumber)->applyFromArray($link_style_array);
                }

                if($insert_data['alternate_email'] != ""){

                    $objPHPExcel->getActiveSheet()->getCell('E' . $rowNumber)->getHyperlink()->setUrl(strip_tags('mailto:' . $insert_data['alternate_email']));
                    $objPHPExcel->getActiveSheet()->getStyle('E' . $rowNumber)->applyFromArray($link_style_array);
                }

                if($insert_data['telno'] !=""){
                    $preg_match = preg_replace('/^(\d+)$/',"+$1",$insert_data['telno']);
                    $insert_data['telno'] = $preg_match;
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $rowNumber,$insert_data['telno'], PHPExcel_Cell_DataType::TYPE_STRING);
                }

                if($insert_data['linkedin_url'] != ""){

                    $objPHPExcel->getActiveSheet()->getCell('H' . $rowNumber)->getHyperlink()->setUrl(strip_tags($insert_data['linkedin_url']));
                    $objPHPExcel->getActiveSheet()->getStyle('H' . $rowNumber)->applyFromArray($link_style_array);
                }

                if($insert_data['website_url'] != ""){

                    $objPHPExcel->getActiveSheet()->getCell('I' . $rowNumber)->getHyperlink()->setUrl(strip_tags($insert_data['website_url']));
                    $objPHPExcel->getActiveSheet()->getStyle('I' . $rowNumber)->applyFromArray($link_style_array);
                }
                if($insert_data['c'] != ""){

                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowNumber,'Yes');
                }
                else
                {
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowNumber,'No');
                }
                
                $rowNumber++;
            }

            // setting up string format for mobile number
            $objPHPExcel->getActiveSheet()->getStyle('F1:F'. $rowNumber)
                        ->getNumberFormat()
                        ->setFormatCode('0000');

            for($j='A'; $j<'No';$j++){
        
                $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
            }

            $filename = 'consultant_search_export_' . date('d_m_Y') . '.xlsx';
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
<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles the Consultant registration
------------------------------------------------------------------------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/
class AdminConsultantController extends BaseController{
	
	/**
     * Admin consultant Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying consultant datatable  
     * @return GET -> consultant Listing 
     * @return AJAX GET -> JSON response consultant 
     */
	public function index()
	{
		if( Request::ajax() ) {

            $where_str 	  = "1 = ?";
            $where_params = array(1);

            /* Common Search Filter */
            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. "consultants.other_names like '%{$search}%'"
                			. " or consultants.surname like '%{$search}%'"
                			. " or users.email like '%{$search}%'"
                			. " or consultants.telno like '%{$search}%'"
                 			. ")";
            }

			$total_count = User::select('users.id')->leftJoin('consultants','consultants.id','=','users.id')
						  ->whereRaw($where_str,$where_params)
						  ->count();
			
			$columns = array('users.id','consultants.surname','consultants.other_names','consultants.gender','users.email',
				'consultants.telno','consultants.resume','consultants.linkedin_url','consultants.website_url','delete_request','consultant_assignments.id as privious_consutancies');

			$user = User::select($columns)
						->leftJoin('consultants','consultants.id','=','users.id')
						->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
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

				'iTotalDisplayRecords'	=> $total_count,
				'iTotalRecords'			=> $total_count,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $user
			);
			
			return Response::json($response, 200);
		}

		return View::make('admin.user.index');
	}
	
	/**
	 * Admin consultant Show form =>show all records of consultant
	 * 
	 * @method GET 
	 * @param  string  $id => consultant id
	 * @return HTML -> consultant show Page  
	 */
	public function show($id)
	{

		$user = User::find($id);
		$nationalities = $user->nationalities();
		$specialization = $user->specialization();
		$workedcountries = $user->workedcountries();
		$skills = $user->skills();
		$languages = $user->languages();
		$agencies = $user->agencies();

		$privious_consutancies = User::select('consultant_assignments.id as privious_consutancies')
						->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
						->where('users.id' ,'=',$id)
						->lists('privious_consutancies');
						
		return View::make('admin.consultant.show', compact('privious_consutancies','user', 'nationalities','specialization','workedcountries','skills','languages','agencies'));
	}

	/**
	 * Admin consultant Edit form 
	 * 
	 * @method GET 
	 * @param  string  $id => consultant id
	 * @return HTML -> consultant Form  
	 */
	public function edit($id){

		$user = User::with('consultant','consultantLanguage','consultantNationality')->find($id);
		
		// to populate dropdowns
		$city           = City::lists('city_des','id');
		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');
		$language       = Language::lists('lang_acr','id');
		$skills         = Skill::lists('skills_des','id');
		$specialization = Specialization::lists('spec_des','id');
		$country_worked = $country;
		$agencies 		= Agency::orderBy('acronym','asc')->lists('acronym','id');

		// existing nationalities
		$consultant_nationalities = ($user->consultantNationality) ? array_fetch($user->consultantNationality->toArray(), 'country_id') : array();
		
		// existing skills
		$consultant_skills = ($user->consultantSkill) ? array_fetch($user->consultantSkill->toArray(), 'skill_id') : array();

		// existing specialization
		$consultant_specialization = ($user->consultantSpecialization) ? array_fetch($user->consultantSpecialization->toArray(), 'specialization_id') : array();

		// existing workedcountries
		$consultant_workedcountries = ($user->consultantWorkedCountry) ? array_fetch($user->consultantWorkedCountry->toArray(), 'country_id') : array();

		// existing selected languages and their level
		$consultant_languages = $user->consultantLanguage->toArray();

		// existing workedcountries
		$consultant_agencies = ($user->consultantAgencies) ? array_fetch($user->consultantAgencies->toArray(), 'agency_id') : array();

		$load_consultant_languages = array();
		if($consultant_languages){
			foreach ($consultant_languages as $value) {
				// echo "<pre>";
				// print_r($value);
				// exit();
				$lang_data = array();
				$lang_data['language'] = $value['language_id'];
				$lang_data['speaking_level'] = $value['speaking_level'];
				$lang_data['reading_level'] = $value['reading_level'];
				$lang_data['writing_level'] = $value['writing_level'];
				$lang_data['understanding_level'] = $value['understanding_level'];
				$load_consultant_languages[] = $lang_data;
			}	
		}
		
		return View::make('admin.consultant.edit',compact(
			'user','city','country','language', 'skills','specialization','country_worked','agencies',
			'load_consultant_languages','consultant_nationalities','consultant_skills',
			'consultant_specialization','consultant_workedcountries','consultant_agencies'
		));
	}

	/**
     * Admin consultant validates and update record in database
     * 
     * @method POST 
	 * @param  string  $id => consultant id
     * @return if failed -> redirect back to consultant edit form with error messages 
     * @return if successful -> redirect back to consultant edit form with successful confirmation 
     */
	public function update($id){


		$user_id = Input::get('user_id');

		$user = User::with('consultant')->find($user_id);

		$post_data = Input::all();

		$consultant = Consultant::find($user_id);

		$nationalities = Input::get('nationality');
		
		$skills = Input::get('skill', array());
		$specializations = Input::get('specialization',array());
		$worked_countries = Input::get('country_worked',array());
		$consultant_agencies = Input::get('consultant_agencies',array());
		if(isset($post_data['languages']['languages'])){

			$languages = $post_data['languages']['languages'];
		}else{
			$languages = '';
		}

		$rules = Consultant::editRules();

		
		if ($consultant->resume == null) {
			if (!Input::hasFile('resume')) {
				$rules['linkedin_url'] = 'url|required_without_all:resume,website_url';
				$rules['website_url'] = 'url|required_without_all:resume,linkedin_url';
			}
			$rules['resume'] = 'mimes:doc,docx,txt,pdf|max:1024|extension:txt,doc,docx,pdf|required_without_all:linkedin_url,website_url';
		}

		$validation = Validator::make($post_data, $rules,Consultant::$messages);

		if ($validation->passes()){

			$old_resume = $consultant->resume;

			$consultant->fill(Input::except('resume','terms_conditions','email'));
			
			if(Input::hasFile('resume')){

	        	$destinationPath = public_path().'/upload/resume/';

	        	if ($old_resume != "") {
	        		$resume_to_delete = $destinationPath . $old_resume;
	        		@unlink($resume_to_delete);
	        	}

	            $file = Input::file('resume');
	            
	            $extension = $file->getClientOriginalExtension();
	            $microtime = microtime();
	            $search = array('.',' ');
	            $microtime = str_replace($search, "_", $microtime);
	            $doc_name = $microtime.'.'.$extension;

	            $file->move($destinationPath,$doc_name);
	           
	            $consultant->resume = $doc_name;
			}

			$consultant->save();

			if($languages)
			{

				ConsultantLanguage::where('consultant_id', $user_id)->delete();
				foreach ($languages as $key => $language) 
				{
					if($language['language'] != ""){

						$consultant_language = new ConsultantLanguage();
						$consultant_language->consultant_id = $user->id;
						$consultant_language->language_id = $language['language'];
						// $consultant_language->language_level = $language['lang_level'];
						$consultant_language->speaking_level = $language['speaking_level'];
						$consultant_language->reading_level = $language['reading_level'];
						$consultant_language->writing_level = $language['writing_level'];
						$consultant_language->understanding_level = $language['understanding_level'];
						$consultant_language->save();
					}
				}
			}

			$nationality_list = array();
			
			if( count($nationalities) > 0 ){

				foreach ($nationalities as $nationality_id) {
					$nationality['country_id'] = $nationality_id;
					array_push($nationality_list, $nationality);
				}

				ConsultantNationality::where('consultant_id', $user_id)->delete();
				$user->consultantNationality()->createMany($nationality_list);
	
			}
			
			$skill_list = array();
			
			if( count($skills) > 0 ){

				foreach ($skills as $skill_id) {
					$skill['skill_id'] = $skill_id;
					array_push($skill_list, $skill);
				}

				ConsultantSkill::where('consultant_id', $user_id)->delete();
				$user->consultantSkill()->createMany($skill_list);
			}
			

			$specialization_list = array();
			
			if( count($specializations) > 0 ){

				foreach ($specializations as $specialization_id) {
					$specialization['specialization_id'] = $specialization_id;
					array_push($specialization_list, $specialization);
				}

				ConsultantSpecialization::where('consultant_id', $user_id)->delete();
				$user->consultantSpecialization()->createMany($specialization_list);
			}
			

			$worked_country_list = array();
			
			if( count($worked_countries) > 0 ){

				foreach ($worked_countries as $worked_country_id) {
					$worked_country['country_id'] = $worked_country_id;
					array_push($worked_country_list, $worked_country);
				}
				ConsultantWorkedCountry::where('consultant_id', $user_id)->delete();
				$user->consultantWorkedCountry()->createMany($worked_country_list);
			}

			$consultant_agency_list = array();
			
			if( count($consultant_agencies) > 0 ){

				foreach ($consultant_agencies as $consultant_agency_id) {
					$consultant_agency['agency_id'] = $consultant_agency_id;
					array_push($consultant_agency_list, $consultant_agency);
				}
				ConsultantAgency::where('consultant_id', $user_id)->delete();
				$user->consultantAgencies()->createMany($consultant_agency_list);
			}

			return Redirect::back()->with('message', 'You have successfully updated Consultant')
								   ->with('message_type', 'success');
		 }else{

				return Redirect::back()
				->withInput(Input::all())
				->withErrors($validation)
				->with('message', 'Some required field(s) have been left blank. Please correct the error(s)!')
				->with('message_type', 'danger');
		}
	}

	/**
	 * Admin consultant  single / multiple resume delete
	 * 
	 * @param  $id => consultant resume id
	 * @method POST 
	 * @return JSON
	 */
	public function deleteResume($id)
	{
		$consultant = Consultant::find($id);

		$old_resume = $consultant->resume;
		$destinationPath = public_path().'/upload/resume/';

    	if($old_resume != ""){
    		$resume_to_delete = $destinationPath . $old_resume;
    		unlink($resume_to_delete);
    		$consultant->resume = null;
    		$consultant->save();
    	}
    	return Response::json(array('msg' => 'Resume deleted Successful','success'=>true), 200);
	}

	
	/**
	 * Admin consultant ShowPreviousConsultancies form =>show all records of Previous Consultancy
	 * 
	 * @method GET 
	 * @param  string  $id => consultantcy id
	 * @return HTML 
	 */
	public function showPreviousConsultancies($id)
	{
		if( Request::ajax() ) {
            $where_str    = "1 = ?";
            $where_params = array(1);
            $consultant_id = $id;

            if (Input::has('sSearch'))
            {
                $search 	= Input::get('sSearch');
                $where_str  .= " and ( "
                			. "title_of_consultancy like '%{$search}%'"
                			. " or akdn.other_name like '%{$search}%'"
                			. " or akdn.email like '%{$search}%'"
                			. " or akdn_manager_email like '%{$search}%'"
                 			. ")";
            }

            $total_count = ConsultantAssignment::select('consultant_assignments.id')->leftJoin('consultants','consultant_id','=','consultant_assignments.consultant_id')
                          ->where('consultant_assignments.consultant_id',$consultant_id)
                          ->whereRaw($where_str,$where_params)
                          ->count();
            
            $columns = array('consultant_assignments.id','consultant_id' ,'title_of_consultancy', 'consultants.other_names as name', 'consultants.email as email','akdn_manager_name', 'akdn_manager_email', DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date_dmy'), DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date_dmy'),'status');

            $user = ConsultantAssignment::select($columns)
            		->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
            		->where('consultant_assignments.consultant_id',$consultant_id)
            		->whereRaw($where_str, $where_params)
                 	->orderBy('consultant_assignments.created_at','DESC');

                    

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
                'aaData'                => $user,
                'success'				=> true
            );

            return Response::json($response, 200);
        }
        $consultant_id = $id;
        // dd($consultant_id);
        $consultantAssignment = ConsultantAssignment::select('consultant_id')->where('consultant_id',$consultant_id)->first();
        $consultant = null;

        if ($consultantAssignment) {    
            if ($consultantAssignment->consultant_id != null) {
                $consultant = Consultant::where('id',$consultantAssignment->consultant_id)->first();
            }
        }
        return View::make('admin.consultant.index',compact('consultant'));
	}

	 /**
     * Admin consultant export filtered records to excel 
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
            			. "consultants.other_names like '%{$search}%'"
            			. " or consultants.surname like '%{$search}%'"
            			. " or users.email like '%{$search}%'"
            			. " or consultants.telno like '%{$search}%'"
             			. ")";
        }

        $columns = array('consultants.surname','consultants.other_names','consultants.gender','users.email',
				'consultants.telno','consultants.resume','consultants.linkedin_url','consultants.website_url','consultant_assignments.id as privious_consutancies');

		$consultantSponsor = User::select($columns)
					->leftJoin('consultants','consultants.id','=','users.id')
					->leftJoin('consultant_assignments','users.id','=','consultant_assignments.consultant_id')
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
            'First/Given Name' => 'other_names',
            'Gender' => 'gender',
            'Email' => 'email',
            'Tele NO.' => 'telno',
            'Resume' => 'resume',
            'LinkedIn' => 'linkedin_url',
            'Blog' =>'website_url',
            'Previous Consultancies' =>'privious_consutancies',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

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
           
           	
        	if($insert_data['gender'] == 1){

                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowNumber,'Male');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowNumber,'Female');
            }

            if($insert_data['email'] != ""){

    			$objPHPExcel->getActiveSheet()->getCell('D' . $rowNumber)->getHyperlink()->setUrl(strip_tags("mailto:".$insert_data['email']));
				$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNumber)->applyFromArray($link_style_array);
			}

			if($insert_data['telno'] !=""){
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowNumber,$insert_data['telno'], PHPExcel_Cell_DataType::TYPE_STRING);
            }

            if($insert_data['resume'] !=""){

                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber,'Yes');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber,'No');
            }


        	if($insert_data['linkedin_url'] != ""){

                $objPHPExcel->getActiveSheet()->getCell('G' . $rowNumber)->getHyperlink()->setUrl(strip_tags($insert_data['linkedin_url']));
                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowNumber)->applyFromArray($link_style_array);
            }

            if($insert_data['website_url'] != ""){

                $objPHPExcel->getActiveSheet()->getCell('H' . $rowNumber)->getHyperlink()->setUrl(strip_tags($insert_data['website_url']));
                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowNumber)->applyFromArray($link_style_array);
            }
            if($insert_data['privious_consutancies'] != ""){

                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowNumber,'Yes');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowNumber,'No');
            }
           
            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'RegisteredConsultants_export_' . date('d_m_Y') . '.xlsx';
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
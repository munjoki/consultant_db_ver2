<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles akdn user registration updating and deletion
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles the AKDN user registration, updating and deletion

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AdminAkdnController extends BaseController{

	/**
	 * Admin Akdn Listing, Searching, Sorting Pagination 
	 *
	 * @method GET for displaying form 
	 * @method AJAX GET for displaying akdn datatable  
	 * @return GET -> AKDN Listing 
	 * @return AJAX GET -> JSON response AKDN users 
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
                			. " other_name like '%{$search}%'"
                			. " or surname like '%{$search}%'"
                			. " or email like '%{$search}%'"
                 			. ")";
            }

			$total_count = Akdn::select('akdn.id')
								->leftjoin('akdn_agencies','akdn_agencies.akdn_id','=','akdn.id')
								->leftjoin('agencies','akdn_agencies.akdn_id','=','agencies.id')
								->leftjoin('akdn_nationalities','akdn_nationalities.akdn_id','=','akdn.id')
								->leftjoin('countries','countries.id','=','akdn_nationalities.akdn_id')
								->whereRaw($where_str,$where_params)
								->count();
			
			$columns = array('akdn.id','surname','other_name','email',DB::raw('countries.country_des as akdnnationality'),DB::raw('agencies.acronym as agency'),'is_verified');

			$akdn = Akdn::select($columns)
								->leftjoin('akdn_agencies','akdn_agencies.akdn_id','=','akdn.id')
								->leftjoin('agencies','akdn_agencies.agency_id','=','agencies.id')
								->leftjoin('akdn_nationalities','akdn_nationalities.akdn_id','=','akdn.id')
								->leftjoin('countries','countries.id','=','akdn_nationalities.country_id')
								->whereRaw($where_str, $where_params);

			// $akdn = Akdn::select($columns)->with('Akdnnationality','agency')->whereRaw($where_str, $where_params);
							
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $akdn->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
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

                    $akdn->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $akdn = $akdn->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $total_count,
				'iTotalRecords'			=> $total_count,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $akdn
			);
			
			return Response::json($response, 200);
		}

		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');
		$agencies 		= Agency::orderBy('acronym','asc')->lists('acronym','id');
		
		return View::make('admin.akdn.index',compact('country','agencies'));
	}

	/**
	 * Admin akdn create form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn Create Form  
	 */
	public function create(){

		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');
		$agencies 		= Agency::orderBy('acronym','asc')->lists('acronym','id');
		
		return View::make('admin.akdn.create',compact('country','agencies'));
	}

	/**
	 * Admin akdn Edit form 
	 * 
	 * @method GET 
	 * @param  string  $id => akdn user id
	 * @return HTML -> Akdn Edit Form  
	 */
	public function edit($id){

		$country = Country::orderBy('country_des','asc')->lists('country_des','id');
		$agencies = Agency::orderBy('acronym','asc')->lists('acronym','id');
	
		$akdn = Akdn::find($id);
		$selected_agency = Agency::select(DB::raw('agencies.acronym as agencies,agencies.id as id'))
								->where('akdn_agencies.akdn_id',$id)
								->leftjoin('akdn_agencies','akdn_agencies.agency_id','=','agencies.id')
								->lists('id','agencies');

		$selected_country = Country::select(DB::raw('countries.country_des as country,countries.id as id'))
								->where('akdn_nationalities.akdn_id',$id)
								->leftjoin('akdn_nationalities','akdn_nationalities.country_id','=','countries.id')
								->lists('id','country');
		return View::make('admin.akdn.edit',compact('akdn','country','agencies','selected_agency','selected_country'));
	}
	
	/**
	 * Admin akdn validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to akdn create form with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function store(){

		$akdn = Input::all();

		$nationalities = Input::get('nationality');
		
		$agencies = Input::get('consultant_agencies',array());

		$validation = Validator::make($akdn, Akdn::$rules,Akdn::$messages);

		if ($validation->passes()){

			$akdn = new Akdn();
			
			$email 	   = Input::get('email');
			$user_name = Input::get('other_name');
			$raw_password = Str::random(8);
			$token  = Str::random(80);
			$surname = Input::get('surname');
			$akdn->surname = $surname;
			$akdn->other_name = $user_name;
			$akdn->email = $email ;
			$akdn->password = Hash::make($raw_password);
			$akdn->remember_token = $token;
			
			$user_name = $user_name.''.$surname;
			$userinfo  = array(
				'email' 	=> $email,
				'user_name' => $user_name,
				'password'  => $raw_password,
				'token'     => $token,
			);

			Mail::send('emails.akdn.registration', $userinfo, function($message) use ($email, $user_name){
				$message->to($email, $user_name)
						->subject("Welcome to AKDN's MER Consultant Database");
			});

			$akdn->save();

			$akdn = Akdn::find($akdn->id);
			$nationality_list = array();
			
			foreach ($nationalities as $nationality_id) {
				$nationality['country_id'] = $nationality_id;
				array_push($nationality_list, $nationality);
			}
			$agency_list = array();

			foreach ($agencies as $agency_id) {

				$agency_list['agency_id'] = $agency_id;
			}

			$akdn->Akdnnationality()->attach($agencies);
			$akdn->Agency()->attach($agency_list);

			return Redirect::back()->with('message', 'Thank you for registering with our consultant database. A confirmation has been sent to the primary email address you provided. Please click on the link contained in this email in order to complete your registration.')
								   ->with('message_type', 'success');
		 }else{

				$errors = $validation->errors()->toArray();
				return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
				->with('message_type', 'danger');
			
		}
	}
	
	/**
	 * Admin akdn validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @param  string  $id => akdn user id
	 * @return if failed -> redirect back to akdn edit form with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function update($id){

		$akdn = Input::all();

		$nationalities = Input::get('nationality');
		
		$agencies = Input::get('consultant_agencies',array());


		$validation = Validator::make($akdn,Akdn::$editRules,Akdn::$messages);

		if ($validation->passes()){

			
			Akdn::where('id','=',$id)->update(Input::except('_token','nationality','consultant_agencies'));

			$nationality_list = array();
			
			foreach ($nationalities as $nationality_id) {
				$nationality['country_id'] = $nationality_id;
				array_push($nationality_list, $nationality);
			}
			$agency_list = array();

			foreach ($agencies as $agency_id) {

				$agency['agency_id'] = $agency_id;
				array_push($agency_list, $agency);

			}
			// print_r($agency_list);exit;
			$akdn = Akdn::find($id);
			DB::table('akdn_nationalities')->where('akdn_id',$id)->delete();
			DB::table('akdn_agencies')->where('akdn_id',$id)->delete();
			$akdn->Akdnnationality()->attach($nationality_list);
			$akdn->Agency()->attach($agency_list);

			return Redirect::back()->with('message', 'Akdn User updated successfully.!')
								   ->with('message_type', 'success');
		 }
		 else
		 {

			$errors = $validation->errors()->toArray();
			return Redirect::back()
			->withInput()
			->withErrors($validation)
			->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
			->with('message_type', 'danger');
		}
	}
	
	/**
	 * Admin akdn  single / multiple users delete
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			Akdn::where('id','=',Input::get('id'))->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			Akdn::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'AkdnUser deleted permanently','success'=>true), 200);
	}

	/**
	 * Admin akdn user export filtered records to excel 
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
                			. " other_name like '%{$search}%'"
                			. " or surname like '%{$search}%'"
                			. " or email like '%{$search}%'"
                 			. ")";
        }

        $columns = array('surname','other_name','email','countries.country_des','agencies.acronym','is_verified');

		$akdn = Akdn::select($columns)
									->leftjoin('akdn_agencies','akdn.id','=','akdn_agencies.akdn_id')
									->leftjoin('agencies','agencies.id','=','akdn_agencies.agency_id')
									->leftjoin('akdn_nationalities','akdn.id','=','akdn_nationalities.akdn_id')
									->leftjoin('countries','countries.id','=','akdn_nationalities.country_id')
		           					->whereRaw($where_str, $where_params);

        $akdn = $akdn->get()->toArray();
          
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'surname' => 'surname',
            'Other name' => 'other_name',
            'Email Address' => 'email',
            'Country'=> 'country_des',
            'Agency'=>'acronym',
            'Verified' => 'is_verified',
        );

        $objPHPExcel->getActiveSheet()->fromArray( array_keys($header), NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $link_style_array = [
            'font'  => [
                'color' => ['rgb' => '0000FF'],
                'underline' => 'single'
            ]
        ];
        $rowNumber = 2;
        $col = 'A';

        foreach ($akdn as $akdn_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {

                $insert_data[$column_name] = $akdn_data[$column_name];
            }

            $objPHPExcel->getActiveSheet()->fromArray($akdn_data, NULL, 'A' . $rowNumber );

            if($insert_data['email'] != ""){

			    $objPHPExcel->getActiveSheet()->getCell('C' . $rowNumber)->getHyperlink()->setUrl(strip_tags("mailto:".$insert_data['email']));
			    $objPHPExcel->getActiveSheet()->getStyle('C' . $rowNumber)->applyFromArray($link_style_array);
			}

            if($insert_data['is_verified'] == 1)
            {

                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber,'Yes');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber,'No');
            }

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'AkdnUser_export_' . date('d_m_Y') . '.xlsx';
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
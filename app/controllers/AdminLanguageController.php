<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles languages 
------------------------------------------------------------------------------------------------------------------------------------------*/ 
/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles the languages

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 
class AdminLanguageController Extends BaseController
{

	/**
     * Admin Language Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying language datatable  
     * @return GET -> Language Listing 
     * @return AJAX GET -> JSON response language 
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
                			. "lang_acr like '%{$search}%'"
                			. " or lang_des like '%{$search}%'"
                 			. ")";
            }

			$total_count = Language::select('id')
						  ->whereRaw($where_str,$where_params)
						  ->count();
			
			$columns = array('id','lang_acr','lang_des');

			$language = Language::select($columns)->whereRaw($where_str, $where_params);
							
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $language->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
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

                    $language->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $language = $language->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $total_count,
				'iTotalRecords'			=> $total_count,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $language
			);
			
			return Response::json($response, 200);
		}

		return View::make('admin.language.index');
	}

	/**
     * Admin language create form 
     * 
     * @method GET 
     * @return HTML -> admin language Create Form  
     */
    public function create(){

		return View::make('admin.language.create');
	}

	/**
     * Admin language validates, stores record in database
     * 
     * @method POST 
     * @return if failed -> redirect back to language create form with error messages 
     * @return if successful -> redirect back to language create form with successful confirmation 
     */
    public function store()
	{
		$all = Input::all();

		$validation = Validator::make($all,Language::$rules,Language::$messages);

		if($validation->passes()){

			$language = new Language($all);
			$language->save();
			return Redirect::back()->with('message', 'Language Added suuccssully..!')
								   ->with('message_type', 'success');

		}else
		{
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
				->with('message_type', 'danger');
		}
	}

	/**
     * Admin language validates, update record in database
     * 
     * @method POST 
     * @return JSON if failed -> redirect back to language edit form with error messages 
     * @return JSON if successful -> redirect back to language edit form with successful confirmation 
     */
    public function update()
	{
		if( Request::ajax() ){

			$all = Input::all();

			$all['lang_acr'] =$all['lang_acr'];
			$all['lang_des'] = $all['lang_des'];
			$all['id'] = $all['id'];
			
			$validator = Validator::make($all, Language::$rules, Language::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$language = Language::find(Input::get('id'));
			$language->update($all);


			return Response::json(array('success' => true));
		}
	}

	public function show(){}
	
    /**
     * Admin language  single / multiple language delete
     * 
     * @method POST 
     * @return JSON
     */
    public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			Language::where('id','=',Input::get('id'))->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			Language::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'Language deleted permanently','success'=>true), 200);
	}

    /**
     * Admin lang uage export filtered records to excel 
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
                            . "lang_acr like '%{$search}%'"
                            . " or lang_des like '%{$search}%'"
                            . ")";
        }

        $columns = array('lang_acr','lang_des');

        $language = Language::select($columns)->whereRaw($where_str, $where_params);
                        

        $language = $language->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'Language' => 'lang_acr',
            'Language Description' => 'lang_des',
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
        foreach ($language as $language_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
                $insert_data[$column_name] = $language_data[$column_name];
            }
            
            $objPHPExcel->getActiveSheet()->fromArray($language_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'Language_export_' . date('d_m_Y') . '.xlsx';
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
<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles the areas of specialization for a consultant
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles the the areas of specialization for a consultant

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 


class AdminSpecializationController extends BaseController
{
	/**
     * Admin specialization(Thematic Area) Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying specialization datatable  
     * @return GET -> AKDN Listing 
     * @return AJAX GET -> JSON response specialization
     */
    public function index(){

		if(Request::ajax()){

			$where_str 	  = "1 = ?";
            $where_params = array(1);  

            if (Input::has('sSearch'))
            {
                $search     = Input::get('sSearch');

                $where_str .= " and ( spec_des like '%{$search}%'"
                            . ")";
            }

			$columns = array('id','spec_des');

            $specialization_count = Specialization::select('id')
							->whereRaw($where_str, $where_params)
							->count();

			$specialization = Specialization::select($columns)
	               		->whereRaw($where_str, $where_params);
		
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1'){
                $specialization = $specialization->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
            }

            if(Input::has('iSortCol_0')){
                $sql_order='';
                for ( $i = 0; $i < Input::get('iSortingCols'); $i++ )
                {
                    $column = $columns[Input::get('iSortCol_' . $i)];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $specialization = $specialization->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $specialization = $specialization->get();
            
            $response['iTotalDisplayRecords'] = $specialization_count;
            $response['iTotalRecords'] = $specialization_count;

            $response['sEcho'] = intval(Input::get('sEcho'));

            $response['aaData'] = $specialization->toArray();
            
            return $response;
		}
		return View::make('admin.specialization.index');
	}

	/**
     * Admin specialization create form 
     * 
     * @method GET 
     * @return HTML -> specialization Create Form  
     */
    public function create()
	{
		return View::make('admin.specialization.create');
	}

	/**
     * Admin specialization validates, stores record in database
     * 
     * @method POST 
     * @return if failed -> redirect back to specialization create form with error messages 
     * @return if successful -> redirect back with successful confirmation 
     */
    public function store()
	{
		$data = Input::all();

		$validator = Validator::make($data,Specialization::$rules,Specialization::$message);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to add details.')
							   ->with('message_type', 'danger');
		}

		$specialization = new Specialization($data);
		$specialization->save();

		return Redirect::back()->with('message', 'Thematic Successfully Added.')
							   ->with('message_type', 'success');
	}

	/**
     * Admin Specialization Edit form 
     * 
     * @method GET 
     * @param  $id => Specialization id
     * @return HTML -> Specialization Edit Form  
     */
    public function edit($id)
	{
		$specialization = Specialization::where('id',$id)->first();

		return View::make('admin.specialization.edit',compact('specialization'));
	}

	/**
     * Admin Specialization validates, update record in database
     * 
     * @method POST 
     * @param  $id => Specialization id
     * @return if failed -> redirect back to Specialization edit form with error messages 
     * @return if successful -> redirect back to Specialization edit form with successful confirmation 
     */
    public function update($id)
	{
		$data = Input::all();

		$validator = Validator::make($data,Specialization::$rules,Specialization::$message);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to edit details.')
							   ->with('message_type', 'danger');
		}

		$specialization = Specialization::find($id);
		$specialization->fill($data);
		$specialization->save();

		return Redirect::back()->with('message', 'Thematic Area Successfully Updated.')
							   ->with('message_type', 'success');
	}

	public function show()
	{}

	/**
     * Admin Specialization  single / multiple Specialization delete
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
			Specialization::where('id','=',$id)->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			Specialization::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'Deleted permanently','success'=>true), 200);
	}

	 /**
     * Admin Specialization export filtered records to excel 
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

            $where_str .= " and ( spec_des like '%{$search}%'"
                        . ")";
        }

        $specialization_count = Specialization::select('id')
							->whereRaw($where_str, $where_params)
							->count();

		$specialization = Specialization::select('spec_des')
	               		->whereRaw($where_str, $where_params);
                        

        $specialization = $specialization->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            '	Thematic Area' => 'spec_des',
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
        foreach ($specialization as $specialization_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
                // echo "<pre>";
                // print_r($header);
                // print_r($column_name);

                $insert_data[$column_name] = $specialization_data[$column_name];
            }
            // echo "<pre>";
            //     print_r($insert_data);
            //     exit();
            $objPHPExcel->getActiveSheet()->fromArray($specialization_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'Specialization_export_' . date('d_m_Y') . '.xlsx';
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
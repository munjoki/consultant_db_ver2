<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
    AKDN Consultant Database Version 1.0
    this controller file handles akdn user registration updating and deletion
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles Add, edit, delete and listing of agencies

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/


class AdminAgenciesController extends \BaseController {

	/**
     * Admin Agency Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying agency datatable  
     * @return Html -> Agency Listing 
     */
    public function index()
    {
		$agencies = Agency::select('fullname','id')->orderby('order_id','asc')->get();
		return View::make('admin.agencies.index',compact('agencies'));
	}

	/**
     * Admin agency create form 
     * 
     * @method GET 
     * @return HTML -> Agency Create Form  
     */
    public function create()
	{
		return View::make('admin.agencies.create');
	}

    /**
     * Admin agency validates, stores record in database
     * 
     * @method POST 
     * @return if failed -> redirect back to agency create form with error messages 
     * @return if successful -> redirect back to agency create form with successful confirmation 
     */
	public function store()	
	{
		$data = Input::all();

		$validator = Validator::make($data,Agency::$rules,Agency::$messages);

		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Unable to add details.')
							   ->with('message_type', 'danger');
		}

		$agency = new Agency($data);

		$agency->save();

		return Redirect::back()->with('message', 'Agency Successfully Added.')
							   ->with('message_type', 'success');
	}

	/**
     * Admin agency Edit form 
     * 
     * @method GET 
     * @param  string  $id => agency id
     * @return HTML -> Agency Edit Form  
     */
    public function edit($id)
	{
		$agencies = Agency::where('id',$id)->first();

		return view::make('admin.agencies.edit',compact('agencies'));
	}

    /**
     * Admin agency validates, stores record in database
     * 
     * @method POST 
     * @param  string  $id => agency id
     * @return if failed -> redirect back to agency edit form with error messages 
     * @return if successful -> redirect back to agency edit form with successful confirmation 
     */
	public function update($id)
	{
		$data = Input::all();
		
		$rules = [
			'acronym' => 'required',
			'fullname' => 'required',
		];
		$message = [
			'acronym.required' => 'Acronym is Required',
			'fullname.required' => 'Fullname is Required',
		];

		$validator = Validator::make($data,$rules,$message);
		if($validator->fails()){
			return Redirect::back()->withInput()
								   ->withErrors($validator->errors())
								   ->with('message','user data not updated')
								   ->with('message_type','danger');
		}

		$agency = Agency::find($id);
		$agency->fill($data);
		
		$agency->save();
		return Redirect::back()->with('message','Agency Updated successfully')
							   ->with('message_type','success');
	}

    /**
     * Admin agency  single / multiple  delete
     * 
     * @method POST 
     * @return JSON
     */
	public function destroy()
	{
		/* Delete Single Record */
		$data = Input::all();

		$id = Input::get('id');
		$agency_delete_id = Agency::where('id',$id)->delete();
		

		return Response::json(array('message' => 'deleted permanently','success'=>true), 200);
	}

	/**
     * Admin agency set order
     * 
     * @method POST 
     */
    public function setOrder()
    {
        $order = Input::get('order');
        foreach ($order as $key => $value) {
            $index = $key + 1;
            Agency::where('id', $value)->update(array('order_id' => $index));
        }
    }

    /**
     * Admin agency export filtered records to excel 
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

            $where_str .= " and ( fullname like '%{$search}%'"
                        . " or acronym like '%{$search}%'"
                        . ")";
        }
        //$agencies = Agency::select('fullname','id')->orderby('order_id','asc')->get();

        $agencies = Agency::select('acronym','fullname')
                                ->whereRaw($where_str, $where_params);
                        

        $agencies = $agencies->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'Agency acronym ' => 'acronym',
            'Agency Fullname' => 'fullname',
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
        foreach ($agencies as $agencies_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
    
                $insert_data[$column_name] = $agencies_data[$column_name];
            }
        
            $objPHPExcel->getActiveSheet()->fromArray($agencies_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'Agency_export_' . date('d_m_Y') . '.xlsx';
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
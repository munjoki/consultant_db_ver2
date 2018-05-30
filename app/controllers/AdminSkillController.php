<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles consultant skills
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles consultant skills

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 
class AdminSkillController Extends BaseController
{

	/**
     * Admin skill Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying skill datatable  
     * @return GET -> skill Listing 
     * @return AJAX GET -> JSON response skill 
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
                			. "skills_des like '%{$search}%'"
                 			. ")";
            }

			$total_count = Skill::select('id')
						  ->whereRaw($where_str,$where_params)
						  ->count();
			
			$columns = array('id','skills_des');

			$skill = Skill::select($columns)->whereRaw($where_str, $where_params);
							
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $skill->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
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

                    $skill->orderBy($column,Input::get('sSortDir_'.$i));   
                }
            }

            $skill = $skill->get()->toArray();
           
            $response   = array(

				'iTotalDisplayRecords'	=> $total_count,
				'iTotalRecords'			=> $total_count,
				'sEcho'					=> intval(Input::get('sEcho')),
				'aaData'				=> $skill
			);
			
			return Response::json($response, 200);
		}

		return View::make('admin.skill.index');
	}

	/**
     * Admin skill create form 
     * 
     * @method GET 
     * @return HTML -> skill Create Form  
     */
    public function create(){

		return View::make('admin.skill.create');

	}

	/**
     * Admin skill validates, stores record in database
     * 
     * @method POST 
     * @return if failed -> redirect back to skill create form with error messages 
     * @return if successful -> redirect back with successful confirmation 
     */

    public function store()
	{
		$all = Input::all();

		$validation = Validator::make($all,Skill::$rules,Skill::$messages);

		if($validation->passes()){

			$skill = new Skill($all);
			$skill->save();
			return Redirect::back()->with('message', 'Skill Added suuccssully..!')
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
     * Admin skill validates, update record in database
     * 
     * @method POST 
     * @return if failed -> redirect back to skill edit form with error messages 
     * @return if successful -> redirect back to skill edit form with successful confirmation 
     */
    public function update()
	{
		if( Request::ajax() ){

			$all = Input::all();
			
			$all['id'] = $all['id'];
			
			$validator = Validator::make($all, Skill::$rules, Skill::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$skill = Skill::find(Input::get('id'));
			$skill->update($all);
			return Response::json(array('success' => true));
		}
	}

	 /**
     * Admin skill  single / multiple skill delete
     * 
     * @method POST 
     * @return JSON
     */

    public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			Skill::where('id','=',Input::get('id'))->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			Skill::whereIn('id',$ids)->forceDelete();
		}

		return Response::json(array('msg' => 'skill deleted permanently','success'=>true), 200);
	}

	public function show()
   	{}
	
    /**
     * Admin skill export filtered records to excel 
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
                			. "skills_des like '%{$search}%'"
                 			. ")";
        }

        $columns = array('skills_des');

		$skill = Skill::select($columns)->whereRaw($where_str, $where_params);
                        

        $skill = $skill->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'Skill Name' => 'skills_des'
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
        foreach ($skill as $skill_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
              
                $insert_data[$column_name] = $skill_data[$column_name];
            }
           
            $objPHPExcel->getActiveSheet()->fromArray($skill_data, NULL, 'A' . $rowNumber );

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'Skill_export_' . date('d_m_Y') . '.xlsx';
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
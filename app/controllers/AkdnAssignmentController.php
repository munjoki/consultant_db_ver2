<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles registering of consultancies
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
    Akdn Module: 
        This controller file handles the AKDN user registration, updating and deletion

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 
class AkdnAssignmentController extends BaseController{
	
	/**
     * Akdn assignment Listing, Searching, Sorting Pagination 
     *
     * @method GET for displaying form 
     * @method AJAX GET for displaying akdn assignment datatable  
     * @return GET -> AKDN Listing 
     * @return AJAX GET -> JSON response AKDN assignment 
     */
    public function index($id)
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

            $total_count = ConsultantAssignment::select('consultant_assignments.id')->leftJoin('akdn','akdn.id','=','consultant_assignments.akdn_id')
                          ->where('consultant_assignments.consultant_id',$consultant_id)
                          ->whereRaw($where_str,$where_params)
                          ->count();
            
            $columns = array('consultant_assignments.id','akdn_id' ,'title_of_consultancy', 'akdn.other_name as name', 'akdn.email as email','akdn_manager_name', 'akdn_manager_email', DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date_dmy'), DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date_dmy'),'status');

            $user = ConsultantAssignment::select($columns)
            		->leftJoin('akdn','akdn.id','=','consultant_assignments.akdn_id')
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

        $consultantAssignment = ConsultantAssignment::select('consultant_id')->where('consultant_id',$id)->first();

        $consultant = null;

        if ($consultantAssignment) {    
            if (isset($consultantAssignment->consultant_id)) {
                $consultant = Consultant::select()->where('id',$consultantAssignment->consultant_id)->first();
            }
        }

        return View::make('akdn.awarded',compact('consultant'));
    }

    public function store(){

    }

}    
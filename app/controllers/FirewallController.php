<?php

/*----------------------------------------------------------------------------------------------------------------------------------------
    Admin Module: 
        This controller file handles the Firewall

    * @author     thinkTANKER
    * @version    AKDN MER Consultant Database Version 1.0
    * @email      hello@thinktanker.in
------------------------------------------------------------------------------------------------------------------------------------------*/ 


class FirewallController extends BaseController
{
    
/**
 * Admin Firewall Listing, Searching, Sorting Pagination 
 *
 * @method GET for displaying form 
 * @method AJAX GET for displaying firewall 
 * @return GET -> AKDN Listing 
 * @return AJAX GET -> JSON response firewall data
 */
    public function index()
    {
        if( Request::ajax() ) {

            $where_str    = "1 = ?";
            $where_params = array(1);

            /* Common Search Filter */
            if (Input::has('sSearch'))
            {
                $search     = Input::get('sSearch');
                $where_str  .= " and ( "
                            . "ip_address like '%{$search}%'"
                            . ")";
            }

            $total_count = Fwall::select('id')
                                  ->whereRaw($where_str,$where_params)
                                  ->count();
            
            $columns = array('id','ip_address','whitelisted');

            $firewall = Fwall::select($columns)->whereRaw($where_str, $where_params);
                            
            if(Input::has('iDisplayStart') && Input::get('iDisplayLength') !='-1')
            {
                $firewall->take(Input::get('iDisplayLength'))->skip(Input::get('iDisplayStart'));
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

                    $firewall->orderBy($column,Input::get('sSortDir_'.$i));                }
            }

            $firewall = $firewall->get()->toArray();
           
            $response   = array(
                'iTotalDisplayRecords'  => $total_count,
                'iTotalRecords'         => $total_count,
                'sEcho'                 => intval(Input::get('sEcho')),
                'aaData'                => $firewall
            );
            
            return Response::json($response, 200);
        }

        return View::make('admin.firewall.index');
    }

    /**
     * Admin Firewall => Block the Ip addresss
     * 
     * @method GET 
     * @return JSON
     */
    public function blockIp()
    {
        $data = Input::get('id');

        if( ! is_array($data) )
        {
            $firewall = Fwall::where('id',$data)->first();

            if($firewall['whitelisted'] == 1) {
                Fwall::where('id',$data)->update(['whitelisted' => '0']);
            } else {
                Fwall::where('id',$data)->update(['whitelisted' => '1']);
            }

            return Response::json(array('success' => true),200);
        }
        
        Fwall::whereIn('id',$data)->update(['whitelisted' => '0']);

        return Response::json(array('success' => true),200);
    }

    /**
     * Admin Firewall => unblock the Ip addresss
     * 
     * @method GET 
     * @return JSON
     */
    public function unblockIp()
    {
        $data = Input::get('id');

        if( ! is_array($data) )
        {
            $data = [$data];
        }

        Fwall::whereIn('id',$data)->update(['whitelisted' => '1']);

        return Response::json(array('success' => true),200);
    }

     /**
     * Admin Firewall  single / multiple ip address delete
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
            Fwall::where('id',$id)->delete();
        }
        /* Delete Multiple Records */
        else
        {
            $ids = Input::get('id');
            Fwall::whereIn('id',$ids)->delete();
        }

        return Response::json(array('msg' => 'Deleted permanently','success'=>true), 200);
    }

     /**
     * Admin firewall export filtered records to excel 
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

            $where_str .= " and ( name like '%{$search}%'"
                            . " or email like '%{$search}%'"
                            . ")";
        }

        $columns = array('ip_address','whitelisted');

        $firewall = Fwall::select($columns)->whereRaw($where_str, $where_params);
                        

        $firewall = $firewall->get()->toArray();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ConsultantDB")
            ->setTitle("ConsultantDB: Excel Export")
            ->setSubject("Consultant search result export")
            ->setDescription("Excel export of customized search result from consultant database")
            ->setKeywords("office 2007 openxml");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('consultant_search_export');

        $header = array(
            'IP Address' => 'ip_address',
            'Firewall Status' => 'whitelisted',
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
        foreach ($firewall as $firewall_data) 
        {
            $insert_data = array();
            foreach ($header as $column_name) {
                // echo "<pre>";
                // print_r($header);
                // print_r($column_name);

                $insert_data[$column_name] = $firewall_data[$column_name];
            }
            // echo "<pre>";
            //     print_r($insert_data);
            //     exit();
            $objPHPExcel->getActiveSheet()->fromArray($firewall_data, NULL, 'A' . $rowNumber );
                
            if($insert_data['whitelisted'] == 0){

                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowNumber,'Blocked');
            }
            else
            {
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowNumber,'Active');
            }    

            $rowNumber++;
        }

        for($j='A'; $j<'No';$j++){
    
            $objPHPExcel->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
        }

        $filename = 'firewall_export_' . date('d_m_Y') . '.xlsx';
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
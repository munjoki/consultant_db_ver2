@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN Consultant Database Version 1.0
    this file handles viewing existing AKDN users and performing some quick actions (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">Agencies List</h3>
                    <div class="box-tools pull-right">
                        <a href="{{URL::route('admin.agencies.create')}}" class="btn btn-primary btn-default">Add New Agency</a>
                        <!-- <a id="adminakdn_destroy_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">DeleteAll</a> -->
                        @if(CheckPermission::isPermitted('admin.role.excelexport'))
                        <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_akdn.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                    </div>
                </div>
                <!-- <div class="box-body">
                    <table id="dt_akdn" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th><input type="checkbox" value="CH" name="selectall"></th>
                                <th>Acronym</th>
                                <th>Full Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div> -->
                <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div id="div_alert"></div>
                            @if(count($agencies) > 0)
                                <div id="sortable">                                    
                                    @foreach($agencies as $key => $agency)
                                        <div class="slider-item" agency-id="{{ $agency->id }}"><i class="icon-sort fa fa-lg fa-arrows"></i>
                                            <h4 class="slider-item-desc" ><?= $agency->fullname ?></h4>
                                            <a href="<?= URL::route('admin.agencies.edit',$agency->id)?>" data-toggle="tooltip" data-placement="top" title="Edit Slider" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{ $agency->id }}"  data-toggle="tooltip" data-placement="top" title="Delete Slider" class="btn btn-sm btn-danger agency_slider_delete"><i class="fa fa-fw fa-sm fa-trash-o"></i></a>                                       
                                        </div>
                                        
                                    @endforeach                    
                                </div>
                            @else
                            <div class="col-md-12">
                                <div class="bg-primary" style="padding: 10px 15px;">
                                    <b>Alert!</b> No images added to home page slider.
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
                <!-- <div class="overlay">
                    <i class="fa fa-spin fa-spinner"></i>
                </div> -->
            </div>
        </div>
    </div>
</section>
@stop
@section('style')
{{ HTML::style('assets/plugins/sweetalert/sweetalert.css') }}
<style type="text/css">
    /*slider item for sortable*/
.slider-item {
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  background-color: #e2e2e2;
  padding: 10px;
  border-radius: 3px;
  margin-bottom: 5px;
}

.slider-item .icon-sort {
  cursor: all-scroll;
  -webkit-align-self: stretch;
      -ms-flex-item-align: stretch;
          align-self: stretch;
  margin-right: 20px;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  background-color: rgba(0, 0, 0, 0.2);
  padding: 5px;
  margin-top: -10px;
  margin-left: -10px;
  margin-bottom: -10px;
  border-radius: 3px 0 0 3px;
}

.slider-item img {
  max-width: 60px;
  margin-right: 20px;
}

.slider-item .slider-item-desc {
  -webkit-flex: 1;
      -ms-flex: 1;
          flex: 1;
  margin: 0;
  font-size: 16px;
}

/*slider positioner*/
.slider-positioner {
  display: table;
}
.slider-positioner .srow {
  display: table-row;
}
.slider-positioner .position {
  display: table-cell;
}
.slider-positioner .position label {
  display: block;
  margin: 0;
  font-weight: 400;
}
.slider-positioner .position input[type="radio"] {
  display: none;
}
.slider-positioner .position input[type="radio"] + .pos-box {
  width: 120px;
  height: 50px;
  cursor: pointer;
  background-color: #eee;
  border: 1px solid #ccc;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease-in-out;
}
.slider-positioner .position input[type="radio"]:checked + .pos-box {
  background-color: #114979;
  border-color: #114979;
  color: #fff;
}

</style>

@stop

@section('script')

{{ HTML::script('assets/plugins/jQueryUISort/jquery-ui.min.js') }}
{{ HTML::script('assets/plugins/sweetalert/sweetalert.min.js') }}

<script type="text/javascript">
    
    // $( "#sortable" ).sortable({
        
    // });

    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.agencies.excelexport") }}?'+query_string);
        return true;
    }

    $('.agency_slider_delete').on('click',function(){
      var id = $(this).attr('data-id');
        swal({
            title: "Are you sure want to delete",
            type: "warning",
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            closeOnConfirm: true
          },
          function(){
            $.ajax({
              url: "{{ URL::route('admin.agencies.delete') }}",
              type: 'post',
              data: { id: id},
              success:function(resp){ 
                location.reload();
              }
          });
        });
      });

    $( "#sortable" ).sortable({
            update: function(){
                var order = $(this).sortable('toArray',{ attribute: 'agency-id' });
                console.log(order);
                $.ajax({
                    url: "{{ URL::route('admin.agencies.order') }}",
                    type: 'post',
                    data: { order: order},
                    complete: function(){
                      swal({
                        title : "Order reset Successfully..",
                        timer:2000 
                    })
                    
                    }                    
                });
            }
    });
     //$( "#sortable" ).disableSelection();
    
</script>
@stop
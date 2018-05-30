@extends('user.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file renders the timestamp for the consultant profile
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{{$user->other_names}}</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row margin">
            <div class="col-sm-6">
              <span id="irs-1" class="irs irs-with-grid"></span>
              <div class="section row mt10">
                    <div class="col-xs-3">
                        <label class="field-label text-right"><strong>User Name:</strong></label>
                        <label class="field-label text-right"><strong>Email Address:</strong></label>
                        <label class="field-label text-right"><strong>Mobile:</strong></label>
                        <hr class="short alt">
                    </div>
                    <div class="col-xs-6">
                        <label class="field-label">{{ $user->other_names }} &nbsp;</label><br>
                        <label class="field-label">{{ $user->email }} &nbsp;</label><br>
                        <label class="field-label">{{ $user->mobile }}</label>
                    	<hr class="short alt">
                    </div>    
                                                                   
                </div>
                
                <div class="section row mb10">
                    <div class="col-xs-3">
                        <label class="field-label strong text-right"><strong>Created At:</strong></label>
                        <label class="field-label strong text-right"><strong>Edited At:</strong></label>
                    </div>                                            
                    <div class="col-xs-3">
                        <label class="field-label">{{ $user->created_at }} &nbsp;</label>
                        <label class="field-label">{{ $user->updated_at }} &nbsp;</label>
                    </div>   
                                                                  
                </div>
             </div>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  
</section >
    

    <!-- Begin: Content -->
  
    <!-- End: Content -->
@stop

@section('style')      
    <style>
        /* page demo styles */
        body {
            min-height: 2500px;
        }
        .custom-nav-animation li {
            display: none;
        }
        .custom-nav-animation li.animated {
            display: block;
        }

        /* nav tray fixed settings */
        ul.tray-nav.affix {
            width: 319px;
            top: 59px;
        }

        #p13 .panel-colorbox .bg-primary,
        #p13 .panel-colorbox .bg-info,
        #p13 .panel-colorbox .bg-danger,
        #p13 .panel-colorbox .bg-alert,
        #p13 .panel-colorbox .bg-dark    {
            display: none;
        }

    </style>
@stop

@section('script')

    <!-- Admin Widgets  -->
    
    <script type="text/javascript">
        jQuery(document).ready(function() {

            // Init custom navigation animation
            setTimeout(function() {
                $('.custom-nav-animation li').each(function(i, e) {
                    var This = $(this);
                    var timer = setTimeout(function() {
                        This.addClass('animated animated-short zoomIn');
                    }, 50 * i);
                });
            }, 500);

            // Init Widget Demo JS
            // demoWidgets.init();

            // Because we are using Admin Panels we use the OnFinish 
            // callback to activate the demoWidgets. It's smoother if
            // when we let the panels be moved and organized before 
            // filling them with content from various plugins

            // Init plugins used on this page
            // HighCharts, JvectorMap, Admin Panels

            // Init Admin Panels on widgets inside the ".admin-panels" container
            $('.admin-panels').adminpanel({
                grid: '.admin-grid',
                draggable: true,
                mobile: false,
                callback: function() {
                    bootbox.confirm('<h3>A Custom Callback!</h3>', function() {
                    });
                },
                onFinish: function() {
                    $('.admin-panels').addClass('animated fadeIn').removeClass('fade-onload');

                    // Init Demo settings 
                    $('#p0 .panel-control-color').click();

                    // Init Demo settings 
                    $('#p1 .panel-control-title').click();

                    // Init Demo smoothscroll
                    $('.tray-nav a').smoothScroll({
                        offset: -145
                    });

                    // Create an example admin panel filter
                    $('#admin-panel-filter a').on('click', function() {
                        var This = $(this);
                        var Value = This.attr('data-filter');

                        // Toggle any elements whos name matches
                        // that of the buttons attr value
                        $('.admin-filter-panels').find($(Value)).each(function(i, e) {
                            if (This.hasClass('active')) {
                                $(this).slideDown('fast').removeClass('panel-filtered');
                            } else {
                                $(this).slideUp().addClass('panel-filtered');
                            }
                        });
                        This.toggleClass('active');
                    });

                },
                onSave: function() {
                    $(window).trigger('resize');
                }
            });

        });
    </script>
@stop

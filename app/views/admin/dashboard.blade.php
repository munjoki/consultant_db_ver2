@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles administrator dashboard
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
	<h1>Dashboard <small>Analytics</small></h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>{{$consultant}}</h3>
					<p>Consultants</p>
				</div>
				<div class="icon"><i class="ion ion-android-person"></i></div>
				<a href="{{URL::to('admin/users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{$language}}</h3>
					<p>Languages</p>
				</div>
				<div class="icon"><i class="ion ion-chatbubble-working"></i></div>
				<a href="{{URL::to('admin/language')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{$agencies}}</h3>
					<p>Agencies</p>
				</div>
				<div class="icon"><i class="ion ion-briefcase"></i></div>
				<a href="{{URL::to('admin/akdn')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{$specialization}}</h3>
					<p>Thematic Areas</p>
				</div>
				<div class="icon"><i class="ion ion-ios-book-outline"></i></div>
				<a href="{{URL::to('admin/specialization')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-orange">
				<div class="inner">
					<h3>{{$skills}}</h3>
					<p>Skills</p>
				</div>
				<div class="icon"><i class="ion ion-person-add"></i></div>
				<a href="{{URL::to('admin/skill')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-purple">
				<div class="inner">
					<h3>{{$registered_consultancies}}</h3>
					<p>Registered Consultancies</p>
				</div>
				<div class="icon"><i class="ion ion-pie-graph"></i></div>
				<a href="{{URL::to('admin/awarded')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
	<div class="row">
        <div class="col-md-6">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Recently Registered Consultancy</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="display: block;">
                    <ul class="products-list product-list-in-box">
                    @foreach($awardeds as $awarded)
                        <li class="item">
                            <div class="product-info" style="margin-left:0px;">
                                <a class="product-title">{{ $awarded->title_of_consultancy }}</a>
                                <span class="product-description">
                                Registered By : {{ $awarded->other_names }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center" style="display: block;">
                    <a href="<?= route('admin.awarded.index') ?>" class="uppercase">View All Registered Consultancies</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <div class="col-md-6">
	        <div class="box box-primary box-solid">
		        <div class="box-header with-border">
					<i class="fa fa-bar-chart-o"></i>
					<h3 class="box-title">Registered Consultants by Gender</h3>
		        </div>
		        <div class="box-body">
		          <div id="donut-chart" style="height: 300px;"></div>
		        </div><!-- /.box-body-->
	      </div><!-- /.box -->
      </div>
    </div>
</section>
@stop
@section('script')
{{ HTML::script('assets/plugins/flot/jquery.flot.min.js') }}
{{ HTML::script('assets/plugins/flot/jquery.flot.pie.min.js') }}
<script type="text/javascript">
function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
            + label
            + "<br>"
            + Math.round(series.percent) + "%</div>";
  }
$(document).ready(function(){
	var male = <?= $male_consultants_percentage ?>;
	var female = <?= $female_consultants_percentage ?>;
	var donutData = [
	  {label: "Male", data: male, color: "#3c8dbc"},
	  {label: "Female", data: female, color: "#00c0ef"},
	];
	$.plot("#donut-chart", donutData, {
	  series: {
	    pie: {
	      show: true,
	      radius: 1,
	      innerRadius: 0.5,
	      label: {
	        show: true,
	        radius: 2 / 3,
	        formatter: labelFormatter,
	        threshold: 0.1
	      }

	    }
	  },
	  legend: {
	    show: false
	  }
	});
});
</script>
@stop
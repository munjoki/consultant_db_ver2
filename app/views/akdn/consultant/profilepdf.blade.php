<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		.container {
			width:700px;
			margin: 0 auto;
		}
		.text-center {
			text-align: center;
		}
		.text-right {
			text-align: right;
		}
		.text-left {
			text-align: left;
		}
		
		.m-0 {
			margin:0;
		}
		.mb-0 {
			margin-bottom:0;
		}
		.pull-left {
			float: left;
		}
		.pull-right {
			float: right;
		}
		hr {
			margin:10px 0;
			border:1px solid #f0f0f0;
			float: left;
			width: 100%;
		}
		hr.line {
			margin:5px 0;
		}
		.mt-30 {
			margin-top:30px;
		}
		.mb-0 {
			margin-bottom:0px;
		}
		.mb-30 {
			margin-bottom: 30px;
		}
		.mt-10 {
			margin-top: 10px;
		}
		.h-100 {
			height: 100px;
		}
		.w-30 {
			width:30%;
		}
		.w-40 {
			width:45%;
		}
		.w-100 {
			width:100%;
		}
		table.price-detail tbody tr td {
			border: none;
			padding-bottom: 5px;
		}
		table.w-100.detail{
			border: 1px solid #f0f0f0;
		}
		table.w-100.detail tr td {
			border: 1px solid #f0f0f0;
			padding:10px;
		}
		table.w-100.detail tr th {
			border: 1px solid #f0f0f0;
			padding:10px 0;
		}
		.border {
			border: 1px solid #f0f0f0;
		}
		.m-5{
			margin:5;
		}

		.pricedetailDiv{
			margin-right: -65px;
		}
		.wp-300 {
			width: 300px;
		}
	</style>
</head>

<body style="font-family: serif; font-size: 11pt;">
<div class="container mt-30">
	<h2 class="mb-0">Consultant Profile</h2>	
	<hr>
	<div class="w-40 pull-left">
		<h3 class="mb-0">Personal Details</h3>
		<hr class="line">
		<table class="w-100 detail mt-30" cellspacing="0" border="1">
			<tbody>
				<tr>
					<td class="text-right">Surname</td>
					<td class="text-left">{{ $user->consultant->surname; }}</td>
				</tr>
				<tr>
					<td class="text-right">First Name</td>
					<td class="text-left">{{ $user->consultant->other_names; }}</td>
				</tr>
				<tr>
					<td class="text-right">Nationality(ies)</td>
					<td class="text-left">
					@if(count($nationalities) > 0)
					<span class="label tags"> {{ implode('</span> <span>', $nationalities); }} </span>
					@endif	
					</td>
				</tr>
				<tr>
					<td class="text-right">Gender</td>
					<td class="text-left">{{ (1 == $user->consultant->gender) ? 'Male' : 'Female' }}</td>
				</tr>
				<tr>
					<td>Consultant Type</td>
					<td>{{ $user->consultant->consultant_type; }}</td>
				</tr>
				<tr>
					<td>Institution Name</td>
					<td>{{ $user->consultant->company_name; }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="w-40 pull-right">
		<h3 class="mb-0">Contact Details</h3>
		<hr class="line">
		<table class="w-100 detail mt-30" cellspacing="0" border="1">
			<tbody>
				<tr>
					<td class="text-right">Primary Email</td>
					<td class="text-left">{{ $user->email; }}</td>
				</tr>
				<tr>
					<td class="text-right">Alternate Email</td>
					<td class="text-left">{{ $user->consultant->alternate_email; }}</td>
				</tr>
				<tr>
					<td class="text-right">Tel. No.</td>
					<td class="text-left">{{ $user->consultant->telno; }}</td>
				</tr>
				<tr>
					<td class="text-right">Skype Id</td>
					<td class="text-left">{{ $user->consultant->skypeid; }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="w-100 pull-left">
		<h3 class="mb-0">Skills and Competencies</h3>
		<hr class="line">
		<table class="w-100 detail mt-30" cellspacing="0" border="1">
			<tbody>
				<tr>
					<td class="text-right wp-300">Languages</td>
					<td class="text-left">
						@if($languages)
							<table class="w-100 detail" cellspacing="0" border="1">
								<tr>
									<th>Language</th>
									<th>Speaking</th>
									<th>Reading</th>
									<th>Writing</th>
									<th>Understanding</th>
								</tr>
							@foreach($languages as $language)
								<tr>
									<td>{{ ucfirst($language['lang_des']) }}</td>
									<td>{{ ucfirst($language['speaking_level']) }}</td>
									<td>{{ ucfirst($language['reading_level']) }}</td>
									<td>{{ ucfirst($language['writing_level']) }}</td>
									<td>{{ ucfirst($language['understanding_level']) }}</td>
								</tr>
							@endforeach
							</table>
						@else
						@endif
					</td>
				</tr>
				<tr>
					<td class="text-right wp-300">Thematic Area(s)</td>
					<td class="text-left">
						@if(count($specialization) > 0)
							<span> {{ implode('</span>; <span>', $specialization); }} </span>
						@endif
					</td>
				</tr>
				<tr>
					<td class="text-right">Skill(s)</td>
					<td class="text-left">
						<span> {{ implode('</span>;<span>', $skills); }} </span>
					</td>
				</tr>
				<tr>
					<td class="text-right">International Experience</td>
					<td class="text-left">
						@if(count($workedcountries) > 0)
							<span> {{ implode('</span>;<span>', $workedcountries); }} </span>
						@endif
					</td>
				</tr>
				<tr>
					<td class="text-right">AKDN Agency Experience</td>
					<td class="text-left">
						@if(count($agencies) > 0)
							<span> {{ implode('</span>;<span>', $agencies); }} </span>
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="w-100 pull-left mb-30">
		<h3 class="mb-0">Professional History</h3>
		<hr class="line">
		<table class="w-100 detail mt-30" cellspacing="0" border="1">
			<tbody>
				<tr>
					<td class="text-right wp-300">Linkedin Profile</td>
					<td class="text-left">
						@if($user->consultant->linkedin_url != "")
							<a href="{{ $user->consultant->linkedin_url }}"  target="_blank">{{ $user->consultant->linkedin_url }}</a>
						@else
							-
						@endif
					</td>
				</tr>
				<tr>
					<td class="text-right">Website/Blog URL</td>
					<td class="text-left">
						@if($user->consultant->website_url != "")
							<a href="{{ $user->consultant->website_url }}"  target="_blank">{{ $user->consultant->website_url }}</a>
						@else
							-
						@endif
					</td>
				</tr>
				<tr>
					<td class="text-right">Resume/CV</td>
					<td class="text-left">
						@if($user->consultant->resume != "")
							Yes
						@else
							-
						@endif
					</td>
				</tr>
				@if(isset($consultant))
				<tr>
					<td class="text-right">Previous Consultancies</td>
					<td class="text-left">
						@if($consultant->c)
							Yes
						@else
							No
						@endif
					</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
</body>
</html>


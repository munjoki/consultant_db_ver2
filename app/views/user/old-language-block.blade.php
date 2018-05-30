<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles languages and language levels that a consultant provides
-------------------------------------------------------------------------------------------------------------------------------------------->
<div id="language_row" class="language_row row border-bottom">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-4 control-label" for="language">Language </label>
														<div class="col-md-8">
															{{ Form::select('language',array(""=>"--select Language---") + $language , '0', array('class'=>'form-control', 'id' => 'language'))}}
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-3 control-label" for="lang_level"> Level </label>
														<div class="col-md-6">
															{{ Form::select('lang_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'lang_level'))}}
														</div>
														<div class="col-md-3">
															<div class="input-group">
																<a id="plus_language" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i></a>
										                		<a id="minus_language" class="btn btn-danger btn-flat btn-sm" style="display:none"><i class="fa fa-times"></i></a>
									                		</div>
														</div>
													</div>
												</div>
											</div>
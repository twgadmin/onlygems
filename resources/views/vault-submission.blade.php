@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12 col-sm-12">
                <h4>Vault Submission</h4>
                @include('partials.errors')
                @include('partials.status')
            </div>
        </div>
        
        <?php if(isset($cardData)&&count($cardData)>0): ?>
       		<h3>Total number of cards submission <?php echo count($cardData); ?></h3>
        	<div class="row">
            <div class="col-lg-12 col-xs-12">
            	    <table class="table table-striped table-hover" id="sheetUploadedData">
                      <tr>
                        <td>Name</td>
                        <td>Grading Co</td>
                        <td>Grading Co Serial Number</td>
                        <td>Year</td>
                        <td>Set</td>
                        <td>Card Number</td>
                        <td>Parallel <strong>"If none, put BASE in this column"</strong></td>
                        <td>Grade</td>
                        <td>Category</td>
                        <td>Estimated Value</td>
                      </tr>
                        @foreach ($cardData as $order)
                            <tr>
                                <td>{!! $order['name'] !!}</td>
                                <td>{!! $order['grading_co'] !!}</td>
                                <td>{!! $order['grading_co_serial_number'] !!}</td>
                                <td>{!! $order['year'] !!}</td>
                                <td>{!! $order['set'] !!}</td>
                                <td>{!! $order['card'] !!}</td>
                                <td>{!! $order['parralel'] !!}</td>
                                <td>{!! $order['grade'] !!}</td>
                                <td>{!! $order['category'] !!}</td>
                                <td>{!! $order['estimated_value'] !!}</td>                                                
                            </tr>
                        @endforeach
                    </table>
                    <form action="{!! route('confirm.sheet.data') !!}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="filetype" value="{!! $type !!}">
                        <input type="hidden" name="filename" value="{!! $filename !!}">
                        <input type="submit" name="submit" value="Confirm" class="btn btn-primary">
                        <input type="button" value="Start Over/Upload New Order File" class="btn btn-primary" onclick="window.location.href='{!! route('vault.submission') !!}'">
                    </form>

             </div>
        </div>
        <?php else: ?>
        	<div class="row">
        <div class="col-lg-12 col-xs-12">Please follow the steps below to create an order to be securely submitted to Only Gems Vault Services</div>
        </div>
        	<div class="row">
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            @role('user')
            <div class="small-box bg-primary">
                <div class="inner">
                <h3>Order xlsx</h3>
                <p>Asset Submission Template</p>
                </div>
                <div class="icon">
                    <i class="far fa-file-xls"></i>
                </div>
                    <a href="{{ asset('storage/only_gems_card_asset_submission_template.xlsx') }}" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            @endrole
            </div>
        </div>
        @role('user')
		    <div class="row">
            <div class="col-lg-3 col-xs-6">
            	<form method="POST" action="{!! route('upload.sheet.data') !!}" accept-charset="UTF-8" class="form-horizontal" role="form" enctype="multipart/form-data">
                	@csrf
                    @method('POST')
                    <br />
                    <input type="file" name="file"/>
                    <br />
                    <input type="submit" name="submit" value="Create New Order" class="btn btn-primary">
                </form>
            </div>
        </div>
        <br />
        	<div class="row">
            <div class="col-lg-12 col-xs-12">
            	<form method="POST" action="{!! route('upload.posted.data') !!}" accept-charset="UTF-8" class="form-horizontal" role="form" enctype="multipart/form-data">
                	@csrf
                    @method('POST')
                    <table class="table table-striped table-hover" id="sheetUploadedData">
                      <tr>
                        <td>Name</td>
                        <td>Grading Co</td>
                        <td>Grading Co Serial Number</td>
                        <td>Year</td>
                        <td>Set</td>
                        <td>Card Number</td>
                        <td>Parallel <strong>"If none, put BASE in this column"</strong></td>
                        <td>Grade</td>
                        <td>Category</td>
                        <td>Estimated Value</td>
                        <td>Action</td>
                      </tr>
                      <tr>
                        <td><input class="form-control" type="text" name="name[]" required="required"/></td>
                        <td>
                            <select name="grading_co[]" class="form-control" required="required">
                            	<option value=""></option>
                                <?php foreach($gradingcos as $gradingco): ?>
                                <option value="<?php echo $gradingco; ?>"><?php echo $gradingco; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" name="grading_co_serial_number[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="year[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="set[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="card_number[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="parralel[]" class="form-control"  required="required"/></td>
                        <td>
                            <select name="grade[]" class="form-control"  required="required">
								<option value=""></option>
                                <?php foreach($gradingcos as $gradingco): ?>
									<?php foreach($grades as $grade): ?>
									<option value="<?php echo $gradingco." ".$grade; ?>"><?php echo $gradingco." ".$grade; ?></option>
									<?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="category[]" class="form-control"  required="required">
                            	<option value=""></option>
                                @foreach($categorys as $category)
                                <option value="{!! ucfirst($category) !!}">{!! ucfirst($category) !!}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="estimated_value[]" class="form-control"  required="required"/></td>
                        <td><input type="button" name="delete" value="Delete" class="btn btn-danger mb-3 btnDelete"></td>
                      </tr>
                    </table>
                    <input type="submit" name="submit" value="Create New Order" class="btn btn-primary">
                    <input type="button" id="addnewrow" value="Add New Row" class="btn btn-primary">
                </form>
            </div>
        </div>
        <br/>
        <?php endif; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript">
		$(document).ready(function(){
			$('#addnewrow').click(function(e) {  
				var content = `<tr>
                        <td><input class="form-control" type="text" name="name[]" required="required"/></td>
                        <td>
                            <select name="grading_co[]" class="form-control" required="required">
                            	<option value=""></option>
                                <?php foreach($gradingcos as $gradingco): ?>
                                <option value="<?php echo $gradingco; ?>"><?php echo $gradingco; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" name="grading_co_serial_number[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="year[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="set[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="card_number[]" class="form-control"  required="required"/></td>
                        <td><input type="text" name="parralel[]" class="form-control"  required="required"/></td>
                        <td>
                            <select name="grade[]" class="form-control"  required="required">
								<option value=""></option>
                                <?php foreach($gradingcos as $gradingco): ?>
									<?php foreach($grades as $grade): ?>
									<option value="<?php echo $gradingco; ?> <?php echo $grade; ?>"><?php echo $gradingco; ?> <?php echo $grade; ?></option>
									<?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="category[]" class="form-control"  required="required">
                                <option value=""></option>
								<?php foreach($categorys as $category): ?>
                                <option value="<?php echo ucfirst($category); ?>"><?php echo ucfirst($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
						<td><input type="text" name="estimated_value[]" class="form-control"  required="required"/></td>
                        <td><input type="button" name="delete" value="Delete" class="btn btn-danger mb-3 btnDelete"></td>
                      </tr>`;
				$('#sheetUploadedData').append(content);
			});
		});
		
		$("#sheetUploadedData").on('click', '.btnDelete', function () {
			$(this).closest('tr').remove();
		});
		</script>        
        @endrole
    </div>
@stop

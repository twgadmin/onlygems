<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .no-border {
            border: 0px !important;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .table-bordered th, td {
            padding: 5px;
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
		<div style="text-align:center"><img src="<?php echo asset('storage/logo.png'); ?>" style="text-align:center !important;"/></div>	
        <hr>
        @if(count($data['orders'])>0)
            <table width="100%" border="0" class="table table-borderless">
              <tr>
                <td align="left" valign="top">{!! $data['userinfo']['first_name']." ".$data['userinfo']['last_name'] !!}<br />
                    {!! $data['userinfo']['email'] !!}<br />
                    {!! $data['userinfo']['phone'] !!}<br />
                    <?php $address  = json_decode($data['userinfo']['address']); ?>
                    {!! isset($address->street)&&$address->street!='' ? $address->street : "" !!}<br />
                    {!! isset($address->zip)&&$address->zip!='' ? $address->zip : "" !!}
                </td>
                <td align="right" valign="top">Order # : {!! $data['orders'][0]['order_id'] !!}</td>
              </tr>
            </table>
            <hr>
        @endif

        @if(count($data['orders'])>0)        
        <table class="table table-borderless">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Grading Co</td>
                    <td>Grading Co Serial Number</td>
                    <td>Year</td>
                    <td>Set</td>
                    <td>Card Number</td>
                    <td>Parallel</td>
                    <td>Grade</td>
                    <td>Category</td>
                    <td>Estimated Value</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['orders'] as $order)
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
            </tbody>
        </table>
        <hr>
        Total Cards : <?php echo ((isset($data['orders'])&&count($data['orders'])>0) ? count($data['orders']) : 0); ?>
        @endif
    </div>
</body>
</html>
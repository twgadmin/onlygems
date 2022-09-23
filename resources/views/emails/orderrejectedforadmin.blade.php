<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div style="background-color: #f4f4f4; padding:10px; margin:auto; font-family: Arial, Helvetica, sans-serif; line-height:30px; ">
        <img src="<?php echo asset('storage/logo.png'); ?>" style="text-align:center !important;"/>
        <div style="background: #ffffff; margin:10px; padding:10px;">
           <p><b>Hi Admin,</b></p>
            <p>You have been rejected order # <a href="">{!! $order !!}</a>.</p>
            <br />
            <p>Thanks,</p>
            <p>
               {{ config('app.name') }}
            </p>
        </div>
        <div style="text-align:center; font-size:12px;">
            <p style="margin:0px;">© {!! date('Y') !!} {{ config('app.name') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
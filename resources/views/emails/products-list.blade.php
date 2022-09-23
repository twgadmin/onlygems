<!DOCTYPE html>
<html>
<head>
    <title>Fred Fund - NFT Collectible Asset Fund - Products List</title>
</head>
<body>

    <h1>{{ $title }}</h1>
    <table border="1" cellspacing="3" cellpadding="3">
        <thead>
            <th>#</th>
            <th>Product Name</th>
            <th>Date</th>
            <th>Category</th>
            <th>Source</th>
            <th>Term</th>
            <th>Item ID</th>
            <th>Price</th>
            <th>StockX Price</th>
            <th>Flight Club Price</th>
            <th>Goat Price</th>
            <th>Missing Variants Sizes</th>
        </thead>
        <?php $inc = 1; ?>
        @foreach ($content as $product)
        <?php
            $missingVariants = json_decode($product[11], true);
            if(!empty($missingVariants))
            $missingVariants = implode(', ',$missingVariants);
            else
            $missingVariants = '--';
        ?>
            <tr>
                <td>{{$inc}}</td>
                <td>{{$product[1]}}</td>
                <td>{{$product[2]}}</td>
                <td>{{$product[3]}}</td>
                <td>{{$product[4]}}</td>
                <td>{{$product[5]}}</td>
                <td>{{$product[6]}}</td>
                <td>{{ ($product[7] != '' ? $product[7] : '--') }}</td>
                <td>{{ ($product[8] != '' ? $product[8] : '--') }}</td>
                <td>{{ ($product[9] != '' ? $product[9] : '--') }}</td>
                <td>{{ ($product[10] != '' ? $product[10] : '--') }}</td>
                <td>{{ $missingVariants }}</td>
            </tr>
            <?php $inc++; ?>
        @endforeach
    </table>
    <p>Thank you</p>
</body>
</html>

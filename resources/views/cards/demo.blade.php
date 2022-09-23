<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form enctype="multipart/form-data" method="POST" class="test-add-card-form">
        {!! csrf_field() !!}
        <table>
            <tbody>
                <tr>
                    <td><label> Name: </label></td>
                    <td><input type="text" name="name" class="form-control name"></td>
                </tr>

                <tr>
                    <td><label> Brand : </label></td>
                    <td><input type="text" name="brand" class="form-control brand"></td>
                </tr>

                <tr>
                    <td><label> Description : </label></td>
                    <td><input type="text" name="description" class="form-control description"></td>
                </tr>

                <!-- <tr>
                    <td><label> Tags : </label></td>
                    <td><input type="text" name="tags" class="form-control tags"></td>
                </tr> -->

                <tr>
                    <td><label> Product Types : </label></td>
                    <td>
                        <select name="product_type" class="form-control product_type">
                            <option value="BGS">BGS</option>
                            <option value="CGC">CGC</option>
                            <option value="CSG">CSG</option>
                            <option value="PSA">PSA</option>
                            <option value="SGC">SGC</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label> SKU : </label></td>
                    <td><input type="text" name="sku" class="form-control sku"></td>
                </tr>

                <tr height="50">
                    <td></td>
                    <td><button type="submit" class="btn btn-success test-card-data-submit-btn">Submit</button></td>
                </tr>
        
            </tbody>
        </table>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" src="{{ asset('js/demo.js') }}" defer></script>
</body>
</html>

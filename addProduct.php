<?php
   require('../includes/connection.inc.php');
   if(isset($_POST['add_btn']))
   {
    $product_name=$_POST['product_name'];
    $category=$_POST['category'];
    if($category!="other")
    {
    $name_id="Select `category_id` from categories where `category_name`='$category'";
    $res_name_id=mysqli_query($con,$name_id);
    if ($res_name_id->num_rows > 0) {
        while($row = $res_name_id->fetch_assoc()) {
          $category_id = $row["category_id"];
        }
      }
    }
    else{
        $other_category=$_POST['other_category'];
        if($other_category!="")
        {
            $check="Select * from `categories` where `category_name`='$other_category'";
            $res_check=mysqli_query($con,$check);
            if ($res_check->num_rows > 0) {
            while($row = $res_check->fetch_assoc()) {
              $category_id = $row["category_id"];
             }
            }
            else{
            $insert_category="INSERT INTO `categories`( `category_name`) VALUES ('$other_category')";
            $res=mysqli_query($con,$insert_category);
            if($res)
            {
                $category=$_POST['other_category'];
                $max="Select MAX(category_id) AS max-id FROM categories";
                $res_max=mysqli_query($con,$max);
                $category_id=$max_id+1;
            }
        }
        }
    }
    $selling_price=$_POST['selling_price'];
    $mrp=$_POST['mrp'];
    $quantity_available=$_POST['quantity_available'];
    $minimum_order=$_POST['minimum_order'];
    $availability=$_POST['availability'];
    $description=$_POST['description'];
    $product_status='true';
    $image=$_FILES['image']['name'];
    $temp_image=$_FILES['image']['tmp_name'];
    $video=$_FILES['video']['name'];
    $temp_video=$_FILES['video']['tmp_name'];
    $other_category=$_POST['other_category'];

    if($image!="")
    {
        //move_uploaded_file($temp_image,"./img/$temp_image");
        move_uploaded_file($temp_image,"./img/$image");
    }
    if($video!="")
    {
        move_uploaded_file($temp_video,"./video/$video");
    }
        $insert_products="Insert into `product` (categories_id,product_name,mrp,price,min_order,qty_available,image,video,description,status) values ('$category_id','$product_name','$mrp','$selling_price','$quantity_available','$minimum_order','$image','$video','$description','$product_status')";

        //$insert_products="Insert into `product` (categories_id,product_name,mrp,price,qty,qty_available,image,description,status) values ('$category,'$product_name','$mrp','$selling_price','$quantity_available','$minimum_order','$image','$video','$description','$product_status')";
        $res=mysqli_query($con,$insert_products);
        if($res)
        {
            echo "<script>alert('Successfully inserted')</script>";
        }
    
    
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table{
            margin: 0 auto;
        }
        body{
            background-color: #F2F1EB;
        }
        .add_products{
            background-color: #F2F1EB;
            font-family: "Open Sans", sans-serif;
        }
        h2{
            font-size:40px;
            font-weight:500;
        }
        label{
            font-size: 1.2rem;
        }
        .border{
            border-radius: 30px;
            border:1px solid black;
        }
        input{
            height:1.4rem;
            font-size: 15px;
            width:100%;
            padding:5px 15px;
        }
        td {
            padding: 20px 15px;
        }
        .btn{
            width:200px;
            border-radius: 15px;
            padding:5px 10px;
            font-size: large;
            font-weight: 500;
            margin-top:1rem;
        }
        .btn:hover{
            background-color:#DCDAF2;
        }
        #otherField{
            display: none;
        }
        .otherField-input{
            display: none;
        }
      
    </style>

    

</head>
<body>
    <div class="add_products">
        
        <form action="" method="POST" enctype="multipart/form-data">
        <h2 class="heading" style="text-align:center;">Add Products</h2>
        <table>
            <tr>
                <td class="col1"><label for="product_name">Product Name</label></td>
                <td> <input type="text" id="product_name" name="product_name" class="border"></td>
            </tr>

            <tr>
                <td> <label for="image">Image</label></td>
                <td>  <input type="file" id="image" name="image" accept="image/png, image/jpeg" /></td>
            </tr>

            <tr>
                <td><label for="category">Category</label></td>
                <td>
                <select name='category' id='category' style=' border-radius:30px;padding: 5px; width:100px;' onchange="showOtherField()">
                <?php 
                    $sql="Select * from `categories`";
                    $res=mysqli_query($con,$sql);
                    while($row_data=mysqli_fetch_assoc($res))
                    {
                        $category_title=$row_data['category_name'];
                        $category_id=$row_data['category_id'];
                        echo "<option value='$category_title' >$category_title</option> ";
                    }
                    
                ?>
                <option value='other'>other</option>
                </select>
                </td>
            </tr>

            <tr >
                <td colspan="2" id="otherField"> <label for="other_category">Other Category</label></td>
                <td><input type="text" id="other_category" name="other_category" class="border otherField-input"  ></td>
            </tr>
            <tr>
                <td><label for="selling_price">Selling Price</label></td>
                <td><input type="number"  min="0" id="selling_price" name="selling_price" class="border"></td>
            </tr>

            <tr>
                <td> <label for="mrp">Maximum Retail Price (MRP)</label></td>
                <td><input type="number"  min="0" id="mrp" name="mrp" class="border"></td>
           </tr>

            <tr>
                <td> <label for="quantity_available">Quantity Available</label></td>
                <td><input type="number" min="0" id="quantity_available" name="quantity_available" class="border"></td>
            </tr>

            <tr>
                <td><label for="minimum_order">Minimum Order</label></td>
                <td><input type="number"  min="0" id="minimum_order" name="minimum_order" class="border"></td>
            </tr>

            <tr>
            <td><label for="availability">Is Available</label></td>
                <td>
                    <select name="availability" id="availability" style=" border-radius:30px;padding: 5px; width:100px;">
                        <option value=1 >Yes</option>
                        <option value=0>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="location">Location</label></td>
                <td><input type="text" id="location" name="location" class="border"></td>
            </tr>

            <tr>
                <td><label for="video">Video</label></td>
                <td><input id="video" type="file" accept="video/mp4" name="video" /></td>
            </tr>

            <tr>
                <td style="vertical-align:top;"><label for="description">Description</label></td>
                <td><textarea name="description" id="description" cols="100%" rows="5" class="border"></textarea></td>
            </tr>

            <tr>
            <td colspan="2">
                <Button class="btn" style="margin-right:30px;" name="add_btn">Add</Button>
                <Button class="btn">Cancel</Button></td>
            </tr>

            </table>
        </form>
    </div>
    <script>
function showOtherField() {
    var select = document.getElementById("category");
    var otherField = document.getElementById("otherField");
    var otherField_input = document.querySelector(".otherField-input");
    if (select.value === "other") {
        otherField.style.display = "block";
        otherField_input.style.display = "block";
    } else {
        otherField.style.display = "none";
        otherField_input.style.display = "none";
    }
}
</script>
</body>
</html>
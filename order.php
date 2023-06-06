<?php

session_start();

 $connect = mysqli_connect("localhost","root","","shopping_cart");

if (isset($_POST['add_to_cart'])) {

   if (isset($_SESSION['cart'])) {

      $session_array_id = array_column($_SESSION['cart'], "id");



      if (!in_array($_GET['id'], $session_array_id)) {

       $session_array = array(
        'id' => $_GET['id'],
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "quantity" => $_POST['quantity']
       );
    
          $_SESSION['cart'][] = $session_array;
      }

   }else{
   
      $session_array = array(
        'id' => $_GET['id'],
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "quantity" => $_POST['quantity']
      );

      $_SESSION['cart'][] = $session_array;
    }
}

 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center">LID Gadgets Shop </h2>
                    <div class="col-md-12">
                <div class="row">
                    


                    <?php

                    $query = "SELECT * FROM items";
                    $result = mysqli_query($connect,$query);



                    while ($row = mysqli_fetch_array($result)) {?>
                    <div class="col-md-4">
                        <form method="post" action="order.php?id=<?=$row['id'] ?>">
                            <img src="shopping_cart/img/<?= $row['image'] ?>" style='height: 150px;'>
                            <h5 class="text-center"><?= $row['name']; ?></h5>
                            <h5 class="text-center"><?= $row['price']; ?></h5>
                            <input type="hidden" name="name" value="<?= $row['name']  ?>">
                            <input type="hidden" name="price" value="<?= $row['price']  ?>">
                            <input type="number" name="quantity" value="1" class="form-control">
                            <input type="submit" name="add_to_cart" class="btn btn-warning btn-block my-2" 
                            value="Add To Cart">

                        </form>
                    </div>

                    <?php }




                    ?>
                        </div> 
                    </div>
                </div>    
                <div class="col-md-6">
                    <h2 class="text-center"> Item Selected</h2>

                    <?php  

                    $total = 0;

                    $output = "";

                    $output .= "
                     <table class='table-bordered table-striped'>
                       <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>item Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                       <tr>
                    ";

                    if (isset($_GET['id']) && isset($_POST['price'])) {
                    if (!empty($_SESSION['cart'])) {

                        $id = $_GET['id'];
                        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
                        $quantity = filter_input(INPUT_POST,'quantity', FILTER_VALIDATE_INT);
                        $total_price =  $price * $quantity;

                       foreach ($_SESSION['cart'] as $key => $value) {

                           $output .= "
                            <tr>
                              <td>" . $id . "</td>
                              <td>" . $name . "</td>
                              <td>" . $price . "</td>
                              <td>" . $quantity . "</td>
                              <td>" . $total_price . "</td>
                              <td>
                                 <a href='order.php?action=remove&id=" . $id . "'>
                                  <buttom class='btn btn-danger btn-block'>Remove</buttom>
                                 </a>
                              </td>
                            </tr>  
                         <tr>
                          <td colspan='3'></td>
                          <td></b>Total Price</b></td>
                          <td>" . $total_price . "</td>
                          <td>
                             <a href='order.php?action=clearall'>
                              <buttom class='btn btn-warning btn-block'>Clear</buttom>
                             </a>
                          </td>

                         </tr>
                       ";
                       }
                    }
                    }



              echo $output;
                    ?>
                </div>
            </div> 
        </div>
    </div>


    <?php

   if (isset($_GET['action'])) {


      if ($_GET['action'] == "clearall") {
          unset($_SESSION['cart']);
      }



      if ($_GET['action'] == "remove") {

        if (isset($_SESSION['cart'])){
         foreach ($_SESSION['cart'] as $key => $value) {

             if ($value['id'] == $_GET['id']) {
                unset($_SESSION['cart'][$key]);
             }
         }
        }
      }
   }



      ?>
</body>
</html><?php
<?php

$product_name = $_POST['ProductName'];
$Price = $_POST['Price'];
$pid = $_POST['pid'];

//upload or move the file to directory
$target_dir = "../uploads/products/";
$file_data = $_FILES['image'];

if ($file_data['tmp_name'] !== '') {
    $file_name = $file_data['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name = 'product-' . time() . '.' . $file_ext;


    $check = getimagesize($file_data['tmp_name']);
    $file_name_value = NULL;
    // Move the file
    if ($check) {
        if(move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)){
        // Save the file_name to the database.
        $file_name_value = $file_name;
        }
    }else {
    //Do not move the files
    }
}



// Save the database
try {
    $sql = "UPDATE products SET ProductName=?, Price=?, image=?
            WHERE id=?";


        include('connection.php');

        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_name, $Price, $file_name_value, $pid]);

        $response = [
            'success' => true,
            'message' => "<strong>$product_name</strong>Successfully updated to the system."
        ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => "Error processing your request "
    ];
}


        echo json_encode($response);
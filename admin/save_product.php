<?php
session_start();
include("../config/db.php");

// Authentication: admin only
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

if(isset($_POST['product_name'])){

    $name        = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    // IMAGE HANDLING
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

        $imageName = $_FILES['image']['name'];
        $tmpName   = $_FILES['image']['tmp_name'];

        // Only allow jpg and png
        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            die("Error: Only JPG and PNG images are allowed.");
        }

        // Rename file to avoid duplicates
        $newImageName = time().'_'.$imageName;

        $uploadPath = "../assets/images/products/".$newImageName;

        if(move_uploaded_file($tmpName, $uploadPath)){
            // Insert into DB
            $sql = "INSERT INTO products (product_name, price, category_id, image)
                    VALUES ('$name','$price','$category_id','$newImageName')";
            if(mysqli_query($conn, $sql)){
                header("Location: add_product.php?success=1");
            }else{
                echo "Database Error: ".mysqli_error($conn);
            }
        }else{
            echo "Failed to upload image.";
        }

    }else{
        echo "No image uploaded.";
    }
}
?>
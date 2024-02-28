<?php
$tempUserId = $_SESSION['user_id'];
require_once("config_session.php");
include("conn.php");



if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve edited product information from $_POST and $_FILES arrays
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = $_POST['product_price'];
    $productStock = $_POST['product_stock'];
    $productWeight = $_POST['product_weight'];

    // Handle uploaded files (images and videos) using $_FILES
    $coverPhoto = $_FILES['coverPhoto'];
    $image1 = $_FILES['image1'];
    $video = $_FILES['video_button'];

    // Check if a new cover photo has been uploaded
    if ($coverPhoto['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded cover photo to a destination directory and update the corresponding database field
        $coverPhotoName = $coverPhoto['name'];
        $coverPhotoTmpName = $coverPhoto['tmp_name'];
        $coverPhotoDest = "uploads/" . $coverPhotoName;
        move_uploaded_file($coverPhotoTmpName, $coverPhotoDest);

        // Update the cover photo field in the database
        $sql = "UPDATE product SET coverPhoto = '$coverPhotoDest' WHERE product_ID = $productID";
        mysqli_query($con, $sql);
    }

    // Repeat the above process for image1 and video if needed

    // Construct an SQL query to update the product information (excluding cover photo, image1, and video)
    $sql = "UPDATE product SET
        product_name = '$productName',
        product_description = '$productDescription',
        product_price = '$productPrice',
        product_stock = '$productStock',
        product_weight = '$productWeight'
        WHERE product_ID = $productID";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        // Redirect back to the product listing page (e.g., farmer_viewproduct.php) after successful update
        header("Location: farmer_viewproduct.php");
    } else {
        echo "Error updating product: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>

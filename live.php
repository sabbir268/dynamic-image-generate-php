<?php
/** Set Browser Content Type */
header('Content-type: image/png');
header('Content-Disposition: inline; filename="badges.png"');
/** Database connection */
$conn = new mysqli("localhost", "w446s11499_dynamic", "clicknow1231", "w446s11499_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/** Database connection end*/


if ((isset($_GET['bid']) && !empty($_GET['bid'])) && (isset($_GET['id']) && !empty($_GET['id']))) {
    $text = "";
    $bid = $_GET['bid'];
    
    /** Get user address from database */
    $sql = "SELECT * FROM `users_data` WHERE user_id=" . $_GET['id'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $state = $row['state_in'] ? ', ' . $row['state_in'] : '';
        $address = $row['city'] .''. $state;
        $text = $address;
    } else {
        header('Location: 404.php');
    }
    /** Get user address from database end*/
         

    /** Check which badges */
    if ($bid == 1) {
        $im = $image = imagecreatefrompng('main1.png');
    } elseif ($bid == 2) {
        $im = $image = imagecreatefrompng('main2.png');
    } else {
        header('Location: 404.php');
    }
    /** Check which badges end*/

    $textColor = imagecolorallocate($image, 255, 255, 254);
    $col_transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
    imagefill($image, 0, 0, $col_transparent);
    imagecolortransparent($image, $col_transparent);
    imagesavealpha($image, true);
    // Full Font-File Path
    // $font = $fontPath = '/opt/lampp/htdocs/clicknow/final/Montserrat-Medium.ttf';
    $font = $fontPath = 'Roboto-Light.ttf';

    $font_size = 35;
    // Get image Width and Height
    $image_width = imagesx($im);
    $image_height = imagesy($im);

    // Get Bounding Box Size
    $text_box = imagettfbbox($font_size, 0, $font, $text);

    // Get your Text Width and Height
    $text_width = $text_box[2] - $text_box[0];
    $text_height = $text_box[7] - $text_box[1];

    // Calculate coordinates of the text
    $x = ($image_width / 2) - ($text_width / 2);
    $y = ($image_height / 2) - ($text_height / 2);

    if ($bid == 1) {
        imagettftext($im, $font_size, 0, $x, $y+35, $textColor, $font, $text);
    } elseif ($bid == 2) {
        imagettftext($im, $font_size, 0, $x, $y+120, $textColor, $font, $text);
    } else {
        header('Location: 404.php');
    }

    //Send Image To Browser
    imagepng($image);

    //Clear Image From Memory
    imagedestroy($image);
} else {
    header('Location: 404.php');
}

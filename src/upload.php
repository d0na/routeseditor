<?php

$LIMIT_SIZE =5000000;
$LIMIT_WIDTH =930;
$LIMIT_HEIGHT=800;


$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

list($orig_width, $orig_height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

if ($orig_width < $LIMIT_WIDTH || $orig_height < $LIMIT_HEIGHT ){
    echo "Sorry, your photo is too narrow.";
    $uploadOk = 0;
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > $LIMIT_SIZE) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //TODO inserire redirection a pagina di errore con messaggio

    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        $res_img = resizeImage($target_file,930,800);

        $res =imagejpeg($res_img, 'res/'. basename($_FILES["fileToUpload"]["name"]));

        echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

            header("Location: editor.php?img=" . basename($_FILES["fileToUpload"]["name"]));
            die();

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function resizeImage($filename, $max_width, $max_height)
{
    list($orig_width, $orig_height) = getimagesize($filename);

    $width = $orig_width;
    $height = $orig_height;

    # taller
    if ($height > $max_height) {
        $width = ($max_height / $height) * $width;
        $height = $max_height;
    }

    # wider
    if ($width > $max_width) {
        $height = ($max_width / $width) * $height;
        $width = $max_width;
    }

    $new_image = imagecreatetruecolor($width, $height);
    $source = imagecreatefromjpeg($filename);

    imagecopyresampled($new_image, $source, 0, 0, 0, 0,
        $width, $height, $orig_width, $orig_height);


    return $new_image;

}

?>
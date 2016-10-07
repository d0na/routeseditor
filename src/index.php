<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/plugins.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/2.3.4/svg.min.js"></script>
    <script src="js/svg.draw.js"></script>

</head>
<body>

<?php echo "a"; ?>

<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<h1>FOL Route editor</h1>
<!-- Add your site or application content here -->
<p>Ciao, benvenuto. Con questo strumento potrai editare le vie sopra la foto di un settore.</p>
<p>Prima devi procedere al caricamento della foto dopodiche' potrai liberamente iniziare a tracciare le linee .</p>


<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>


</body>




</html>

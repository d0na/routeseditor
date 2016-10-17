<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/main.css">

</head>
<body>



<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<h1>FOL Route editor</h1>
<!-- Add your site or application content here -->
<p>Ciao, benvenuto. Con questo strumento potrai editare le vie sulla foto di un settore di arrampicata.</p>
<p>Seleziona prima una delle opzioni sotto elencate e potrai liberamente iniziare a tracciare le linee .</p>


<div style="border: 1px solid black;  border-radius: 5px; padding: 5px; margin: 2px; ">
    <form action="editor.php">
        <b>Select svg to upload:</b>
        <select name="svg" >
            <?php
            $directory = '/Users/francesco/git/routeseditor/src/svg/';
            $scanned_directory = array_diff(scandir($directory), array('..', '.'));
            foreach ($scanned_directory as &$value) {
                echo "<option value=\"$value\">$value</option>";
            }
            ?>
        </select>
        <input type="submit" value="Upload SVG" name="submit">
    </form>
</div>

<div style="border: 1px solid black;  border-radius: 5px; padding: 5px; margin: 2px; ">
    <form action="editor.php">
        <b>Select crag image:</b>
        <select name="img" >
            <?php
            $directory = '/Users/francesco/git/routeseditor/src/res/';
            $scanned_directory = array_diff(scandir($directory), array('..', '.'));
            foreach ($scanned_directory as &$value) {
                echo "<option value=\"$value\">$value</option>";
            }
            ?>
        </select>
        <input type="submit" value="Submit image" name="submit">
    </form>
</div>




<div style="border: 1px solid black;  border-radius: 5px; padding: 5px; margin: 2px; ">
<form action="upload.php" method="post" enctype="multipart/form-data">
    <b>Select image to upload:</b>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
</div>



</body>




</html>

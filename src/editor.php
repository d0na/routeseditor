<!DOCTYPE html>

<meta charset="utf-8">
<title>Crag route Editor</title>
<link rel="stylesheet" href="css/main.css">
<body>
<script src="js/editor.js"></script>
<?php

$img = isset($_GET['img']) ? $_GET['img'] : '';
$svg = isset($_GET['svg']) ? $_GET['svg'] : '';

$img_width = null;
$img_height = null;

if (!empty($img)) {

    list($img_width, $img_height) = getimagesize('res/' . $img);
}

?>


<a href="index.php" class="flatbtn">HOME</a>
<p/>
<!--BOTTONI -->
<a href="#" class="flatbtn" id="btnAdd">Nuova via</a>
<a href="#" class="flatbtn" id="btnDelete" style="background-color: red">Elimina via</a>
<!--<a href="#" class="flatbtn" id="btnUndo" style="background-color: lightskyblue">Undo</a>-->
<a href="#" class="flatbtn" id="btnDeletePoint" style="background-color: darkgoldenrod">Elimina punto</a>
<a href="#" class="flatbtn" id="btnDeselect" style="background-color: gold">Deseleziona</a>
<a href="#" class="flatbtn" id="btnSave" style="background-color: green">Salva</a>

<div id="crag"></div>

<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>

<script>


    //Controllo bottone elimina punto
    document.getElementById("btnDeletePoint").addEventListener("click", function () {
        $('.selected').remove();
        removePoint(selected);
    });

    //Controllo bottone elimina via
    document.getElementById("btnDelete").addEventListener("click", function () {
        key = getKeyByValue(selected);
        delete  allPoints[key];
        d3.selectAll('[data-route=' + key + ']').remove()
        d3.selectAll('[id=' + key + ']').remove()
        redraw();
    });

    Array.prototype.last = function () {
        return this[this.length - 1];
    }

    //Controllo bottone aggiungi via
    document.getElementById("btnAdd").addEventListener("click", function () {
        newRoute();
    });

    //Controllo bottone elimina punto
    document.getElementById("btnSave").addEventListener("click", function () {

        //TODO Guardare questi due link per upload blob server side
        //http://stackoverflow.com/questions/13333378/how-can-javascript-upload-a-blob
            // http://stackoverflow.com/questions/19015555/pass-blob-through-ajax-to-generate-a-file

        var svgData = $("#crag")[0].outerHTML;
        var svgBlob = new Blob([svgData], {type:"image/svg+xml;charset=utf-8"});
        var svgUrl = URL.createObjectURL(svgBlob);
        var downloadLink = document.createElement("a");
        downloadLink.href = svgUrl;
        downloadLink.download = "newesttree.svg";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });

    var image = '<?php echo $img ?>';
    var extsvg = '<?php echo $svg ?>';

    if (image != '') {

        width = <?php echo(is_null($img_width) ? "930" : $img_width); ?>;
        height = <?php echo(is_null($img_height) ? "930" : $img_height);  ?>;
        init(width, height, 'res/' + image);

    } else if (extsvg != '') {
        //loads the external svg
        d3.xml("svg/" + extsvg).mimeType("image/svg+xml").get(function (error, data) {
            if (error) throw error;

            //Recupera l'immagine dal SVG
            img = [].map.call(data.querySelectorAll("image"), function (d) {
                return {
                    link: d.getAttribute("xlink:href"),
                    width: d.getAttribute("width"),
                    height: d.getAttribute("height")
                };
            });

            //Imposta parametri immagine
            width = img.map(function (d) {
                return d.width;
            })
            height = img.map(function (d) {
                return d.height;
            })
            image = img.map(function (d) {
                return d.link;
            });

            //Inizializza l'svg per la creazione
            init(width, height, image);

            //Recupera e appende i paths
            circles = [].map.call(data.querySelectorAll("circle"), function (c) {
                return {
                    route: c.getAttribute("data-route"),
                    cx: c.getAttribute("cx"),
                    cy: c.getAttribute("cy"),
                    cl: c.getAttribute("class")
                };
            })

            //Recupera e appende i punti
            tKey = '';
            circles.forEach(function (v, key, circles) {
                selected = [v.cx, v.cy];
                //Nuova via
                if (tKey != v.route) {
                    tKey = v.route;
                    newRoute(v.route, v.cx, v.cy)
                } else {
                    //Appendi punto della via
                    tKey = key = v.route;

                    if (circles.last() == v) {
                        allPoints[key].push(selected);
                        dragged = null;
                        redraw();
                    } else {
                        appendPoint(selected);
                    }
                }
            });
        });

    } else {
        die('Some problem occur');
    }


    //    //Duplica via TODO: rimuovere??
    //    document.getElementById("btnDuplicate").addEventListener("click", function () {
    //        //duplicateRoute();
    //
    //    });

    //Undo operazioni
    //    document.getElementById("btnUndo").addEventListener("click", function () {
    //        $('.selected').remove();
    //        undo();
    //    });


</script>
</body>

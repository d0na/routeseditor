<!DOCTYPE html>

<meta charset="utf-8">
<title>Crag route Editor</title>
<link rel="stylesheet" href="css/main.css">
<body>
<script src="js/editor.js"></script>


<a href="index.php" class="flatbtn" >HOME</a>
<p/>
<!--BOTTONI -->
<a href="#" class="flatbtn" id="btnAdd">Nuova via</a>
<a href="#" class="flatbtn" id="btnDelete" style="background-color: red">Elimina via</a>
<!--<a href="#" class="flatbtn" id="btnUndo" style="background-color: lightskyblue">Undo</a>-->
<a href="#" class="flatbtn" id="btnDeletePoint" style="background-color: darkgoldenrod">Elimina punto</a>
<a href="#savemodal" class="flatbtn" id="modaltrigger" style="background-color: green">Salva</a>
<a href="#" class="flatbtn" id="btnImport">Importa</a>
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
        d3.selectAll('[data-route='+key+']').remove()
        d3.selectAll('[id='+key+']').remove()
        redraw();
    });

    Array.prototype.last = function() {
        return this[this.length-1];
    }

    //Controllo bottone aggiungi via
    document.getElementById("btnAdd").addEventListener("click", function () {
            newRoute();

    });


    //Controllo bottone importa via
    document.getElementById("btnImport").addEventListener("click", function () {
        //loads the external svg
        d3.xml("svg/example.svg").mimeType("image/svg+xml").get(function (error, data) {
            if (error) throw error;



            //Recupera l'immagine nel SVG
            img = [].map.call(data.querySelectorAll("image"), function (d) {
                return {
                    link: d.getAttribute("xlink:href"),
                    width: d.getAttribute("width"),
                    height: d.getAttribute("height")
                };
            });

            width =img.map(function (d) {return d.width;})
            height = img.map(function (d) {return d.height;})
            image = img.map(function (d) {return d.link;});

            init(width,height,image);



            //Recupera i path
            circles = [].map.call(data.querySelectorAll("circle"), function (c) {
                return {
                    route: c.getAttribute("data-route"),
                    cx: c.getAttribute("cx"),
                    cy: c.getAttribute("cy"),
                    cl: c.getAttribute("class")
                };})

            tKey = '';
            circles.forEach(function(v, key, circles) {
                console.log("m[" + v.route + "] = "+ v.cx+","+v.cy+","+v.cl);
                if (tKey != v.route){
                    tKey=v.route;
                    newRoute(v.route, v.cx,v.cy)
                } else {
                    tKey = key = v.route;
                    appendPoint([v.cx,v.cy]);
                }
                // Do something

            });

        });

        $('circle').show();


    });



//    //Controllo bottone importa via
//    document.getElementById("btnImport").addEventListener("click", function () {
//        d3.xml("svg/example.svg").mimeType("image/svg+xml").get(function(error, xml) {
//            if (error) throw error;
//            document.body.appendChild(xml.documentElement);
//        });
//        line = d3.svg.line();
//
//        svg = d3.select("#crag")
//
//        d3.select(window)
//            .on("mousemove", mousemove)
//            .on("mouseup", mouseup)
//            .on("keydown", keydown);
//
//        line.interpolate("cardinal");
//        svg.node().focus();
//    });



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

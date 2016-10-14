var idLine = 0;
var key = null;
var allPoints = {};
var lastPoint = 50;
var offsetPoint = 20;
var undoPoints = [];
var dragged = null, selected = null, first = null;

var line, svg = null;

function init(){
    if (image != ''){
        line = d3.svg.line();
        //Make an SVG container
        svg = d3.select("#crag").append("svg")
            .attr('width', width)
            .attr('height', height)
        //        .attr("tabindex", 1);

        //Add an image (Crag image)
        svg.append("svg:image")
            .attr('width', width)
            .attr('height', height)
            //        .style("width", 'auto')
            //        .style("height", 'auto')
            .attr("xlink:href", "res/"+image);

        //Draw the rectangle
        svg.append("rect")
            .attr("width", width)
            .attr("height", height)
            .on("mousedown", mousedown);

        d3.select(window)
            .on("mousemove", mousemove)
            .on("mouseup", mouseup)
            .on("keydown", keydown);

        line.interpolate("cardinal");
        svg.node().focus();
    }
}


function mousedown() {
    selected = dragged = d3.mouse(svg.node());
    allPoints[key].push(selected);
    //undo.push([key,selected]);
    undoPoints.push(key);
    redraw();
}

function mousemove() {
    if (!dragged) return;
    var m = d3.mouse(svg.node());
    dragged[0] = Math.max(0, Math.min(width, m[0]));
    dragged[1] = Math.max(0, Math.min(height, m[1]));
    redraw();
}

function mouseup() {
//        console.log(d3.selected);
    key = getKeyByValue(selected);

    if (!dragged) return;
    mousemove();
    dragged = null;
}


//Intercetta la pressione dei tasti, backspace
function keydown() {
    if (!selected) return;
    switch (d3.event.keyCode) {
        case 8: // backspace
        case 46: { // delete
            removePoint(selected);
            break;
        }
    }
}

//Rimuove i punti
function removePoint(s) {
    var key = getKeyByValue(s);
    var i = allPoints[key].indexOf(s);
    allPoints[key].splice(i, 1);
    redraw();
}

//Rimuove i punti
function undo() {
    var i = allPoints[key].indexOf(selected);
    allPoints[key].splice(i, 1);
    selected = allPoints[key].length ? allPoints[key][i > 0 ? i - 1 : 0] : null;
    redraw();
}


function newRoute() {
    idLine++; //incrementa id della via
    key = "route_" + idLine;
    allPoints[key] = [[lastPoint = lastPoint + offsetPoint, 200]]; //posiziona il primo punto
    selected = allPoints[key][0];           //resetta il punto selezionato
    first = selected;

    //Appendi la nuova via (path) all'SVG
    svg.append("path")
        .datum(allPoints[key])
        .attr("class", "line")
        .attr("id", key)
        .call(redraw);
}


function getKeyByValue(v) {
    for (key in allPoints) {
        var intPoints = allPoints[key];
        var arrayLength = intPoints.length;
        for (var i = 0; i < arrayLength; i++) {
            if (intPoints[i] === v)
                return key
        }
    }
}

//Redraw
function redraw() {
    //Seleziona la via associata al cerchio selezionato
    svg.select("#" + key).attr("d", line);

    $('circle').not('.first').hide();
    $('[data-route=' + key + ']').show();

    var circle = svg.selectAll("circle")
        .data(allPoints[key], function (d) {
            return d;
        });


    circle.enter().append("circle")
        .attr("data-route", key)
        .attr("r", 1e-6)
        .on("mousedown", function (d) {
            selected = dragged = d;
            redraw();
        })
        .transition()
        .duration(750)
        .ease("elastic")
        .attr("r", 6.5);

    circle
        .classed("selected", function (d) {
            return d === selected;
        })
        .classed("first", function (d) {
            var c = d3.select(this).attr("class");
            if (c.indexOf("first") != -1) {
                return true;
            }
            return d === first;
        })
        .attr("cx", function (d) {
            return d[0];
        })
        .attr("cy", function (d) {
            return d[1];
        });


    //circle.exit().remove();

    //circle.exit().attr("class","");

    if (d3.event) {
        d3.event.preventDefault();
        d3.event.stopPropagation();
    }
}

function duplicateRoute() {
    var keyRef = key;
    idLine++; //incrementa id della via
    key = "route_" + idLine;

    allPoints[key] = allPoints[keyRef]; //posiziona il primo punto

    var circle = d3.selectAll('[data-route=' + keyRef + ']');

    //Appendi la nuova via (path) all'SVG
    svg.append("path")
        .datum(allPoints[key])
        .attr("class", "line")
        .attr("id", key)
        .call(redraw);

    cloneSelection(svg, circle, circle.length, key);
//        redraw();
}

function cloneSelection(appendTo, toCopy, times, dataVal) {
    toCopy.each(function () {
        for (var i = 0; i < times; i++) {
            var clone = svg.node().appendChild(this.cloneNode(true));
            d3.select(clone).attr("data-route", dataVal);
        }
    });
    return appendTo.selectAll('.clone');
}

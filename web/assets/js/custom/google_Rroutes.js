window.onload = function () {
    initialize();
    document.getElementsByTagName("body")[0].addEventListener("onunload", GUnload());
    document.getElementsByTagName("body")[0].setAttribute("jstcache", 0);

    var in_or = document.getElementById('appbundle_rutina_origen');
    var in_de = document.getElementById('appbundle_rutina_destino');
    if(in_or.value !== "" && in_de.value !== ""){
        findRoute(in_or.value,in_de.value);
    }
    in_or.addEventListener('change', function () {
        findRoute(in_or.value,in_de.value);
    });
    in_de.addEventListener('change', function () {
        findRoute(in_or.value,in_de.value);
    });
};

function findRoute(o, d) {
    if (o !== "" && d !== "") {
        setDirections(o, d, 'es');
        return false;
    }
}

var map;
var gdir;
var toggleState = 1;

//traffic
var trafficInfo = new GTrafficOverlay();

function initialize() {
    if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map_canvas"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.addOverlay(trafficInfo);
        gdir = new GDirections(map, document.getElementById("directions"));
        GEvent.addListener(gdir, "load", onGDirectionsLoad);
        GEvent.addListener(gdir, "error", handleErrors);

        setDirections("Paseo de las palmeras, Bailén", "I.E.S. Oretania, Linares");
    }
}

function toggleTraffic(button) {
    if (toggleState === 1) {
        map.removeOverlay(trafficInfo);
        toggleState = 0;
    } else {
        map.addOverlay(trafficInfo);
        toggleState = 1;
    }
}


function setDirections(fromAddress, toAddress, locale) {
    toAddress.replace("1 ", "");
    gdir.load("from: " + fromAddress + " to: " + toAddress, {
        "locale": locale
    });
}

function handleErrors() {
    if (gdir.getStatus().code === G_GEO_UNKNOWN_ADDRESS)
        alert("La ubicación geográfica correspondiente no puede encontrarse en una de las direcciones especificadas. Esto puede ser debido a que la dirección es relativamente nueva, o puede ser incorrecta.\nCódigo de error: " + gdir.getStatus().code);
    else if (gdir.getStatus().code === G_GEO_SERVER_ERROR)
        alert("Una solicitud de geocodificación o de direcciones no podría ser procesada con éxito, todavía no se sabe el motivo exacto del fallo.\nCódigo ded error: " + gdir.getStatus().code);

    else if (gdir.getStatus().code === G_GEO_MISSING_QUERY)
        alert("El parámetro 'q' HTTP 'falta o no tiene ningún valor. Para las solicitudes de geocoder esto significa que una dirección vacía se especifica como entrada. Para las solicitudes de direcciones esto significa que ninguna consulta se especifica en la entrada.\nCódigo de error: " + gdir.getStatus().code);

    else if (gdir.getStatus().code === G_GEO_BAD_KEY)
        alert("La clave dada es inválida o no coincide con el dominio para el cual fue dada. \nCódigo de error: " + gdir.getStatus().code);

    else if (gdir.getStatus().code === G_GEO_BAD_REQUEST)
        alert("No se pudo analizar con éxito una solicitud de dirección.\nCódigo de error: " + gdir.getStatus().code);

    else alert("Ha ocurrido un error desconocido.");

}

function onGDirectionsLoad() {
    // Use this function to access information about the latest load()
    // results.

    // e.g.
    // document.getElementById("getStatus").innerHTML = gdir.getStatus().code;
    // and yada yada yada...
}
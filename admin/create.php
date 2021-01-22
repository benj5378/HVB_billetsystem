<?php

    if(!($_GET["auth"] == "2#n#QXN@nP+H^Q%nqy=8Jqb5dYj3hJ=WsRpGL9@2")) {
        print_r($_GET["auth"]);
        die();
    }

?>


<html>
<head>
<style>
    div.del {
        padding: 2em;
        background-color: lightgray;
        margin-bottom: 1em;
        overflow: auto;
    }
</style>
<script>
    var idCount = 0

    function create() {
        
        idCount++;
        id = idCount;

        var default_stops = ["Hedehusgård", "Sølund", "Stenager", "Brandhøj", "Flintebjerg", "Rubjerg", "Plantagen", "Fem Ege"]

        var num_stops = document.getElementById("create_num_stops").value;
        var num_trains = document.getElementById("create_num_trains").value;
        var num_departures = document.getElementById("create_num_departures").value;
        var input_main_compartments =  document.getElementById("input_main_compartments").value;

        var alphabet = "ABCDEFGHIJKLMNOPQRSTUVXYZÆØÅ"

        html = "<div class=\"del\" id=\"id-" + id + "_div\">";

        html += "Dato: <input id=\"id-" + id + "_date\" type=\"date\"/>";
        html += "<br />";

        // create train type: motor, damp
        for(var trains = 0; trains < num_trains; trains++) {
            // train 1 = A, train 2 = B, train 3 = C
            html += "Tog '" + alphabet[trains] + "' er et <select class=\"id-" + id + "_traintype\" data-train=\"" + alphabet[trains] + "\" ><option>motor</option><option>damp</option></select>";
            html += "<br />";
        }
        
        html += "<br />";
        html += "<br />";

        // UDREJSE

        html += "<table id=\"id-" + id + "_table_udrejse\">";
        html += "<thead>";
        
        // add train types to header
        html += "<tr>";
        html += "<th></th>";
        // create columns for header: num_departures = columns
        for(var columns = 0; columns < num_departures; columns++) {
            // create dropdown
            html += "<th><select>";
            for(var trains = 0; trains < num_trains; trains++) {
                // train 1 = A, train 2 = B, train 3 = C
                html += "<option>" + alphabet[trains] + "</option>";
            }
            html += "</select></th>";
        }
        html += "</tr>";
        
        // add compartments input
        html += "<tr>";
        html += "<th>Kupéer per afgang</th>";
        // create columns for header: num_departures = columns
        for(var columns = 0; columns < num_departures; columns++) {
            // create input for compartments
            html += "<th><input style=\"width: 98px\" type=\"number\" value=\"" + input_main_compartments + "\" /></th>";
        }
        html += "</tr>";

        html += "</thead>";
        html += "<tbody>";

        // stops = rows
        for(var rows = 0; rows < num_stops; rows++) {
            html += "<tr>";

            // add stops
            html += "<td><input type=\"text\" value=\"" + default_stops[rows] + "\" /></td>";

            // num_departures = columns
            for(var columns = 0; columns < num_departures; columns++) {
                html += "<td><input type=\"time\" step=\"2\"></td>";
            }
            html += "</tr>";
        }
        html += "</tbody>";
        html += "</table>";

        html += "<br />";

        // RETURREJSE

        html += "<table id=\"id-" + id + "_table_hjemrejse\">";
        html += "<thead>";
        
        // add train types to header
        html += "<tr>";
        html += "<th></th>";
        // create columns for header: num_departures = columns
        for(var columns = 0; columns < num_departures; columns++) {
            // create dropdown
            html += "<th><select>";
            for(var trains = 0; trains < num_trains; trains++) {
                // train 1 = A, train 2 = B, train 3 = C
                html += "<option>" + alphabet[trains] + "</option>";
            }
            html += "</select></th>";
        }
        html += "</tr>";

        // add compartments input
        html += "<tr>";
        html += "<th></th>";
        // create columns for header: num_departures = columns
        for(var columns = 0; columns < num_departures; columns++) {
            // create input for compartments
            html += "<th><input style=\"width: 98px\" type=\"number\" value=\"" + input_main_compartments + "\" /></th>";
        }
        html += "</tr>";

        html += "</thead>";
        html += "<tbody>";

        // stops = rows
        for(var rows = 0; rows < num_stops; rows++) {
            html += "<tr>";

            // add stops
            html += "<td><input type=\"text\" value=\"" + default_stops[num_stops - 1 - rows] + "\" /></td>";

            // num_departures = columns
            for(var columns = 0; columns < num_departures; columns++) {
                html += "<td><input type=\"time\" step=\"2\"></td>";
            }
            html += "</tr>";
        }
        html += "</tbody>";
        html += "</table>";

        html += "<br />";
        html += "<button onclick=\"save('id-" + id + "')\">Gem</button>"; //<button onclick=\"duplicate(this)\">Duplikér</button>"

        html += "</div>";
        
        document.getElementById("liste").innerHTML = html;
        
        console.log(html);
    }

    function save(id) {
        
        date = document.getElementById(id + "_date").value;
        table_udrejse = document.getElementById(id + "_table_udrejse");
        table_returrejse = document.getElementById(id + "_table_returrejse");

        array_stations = [];
        stop_data = JSON.parse("[]")

        // parse table to stop_data
        table = table_udrejse;
        for (var i = 0, row; row = table.rows[i]; i++) {
            for (var j = 0, col; col = row.cells[j]; j++) {

                //console.log(JSON.stringify(stop_data, null, 3));
                console.log("row: " + i + ", col: " + j);

                // first row is headers:
                if (i == 0) {
                    //first column is empty, skip
                    if(j == 0) {
                        continue;
                    }
                    var train = col.getElementsByTagName("select")[0].value;
                    // ???? create new empty array with trains. Afterwards [A, B, A, A, B]
                    // j - 1 because first column is skipped
                    stop_data[j - 1] = {"train": train}; // not = stop_data[j - 1]["train"] because of appending
                    console.log(stop_data[j - 1]);
                    console.log("WAS HERE");
                    continue;
                }
                // second row is compartments
                else if (i == 1) {
                    //first column is empty, skip
                    if(j == 0) {
                        continue;
                    }
                    var compartments = col.getElementsByTagName("input")[0].value;
                    // j - 1 because first column is skipped
                    stop_data[j - 1]["compartments"] = compartments;
                    continue;
                }
                // get station of times iterating through. Current station will for instance be hhg. until the row is done
                if(j == 0) {
                    var current_station = col.getElementsByTagName("input")[0].value;
                    continue;
                }
                // eg. timestamp = 10:20
                var timestamp = col.getElementsByTagName("input")[0].value;
                //eg. json[0]["Hedehusgaard"] = "10:20"
                stop_data[j - 1][current_station] = timestamp;
                
            }
            
        }
        //console.log(array_stations)

        train_types = document.getElementsByClassName(id + "_traintype")

        var train_data = JSON.parse("{}")
        for (var i = 0; i < train_types.length; i++) {
            train_data[train_types[i].getAttribute('data-train')] = train_types[i].value;
        }

        json = JSON.parse("{}")
        json["stop_data"] = stop_data;
        json["train_types"] = train_data;
        json["date"] = date;

        console.log(json)

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    alert("success")
                }
                else if (response == "failure") {
                    alert("Fejl. Har du tjekket at alle felterne er udfyldt?")
                }
                else {
                    alert("unknown error")
                }
                console.log(JSON.stringify(json));
                console.log(response);
            }
        };
        xhttp.open("POST", "./handler.php?auth=2%23n%23QXN%40nP%2BH%5EQ%25nqy%3D8Jqb5dYj3hJ%3DWsRpGL9%402", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhttp.send(JSON.stringify(json, null, 3));

        document.getElementById(id + "_div").style.backgroundColor = "lightgreen";
    }
</script>
</head>
<body>
    <h1>Nyt plantog</h1>
    Antal stop: <input id="create_num_stops" value="8" type="number" />
    Antal oprangeringer: <input id="create_num_trains" value="2" type="number" />
    Antal afgange hver vej: <input id="create_num_departures" value="10" type="number" />
    Antal kupéer per afgang: <input id="input_main_compartments" value="10" type="number" />
    <button onclick="create()">Kréer</button> (sletter den gamle)

    <br /><br /><br />

    Liste:<br />
    <div id="liste">

    </div>
</body>
</html>






<!-- <html>
    <head>
        <script>
            function insert() {
                json = {};
                json["type"] = document.getElementById("type").value;
                json["stops"] = {}

                element = document.getElementById("stops");
                for (var i = 0; i < element.childElementCount; i++) {
                    json["stops"][element.childNodes[i].childNodes[0].value] = element.childNodes[i].childNodes[1].value;
                }

                json["date"] = document.getElementById("date").value;

                console.log(json);
            }
        </script>
    </head>
    <body>
        <input id="type" type="text" placeholder="Motor/Damp" />
        <br />
        <div id="stops" style="margin-left: 1em">
            <div>
                <input type="text" value="Hedehusgård St." />
                <input type="text" value="10:45" />
            </div>
            <div>
                <input type="text" value="Sølund St." />
                <input type="text" value="10:50" />
            </div>
            <div>
                <input type="text" value="Stenager Trb." />
                <input type="text" value="10:52" />
            </div>
            <div>
                <input type="text" value="Brandhøj St." />
                <input type="text" value="10:54" />
            </div>
            <div>
                <input type="text" value="Flintebjerg Trb." />
                <input type="text" value="11:00" />
            </div>
            <div>
                <input type="text" value="Rubjerg St." />
                <input type="text" value="11:03" />
            </div>
            <div>
                <input type="text" value="Plantagen Trb." />
                <input type="text" value="11:07" />
            </div>
            <div>
                <input type="text" value="Fem Ege St." />
                <input type="text" value="11:11" />
            </div>
        </div>
        <input id="date" type="date" />
        <button onclick="insert()">Indsæt</button>
    </body>
</html> -->
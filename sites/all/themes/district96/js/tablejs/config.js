/**
 * @file
 * Theme hooks for the Drupal Bootstrap base theme.
 */
(function ($, Drupal, Bootstrap) {
    window.googleDocCallback = function () { return true; };
    d3.text("https://d96toastmasters.ca/sites/all/themes/district96/js/tablejs/OnlinezoommeetingsDistrict96.csv", function(data) {
          var parsedCSV = d3.csv.parseRows(data);
                console.log("ORG parsedCSV : " + parsedCSV);
              
                // take the third column
                //var first = parsedCSV.map(function(value,index) { return value[0]; });
                // console.log("first : " + first);
                
            
                
                parsedCSV.shift()
                container = d3.select("div#onlineMeetings table")
                    .append("tbody")

                    .selectAll("tr")
                        .data(parsedCSV).enter()
                        .append("tr")

                    .selectAll("td")
                        .data(function(d) { return d; }).enter()
                        .append("td")
                        .text(function(d) { return d; });
    });
    console.log(parsedCSV);
    $( document ).ready(function() {
        $("table").addClass("table table-striped");
    });
    

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);
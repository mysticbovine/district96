/**
 * @file
 * Theme hooks for the Drupal Bootstrap base theme.
 */
/*
    Column names:
    		<th><span class="date">Date</span></th>
			<th><span class="start">Start</span></th>
			<th><span class="end">End</span></th>
			<th><span class="zone">Time zone</span></th>
			<th><span class="title">Title</span></th>
			<th><span class="host">Host</span></th>
			<th><span class="special-presentation">Special presentation</span></th>
			<th><span class="registration">Registration</span></th>
			<th><span class="zoom">Zoom</span></th>
			<th><span class="password">Password</span></th>
 */
(function ($, Drupal, Bootstrap) {

	$(function() {
        $.tablesorter.themes.bootstrap = {
            // these classes are added to the table. To see other table classes available,
            // look here: http://getbootstrap.com/css/#tables
            table        : 'table table-bordered table-striped d96',
            caption      : 'caption',
            // header class names
            header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
            sortNone     : '',
            sortAsc      : '',
            sortDesc     : '',
            sortRestart  : true,
            active       : '', // applied when column is sorted
            hover        : '', // custom css required - a defined bootstrap style may not override other classes
            // icon class names
            icons        : '', // add "bootstrap-icon-white" to make them white; this icon class is added to the <i> in the header
            iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
            iconSortAsc  : 'glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
            iconSortDesc : 'glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
            filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
            footerRow    : '',
            footerCells  : '',
            even         : '', // even row zebra striping
            odd          : ''  // odd row zebra striping
          };

        $("#cot").tablesorter({
            // this will apply the bootstrap theme if "uitheme" widget is included
            // the widgetOptions.uitheme is no longer required to be set
            theme : "bootstrap",

            sortList: [[1,0],[0,0]],
        
            widthFixed: true,
        
            headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

            headers: {
                // disable sorting of the first & second column - before we would have to had made two entries
                // note that "first-name" is a class on the span INSIDE the first column th cell
                '.special-presentation, .registration, .zoom, .password' : {
                  // disable it by setting the property sorter to false
                  sorter: false
                }
            },
            // widget code contained in the jquery.tablesorter.widgets.js file
            // use the zebra stripe widget if you plan on hiding any rows (filter widget)
            widgets : [ "uitheme", "filter", "columns", "zebra" ],
        
            widgetOptions : {
              // using the default zebra striping class name, so it actually isn't included in the theme variable above
              // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
              zebra : ["even", "odd"],
        
              // class names added to columns when sorted
              columns: [ "primary", "secondary", "tertiary" ],
        
              // reset filters button
              filter_reset : ".reset",
        
              // extra css class name (string or array) added to the filter element (input or select)
              filter_cssFilter: "form-control",
        
              // set the uitheme widget to use the bootstrap theme class names
              // this is no longer required, if theme is set
              // ,uitheme : "bootstrap"
              filter_functions : {

                // Add select menu to this column
                // set the column value to true, and/or add "filter-select" class name to header
                '.zone' : true, 
                '.host' : true 
              
              }
           
        
            }
            
          });
          $("#onlineMeetings").tablesorter({
            // this will apply the bootstrap theme if "uitheme" widget is included
            // the widgetOptions.uitheme is no longer required to be set
            theme : "bootstrap",

            sortList: [[1,0],[0,0]],
        
            widthFixed: true,
        
            headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

            headers: {
                // disable sorting of the first & second column - before we would have to had made two entries
                // note that "first-name" is a class on the span INSIDE the first column th cell
                '.special-presentation, .registration, .zoom, .password' : {
                  // disable it by setting the property sorter to false
                  sorter: false
                }
            },
            // widget code contained in the jquery.tablesorter.widgets.js file
            // use the zebra stripe widget if you plan on hiding any rows (filter widget)
            widgets : [ "uitheme", "filter", "columns", "zebra" ],
        
            widgetOptions : {
              // using the default zebra striping class name, so it actually isn't included in the theme variable above
              // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
              zebra : ["even", "odd"],
        
              // class names added to columns when sorted
              columns: [ "primary", "secondary", "tertiary" ],
        
              // reset filters button
              filter_reset : ".reset",
        
              // extra css class name (string or array) added to the filter element (input or select)
              filter_cssFilter: "form-control",
        
              // set the uitheme widget to use the bootstrap theme class names
              // this is no longer required, if theme is set
              // ,uitheme : "bootstrap"
              filter_functions : {

                // Add select menu to this column
                // set the column value to true, and/or add "filter-select" class name to header
                '.day' : true, 
                '.frequency' : true,
                '.type' : true,

              
              }
           
        
            }
            
          });
    });
	
	  $('button').click(function() {
      $('table').trigger('sortReset');
      return false;
    });

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);

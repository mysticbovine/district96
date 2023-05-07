/**
 * @file
 * Theme hooks for the Drupal Bootstrap base theme.
 */
 (function ($, Drupal, Bootstrap) {
	console.log("Training calendar Loaded");
	
    document.addEventListener('DOMContentLoaded', function() {
        // Training Events
        var calendarEl = document.getElementById('training');
        var filterEvents = document.querySelector("#filterEvents");
        var url = '/event/cot-feed.json';
    
        var calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
       
          //initialDate:,
          editable: true,
          navLinks: true, // can click day/week names to navigate views
          dayMaxEvents: true, // allow "more" link when too many events
    
          eventDidMount: function(arg) {
            // let val = filterEvents.value;
            let val = 'all';
            if (!(val == arg.event.extendedProps.category || val == "all")) {
              arg.el.style.display = "none";
            } else {
              arg.el.style.display = "flex";
            };
            var tooltip = new Tooltip(arg.el, {
               
              //title: info.event.extendedProps.description,
              title: arg.event._def.title,
              placement: 'top',
              trigger: 'hover',
              container: 'body',
              customClass: "THIS-STYLED",
            });
          },
          events: {
    
            url: url,
            failure: function() {
              document.getElementById('script-warning').style.display = 'block'
            },
          },
        
          loading: function(bool) {
            document.getElementById('loading').style.display =
              bool ? 'block' : 'none';
              document.getElementById('training').style.visibility =
              bool ? 'hidden' : 'visible';
          }
        });
    
        calendar.render();
        
        filterEvents.addEventListener('change', function() {
          calendar.refetchEvents();
        });
    
      });

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);

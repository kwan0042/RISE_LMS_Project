var td = new Date();
      var crntmnt = td.getMonth();
      var cy = td.getFullYear();
      var monthtw = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      var mandyr = document.getElementById("mandyr");
      showCalendar(crntmnt, cy);
  
      function pre() {
          cy = (crntmnt === 0) ? cy - 1 : cy;
          crntmnt = (crntmnt === 0) ? 11 : crntmnt - 1;
          showCalendar(crntmnt, cy);
      }
      function nex() {
          cy = (crntmnt === 11) ? cy + 1 : cy;
          crntmnt = (crntmnt + 1) % 12;
          showCalendar(crntmnt, cy);
      }
  
      function showCalendar(month, year) {
          var first_day = (new Date(year, month)).getDay();
          var dysin_mnth = 32 - new Date(year, month, 32).getDate();
  
          first_day = (first_day === 0) ? 6 : first_day - 1; // Adjust first_day to consider week start as Monday
  
          var prev_month_last_day = new Date(year, month, 0).getDate() - first_day + 1;
          var next_month_day = 1;
  
          var table_body = document.getElementById("calendar-table-body"); // body of the calendar
          table_body.innerHTML = "";
          mandyr.innerHTML = monthtw[month] + " " + year;
          var date_for_calendar = 1;
          
          var week_row_count = Math.ceil((first_day + dysin_mnth) / 7);
  
          for (var i = 0; i < week_row_count; i++) {
              var calendar_rows = document.createElement("tr");
              for (var j = 0; j < 7; j++) {
                  var calendar_cell = document.createElement("td");
                  var calendar_cell_text;
  
                  if (i === 0 && j < first_day) {
                      calendar_cell_text = document.createTextNode(prev_month_last_day++);
                      calendar_cell.classList.add("previous-next-month");
                  } else if (date_for_calendar > dysin_mnth) {
                      calendar_cell_text = document.createTextNode(next_month_day++);
                      calendar_cell.classList.add("previous-next-month");
                  } else {
                      calendar_cell_text = document.createTextNode(date_for_calendar);
                      if (date_for_calendar === td.getDate() && year === td.getFullYear() && month === td.getMonth()) {
                        calendar_cell.classList.add("today-date");
                      }
                      date_for_calendar++;
                  }
                  calendar_cell.appendChild(calendar_cell_text);
                  calendar_rows.appendChild(calendar_cell);
              }
              table_body.appendChild(calendar_rows);
          }
      }
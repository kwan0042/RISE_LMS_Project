// current Day
date = new Date();
year = date.getFullYear();
day = date.getDate();
const weekdays = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

let monthname = month[date.getMonth()];
let weekday = weekdays[date.getDay()];
document.getElementById("current_date").innerHTML = weekday + ", " + monthname + " " + day + ", " + year;

$(".todo-checkbox").click(function(){
    var checkbox = $(this);
    let todoId = checkbox.attr("value");
    let checked = checkbox.is(":checked");

    var status;

    if (checked){
        status = "Complete";
    } else {
        status = "Incomplete";
    }

    // Disable the checkbox after it is clicked
    checkbox.prop('disabled', true);

    $.ajax({
        url: webServicePath + "/SetWorkbookStatus/" + todoId + "/" + status,
        type: "PUT",
        success: function(){
            console.log("Workbook status set successfully");
            checkbox.prop('disabled', false);
        },
        error: function(response){
            console.log("Error setting workbook status: " + response.statusText);
            // Revert the checkbox to its original state
            checkbox.prop('checked', !checked);
        }
    });
});
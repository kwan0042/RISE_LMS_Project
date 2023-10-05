var webServicePath = 'http://34.125.91.0:8080/RiseApp';
window.onscroll = function() {scrollFunction()};
var personalProfile = document.getElementById("personalProfile");
var portfolioOverlayContainer = document.getElementById("myModal");

var span = document.getElementsByClassName("close")[0];

personalProfile.addEventListener("click", function () {
portfolioOverlayContainer.style.display = "block";
});

span.addEventListener("click", function () {
portfolioOverlayContainer.style.display = "none";
});
  

function scrollFunction() {
    if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {
      document.getElementById("moblieHomeBtn").hidden = false;
    } else {
      document.getElementById("moblieHomeBtn").hidden = true;
    }
  }

  //calander btn switch
$(".calendar-btn").on("click", function () {

  $(".calendar-btn").removeClass("active");
  
  $(this).addClass("active");
  
});
// setInterval(() => {
//     //   alert("hello world");
//       $.ajax({
//           url: "includes/ajax.php",
//           type: "POST",
//           data: {"update-funding": true},
//           success: function (data){
//               return true;
//           },
//           error: function (error) {
//               return false;
//           }
//       })
//     }, 5000);

$(document).ready(function () {
    $('#nav-closer').on('click', function () {
      console.log("closed!");
	  $('#sidebar').toggleClass('active');
	  
	});
})
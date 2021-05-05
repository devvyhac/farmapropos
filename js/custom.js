$(document).ready(function () {

$('.minus').click(function () {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.change();
    return false;
});
$('.plus').click(function () {
    var $input = $(this).parent().find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
});
    
    
//   var prices = document.querySelectorAll(".pricing");
//   var forms = document.querySelectorAll(".pricing-form");
  
//   setInterval(() => {
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
//   }, 5000);

//   const getAjax2 = (data) => {
//     return $.ajax({
//       url: "includes/ajax.php",
//       type: "POST",
//       data: {"pricing": data.val()}
//     })
//   }

//   const getAjax = (data) => {
//     return $.ajax({
//       url: "includes/ajax.php",
//       type: "POST",
//       data: {"pricing": data.value}
//     })
//   }
  
// //   this is the section that deals with checking whether the visitor is logged in or registered with us below
  
  forms.forEach(form => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const ajaxCheck = () => {
        return $.ajax({
          url: "includes/isloggedin.php",
          type: "POST"
        })
      }
      
      let session = ajaxCheck();
      
      session.done(function(data, status, xhr) {
          data = JSON.parse(data)

          if(!data){
              let login = confirm("You must Login to Continue!, Do You Want to Login?")
              if (login) {
                e.target.submit()
                console.log("Submit Successful!")
              }
            // alert(data)
          } else if (data) {
            //   alert(data)
              e.target.submit();
          }
      })
      
      session.fail(function(xmlhttp, status, error){
          alert(xmlhttp)
          alert(status)
          alert(error)
      })
    })
  })
  
// //   this is the section that handles the functionalities of dynamically changing the pricing when selected in the dropdown

//   prices.forEach(element => {
//     let pack = getAjax(element)

//       // console.log($(this).parents().parentsUntil("form").children().find(".roi"))

//       pack.done(function (data, status, xmlhttp) {

//         data = JSON.parse(data);
        
//         element.parentElement.parentElement.parentElement.childNodes[3].innerHTML = (`<span>ROI:</span> ${data.roi}%`);
          
//         element.parentElement.parentElement.parentElement.childNodes[5].innerHTML = (`<span>Package:</span> ${data.package_name}`);

//       })

//       pack.fail(function(xmlhttp, status, error) {
//         alert(error)
//         alert(status)
//         alert(xmlhttp)
//       })

//       element.addEventListener("change", (e) => {
//         let pack = getAjax(element)

//         pack.done(function (data, status, xmlhttp) {

//         data = JSON.parse(data);

//         console.log($(this).parentsUntil("form").children(".roi"))

//         e.target.parentElement.parentElement.parentElement.childNodes[3].innerHTML = (`<span>ROI:</span> ${data.roi}%`);
          
//         e.target.parentElement.parentElement.parentElement.childNodes[5].innerHTML = (`<span>Package:</span> ${data.package_name}`);

//         })

//         pack.fail(function(xmlhttp, status, error) {
//         alert(error)
//         alert(status)
//         alert(xmlhttp)
//         })

//       })
//   });

});
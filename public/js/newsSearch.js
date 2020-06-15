
// document.querySelector('.search-form').addEventListener('keyup', function (e) {
//     e.preventDefault();
//     const inputValue = document.querySelector('#search-value').value;
//
//         var xmlhttp=new XMLHttpRequest();
//         xmlhttp.onreadystatechange=function() {
//             if (this.readyState===4 && this.status===200) {
//
//                     document.getElementById("search-result").innerHTML=this.responseText;
//                     document.getElementById("search-result").style.border="2px solid blue";
//                 document.getElementById("search-result").style.backgroundColor = "white";
//
//             }
//         }
//         xmlhttp.open("GET","/search?keyword="+inputValue,true);
//         xmlhttp.send();
// });


$('.search-form').keyup(function (e) {
    e.preventDefault();
    const inputValue = document.querySelector('#search-value').value;

    $.ajax({
        url: "/search?keyword="+inputValue,
        method: 'GET',
        dataType: 'json',
        success: function (resp) {
            if (!resp.error) {
                let output = '';
                resp.news.forEach(function (novelty) {
                    output += `<a href="/news/${novelty.id}"><h3 class="post-catagory">${novelty.name}</h3>
                                  <hr>
                                  ${novelty.description}
                                 
                                   <hr>
                                   ${novelty.author}
                               
                              </a>`
                })

                document.getElementById("search-result").innerHTML = output;
                document.getElementById("search-result").style.border = "2px solid blue";
                document.getElementById("search-result").style.backgroundColor = "white";

            } else {
                alert('Something wrong');
            }
        }
    });
})

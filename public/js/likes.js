// const likes = document.querySelectorAll('.post-like');
//
// for (let i = 0; i < likes.length; i++) {
//     likes[i].addEventListener('click', addLike);
// }

$('.post-like').click(function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var that = $(this);

    $.ajax({
        url: href,
        method: 'POST',
        dataType: 'json',
        success: function (resp) {
            if (!resp.error) {
                that.find('span').text(resp.count);
            } else {
                alert('Something wrong');
            }
        }
    });
})

// function addLike(e) {
//     e.preventDefault();
//
//     const xhr = new XMLHttpRequest();
//     xhr.open('POST', this.getAttribute('href'), true);
//
//     xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     var that = this;
//     xhr.onreadystatechange = function() {
//         if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
//             that.querySelector('span').innerText = xhr.response;
//         }
//     }
//     xhr.send();
// }


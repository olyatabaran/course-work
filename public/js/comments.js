$('#comment-add-form').submit(function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {
            content: document.getElementById('message').value
        },
        success: function (resp) {
            loadComments();
        }
    });
})

const commentList = document.querySelector('#comment-list');

function loadComments() {
    $.ajax({
        url: '/ajax-comments/' + commentList.getAttribute('data-news-id'),
        method: 'GET',
        dataType: 'json',
        success: function (resp) {
            let output = `<h5 class="title">${resp.count} Comments</h5><ol>`;
            resp.comments.forEach(function (comment) {
                output += `

            <li class="single_comment_area">
                    <!-- Comment Content -->
                <div class="comment-content d-flex">
                    <!-- Comment Author -->
                    <div class="comment-author">
                        <img src="/img/users/${comment.image}" alt="author">
                    </div>
                    <!-- Comment Meta -->
                    <div class="comment-meta">
                        <a href="#" class="post-author">${comment.name}</a>
                        <a href="#" class="post-date">April 15, 2018</a>
                        <p>${comment.content}</p>
                    </div>
                </div>
            </li>`
            })
            commentList.innerHTML = output + '</ol>';
        }

    })
}

loadComments();




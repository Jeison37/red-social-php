const likes_btn = document.querySelectorAll(".like");
const comments_btn = document.querySelectorAll(".comment");
const edits_btn = document.querySelectorAll(".edit");
const dialog = document.getElementById("edit_window");
const id_edit = document.getElementById("id");
const edit_content = document.getElementById("content");

likes_btn.forEach((like) => {
  like.addEventListener("click", (e) => {
    const id_post = like.dataset.post;

    fetch(`http://localhost/red-social/querys/setLike.php?id_post=${id_post}`)
      .then((res) => res.json())
      .then((res) => res);
    like.querySelector(".current-n").classList.toggle("hidden");
    like.querySelector(".dif-n").classList.toggle("hidden");
    like.classList.toggle("liked");
  });
});

comments_btn.forEach((comment) => {
  comment.addEventListener("click", (e) => {
    const id_post = comment.dataset.post;

    window.location.href =
      "http://localhost/red-social/post.php?post=" + id_post;
  });
});

edits_btn.forEach((edit) => {
  edit.addEventListener("click", (e) => {
    e.preventDefault();
    const id_data = edit.dataset.edit;
    dialog.showModal();
    id_edit.value = id_data;
    edit_content.value =
      edit.parentElement.querySelector(".contenido").textContent;
  });
});

const searchRatingForm = document.getElementById("searchRatingForm")
if(searchRatingForm){
  searchRatingForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const rating = document.querySelector('input[name="rating"]:checked').value;
    const comment = document.getElementById("comment").value;

    // Here you can handle the submission, for now just logging the values
    console.log("Rating: ", rating);
    console.log("Comment: ", comment);

    // You can send this data to the server using AJAX or other methods
    // For simplicity, I'm just logging it here
  });
}

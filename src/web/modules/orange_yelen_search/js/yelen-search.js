/**
 *
 * Enable Boosted Tooltip
 */

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new boosted.Tooltip(tooltipTriggerEl))

/**
 *
 * End Tooltip Enable
 */


const searchRatingForm = document.getElementById("searchRatingForm")
if(searchRatingForm){
  searchRatingForm.addEventListener("submit", function (event) {
    event.preventDefault();
    console.log('xxx searchRatingForm submission xxx')
    const rating = document.querySelector('input[name="rating"]:checked').value;
    const comment = document.getElementById("comment").value;

    console.log("Rating: ", rating);
    console.log("Comment: ", comment);
  });
}

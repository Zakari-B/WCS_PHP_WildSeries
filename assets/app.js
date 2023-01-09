/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";
import "./styles/navbar.scss";

// start the Stimulus application
import "./bootstrap";

//Bootstrap
require("bootstrap");
import "bootstrap-icons/font/bootstrap-icons.css";

document.getElementById("watchlist").addEventListener("click", addToWatchlist);

let watchlistPending = false;

function addToWatchlist(e) {
  e.preventDefault();
  // If a request is still pending, do not fetch again.
  if (watchlistPending) {
    exit();
  }
  // Set the "request pending" boolean to true.
  watchlistPending = true;

  // Get the link object you click in the DOM
  const watchlistLink = e.currentTarget;
  const link = watchlistLink.href;
  // Send an HTTP request with fetch to the URI defined in the href
  try {
    fetch(link)
      // Extract the JSON from the response
      .then((res) => res.json())
      // Then update the icon
      .then((data) => {
        const watchlistIcon = watchlistLink.firstElementChild;
        if (data.isInWatchlist) {
          watchlistIcon.classList.remove("bi-heart"); // Remove the .bi-heart (empty heart) from classes in <i> element
          watchlistIcon.classList.add("bi-heart-fill"); // Add the .bi-heart-fill (full heart) from classes in <i> element
          watchlistPending = false; // Set the boolean back to false to indicate the promise has been resolved
        } else {
          watchlistIcon.classList.remove("bi-heart-fill"); // Remove the .bi-heart-fill (full heart) from classes in <i> element
          watchlistIcon.classList.add("bi-heart"); // Add the .bi-heart (empty heart) from classes in <i> element
          watchlistPending = false; // Set the boolean back to false to indicate the promise has been resolved
        }
      });
  } catch (err) {
    console.error(err);
  }
}

const { default: axios } = require("axios");

const change_email = document.getElementById("change_email")
const test = document.getElementById('send');
if (change_email) {
  change_email.addEventListener("click", (event) => {
    event.preventDefault();
    document.getElementById("sendmail").classList.add("hide");
    document.getElementById("change_email_form").classList.remove("hide");
    console.log("hello");
  });
}
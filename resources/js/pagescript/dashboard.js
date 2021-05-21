document.getElementById("change_email").addEventListener("click", (event) => {
  event.preventDefault();
  document.getElementById("sendmail").classList.add("hide");
  document.getElementById("change_email_form").classList.remove("hide");
  console.log("hello");
});

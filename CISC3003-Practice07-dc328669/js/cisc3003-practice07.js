"use strict";

document.addEventListener("DOMContentLoaded", function () {

  var hilightables = document.querySelectorAll(".hilightable");

  for (var i = 0; i < hilightables.length; i++) {
    hilightables[i].addEventListener("focus", function () {
      this.classList.add("highlight");
    });

    hilightables[i].addEventListener("blur", function () {
      this.classList.remove("highlight");
    });
  }

  var requiredFields = document.querySelectorAll(".required");

  for (var j = 0; j < requiredFields.length; j++) {
    requiredFields[j].addEventListener("input", function () {
      if (this.value.trim() !== "") {
        this.classList.remove("error");
      }
    });
  }

  var form = document.getElementById("artworkForm");

  form.addEventListener("submit", function (e) {
    var hasError = false;

    for (var k = 0; k < requiredFields.length; k++) {
      if (requiredFields[k].value.trim() === "") {
        requiredFields[k].classList.add("error");
        hasError = true;
      } else {
        requiredFields[k].classList.remove("error");
      }
    }

    if (hasError) {
      e.preventDefault();
    }
  });
});

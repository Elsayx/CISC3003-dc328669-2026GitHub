(function () {
  function outputCartRows() {
    var i;
    var rowTotal;

    for (i = 0; i < cartImageFiles.length; i += 1) {
      rowTotal = calculateTotal(cartQuantities[i], cartPrices[i]);
      outputCartRow(
        cartImageFiles[i],
        cartTitles[i],
        cartQuantities[i],
        cartPrices[i],
        rowTotal
      );
    }
  }

  function outputCartSummary() {
    var subtotal = calculateSubtotal(cartQuantities, cartPrices);
    var tax = calculateTax(subtotal);
    var shipping = calculateShipping(subtotal);
    var grandTotal = subtotal + tax + shipping;

    outputSummaryRow("Subtotal", subtotal, false);
    outputSummaryRow("Tax", tax, false);
    outputSummaryRow("Shipping", shipping, false);
    outputSummaryRow("Grand Total", grandTotal, true);
  }

  var currentScript = document.currentScript;
  var section = currentScript ? currentScript.getAttribute("data-section") : "";

  if (section === "summary") {
    outputCartSummary();
  } else {
    outputCartRows();
  }
})();

function calculateTotal(quantity, price) {
  return quantity * price;
}

function outputCartRow(file, title, quantity, price, total) {
  document.write('<tr class="cart-row">');
  document.write(
    '<td class="product-image-cell"><img src="images/' +
      file +
      '" alt="' +
      title +
      '"></td>'
  );
  document.write('<td class="product-title-cell">' + title + "</td>");
  document.write('<td class="quantity-cell">' + quantity + "</td>");
  document.write('<td class="price-cell">$' + price.toFixed(2) + "</td>");
  document.write('<td class="amount-cell">$' + total.toFixed(2) + "</td>");
  document.write("</tr>");
}

function calculateSubtotal(quantities, prices) {
  var subtotal = 0;
  var i;

  for (i = 0; i < quantities.length; i += 1) {
    subtotal += calculateTotal(quantities[i], prices[i]);
  }

  return subtotal;
}

function calculateTax(subtotal) {
  return subtotal * 0.1;
}

function calculateShipping(subtotal) {
  if (subtotal > 1000) {
    return 0;
  }

  return 40;
}

function outputSummaryRow(label, amount, isGrandTotal) {
  var rowClass = "summary-row";

  if (isGrandTotal) {
    rowClass += " grand-total-row";
  }

  document.write('<tr class="' + rowClass + '">');
  document.write('<td colspan="4" class="summary-label">' + label + "</td>");
  document.write('<td class="summary-value">$' + amount.toFixed(2) + "</td>");
  document.write("</tr>");
}

const stripe = Stripe(
  "pk_test_51K55t7CL91AZNNg1na0cSCLzyHswcUfT6Mk6Uo5FbZPhaRZ1xpErdkJHi6bYBBWsBxYdIy6CKqHvMx6rtETl5zNS00ideL9q6x"
);

var elements = stripe.elements();
// Custom styling can be passed to options when creating an Element.
var style = {
  base: {
    // Add your base input styles here. For example:
    fontSize: "16px",
    color: "#32325d",
  },
};

const apparence = {
  theme: "night",
  labels: "floating",
};
// Create an instance of the card Element.
var card = elements.create("card", apparence);
// Add an instance of the card Element into the `card-element` <div>.
card.mount("#card-element");
card.addEventListener("change", function (event) {
  var displayError = document.getElementById("card-errors");
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = "";
  }
});
// Create a token or display an error when the form is submitted.
var form = document.getElementById("payment-form");
form.addEventListener("submit", function (event) {
  event.preventDefault();
  stripe.createToken(card).then(function (result) {
    if (result.error) {
      // Inform the customer that there was an error.
      var errorElement = document.getElementById("card-errors");
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById("payment-form");
  var hiddenInput = document.createElement("input");
  hiddenInput.setAttribute("type", "hidden");
  hiddenInput.setAttribute("name", "stripeToken");
  hiddenInput.setAttribute("value", token.id);
  form.appendChild(hiddenInput);
  // Submit the form
  form.submit();
}

window.onload = function() {
  let cart = localStorage.getItem('cart');// Load cart from localStorage
  cart = cart ? JSON.parse(cart) : {};  // parse back to JavaScript object
  updateCartDisplay(cart); // Update the cart display when the page loads
};

// Update the cart display
function updateCartDisplay(cart) {
  const cartList = document.getElementById('cart');
  cartList.innerHTML = ''; // removes duplicate enteries

  let total = 0; // start with 0

  // grab cart items and display them
  for (let product in cart) { //for each item 
    const totalPrice = cart[product].quantity * cart[product].itemPrice; //change price based on quantity
    total += totalPrice; // Add total of all items to overall total

    // add products in a list
    const listItem = document.createElement('li');

    // add image
    const productImage = document.createElement('img');
    productImage.src = cart[product].image;
    productImage.alt = product;
    productImage.classList.add('product-imageCheckOut'); //allows css for all products
    listItem.appendChild(productImage); //add to parent
    
    //add name
    const productName = document.createElement('span');
    productName.textContent = product; // product as text
    listItem.appendChild(productName); //add to parent

    // add quantity 
    const productQuantity = document.createElement('span');
    productQuantity.textContent = `Quantity: ${cart[product].quantity}`; // add text to introduce quanitity 
    listItem.appendChild(productQuantity); //add to parent

    // add size 
    const productSize = document.createElement('span');
    productSize.textContent = `Item Option: ${cart[product].size}`; // add text to introduce size option 
    listItem.appendChild(productSize);//add to parent

    // add total 
    const productTotalPrice = document.createElement('span');
    productTotalPrice.textContent = `Total Price: $${totalPrice.toFixed(2)}`; // to fixed make 2 decimal place
    listItem.appendChild(productTotalPrice); // add to parent

    // remove button displayed in list
    const removeButton = document.createElement('button');
    removeButton.textContent = "Remove";
    removeButton.classList.add('remove-button');
    removeButton.onclick = function() {
      removeItemFromCart(product); // Remove the item from the cart
    };
    listItem.appendChild(removeButton);// add to parent
    cartList.appendChild(listItem);// add to parent
  }

  // create grand total
  const totalElement = document.createElement('li'); //add as list item
  totalElement.innerHTML = `Grand Total: $${total.toFixed(2)}`; //display total
  totalElement.classList.add('grand-total');//css style

  cartList.appendChild(totalElement);//add to parent
}

// Remove an item from the cart
function removeItemFromCart(product) {
  let cart = localStorage.getItem('cart');
  cart = cart ? JSON.parse(cart) : {};  

  if (cart[product].quantity > 1) {
    // If quantity is more than 1, - by 1
    cart[product].quantity -= 1;
  } else {
    // quantity is 1 remove item 
    delete cart[product];
  }

  // Save the updated cart to Local Storage
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartDisplay(cart); // Update the cart display
}


 // Fetch user login status
fetch('user_wiki.php')
    .then(res => res.json())
    .then(data => {
        const userLoggedIn = data.userLoggedIn; 
        const productHelp = document.querySelector('.wiki');
        if (userLoggedIn) {
            // If user is logged in
            if (!productHelp) {
                const newProductHelp = document.createElement('div');
                newProductHelp.classList.add('wiki');
                newProductHelp.textContent = 'Cart Wiki';
                newProductHelp.onclick = function () {
                    window.location.href = 'cart-help.html';
                };
                document.querySelector('.content-container').appendChild(newProductHelp);
            }
        } else {
            // If user is not logged in
            console.log("User is not logged in.");
            if (productHelp) {
                productHelp.style.display = 'none'; // Hide button
                }
            }
        })
        .catch(error => console.error('Error fetching user wiki:', error));


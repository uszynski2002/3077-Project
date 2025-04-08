// When clicking the add button
document.querySelector(".add").addEventListener("click", function() {
    // duplicate first <p> in form 
    var firstItem = document.querySelector("form > p");
    var newItem = firstItem.cloneNode(true);
    
    // add new item before the add button <p>
    var form = document.querySelector("form");
    form.insertBefore(newItem, form.lastElementChild);

    // remove function for new item
    newItem.querySelector(".remove").addEventListener("click", function() {
        newItem.remove();
    });
});

// remove function for all remove buttons
document.querySelectorAll(".remove").forEach(function(button) {
    button.addEventListener("click", function() {
        this.parentElement.remove();
    });
});

// submit form and log the collected items
document.querySelector("#productRequest").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the form from actually submitting and refreshing the page
    
    // get all input fields in the form
    var inputs = document.querySelectorAll("form input[name='product']");
    var products = [];

    // collect the values
    inputs.forEach(function(input) {
        products.push(input.value);  // add each product 
    });

    // Log the collected items
    console.log("Items Requested:", products);

    document.querySelector("#productRequest").reset();

    var submitDiv = document.querySelector("#subMessage");
    submitDiv.textContent = "Thanks for your Feedback :) ";
    submitDiv.style.display = "block";  // display message after cleared
});
fetch('user_wiki.php')
    .then(res => res.json())
    .then(data => {
        const userLoggedIn = data.userLoggedIn; // if user is logged in

        // Locate existing button 
        const productHelp = document.querySelector('.wiki');

        if (userLoggedIn) {
            // If user is logged in, create button
            if (!productHelp) {
                const newProductHelp = document.createElement('div');
                newProductHelp.classList.add('wiki');
                newProductHelp.textContent = 'Form Wiki';
                newProductHelp.onclick = function () {
                    window.location.href = 'requestForm-help.html';
                };
                document.querySelector('.content-container').appendChild(newProductHelp);
            }
        } else {
            if (productHelp) {
                productHelp.style.display = 'none'; // Hide button 
            }
        }
    })
    .catch(error => console.error('Error fetching user wiki:', error));



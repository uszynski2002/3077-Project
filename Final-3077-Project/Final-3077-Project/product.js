window.onload = function () {
    const productName = document.body.getAttribute('data-product'); // Get product name from HTML product page
    console.log("Changed Data-Attribute:", productName, "Please wait for it to update if not"); // Log the value

    // Fetch product data from PHP API
    fetch(`product.php?name=${encodeURIComponent(productName)}`)
        .then(response => response.json())
        .then(productData => {

            // Set default image from the first size's image
            const defaultImage = productData.sizes[0].image;

            // Display product info
            document.querySelector('h1').textContent = productData.name;
            document.querySelector('.product-image').src = defaultImage;
            document.querySelector('.product-image').alt = productData.sizes[0].imageAlt;
            document.querySelector('h3').textContent = productData.description;

            const sizeOptionsContainer = document.getElementById('size-options');
            sizeOptionsContainer.innerHTML = ""; // Clear previous buttons

            // Create size buttons
            productData.sizes.forEach((sizeOption) => {
                const sizeButton = document.createElement('button');
                sizeButton.textContent = `${sizeOption.size} $${sizeOption.price}`;
                sizeButton.onclick = function () {
                    changeImage(sizeOption);  // Update image based on size
                    addToBag(productData, sizeOption.size, sizeOption.price, sizeOption.image);
                };
                sizeOptionsContainer.appendChild(sizeButton);
            });

            document.title = `${productData.name}`;

            // Meta data setup
            const metaKeywords = document.querySelector('meta[name="keywords"]');
            if (metaKeywords) {
                metaKeywords.setAttribute('content', `${productData.name}, productpage, buy,`);
            }
            const metaDescription = document.querySelector('meta[name="description"]');
            if (metaDescription) {
                metaDescription.setAttribute('content', productData.name);
            }

            // Function to change image based on selected size
            function changeImage(sizeOption) {
                const productImage = document.querySelector('.product-image');
                productImage.src = sizeOption.image;
                productImage.alt = sizeOption.imageAlt;
            }

        })
        .catch(error => console.error('Error fetching product:', error));
};

// Add product to the cart
function addToBag(productData, productSize, productCost, selectedImage) {
    let cart = localStorage.getItem('cart');
    cart = cart ? JSON.parse(cart) : {};

    const productKey = `${productData.name} ${productSize}`;

    if (cart[productKey]) {
        cart[productKey].quantity += 1;
    } else {
        cart[productKey] = {
            quantity: 1,
            size: productSize,
            itemPrice: productCost,
            image: selectedImage
        };
    }

    localStorage.setItem('cart', JSON.stringify(cart));
}

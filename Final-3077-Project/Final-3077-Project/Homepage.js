const cacheKey = 'productData'; //cache infor for quick load 
const cacheExpiryKey = 'productDataExpiry';
const now = Date.now();
const cacheExpiry = 5 * 60 * 1000; // 5 minutes of caching 
const contentContainer = document.querySelector('.content-container');

//  display products
function displayProducts(products) {
    products.forEach(product => {
        const column = document.createElement('div');
        column.classList.add('column');

        const img = document.createElement('img'); //images
        img.src = product.sizes[0].image;
        img.alt = product.sizes[0].imageAlt; //alt text 
        column.appendChild(img);

        const title = document.createElement('h3'); //title
        title.textContent = product.name;
        column.appendChild(title);

        const button = document.createElement('button'); //product page button 
        button.textContent = 'View Product';
        button.onclick = () => window.location.href = product.productPageURL;
        column.appendChild(button);

        contentContainer.appendChild(column);
    });

    // wiki for logged in users , lazy load for time 
    setTimeout(() => {
        fetch('user_wiki.php')
            .then(res => res.json())
            .then(data => {
                if (data.userLoggedIn) {
                    const videoHelp = document.createElement('div');
                    videoHelp.classList.add('wiki');
                    videoHelp.textContent = 'Video Wiki';
                    videoHelp.onclick = () => window.location.href = 'video-help.html';
                    contentContainer.appendChild(videoHelp);
                }
            })
            .catch(err => console.error('Wiki load error:', err));
    }, 200); // delay
}

// load from cache 
const cached = localStorage.getItem(cacheKey);
const expiry = localStorage.getItem(cacheExpiryKey);

if (cached && expiry && now < parseInt(expiry)) {
    displayProducts(JSON.parse(cached));
} else { //if not 
    fetch('homepage.php')
        .then(res => res.json())
        .then(data => {
            if (data.products) {
                localStorage.setItem(cacheKey, JSON.stringify(data.products));
                localStorage.setItem(cacheExpiryKey, (now + cacheExpiry).toString());
                displayProducts(data.products);
            } else {
                console.error('Failed in loading  product:', data.error);
            }
        })
        .catch(err => console.error('Product fetch error:', err));
}

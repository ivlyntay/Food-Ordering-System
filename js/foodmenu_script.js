var modal = document.getElementById("item-modal");
var itemModal = document.getElementById("item-modal");
var categoryButtons = document.querySelectorAll(".category-button");
var menuItemsContainer = document.getElementById("menu-items");
var priceSortSelect = document.getElementById("price-sort");

// Function to filter menu items by category
function filterMenuItems(category) {
    var menuItems = document.querySelectorAll(".menu-item");
    menuItems.forEach(item => {
        if (category === "all" || item.dataset.category === category) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}

// Event listeners for category buttons
categoryButtons.forEach(button => {
    button.addEventListener("click", () => {
        var category = button.getAttribute("data-category");
        // Remove active class from all buttons
        categoryButtons.forEach(btn => btn.classList.remove("active"));
        // Add active class to the clicked button
        button.classList.add("active");
        filterMenuItems(category);
    });
});

if (itemModal) {
    itemModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget

        const menuItemId = button.getAttribute('data-item-id')

        var menuItem = document.getElementById(`menu-item-${menuItemId}`);
        document.getElementById("modal-item-name").textContent = menuItem.getElementsByClassName('item-name')[0].innerHTML;
        document.getElementById("modal-item-price").textContent = `RM ${menuItem.getAttribute('data-price')}`;
        document.getElementById("modal-item-description").textContent = menuItem.getElementsByClassName('item-description')[0].innerHTML;
        document.getElementById("modal-item-image").src = menuItem.getElementsByClassName('item-image')[0].getAttribute('src');

        // Set initial quantity value
        const quantityInput = document.getElementById("quantity");
        quantityInput.value = "1";

        // Update add to cart button action
        const addToCartModalBtn = document.getElementById("confirm-modal");
        addToCartModalBtn.onclick = () => {
           addToCart(menuItemId, quantityInput.value);
        };
    })
  }

function addToCart(itemId, quantity) {

    // Prepare data to send
    const data = new FormData();
    data.append('itemId', itemId);
    data.append('quantity', quantity);

    // Create AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_item.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Item saved successfully!');
        } else {
            alert('Error saving item.');
        }
    };

    // Send data
    xhr.send(data);
}

// Event listeners for quantity buttons
const decreaseQuantityBtn = document.getElementById("decrease-quantity");
const increaseQuantityBtn = document.getElementById("increase-quantity");

decreaseQuantityBtn.addEventListener("click", () => {
    const quantityInput = document.getElementById("quantity");
    if (quantityInput.value > 1) {
        quantityInput.value--;
    }
});

increaseQuantityBtn.addEventListener("click", () => {
    const quantityInput = document.getElementById("quantity");
    quantityInput.value++;
});

// Event listener for sorting by price
priceSortSelect.addEventListener("change", () => {
    const sortOrder = priceSortSelect.value;
    sortMenuItemsByPrice(sortOrder);
});


// Function to sort menu items by price
function sortMenuItemsByPrice(order) {
    var menuItemsArray = Array.from(document.querySelectorAll(".menu-item"));
    
    menuItemsArray.sort((a, b) => {
        var priceA = parseFloat(a.querySelector("p").textContent.replace("RM ", ""));
        var priceB = parseFloat(b.querySelector("p").textContent.replace("RM ", ""));
        return order === "low-to-high" ? priceA - priceB : priceB - priceA;
    });
    
    menuItemsContainer.innerHTML = "";
    menuItemsArray.forEach(item => {
        menuItemsContainer.appendChild(item);
    });
    console.log("Menu sorted in order:", order);
}

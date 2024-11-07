document.addEventListener("DOMContentLoaded", () => {
  const loginBtn = document.getElementById("login-btn");
  const cartBtn = document.getElementById("cart-btn");
  const loginModal = document.getElementById("login-modal");
  const cartModal = document.getElementById("cart-modal");
  const closeButtons = document.querySelectorAll(".close");

  loginBtn.addEventListener("click", () => {
      loginModal.style.display = "block";
  });

  cartBtn.addEventListener("click", () => {
      cartModal.style.display = "block";
  });

  closeButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
          loginModal.style.display = "none";
          cartModal.style.display = "none";
      });
  });

  window.onclick = (event) => {
      if (event.target === loginModal) {
          loginModal.style.display = "none";
      }
      if (event.target === cartModal) {
          cartModal.style.display = "none";
      }
  };
});

let cart = [];  // Initialize an empty cart

// Function to add items to the cart
function addToCart(productName, productPrice) {
    // Check if the item is already in the cart
    const item = cart.find(i => i.name === productName);
    if (item) {
        item.quantity += 1;
    } else {
        cart.push({ name: productName, price: productPrice, quantity: 1 });
    }

    updateCartCount();
    updateCartTotal();
}

// Function to update the cart count in the header
function updateCartCount() {
    const cartCount = document.getElementById("cart-count");
    const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = itemCount;
}

// Function to update the cart total
function updateCartTotal() {
    const cartTotal = document.getElementById("cart-total");
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    cartTotal.textContent = total.toFixed(2);
}

// Function to display items in the cart modal
function displayCartItems() {
    const cartItems = document.getElementById("cart-items");
    cartItems.innerHTML = "";  // Clear the cart items list
    cart.forEach(item => {
        const li = document.createElement("li");
        li.textContent = `${item.name} - $${item.price} x ${item.quantity}`;
        cartItems.appendChild(li);
    });
    updateCartTotal();
}

// Show or hide the cart modal
const cartBtn = document.getElementById("cart-btn");
const cartModal = document.getElementById("cart-modal");
const closeModal = document.querySelector(".close");

cartBtn.addEventListener("click", () => {
    displayCartItems();
    cartModal.style.display = "block";
});

closeModal.addEventListener("click", () => {
    cartModal.style.display = "none";
});

window.onclick = (event) => {
    if (event.target === cartModal) {
        cartModal.style.display = "none";
    }
};

// Clear cart function
function clearCart() {
    cart = [];
    updateCartCount();
    displayCartItems();
}

let slideIndex = 0;
showSlides();

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  slides[slideIndex-1].style.display = "block";
  setTimeout(showSlides, 6000); // Change image every 2 seconds
}
  
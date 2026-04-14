const customizableProducts = {
  "Mini Cornet Gourmand": ["Orange vif", "Rouge cerise", "Rose pastel", "Rose framboise", "Blanc vanille", "Jaune citron"],
  "Porte-clé Mini Méduse": ["Violet pastel", "Rose clair", "Bleu ciel", "Jaune moutarde", "Orange corail", "Beige clair", "Vert menthe", "Lavande claire", "Rose poudré", "Vert anis", "Blanc cassé", "Violet foncé"],
  "Bracelet Dentelle Bohème": ["Beige", "Rose ancien", "Gris violacé"],
  "Bracelet Perles de Lune": ["Blanc", "Rose claire", "Rose foncé"],
  "Pelotes de laine multicolores": ["Violet pastel", "Rose clair", "Bleu", "Jaune moutarde", "Orange corail", "Beige clair", "Vert menthe", "Lavande claire", "Rose poudré", "Vert anis", "Blanc cassé", "Violet foncé"],
  "Gamme de pelotes de laine colorées": ["Violet pastel", "Rose clair", "Bleu", "jaune", "Jaune moutarde", "Orange corail", "Beige clair", "Vert menthe", "Lavande claire", "Rose poudré", "Vert anis", "Blanc cassé", "Violet foncé"],
  "Gamme de quatre pelotes de laine pastel": ["rosée", "grise", "verte", "blanche"],
  "Pelotes de laine": ["Violet pastel", "Rose clair", "Bleu", "jaune", "Jaune moutarde", "Orange corail", "Beige clair", "Vert menthe", "Lavande claire", "Rose poudré", "Vert anis", "Blanc cassé", "Violet foncé"],
  "Crochets à laine en acier colorés": ["Rouge", "Vert", "Bleu", "jaune"],
};

let cart = JSON.parse(localStorage.getItem("cart")) || [];

const cartContainer = document.getElementById("cart");
const cartCount = document.getElementById("cart-count");
const cartItems = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");

function toggleCart() {
  cartContainer.classList.toggle("show");
}

function saveCart() {
  localStorage.setItem("cart", JSON.stringify(cart));
}

function changeColor(index, newColor) {
  cart[index].color = newColor;
  saveCart();
}

function changeSize(index, newSize) {
  cart[index].size = newSize;
  saveCart();
}

function updateCartDisplay() {
  if (!cartItems || !cartTotal || !cartCount) return;

  cartItems.innerHTML = '';
  let total = 0;

  cart.forEach((item, index) => {
    const colorSelectHTML = customizableProducts[item.name]
      ? `<label class="color-label">Couleur : 
            <select onchange="changeColor(${index}, this.value)" class="color-select">
              ${customizableProducts[item.name]
                .map(color => `<option value="${color}" ${item.color === color ? "selected" : ""}>${color}</option>`)
                .join("")}
            </select>
         </label>` : "";

    const sizeSelectHTML = `
      <label class="size-label">Taille :
        <select onchange="changeSize(${index}, this.value)" class="size-select">
          ${["S", "M", "L", "XL", "XXL"]
            .map(size => `<option value="${size}" ${item.size === size ? "selected" : ""}>${size}</option>`)
            .join("")}
        </select>
      </label>`;

    const itemHTML = `
      <div class="cart-item fancy-shadow">
        <img src="${item.image}" alt="${item.name}">
        <div class="cart-item-details">
          <p class="item-name">${item.name}</p>
          <p class="item-price">${item.price.toFixed(2)} €</p>
          ${colorSelectHTML}
          ${sizeSelectHTML}
          <div class="cart-item-quantity">
            <button class="qty-btn" onclick="changeQuantity(${index}, -1)">-</button>
            <span class="qty">${item.quantity}</span>
            <button class="qty-btn" onclick="changeQuantity(${index}, 1)">+</button>
            <button class="remove-btn" onclick="removeItem(${index})">×</button>
          </div>
        </div>
      </div>`;
    
    cartItems.innerHTML += itemHTML;
    total += item.quantity * item.price;
  });

  cartTotal.textContent = `${total.toFixed(2)} €`;
  cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  saveCart();
}

function addToCart(name, price, image) {
  const existing = cart.find(item => item.name === name && item.size === "M"); // check par défaut sur "M"
  if (existing) {
    existing.quantity++;
  } else {
    const newItem = {
      name,
      price,
      image,
      quantity: 1,
      size: "M"
    };
    if (customizableProducts[name]) {
      newItem.color = customizableProducts[name][0];
    }
    cart.push(newItem);
  }

  updateCartDisplay();
}

function changeQuantity(index, change) {
  cart[index].quantity += change;
  if (cart[index].quantity <= 0) cart.splice(index, 1);
  updateCartDisplay();
}

function removeItem(index) {
  cart.splice(index, 1);
  updateCartDisplay();
}

// Initialisation
document.addEventListener("DOMContentLoaded", () => {
  updateCartDisplay();

  document.querySelectorAll(".buy-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const product = btn.closest(".product");
      const name = product.querySelector("strong").textContent;
      const price = parseFloat(product.querySelector(".price span").textContent.replace("€", ""));
      const image = product.querySelector("img").src;
      addToCart(name, price, image);
    });
  });
});

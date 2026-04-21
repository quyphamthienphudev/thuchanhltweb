const showModalBtn = document.getElementById('show-order-form-btn');
const closeModalBtn = document.getElementById('close-modal-btn');
const modal = document.getElementById('order-modal');
// Lấy các phần tử thông tin sản phẩm
const quantityInput = document.getElementById('quantity');
const productNameEl = document.getElementById('product-name');
const productPriceEl = document.getElementById('product-price');
// Lấy các phần tử trong modal để điền thông tin
const modalProductName = document.getElementById('modal-product-name');
const modalQuantity = document.getElementById('modal-quantity');
const modalPrice = document.getElementById('modal-price');
// Hàm mở Modal
function openModal() {
    // Lấy thông tin từ trang
    const name = productNameEl.innerText;
    const quantity = parseInt(quantityInput.value);
    if (isNaN(quantity) || quantity <= 0) quantity = 1;
    quantityInput.value = quantity;
    // Lấy giá và chuyển đổi thành số (loại bỏ 'đ' và '.')
    const pricePerUnitText = productPriceEl.innerText.replace(/[^0-9]/g, '');
    const pricePerUnit = parseFloat(pricePerUnitText);
    // Tính tổng tiền
    const totalPrice = pricePerUnit * quantity;
    // Định dạng tiền tệ kiểu Việt Nam
    const formatter = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    });
    // Điền thông tin vào modal
    modalProductName.innerText = name;
    modalQuantity.innerText = quantity;
    modalPrice.innerText = formatter.format(totalPrice);
    // Hiển thị modal
    modal.classList.add('active');
}
// Hàm đóng modal
function closeModal() {
    modal.classList.remove('active');
}
// Thêm sự kiện click
showModalBtn.addEventListener('click', openModal);
closeModalBtn.addEventListener('click', closeModal);
// Thêm sự kiện: Nếu click vào lớp phủ mờ (bên ngoài form) thì cũng đóng
modal.addEventListener('click', (event) => {
    if (event.target === modal) {
        closeModal();
    }
});
quantityInput.addEventListener('input', () => {
    let value = parseInt(quantityInput.value);
    if (isNaN(value) || value <= 0) {
        quantityInput.value = 1;
    }
});
document.getElementById("show-order-form-btn").onclick = function () {
    const qty = document.getElementById("quantity").value;
    document.getElementById("modal-quantity").innerText = qty;
    document.getElementById("modal-quantity-input").value = qty;
    const price = parseInt(document.getElementById("product-price").innerText.replace(/\./g, ''));
    document.getElementById("modal-price").innerText = (price * qty).toLocaleString("vi-VN") + " đ";
    openModal();
};
const successPopup = document.getElementById("success-popup");
if (successPopup) {
    document.getElementById("close-success-popup").addEventListener("click", () => {
        successPopup.style.display = "none";
    });
}
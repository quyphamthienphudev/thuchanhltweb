document.addEventListener("DOMContentLoaded", function () {
    // Modal thêm
    const addModal = document.getElementById("add-modal");
    const showAddModalBtn = document.getElementById("show-add-modal-btn");
    const closeAddModalBtn = document.getElementById("close-modal-btn");
    // Modal sửa
    const editModal = document.getElementById("edit-modal");
    const closeEditModalBtn = document.getElementById("close-edit-modal-btn");
    const editButtons = document.querySelectorAll(".btn-edit");
    // Form sửa
    const editAccountId = document.getElementById("edit-account-id");
    const editAccountName = document.getElementById("edit-account-name");
    const editAccountRole = document.getElementById("edit-account-role");
    const editAccountPassword = document.getElementById("edit-account-password");
    // Xử lý Modal thêm
    if (showAddModalBtn) {
        showAddModalBtn.addEventListener("click", function () {
            const addForm = addModal.querySelector("form");
            if (addForm) addForm.reset();
            addModal.style.display = "flex";
        });
    }
    if (closeAddModalBtn) {
        closeAddModalBtn.addEventListener("click", function () {
            addModal.style.display = "none";
        });
    }
    // Xử lý nút sửa
    if (closeEditModalBtn) {
        closeEditModalBtn.addEventListener("click", function () {
            editModal.style.display = "none";
        });
    }
    editButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const maNV = this.getAttribute("data-manv");
            const hoTen = this.getAttribute("data-hoten");
            const vaiTro = this.getAttribute("data-vaitro");
            editAccountId.value = maNV;
            editAccountName.value = hoTen;
            if (vaiTro === "Admin" || vaiTro === "NhanVien") {
                editAccountRole.value = vaiTro;
            } else {
                editAccountRole.value = "NhanVien";
            }
            editAccountPassword.value = "";
            editModal.style.display = "flex";
        });
    });
    // Xử lý đóng modal khi bấm ra ngoài
    window.addEventListener("click", function (event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    });
});
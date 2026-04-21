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
    const editCategoryId = document.getElementById("edit-category-id");
    const editCategoryName = document.getElementById("edit-category-name");
    const editCategoryDesc = document.getElementById("edit-category-desc");
    // Xử lý modal thêm
    if (showAddModalBtn) {
        showAddModalBtn.addEventListener("click", function () {
            addModal.classList.add('active');
        });
    }
    // Khi bấm nút X (đóng) trên modal thêm
    if (closeAddModalBtn) {
        closeAddModalBtn.addEventListener("click", function () {
            addModal.classList.remove('active');
        });
    }
    // Xử lý Modal sửa 
    // Khi bấm nút X (đóng) trên modal Sửa
    if (closeEditModalBtn) {
        closeEditModalBtn.addEventListener("click", function () {
            editModal.classList.remove('active');
        });
    }
    editButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const maDM = this.getAttribute("data-madm");
            const tenDM = this.getAttribute("data-tendm");
            const moTaDM = this.getAttribute("data-motadm");
            editCategoryId.value = maDM;
            editCategoryName.value = tenDM;
            editCategoryDesc.value = moTaDM;
            editModal.classList.add('active');
        });
    });
    // Xử lý đóng modal khi bấm ra ngoài
    window.addEventListener("click", function (event) {
        if (event.target == addModal) {
            addModal.classList.remove('active');
        }
        if (event.target == editModal) {
            editModal.classList.remove('active');
        }
    });
});
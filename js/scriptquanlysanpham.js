// Modal thêm
        const showAddModalBtn = document.getElementById('show-add-modal-btn');
        const closeAddModalBtn = document.getElementById('close-modal-btn');
        const addModal = document.getElementById('add-modal');
        const addForm = document.getElementById('add-product-form');
        function openAddModal() {
            if (addModal) {
                addForm.reset();
                addModal.classList.add('active');
            }
        }
        function closeAddModal() {
            if (addModal) {
                addModal.classList.remove('active');
            }
        }
        if (showAddModalBtn) {
            showAddModalBtn.addEventListener('click', openAddModal);
        }
        if (closeAddModalBtn) {
            closeAddModalBtn.addEventListener('click', closeAddModal);
        }
        if (addModal) {
            addModal.addEventListener('click', (event) => {
                if (event.target === addModal) {
                    closeAddModal();
                }
            });
        }
// Modal sửa
const editModal = document.getElementById('edit-modal');
const closeEditModalBtn = document.getElementById('close-edit-modal-btn');
const editButtons = document.querySelectorAll('.btn-edit');
function openEditModal() {
    editModal.classList.add('active');
}
function closeEditModal() {
    editModal.classList.remove('active');
}
closeEditModalBtn.addEventListener('click', closeEditModal);
editModal.addEventListener('click', (e) => {
    if (e.target === editModal) closeEditModal();
});
// Gán sự kiện cho nút sửa
editButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('edit-masp').value = btn.dataset.masp;
        document.getElementById('edit-tensp').value = btn.dataset.tensp;
        document.getElementById('edit-hangsx').value = btn.dataset.hangsx;
        document.getElementById('edit-giaban').value = btn.dataset.giaban;
        document.getElementById('edit-soluong').value = btn.dataset.soluong;
        document.getElementById('edit-mota').value = btn.dataset.mota;
        const selectDM = document.getElementById('edit-madm');
        selectDM.value = btn.dataset.madm;
        openEditModal();
    });
});
        // Nút xoá
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const row = button.closest('tr');
                const productName = row.cells[1].innerText;
                const deleteUrl = button.getAttribute('href');
                const isConfirmed = confirm(`Bạn có chắc chắn muốn xoá sản phẩm "${productName}" không?`);
                if (isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
// Kiểm tra số > 0 cho form thêm
addForm.addEventListener('submit', function (e) {
    let price = addForm.querySelector("input[name='giaban']").value;
    let qty   = addForm.querySelector("input[name='soluong']").value;
    if (price <= 0 || qty <= 0) {
        e.preventDefault();
        alert("Giá bán và số lượng tồn phải lớn hơn 0!");
    }
});
// Kiểm tra số > 0 cho form sửa
const editForm = document.getElementById('edit-product-form');
editForm.addEventListener('submit', function (e) {
    let price = document.getElementById("edit-giaban").value;
    let qty   = document.getElementById("edit-soluong").value;
    if (price <= 0 || qty <= 0) {
        e.preventDefault();
        alert("Giá bán và số lượng tồn phải lớn hơn 0!");
    }
});
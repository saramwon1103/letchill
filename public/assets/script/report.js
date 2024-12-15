document.addEventListener("DOMContentLoaded", function () {
    const resultTableBody = document.querySelector("#result tbody");
    const totalRevenueSpan = document.getElementById("total-revenue");

    // Hàm tải dữ liệu từ server
    function loadReportData(filters = {}) {
        fetch("/web_nghe_nhac/app/pages/admin/fetch_report.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filters),
        })
        .then(response => {
            // Kiểm tra xem response có phải là JSON hợp lệ không
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Response không hợp lệ: " + response.statusText);
            }
        })
        .then(data => {
            console.log("Server response:", data);
            // Xóa dữ liệu cũ
            resultTableBody.innerHTML = "";

            // Hiển thị dữ liệu mới
            data.data.forEach(row => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${row.package_name}</td>
                    <td>${row.time_period}</td>
                    <td>${row.subscribers}</td>
                    <td>${row.revenue.toLocaleString()} VND</td>
                `;
                resultTableBody.appendChild(tr);
            });

            // Cập nhật tổng doanh thu
            totalRevenueSpan.textContent = data.total_revenue.toLocaleString() + " VND";
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
    }

    // Gọi API ngay khi trang tải để lấy toàn bộ dữ liệu
    loadReportData();

    // Thêm sự kiện cho nút Tra cứu
    document.getElementById("search-btn").addEventListener("click", function () {
        const startMonth = document.getElementById("start-month").value;
        const endMonth = document.getElementById("end-month").value;
        const packageValue = document.getElementById("package").value;

        loadReportData({
            start_month: startMonth,
            end_month: endMonth,
            package: packageValue,
        });
    });
});


document.getElementById("export-btn").addEventListener("click", function () {
    const startMonth = document.getElementById("start-month").value;
    const endMonth = document.getElementById("end-month").value;
    const packageValue = document.getElementById("package").value;

    const queryParams = new URLSearchParams({
        start_month: startMonth,
        end_month: endMonth,
        package: packageValue,
    });

    // Tạo URL để tải file
    const exportUrl = `/web_nghe_nhac/app/pages/admin/export_excel.php?${queryParams.toString()}`;

    // Điều hướng đến URL để tải file
    window.location.href = exportUrl;
});


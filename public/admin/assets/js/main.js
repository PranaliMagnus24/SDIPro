document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
        const toggle = submenu.querySelector('.dropdown-toggle');

        // For desktop hover
        submenu.addEventListener('mouseenter', function () {
            const dropdown = submenu.querySelector('.dropdown-menu');
            if (dropdown) dropdown.classList.add('show');
        });

        submenu.addEventListener('mouseleave', function () {
            const dropdown = submenu.querySelector('.dropdown-menu');
            if (dropdown) dropdown.classList.remove('show');
        });

        // For mobile click
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdown = submenu.querySelector('.dropdown-menu');
            dropdown.classList.toggle('show');
        });
    });
});


////delete button
$(document).on('click', '.delete-confirm', function (e) {
    e.preventDefault();
    const url = $(this).attr('href');

    Swal.fire({
        title: 'Are you sure?',
        text: "This Data will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});

///////////Qurbani Hisse
// $(document).ready(function () {
//     let totalAmount = 0;

//     function calculateTotal() {
//         totalAmount = 0;

//         $(".rowClass").each(function () {
//             let isAqiqah = $(this).find(".aqiqah-check").is(":checked");
//             let selectedGender = $(this).find(".aqiqah-select").val();
//             let hissaAmount = 1500;
//             let hissaCount = 1;
//             let name = $(this).find(".name-input, input[readonly]").val().trim();

//             if (name !== "") {
//                 if (isAqiqah) {
//                     hissaCount = (selectedGender === "Male") ? 2 : 1;
//                 }

//                 $(this).find(".hissa-input").val(hissaCount);
//                 totalAmount += hissaCount * hissaAmount;
//             }
//         });

//         $("#txtamount").text(totalAmount.toFixed(2));
//     }

//     $("#addBtn").click(function () {
//         let newRow = `
//         <tr class="rowClass">
//             <td class="text-center">
//                 <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
//                 <input type="checkbox" class="aqiqah-check">
//             </td>
//             <td class="text-center">
//                 <input type="text" name="name[]" class="form-control name-input" placeholder="Name">
//             </td>
//             <td class="text-center">
//                 <select name="gender[]" class="form-select aqiqah-select" style="display:none;">
//                     <option value="">Select</option>
//                     <option value="Male">Male</option>
//                     <option value="Female">Female</option>
//                 </select>
//             </td>
//             <td class="text-center">
//                 <input type="number" name="hissa[]" class="form-control hissa-input" value="1" readonly>
//             </td>
//             <td class="text-center">
//                 <input type="hidden" name="huzur[]" value="0">
//                 <button class="btn btn-danger remove" type="button">Remove</button>
//             </td>
//         </tr>`;
//         $('#tbody').append(newRow);
//     });

//     ////////Paigambar Name Logic
//     $("#addBtnHuzur").click(function () {
//     if ($(".huzur-row").length === 0) {
//         let huzurRow = `
//         <tr class="rowClass huzur-row">
//            <td class="text-center">
//                                 <input type="hidden" name="aqiqah[]" value="">
//                             </td>
//                             <td class="text-center" style="width: 448px;">
//                                 <input type="text" name="name[]" class="form-control" value="HAZRAT MOHAMMAD SALLALLAHU ALAIHI WASALLAM" readonly>
//                             </td>
//                             <td class="text-center">
//                                 <select name="gender[]" class="form-select" style="display:none;">
//                                     <option value="">Select</option>
//                                 </select>
//                             </td>
//             <td class="text-center">
//                 <input type="number" name="hissa[]" class="form-control" value="1" readonly>
//             </td>
//             <td class="text-center">
//                 <input type="hidden" name="huzur[]" value="1">
//                 <button class="btn btn-danger remove" type="button">Remove</button>
//             </td>
//         </tr>`;
//         $('#tbody').prepend(huzurRow);
//         calculateTotal();
//     }
// });


//     $(document).on("input", ".name-input", calculateTotal);
//     $(document).on("change", ".aqiqah-select", calculateTotal);

//     $(document).on("change", ".aqiqah-check", function () {
//         let row = $(this).closest("tr");
//         let genderSelect = row.find(".aqiqah-select");
//         let hiddenAqiqahInput = row.find(".aqiqah-input");

//         if ($(this).is(":checked")) {
//             hiddenAqiqahInput.val("1");
//             genderSelect.show();
//         } else {
//             hiddenAqiqahInput.val("0");
//             genderSelect.hide().val("");
//         }
//         calculateTotal();
//     });

//     $(document).on("click", ".remove", function () {
//         if ($(".rowClass").length > 1) {
//             $(this).closest("tr").remove();
//         }
//         calculateTotal();
//     });

//     window.togglePaymentDetails = function (select) {
//         $("#razorpay-details").toggle(select.value === 'RazorPay');
//         $("#attachement").toggle(select.value === 'RazorPay');
//     };

//     togglePaymentDetails(document.getElementById('payment_method'));
//     calculateTotal();
// });



$(document).ready(function () {
    let totalAmount = 0;

    function calculateTotal() {
        totalAmount = 0;

        $(".rowClass").each(function () {
            let isAqiqah = $(this).find(".aqiqah-check").is(":checked");
            let selectedGender = $(this).find(".aqiqah-select").val();
            let hissaAmount = 1500;
            let hissaCount = 1;
            let name = $(this).find(".name-input, input[readonly]").val().trim();

            if (name !== "") {
                if (isAqiqah) {
                    hissaCount = (selectedGender === "Male") ? 2 : 1;
                }

                $(this).find(".hissa-input").val(hissaCount);
                totalAmount += hissaCount * hissaAmount;
            }
        });

        $("#txtamount").val(totalAmount.toFixed(2));
    }

    // Auto-suggest for contact name and mobile
    $('input[name="contact_name"], input[name="mobile"]').each(function () {
    $(this).autocomplete({
        source: function (request, response) {
            const fieldName = this.element.attr('name'); // 'contact_name' or 'mobile'

            $.ajax({
                url: autosuggestUrl,
                data: {
                    query: request.term,
                    field: fieldName
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $('input[name="contact_name"]').val(ui.item.contact_name);
            $('input[name="mobile"]').val(ui.item.mobile);
            $('select[name="payment_type"]').val(ui.item.payment_type);
            $('select[name="receipt_book"]').val(ui.item.receipt_book);

            // Clear existing rows before adding new ones
            $('#tbody').empty();

            if (ui.item.hisses) {
                ui.item.hisses.forEach(function (hisse) {
                    addHissaRow(hisse);
                });
            }

            calculateTotal();
            return false;
        }
    });
});

    // Function to add a new row for QurbaniHisse
    function addHissaRow(hisse) {
        const row = `
            <tr class="rowClass">
                <td class="text-center">
                    <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="${hisse.aqiqah}">
                    <input type="checkbox" class="aqiqah-check" ${hisse.aqiqah ? 'checked' : ''}>
                </td>
                <td class="text-center">
                    <input type="text" name="name[]" class="form-control name-input" value="${hisse.name}" placeholder="Name">
                </td>
                <td class="text-center">
                    <select name="gender[]" class="form-select aqiqah-select">
                        <option value="Male" ${hisse.gender === 'Male' ? 'selected' : ''}>Male</option>
                        <option value="Female" ${hisse.gender === 'Female' ? 'selected' : ''}>Female</option>
                    </select>
                </td>
                <td class="text-center">
                    <input type="number" name="hissa[]" class="form-control hissa-input" value="${hisse.hissa}" readonly>
                </td>
                <td class="text-center">
                    <input type="hidden" name="huzur[]" value="0">
                    <button class="btn btn-danger remove" type="button">Remove</button>
                </td>
            </tr>
        `;
        $('#tbody').append(row);
    }

    // Add new row button
    $("#addBtn").click(function () {
        let newRow = `
        <tr class="rowClass">
            <td class="text-center">
                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
                <input type="checkbox" class="aqiqah-check">
            </td>
            <td class="text-center">
                <input type="text" name="name[]" class="form-control name-input" placeholder="Name">
            </td>
            <td class="text-center">
                <select name="gender[]" class="form-select aqiqah-select" style="display:none;">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td class="text-center">
                <input type="number" name="hissa[]" class="form-control hissa-input" value="1" readonly>
            </td>
            <td class="text-center">
                <input type="hidden" name="huzur[]" value="0">
                <button class="btn btn-danger remove" type="button">Remove</button>
            </td>
        </tr>`;
        $('#tbody').append(newRow);
    });

    // Logic for adding Huzur row
    $("#addBtnHuzur").click(function () {
    if ($(".huzur-row").length === 0) {
        let huzurRow =
        `<tr class="rowClass huzur-row">
            <td class="text-center">
                <input type="hidden" name="aqiqah[]" value="">
            </td>
            <td class="text-center" style="width: 448px;">
                <input type="text" name="name[]" class="form-control" value="HAZRAT MOHAMMAD SALLALLAHU ALAIHI WASALLAM" readonly>
            </td>
            <td class="text-center">
                <select name="gender[]" class="form-select" style="display:none;">
                    <option value="">Select</option>
                </select>
            </td>
            <td class="text-center">
                <input type="number" name="hissa[]" class="form-control" value="1" readonly>
            </td>
            <td class="text-center">
                <input type="hidden" name="huzur[]" value="1">
                <button class="btn btn-danger remove" type="button">Remove</button>
            </td>
        </tr>`;
        $('#tbody').prepend(huzurRow);
        calculateTotal();
    }
});


    // Event listeners for input changes
    $(document).on("input", ".name-input", calculateTotal);
    $(document).on("change", ".aqiqah-select", calculateTotal);

    $(document).on("change", ".aqiqah-check", function () {
        let row = $(this).closest("tr");
        let genderSelect = row.find(".aqiqah-select");
        let hiddenAqiqahInput = row.find(".aqiqah-input");

        if ($(this).is(":checked")) {
            hiddenAqiqahInput.val("1");
            genderSelect.show();
        } else {
            hiddenAqiqahInput.val("0");
            genderSelect.hide().val("");
        }
        calculateTotal();
    });

    $(document).on("click", ".remove", function () {
        if ($(".rowClass").length > 1) {
            $(this).closest("tr").remove();
        }
        calculateTotal();
    });

    window.togglePaymentDetails = function (select) {
        $("#razorpay-details").toggle(select.value === 'RazorPay');
        $("#attachement").toggle(select.value === 'RazorPay');
    };

    togglePaymentDetails(document.getElementById('payment_method'));
    calculateTotal();
});

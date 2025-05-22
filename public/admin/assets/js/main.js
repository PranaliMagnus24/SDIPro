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

//         $("#txtamount").val(totalAmount.toFixed(2));
//     }

//     // Auto-suggest for contact name and mobile
//     $('input[name="contact_name"], input[name="mobile"]').each(function () {
//     $(this).autocomplete({
//         source: function (request, response) {
//             const fieldName = this.element.attr('name');

//             $.ajax({
//                 url: autosuggestUrl,
//                 data: {
//                     query: request.term,
//                     field: fieldName
//                 },
//                 success: function (data) {
//                     response(data);
//                 }
//             });
//         },
//         minLength: 2,
//         select: function (event, ui) {
//             $('input[name="contact_name"]').val(ui.item.contact_name);
//             $('input[name="mobile"]').val(ui.item.mobile);
//             $('select[name="payment_type"]').val(ui.item.payment_type);
//             $('select[name="receipt_book"]').val(ui.item.receipt_book);

//             // Clear existing rows before adding new ones
//             $('#tbody').empty();

//             if (ui.item.hisses) {
//                 ui.item.hisses.forEach(function (hisse) {
//                     addHissaRow(hisse);
//                 });
//             }

//             calculateTotal();
//             return false;
//         }
//     });
// });

//     // Function to add a new row for QurbaniHisse
//     function addHissaRow(hisse) {
//         const row = `
//             <tr class="rowClass">
//                 <td class="text-center">
//                     <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="${hisse.aqiqah}">
//                     <input type="checkbox" class="aqiqah-check" ${hisse.aqiqah ? 'checked' : ''}>
//                 </td>
//                 <td class="text-center">
//                     <input type="text" name="name[]" class="form-control name-input" value="${hisse.name}" placeholder="Name">
//                 </td>
//                 <td class="text-center">
//                     <select name="gender[]" class="form-select aqiqah-select">
//                         <option value="Male" ${hisse.gender === 'Male' ? 'selected' : ''}>Male</option>
//                         <option value="Female" ${hisse.gender === 'Female' ? 'selected' : ''}>Female</option>
//                     </select>
//                 </td>
//                 <td class="text-center">
//                     <input type="number" name="hissa[]" class="form-control hissa-input" value="${hisse.hissa}" readonly>
//                 </td>
//                 <td class="text-center">
//                     <input type="hidden" name="huzur[]" value="0">
//                     <button class="btn btn-danger remove" type="button">Remove</button>
//                 </td>
//             </tr>
//         `;
//         $('#tbody').append(row);
//     }

//     // Add new row button
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

//     // Logic for adding Huzur row
//     $("#addBtnHuzur").click(function () {
//     if ($(".huzur-row").length === 0) {
//         let huzurRow =
//         `<tr class="rowClass huzur-row">
//             <td class="text-center">
//                 <input type="hidden" name="aqiqah[]" value="">
//             </td>
//             <td class="text-center" style="width: 448px;">
//                 <input type="text" name="name[]" class="form-control" value="HAZRAT MOHAMMAD SALLALLAHU ALAIHI WASALLAM" readonly>
//             </td>
//             <td class="text-center">
//                 <select name="gender[]" class="form-select" style="display:none;">
//                     <option value="">Select</option>
//                 </select>
//             </td>
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


//     // Event listeners for input changes
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

    // Calculate total based on all rows
    function calculateTotal() {
        totalAmount = 0;

        $(".rowClass").each(function () {
            let name = $(this).find(".name-input, input[readonly]").val().trim();
            let isAqiqah = $(this).find(".aqiqah-check").is(":checked");
            let selectedGender = $(this).find(".aqiqah-select").val();
            let hissaAmount = 1500;
            let hissaCount = 1;

            if (name !== "") {
                if (isAqiqah) {
                    hissaCount = 1; // Always 1 regardless of gender per row
                }

                $(this).find(".hissa-input").val(hissaCount);
                totalAmount += hissaCount * hissaAmount;
            }
        });

        $("#txtamount").val(totalAmount.toFixed(2));
    }

    // Remove duplicate aqiqah rows for a given name, keeping max two for male, one for female
    function fixDuplicateRows(name) {
        let rows = $(".rowClass").filter(function () {
            let n = $(this).find(".name-input, input[readonly]").val().trim();
            let checked = $(this).find(".aqiqah-check").is(":checked");
            return checked && n === name;
        });

        if (rows.length <= 1) return; // nothing to fix

        // Determine gender from first row
        let gender = rows.first().find(".aqiqah-select").val();

        if (gender === "Male") {
            // Keep max 2 rows, remove extras
            rows.slice(2).remove();
        } else {
            // Keep only 1 row for female or others
            rows.slice(1).remove();
        }
    }

    // When Aqiqah checkbox changes
    $(document).on("change", ".aqiqah-check", function () {
        let row = $(this).closest("tr");
        let checked = $(this).is(":checked");
        let nameInput = row.find(".name-input");
        let genderSelect = row.find(".aqiqah-select");
        let hiddenAqiqahInput = row.find(".aqiqah-input");

        if (checked) {
            hiddenAqiqahInput.val("1");
            genderSelect.show();
            // Do not prevent checking even if name empty
            // We will handle duplicates once name and gender are provided
        } else {
            hiddenAqiqahInput.val("0");
            genderSelect.hide().val("");
            // Remove duplicates if any for this row's name
            let name = nameInput.val().trim();
            if (name) {
                fixDuplicateRows(name);
            }
        }
        calculateTotal();
    });

    // When name or gender changes, handle duplicate rows and hissa adjustments
    function handleNameOrGenderChange(row) {
        let name = row.find(".name-input").val().trim();
        let checked = row.find(".aqiqah-check").is(":checked");
        let gender = row.find(".aqiqah-select").val();

        if (checked && name && gender) {
            if (gender === "Male") {
                // Count how many rows for this name with aqiqah checked
                let rowsForName = $(".rowClass").filter(function () {
                    let n = $(this).find(".name-input, input[readonly]").val().trim();
                    let c = $(this).find(".aqiqah-check").is(":checked");
                    return c && n === name;
                });
                if (rowsForName.length < 2) {
                    // Add second row for male with same name
                    addNewRow(name, gender, true);
                } else if (rowsForName.length > 2) {
                    // Remove extra rows
                    rowsForName.slice(2).remove();
                }
            } else {
                // For female or others, keep only 1 row
                let rowsForName = $(".rowClass").filter(function () {
                    let n = $(this).find(".name-input, input[readonly]").val().trim();
                    let c = $(this).find(".aqiqah-check").is(":checked");
                    return c && n === name;
                });

                if (rowsForName.length > 1) {
                    rowsForName.slice(1).remove();
                }
            }
        }
        calculateTotal();
    }

    // Add new row function
    function addNewRow(name, gender, isAqiqah = false) {
        const isChecked = isAqiqah ? "checked" : "";
        const aqiqahValue = isAqiqah ? "1" : "0";
        const genderStyle = isAqiqah ? "" : "display:none;";
        const hissaValue = 1;

        const newRow = $(`
            <tr class="rowClass">
                <td class="text-center">
                    <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="${aqiqahValue}">
                    <input type="checkbox" class="aqiqah-check" ${isChecked}>
                </td>
                <td class="text-center">
                    <input type="text" name="name[]" class="form-control name-input" value="${name}" placeholder="Name">
                </td>
                <td class="text-center">
                    <select name="gender[]" class="form-select aqiqah-select" style="${genderStyle}">
                        <option value="">Select</option>
                        <option value="Male" ${gender === "Male" ? "selected" : ""}>Male</option>
                        <option value="Female" ${gender === "Female" ? "selected" : ""}>Female</option>
                    </select>
                </td>
                <td class="text-center">
                    <input type="number" name="hissa[]" class="form-control hissa-input" value="${hissaValue}" readonly>
                </td>
                <td class="text-center">
                    <input type="hidden" name="huzur[]" value="0">
                    <button class="btn btn-danger remove" type="button">Remove</button>
                </td>
            </tr>
        `);

        $("#tbody").append(newRow);
    }

    // On name input change
    // $(document).on("input", ".name-input", function () {
    //     let row = $(this).closest("tr");
    //     handleNameOrGenderChange(row);
    // });

    $(document).on("input", ".name-input", function () {
        calculateTotal();
    });

    // On gender select change
    $(document).on("change", ".aqiqah-select", function () {
        let row = $(this).closest("tr");
        handleNameOrGenderChange(row);
    });

    // Add new row button
    $("#addBtn").click(function () {
        addNewRow("", "", false);
        calculateTotal();
    });

    // Add huzur row button
    $("#addBtnHuzur").click(function () {
        if ($(".huzur-row").length === 0) {
            const huzurRow = $(`
                <tr class="rowClass huzur-row">
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
                </tr>
            `);
            $("#tbody").prepend(huzurRow);
            calculateTotal();
        }
    });

    // Remove row button
    $(document).on("click", ".remove", function () {
        if ($(".rowClass").length > 1) {
            $(this).closest("tr").remove();
            calculateTotal();
        }
    });

    // Auto-suggest for contact_name and mobile (assuming autosuggestUrl defined)
    $('input[name="contact_name"], input[name="mobile"]').each(function () {
        $(this).autocomplete({
            source: function (request, response) {
                const fieldName = this.element.attr("name");

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
                $("input[name='contact_name']").val(ui.item.contact_name);
                $("input[name='mobile']").val(ui.item.mobile);
                $("select[name='payment_type']").val(ui.item.payment_type);
                $("input[name='receipt_book']").val(ui.item.receipt_book);

                // Clear existing rows before adding new ones
                $("#tbody").empty();

                if (ui.item.hisses) {
                    ui.item.hisses.forEach(function (hisse) {
                        addNewRow(hisse.name, hisse.gender, hisse.aqiqah == 1);
                    });
                } else {
                    addNewRow("", "", false);
                }

                calculateTotal();
                return false;
            }
        });
    });

    // Toggle payment details
    window.togglePaymentDetails = function (select) {
        $("#razorpay-details").toggle(select.value === "RazorPay");
        $("#attachement").toggle(select.value === "RazorPay");
    };
    togglePaymentDetails(document.getElementById("payment_method"));

    // Initial total calculation
    calculateTotal();
});





function populateFormFields(data) {
    $('#Requestor').val(data.requestorName);
    $('#division').val(data.division);
    $('#Division').val(data.division);
    $('#Departement').val(data.departement);
    $('#idCard').val(data.idCard);
    $('#Email').val(data.email);
    $('#phoneNumber').val(data.phoneNumber);
    $('#gender').val(data.gender);
    $('#company').val(data.company);
    $('#manDropdown').val(data.manager);
}

// Assuming you're already fetching data and calling this function when data is available
function populateManager(managerName) {
selectManager(managerName); // Call the function to select the manager
}

// Call the function to populate manager name when data is available
$(document).ready(function() {
var managerName = "Your Manager Name"; // Replace with the manager name fetched from database
populateManager(managerName);
});

//requestor name
function detail() {
var requestorValue = $("#requestor").val();

$.ajax({
type: 'POST',
url: 'test4.php', // Replace with your PHP script to fetch data
data: { requestor: requestorValue },
success: function(response) {
    var data = JSON.parse(response);
    populateFormFields(data); // Populate other form fields as usual

    // Display the fetched company
    if (data.company !== undefined) {

        // Update the company logo
        updateLogo(data.company);
    } else {
        // Set default logo or handle other cases
        updateLogo(null);
    }
},
error: function(xhr, status, error) {
    console.error(xhr.responseText);
    // Handle error if needed
}
});
}

// Hotel required
function toggleHotelField(isYesSelected) {
var hotelFieldDiv = document.getElementById("hotelInformation");
var hotelNameInput = document.getElementById("hotelName");
var hotelAddressInput = document.getElementById("hotelAddress");
var hotelPhoneInput = document.getElementById("hotelPhone");
var hotelRemarkInput = document.getElementById("hotelRemark");

if (isYesSelected) {
hotelFieldDiv.style.display = "block";
setRequiredAttribute(hotelNameInput, true);
setRequiredAttribute(hotelAddressInput, true);
setRequiredAttribute(hotelPhoneInput, true);
setRequiredAttribute(hotelRemarkInput, true);
} else {
hotelFieldDiv.style.display = "none";
setRequiredAttribute(hotelNameInput, false);
setRequiredAttribute(hotelAddressInput, false);
setRequiredAttribute(hotelPhoneInput, false);
setRequiredAttribute(hotelRemarkInput, false);
}
}

function setRequiredAttribute(element, isRequired) {
if (isRequired) {
element.setAttribute("required", "required");
} else {
element.removeAttribute("required");
}
}


function setRequiredAttribute(element, isRequired) {
if (isRequired) {
element.setAttribute("required", "required");
} else {
element.removeAttribute("required");
}
}

// CODE FELIX
// $(document).on('input', '.amountInput', function () {
//     validateAndCalculateTotalAmount();
// });

// function validateAndCalculateTotalAmount() {
//     let totalAmount = 0;

//     $('.amountInput').each(function () {
//         const inputValue = parseFloat($(this).val()) || 0;

//         // Ensure the input is greater than or equal to 0
//         if (inputValue < 0) {
//             alert("Please enter a number greater than or equal to 0.");
//             $(this).val(""); // Clear the input field
//         } else {
//             totalAmount += inputValue;
//         }
//     });

//     // Update the total amount only if all input values are valid
//     $('#totalAmount1').val(totalAmount);
//     $('#totalAmount').val(totalAmount);
// }

//CODE SAMUEL
const amountInputFields = document.getElementsByClassName("amountInput");
const totalAmount1 = document.getElementById("totalAmount1");
const totalAmount = document.getElementById("totalAmount");

const formatter = new Intl.NumberFormat("id-ID", {
//   style: "currency",
//   currency: "IDR",
style: "decimal",
// minimumFractionDigits: 2
});

for (let i = 0; i<amountInputFields.length; i++){
amountInputFields[i].addEventListener("input", () => {
// 		amountInputFields[i].value = floatToFormatted(amountInputFields[i].value.replace(/\D/g, "")/100);
amountInputFields[i].value = floatToFormatted(amountInputFields[i].value.replace(/\D/g, ""));
totalAmount.value = floatToFormatted(sumAllInputs());
totalAmount1.value = floatToFormatted(sumAllInputs());
});
}


function formattedToFloat(formatted){
// 	return parseFloat(formatted.substring(3).replaceAll(".", "").replace(",", "."));
return parseFloat(formatted.replaceAll(".", "").replace(",", "."));
}

function floatToFormatted(floatNumber){
return formatter.format(floatNumber);
}

function sumAllInputs(){
let sum = 0;

for (let i = 0; i < amountInputFields.length; i++){
if (amountInputFields[i].value){
    sum += formattedToFloat(amountInputFields[i].value)
}
}
return parseFloat(sum)
}

// document.getElementById("formRequest").addEventListener("submit", function(event) {
// //     for (let i = 0; i < amountInputFields.length; i++){
// // 		if (amountInputFields[i].value){
// // 			amountInputFields[i].value = formattedToFloat(amountInputFields[i].value);
// // 		}
// // 	}

// // 	totalAmount.value = formattedToFloat(totalAmount.value);
// // 	totalAmount1.value = formattedToFloat(totalAmount1.value);




//   // Allow form submission (optional, if needed)
// //   event.preventDefault(); // Uncomment to prevent default form submission
// });

//CODE SAMUEL ENDS HERE


function displayFileName() {
var fileInput = document.getElementById('file');
var fileDisplay = document.getElementById('file-display');

// Display the selected file name
fileDisplay.textContent = fileInput.files[0] ? fileInput.files[0].name : '';
}

let costRowCounter = 1; // Initialize a counter for cost rows

// Function to add a new row for travel cost
function tambahCost() {
const tbody = document.getElementById('tableCost').getElementsByTagName('tbody')[0];
const totalRow = tbody.querySelector('tr:last-child'); // Get the last row (Total Amount row)

const newRow = document.createElement('tr');

const cell1 = document.createElement('td');
const cell2 = document.createElement('td');
const cell3 = document.createElement('td');
const cell4 = document.createElement('td');
const deleteButtonCell = document.createElement('td');

cell1.innerHTML = `
<select class="select-style" name="costData[category][]" required>
    <option value="" selected>Select Category</option>
    <option value="Accomodation">Accomodation</option>
    <option value="Entertain">Entertain</option>
    <option value="Transportation">Transportation</option>
    <option value="Visa/Pasport">Visa/Pasport</option>
    <option value="Flight">Flight</option>
    <option value="Toll">Toll</option>
    <option value="Meals">Meals</option>
    <option value="Others">Others</option>
</select>`;
cell2.innerHTML = `<input type="text" name="costData[amountTravel][]" class="amountInput" id="amountId${costRowCounter}" placeholder="Input Cost Amount" value="0" required>`;
cell3.innerHTML = `<input type="text" name="costData[currency][]" class="currencyInput">`;
cell4.innerHTML = `<input type="text" name="costData[remark][]" id="remarksId${costRowCounter}" placeholder="Input Remarks" required>`;

newRow.appendChild(cell1);
newRow.appendChild(cell2);
newRow.appendChild(cell3);
newRow.appendChild(cell4);

const deleteButton = document.createElement('button');
deleteButton.textContent = 'Delete';
deleteButton.addEventListener('click', function (event) {
event.preventDefault(); // Prevent the default form submission
deleteRow(newRow);
});
deleteButtonCell.appendChild(deleteButton);
newRow.appendChild(deleteButtonCell);

tbody.insertBefore(newRow, totalRow); // Insert the new row before the total row

costRowCounter++; // Increment the counter for the next row

// Prefill the currency input field with the selected currency
const selectedCurrency = $('#currency').val();
newRow.querySelector('.currencyInput').value = selectedCurrency;

// Add event listener to amount input fields
const amountInputFields = newRow.getElementsByClassName("amountInput");
for (let i = 0; i < amountInputFields.length; i++) {
amountInputFields[i].addEventListener("input", () => {
    amountInputFields[i].value = floatToFormatted(amountInputFields[i].value.replace(/\D/g, ""));
    totalAmount.value = floatToFormatted(sumAllInputs());
    totalAmount1.value = floatToFormatted(sumAllInputs());
});
}
}

// Function to delete a row
function deleteRow(row) {
const tbody = row.parentNode;
tbody.removeChild(row);
return false; // Prevent the default behavior of the button click event
}

// Function to update currency input fields
function change() {
var currencyValue = $("#currency").val();

$.ajax({
type: 'POST',
url: 'test5.php',
data: { currency: currencyValue },
success: function(response) {
    var data = JSON.parse(response);
    
    // Update currency input fields
    $('.currencyInput').val(data.Currency);
}
});
}

// Function to convert formatted number to float
function formattedToFloat(formatted) {
return parseFloat(formatted.replaceAll(".", "").replace(",", "."));
}

// Function to convert float number to formatted string
function floatToFormatted(floatNumber) {
return formatter.format(floatNumber);
}

// Function to sum all input fields
function sumAllInputs() {
let sum = 0;
const amountInputFields = document.getElementsByClassName("amountInput");
for (let i = 0; i < amountInputFields.length; i++) {
if (amountInputFields[i].value) {
    sum += formattedToFloat(amountInputFields[i].value);
}
}
return parseFloat(sum);
}

// Call the change function when the page loads to populate currency input fields
$(document).ready(function() {
change();
});

// Call the change function when the currency selection changes
$('#currency').on('change', change);



// Call this function when the Requestor selection changes
$('#requestorId').on('change', function() {
const selectedRequestor = $(this).val();
displayRequestorData(selectedRequestor);
});

// Initiated Date
const date = new Date();
const tanggal = date.toLocaleDateString();
const waktu = date.toLocaleTimeString();

document.addEventListener("DOMContentLoaded", (event) => {
document.getElementById('dateId').value = `${tanggal} ${waktu}`;
});

const startDateInput = document.getElementById('startDate');
const returnDateInput = document.getElementById('returnDate');

startDateInput.addEventListener('input', calculateDateDifference);
returnDateInput.addEventListener('input', calculateDateDifference);

function calculateDateDifference() {
const startDateString = startDateInput.value;
const returnDateString = returnDateInput.value;

const startDate = new Date(startDateString);
const returnDate = new Date(returnDateString);

const currentDate = new Date();
currentDate.setHours(7, 0, 0, 0);

if (startDate < currentDate) {
startDateInput.removeEventListener('input', calculateDateDifference);
startDateInput.valueAsDate = currentDate;
startDateInput.addEventListener('input', calculateDateDifference);
}

if (startDate > returnDate) {
returnDateInput.valueAsDate = startDate;
}

const dateDifference = Math.floor((returnDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

if (dateDifference < 0) {
document.getElementById('totalDays').value = `1 day`;
} else {
document.getElementById('totalDays').value = `${dateDifference} days`;
}
}


const flightDate = new Date();

// tambah trip routing
let routingRowCounter = 1; // Initialize a counter for trip routing rows

function tambahRou() {
const tbody = document.getElementById('tableRouting').getElementsByTagName('tbody')[0];

const newRow = document.createElement('tr');

const cell1 = document.createElement('td');
const cell2 = document.createElement('td');
const cell3 = document.createElement('td');
const cell4 = document.createElement('td');
const cell5 = document.createElement('td');
const deleteButtonCell = document.createElement('td');

cell1.innerHTML = `<input type="text" name="tripData[flightFrom][]" id="fromId${routingRowCounter}" placeholder="Input From Where" required>`;
cell2.innerHTML = `<input type="text" name="tripData[flightTo][]" id="toId${routingRowCounter}" placeholder="Input To Where" required>`;
cell3.innerHTML = `
        <select class="select-style" name="tripData[tripClass][]" required>
            <option value="">Choose Trip</option>
            <option value="Train - Economy">Train - Economy</option>
            <option value="Airplane - Economy">Airplane - Economy</option>
            <option value="Private Transportation">Private Transportation</option>
            <option value="Public Transportation">Public Transportation</option>
        </select>`;
cell4.innerHTML = `<input type="date" name="tripData[flightDate][]" id="flightDateId${routingRowCounter}" placeholder="Input Flight Date" oninput="validateFlightDate(this)" required>`;
cell5.innerHTML = `<input type="text" name="tripData[flightComment][]" id="comments2Id${routingRowCounter}" placeholder="Input Your Comments">`;

newRow.appendChild(cell1);
newRow.appendChild(cell2);
newRow.appendChild(cell3);
newRow.appendChild(cell4);
newRow.appendChild(cell5);

const deleteButton = document.createElement('button');
deleteButton.textContent = 'Delete';
deleteButton.addEventListener('click', function (event) {
event.preventDefault(); // Prevent the default form submission
deleteRow(newRow);
});
deleteButtonCell.appendChild(deleteButton);
newRow.appendChild(deleteButtonCell);

tbody.appendChild(newRow);

routingRowCounter++; // Increment the counter for the next row
}

function deleteRow(row) {
const tbody = row.parentNode;
tbody.removeChild(row);
return false; // Prevent the default behavior of the button click event
}

function validateFlightDate(input) {
if (!input || !input.value) {
return; // Exit function if input is null or value is not available
}

const selectedDate = new Date(input.value);
const row = input.closest('tr');
const rowIndex = Array.from(row.parentNode.children).indexOf(row);

// Compare the selected date with the preceding flight date
if (rowIndex > 0) {
const previousDateInput = document.getElementById(`flightDateId${rowIndex - 1}`);
if (previousDateInput && previousDateInput.value) {
    const previousDate = new Date(previousDateInput.value);
    if (selectedDate < previousDate) {
        input.valueAsDate = previousDate;
    }
}
}

// Ensure the selected date is not in the past
const currentDate = new Date();
currentDate.setHours(7, 0, 0, 0); // Set hours to midnight for accurate comparison
if (selectedDate < currentDate) {
input.valueAsDate = currentDate;
}
}

// tambah hotel
let hotelRowCounter = 1; // Initialize a counter for trip routing rows
function tambahHot() {
const tbody = document.getElementById('tableHotel').getElementsByTagName('tbody')[0];

const newRow = document.createElement('tr');

const cell1 = document.createElement('td');
const cell2 = document.createElement('td');
const cell3 = document.createElement('td');
const cell4 = document.createElement('td');

cell1.innerHTML = '<input name="hotelData[hotelName][]" type="text" id="hotelNameId${hotelRowCounter}" placeholder="Input Hotel Name">';
cell2.innerHTML = '<input name="hotelData[hotelAddress][]" type="text" id="hotelAddressId${hotelRowCounter}" placeholder="Input Hotel Address">';
cell3.innerHTML = '<input name="hotelData[hotelPhone][]" type="string" id="telephoneId${hotelRowCounter}" placeholder="Input Hotel Phone Number">';
cell4.innerHTML = '<input name="hotelData[hotelRemark][]" type="text" id="Remarks2Id${hotelRowCounter}" placeholder="Input Remarks">';

newRow.appendChild(cell1);
newRow.appendChild(cell2);
newRow.appendChild(cell3);
newRow.appendChild(cell4);

const deleteButton = document.createElement('button');
deleteButton.textContent = 'Delete';
deleteButton.addEventListener('click', function (event) {
event.preventDefault(); // Prevent the default form submission
deleteRow(newRow);
});
const deleteButtonCell = document.createElement('td');
deleteButtonCell.appendChild(deleteButton);
newRow.appendChild(deleteButtonCell);

tbody.appendChild(newRow);
hotelRowCounter++; // Increment the counter for the next row
}

function deleteRow(row) {
    $(row).closest('tr').remove();
}

function saveDraft() {
    var formData = {
        'logoHtml': $('#gambar-container').html(),
        'referenceNumber': $('input[name="referenceNumber"]').val(),
        'initiatedName': $('#initiatedName').text(),
        'status': $('input[name="status"]').val(),
        'dateInitiated': $('input[name="Date"]').val(),
        'title': $('input[name="title"]').val(),
        'purpose': $('input[name="purpose"]').val(),
        'requestor': $('select[name="requestor"]').val(),
        'email': $('input[name="Email"]').val(),
        'division': $('input[name="division"]').val(),
        'department': $('input[name="Departement"]').val(),
        'idCard': $('input[name="idCard"]').val(),
        'phoneNumber': $('input[name="phoneNumber"]').val(),
        'manager': $('select[name="manager"]').val(),
        'startDate': $('input[name="startDate"]').val(),
        'returnDate': $('input[name="returnDate"]').val(),
        'destination': $('select[name="destination"]').val(),
        'totalDays': $('input[name="totalDays"]').val(),
        'advanceAmount': $('input[name="amount"]').val(),
        'currency': $('select[name="currency"]').val(),
        'passengerName': $('#Requestor').val(),
        'passengerDivision': $('#Division').val(),
        'passengerGender': $('#gender').val(),
        'hotelBooking': $('input[name="hotel"]:checked').val(),
        'passengerComment': $('#comment1Id').val(),
        'routing': [],
        'cost': [],
        'hotelInformation': [],
        'totalAmount': $('input[name="totalAmount"]').val(),
        'totalCurrency': $('input[name="totalCurrency"]').val(),
        'totalRemark': $('input[name="totalRemark"]').val(),
        'fileName': $('#file')[0].files[0] ? $('#file')[0].files[0].name : null
    };

// Remove cost objects with any undefined property
formData.cost = formData.cost.filter(function(costData) {
    // Check if any property of costData is undefined
    for (var key in costData) {
        if (costData.hasOwnProperty(key) && costData[key] === undefined) {
            return false; // Exclude this costData object
        }
    }
    return true; // Include this costData object
});

    var routingData = [];
    $('#tableRouting tbody tr').each(function() {
        var routingRow = {};
        routingRow.flightFrom = $(this).find('input[name="tripData[flightFrom][]"]').val();
        routingRow.flightTo = $(this).find('input[name="tripData[flightTo][]"]').val();
        routingRow.tripClass = $(this).find('select[name="tripData[tripClass][]"]').val();
        routingRow.flightDate = $(this).find('input[name="tripData[flightDate][]"]').val();
        routingRow.flightComment = $(this).find('input[name="tripData[flightComment][]"]').val();
        routingData.push(routingRow);
    });
    formData.routing = routingData;

    $('#tableCost tbody tr').each(function() {
        var costData = {
            'category': $(this).find('select[name="costData[category][]"]').val(),
            'amount': $(this).find('input[name="costData[amountTravel][]"]').val(),
            'currency': $(this).find('input[name="costData[currency][]"]').val(),
            'remark': $(this).find('input[name="costData[remark][]"]').val()
        };
    
        // Check if any property in costData is undefined
        var hasUndefined = Object.values(costData).some(value => value === undefined);
    
        // Only push costData if it doesn't have any undefined values
        if (!hasUndefined) {
            formData['cost'].push(costData);
        }
    });
    

    $('#tableHotel tbody tr').each(function() {
        var hotelData = {
            'hotelName': $(this).find('input[name="hotelData[hotelName][]"]').val(),
            'hotelAddress': $(this).find('input[name="hotelData[hotelAddress][]"]').val(),
            'hotelPhone': $(this).find('input[name="hotelData[hotelPhone][]"]').val(),
            'hotelRemark': $(this).find('input[name="hotelData[hotelRemark][]"]').val()
        };
        formData['hotelInformation'].push(hotelData);
    });

    localStorage.setItem('draftFormData', JSON.stringify(formData));

    console.log(formData);
}

// Function to load draft data from local storage
function loadDraft() {
    var draftData = localStorage.getItem('draftFormData');
    if (draftData) {
        var formData = JSON.parse(draftData);
        
        // Populate individual form fields
        $('#gambar-container').html(formData.logoHtml);
        $('input[name="company"]').val(formData.company);
        $('input[name="referenceNumber"]').val(formData.referenceNumber);
        $('#initiatedName').text(formData.initiatedName);
        $('input[name="status"]').val(formData.status);
        $('input[name="Date"]').val(formData.dateInitiated);
        $('input[name="title"]').val(formData.title);
        $('input[name="purpose"]').val(formData.purpose);
        $('select[name="requestor"]').val(formData.requestor);
        $('input[name="Email"]').val(formData.email);
        $('input[name="division"]').val(formData.division);
        $('input[name="Departement"]').val(formData.department);
        $('input[name="idCard"]').val(formData.idCard);
        $('input[name="phoneNumber"]').val(formData.phoneNumber);
        $('select[name="manager"]').val(formData.manager);
        $('input[name="startDate"]').val(formData.startDate);
        $('input[name="returnDate"]').val(formData.returnDate);
        $('select[name="destination"]').val(formData.destination);
        $('input[name="totalDays"]').val(formData.totalDays);
        $('input[name="amount"]').val(formData.advanceAmount);
        $('select[name="currency"]').val(formData.currency);
        $('#Requestor').val(formData.passengerName);
        $('#Division').val(formData.passengerDivision);
        $('#gender').val(formData.passengerGender);
        $('input[name="hotel"][value="' + formData.hotelBooking + '"]').prop('checked', true);
        $('#comment1Id').val(formData.passengerComment);
        $('input[name="totalAmount"]').val(formData.totalAmount);
        $('input[name="totalCurrency"]').val(formData.totalCurrency);
        $('input[name="totalRemark"]').val(formData.totalRemark);
        
        // Populate routing table
        formData.routing.forEach(function(routingData, index) {
            var $row = $('#tableRouting tbody tr').eq(index);
            if (index === 0) {
                $row.find('input[name="tripData[flightFrom][]"]').val(routingData.flightFrom);
                $row.find('input[name="tripData[flightTo][]"]').val(routingData.flightTo);
                $row.find('select[name="tripData[tripClass][]"]').val(routingData.tripClass);
                $row.find('input[name="tripData[flightDate][]"]').val(routingData.flightDate);
                $row.find('input[name="tripData[flightComment][]"]').val(routingData.flightComment);
            } else {
                // Add a new row below the first row
                var newRowHtml = $('#tableRouting tbody tr').eq(0).clone();
                $('#tableRouting tbody').append(newRowHtml);
                $row = $('#tableRouting tbody tr').eq(index);
                $row.find('input[name="tripData[flightFrom][]"]').val(routingData.flightFrom);
                $row.find('input[name="tripData[flightTo][]"]').val(routingData.flightTo);
                $row.find('select[name="tripData[tripClass][]"]').val(routingData.tripClass);
                $row.find('input[name="tripData[flightDate][]"]').val(routingData.flightDate);
                $row.find('input[name="tripData[flightComment][]"]').val(routingData.flightComment);
                
                if(index > 0){
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent the default form submission
                        deleteRow($row);
                    });
                    const deleteButtonCell = document.createElement('td');
                    deleteButtonCell.appendChild(deleteButton);
                    $row.append(deleteButtonCell);
                }
            }
        });
        
        // Populate cost table
        formData.cost.forEach(function(costData, index) {
            var $row;
            if (index < 2) {
                $row = $('#tableCost tbody tr').eq(index);
            } else {
                // Add a new row above the total amount row
                $row = $('#tableCost tbody tr').eq(0).clone();
                var $totalAmountRow = $('#tableCost tbody tr').filter(function() {
                    return $(this).find('input[name="totalAmount"]').length > 0;
                });
                $totalAmountRow.before($row);
            }
            $row.find('select[name="costData[category][]"]').val(costData.category);
            $row.find('input[name="costData[amountTravel][]"]').val(costData.amount);
            $row.find('input[name="costData[currency][]"]').val(costData.currency);
            $row.find('input[name="costData[remark][]"]').val(costData.remark);
        
            if (index >= 2) {
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent the default form submission
                    deleteRow($row);
                });
                const deleteButtonCell = document.createElement('td');
                deleteButtonCell.appendChild(deleteButton);
                $row.append(deleteButtonCell);
            }
        });
        
        // Ensure only one total amount row exists
        var $totalAmountRows = $('#tableCost tbody tr').filter(function() {
            return $(this).find('input[name="totalAmount"]').length > 0;
        });
        if ($totalAmountRows.length > 1) {
            $totalAmountRows.slice(1).remove();
        }
        
        if (formData.hotelInformation && formData.hotelInformation.length >= 1) {
            // Show the hotel information section
            var hotelInformationDiv = document.getElementById('hotelInformation');
            if (hotelInformationDiv) {
                hotelInformationDiv.style.display = "block";
            }
        }

        // Populate hotel information table
        formData.hotelInformation.forEach(function(hotelData, index) {
            var $row = $('#tableHotel tbody tr').eq(index);
            if (index === 0) {
                $row.find('input[name="hotelData[hotelName][]"]').val(hotelData.hotelName);
                $row.find('input[name="hotelData[hotelAddress][]"]').val(hotelData.hotelAddress);
                $row.find('input[name="hotelData[hotelPhone][]"]').val(hotelData.hotelPhone);
                $row.find('input[name="hotelData[hotelRemark][]"]').val(hotelData.hotelRemark);
            } else {
                // Add a new row below the first row
                var newRowHtml = $('#tableHotel tbody tr').eq(0).clone();
                $('#tableHotel tbody').append(newRowHtml);
                $row = $('#tableHotel tbody tr').eq(index);
                $row.find('input[name="hotelData[hotelName][]"]').val(hotelData.hotelName);
                $row.find('input[name="hotelData[hotelAddress][]"]').val(hotelData.hotelAddress);
                $row.find('input[name="hotelData[hotelPhone][]"]').val(hotelData.hotelPhone);
                $row.find('input[name="hotelData[hotelRemark][]"]').val(hotelData.hotelRemark);

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent the default form submission
                    deleteRow($row);
                });
                const deleteButtonCell = document.createElement('td');
                deleteButtonCell.appendChild(deleteButton);
                $row.append(deleteButtonCell);
            }
        });
        
        // Set file name if available
        if (formData.fileName) {
            $('#file-display').text(formData.fileName);
        }
    }
}

$(document).ready(function() {
    // Check if there is data in the draft
    console.log(localStorage.getItem('draftFormData'));
    if (localStorage.getItem('draftFormData')) {
        $('#loadDraftButton').show();
    } else {
        $('#loadDraftButton').hide();
    }

    // Attach event listener to the button
    $('#loadDraftButton').click(function() {
        loadDraft(); // Call the loadDraft function when the button is clicked
    });
});


// Auto-save draft every 30 seconds (adjust time interval as needed)
setInterval(saveDraft, 30000);


// logout
function logout() {
const userConfirmed = window.confirm('Apakah Anda yakin ingin logout?');
if (userConfirmed) {
window.location.href = 'login.php';    
}else {
}
}

const logoutLink = document.getElementById('logoutLink');
if (logoutLink) {
logoutLink.addEventListener('click', function (event) {
event.preventDefault();
logout();
});
}
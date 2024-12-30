// Get all form elements
const minMonthSelect = document.querySelector('select[name="min_month"]');
const maxMonthSelect = document.querySelector('select[name="max_month"]');
// const minDayInput = document.querySelector('input[name="min_day"]');
const minDayInput = document.getElementById("min_day");
const maxDayInput = document.querySelector('input[name="max_day"]');

// Disable day inputs initially
minDayInput.disabled = true;
maxDayInput.disabled = true;

// Create error messages container
const createErrorMessage = (inputElement, id) => {
    let errorMessage = document.getElementById(id);
    if (!errorMessage) {
        errorMessage = document.createElement('p');
        errorMessage.id = id;
        errorMessage.style.color = 'red';
        errorMessage.style.display = 'none';
        inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
    }
    return errorMessage;
};

const minErrorMessage = createErrorMessage(minDayInput, 'minErrorMessage');
const maxErrorMessage = createErrorMessage(maxDayInput, 'maxErrorMessage');

// Object containing the number of days for each month
const daysInMonth = {
    1: 31,  // January
    2: 29,  // February (accounting for leap year)
    3: 31,  // March
    4: 30,  // April
    5: 31,  // May
    6: 30,  // June
    7: 31,  // July
    8: 31,  // August
    9: 30,  // September
    10: 31, // October
    11: 30, // November
    12: 31  // December
};

// Function to validate day input
function validateDay(monthSelect, dayInput, errorMessage) {
    const selectedMonth = parseInt(monthSelect.value);
    const dayValue = parseInt(dayInput.value);
    
    // Enable/disable day input based on month selection
    dayInput.disabled = (monthSelect.value === "Select Month" || !selectedMonth);
    
    if (selectedMonth && !isNaN(dayValue)) {
        const maxDays = daysInMonth[selectedMonth];
        
        if (dayValue < 1 || dayValue > maxDays) {
            errorMessage.textContent = `Please enter a day between 1 and ${maxDays} for the selected month`;
            errorMessage.style.display = 'block';
            dayInput.setCustomValidity(`Please enter a day between 1 and ${maxDays}`);
        } else {
            errorMessage.style.display = 'none';
            dayInput.setCustomValidity('');
        }
    } else if (monthSelect.value === "Select Month") {
        errorMessage.textContent = "Please select a month first";
        errorMessage.style.display = 'block';
        dayInput.setCustomValidity("Please select a month first");
        dayInput.value = ''; // Clear the input when month is deselected
    }
}

// Add event listeners for min date
minMonthSelect.addEventListener('change', () => {
    validateDay(minMonthSelect, minDayInput, minErrorMessage);
});

minDayInput.addEventListener('input', () => {
    validateDay(minMonthSelect, minDayInput, minErrorMessage);
});

// Add event listeners for max date
maxMonthSelect.addEventListener('change', () => {
    validateDay(maxMonthSelect, maxDayInput, maxErrorMessage);
});

maxDayInput.addEventListener('input', () => {
    validateDay(maxMonthSelect, maxDayInput, maxErrorMessage);
});

// Add event listener for form submission
document.querySelector('form').addEventListener('submit', (e) => {
    validateDay(minMonthSelect, minDayInput, minErrorMessage);
    validateDay(maxMonthSelect, maxDayInput, maxErrorMessage);
    
    // Check if either input is invalid
    if (minDayInput.validationMessage || maxDayInput.validationMessage) {
        e.preventDefault();
    }
    
    // Validate that max date is after min date
    const minMonth = parseInt(minMonthSelect.value);
    const maxMonth = parseInt(maxMonthSelect.value);
    const minDay = parseInt(minDayInput.value);
    const maxDay = parseInt(maxDayInput.value);
    
    if (minMonth && maxMonth && minDay && maxDay) {
        if (maxMonth < minMonth || (maxMonth === minMonth && maxDay < minDay)) {
            e.preventDefault();
            maxErrorMessage.textContent = "End date must be after start date";
            maxErrorMessage.style.display = 'block';
        }
    }
});

// Initial validation on page load
document.addEventListener('DOMContentLoaded', () => {
    // Ensure inputs are disabled on page load if no month is selected
    minDayInput.disabled = minMonthSelect.value === "Select Month";
    maxDayInput.disabled = maxMonthSelect.value === "Select Month";
    
    if (minDayInput.value || minMonthSelect.value !== "Select Month") {
        validateDay(minMonthSelect, minDayInput, minErrorMessage);
    }
    if (maxDayInput.value || maxMonthSelect.value !== "Select Month") {
        validateDay(maxMonthSelect, maxDayInput, maxErrorMessage);
    }
});

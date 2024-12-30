function updateDate() {
        const dateContainer = document.getElementById('current-date');
        const now = new Date();
        const formattedDate = now.toLocaleString('en-US', {
            month: 'long', day: 'numeric', year: 'numeric'});
        dateContainer.textContent = "Today is: " + formattedDate;
    }
    updateDate();
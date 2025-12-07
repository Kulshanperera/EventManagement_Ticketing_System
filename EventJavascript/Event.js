        
    //delete event confirmation message
    function deleteEvent(id, title) {
        if (confirm('Are you sure you want to delete "' + title + '"?')) {
                window.location.href = 'deleteEvent.php?id=' + id;
        }
    };

    window.addEventListener("load" , function () {
    // Summary counter
    const sumTa = document.getElementById('sum');
    const sumCounter = document.getElementById('sumCounter');
    const sumMax = 100;

    sumTa.oninput= () => {
    const len=sumTa.value.length;
    sumCounter.textContent  = `${len} / ${sumMax}`;

    // Change color when close to limit
    sumCounter.style.color = len >= sumMax - 20 ? "red":"#af4f01ff";
    };

    // DESCRIPTION COUNTER
    const descTa = document.getElementById("description");
    const descCounter = document.getElementById("descCounter");
    const descMax = 500;      // Set max characters allowed

    descTa.oninput = () => {
    const len = descTa.value.length;
    descCounter.textContent = `${len} / ${descMax}`;

    // Change color when close to limit
    descCounter.style.color = len >= descMax - 50 ? "red" : "#af4f01ff";
    };

    // Date minimum
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('eventDate').min = today;

    // On submit
    document.querySelector('form').onsubmit = function () {
        return validateForm(this);
        };
    });

    function validateForm(form) {
        const title = form.title.value.trim();
        const date = form.event_date.value;
        const time = form.event_time.value;
        const location = form.location.value.trim();
        const category = form.category.value;
        const desc = form.description.value.trim();

    // Required fields
    if (!title || !date || !time || !location || !category || !desc) {
        alert('Please fill all required fields (*) \n Event Title*\n Event Date*\n Event Time*\n Location*\n Category*\n Description*\n ');
        return false;
        }

    // Date must be today or future
    const today = new Date().setHours(0, 0, 0, 0);
    const eventDay = new Date(date).setHours(0, 0, 0, 0);

    if (eventDay < today) {
        alert('Event date must be today or future.');
        form.event_date.focus();
        return false;
        }

    // Description length
    if (desc.length < 100) {
        alert('Description must be at least 100 characters.');
        return false;
        }

    return true;
    };

    //clear the image event listener helps to avoid overwritten problem when have a multiple onload function
    window.addEventListener("load", function () {
        document.getElementById("clearImage").onclick=function(){
            document.getElementById("image").value=""
            };
    });
        var ticketCount = 1;
        var quantities = {};
        var prices = {};
        
        function addTicket() {
            ticketCount++;
            var html = '<div class="ticket-item">' +
                '<h3>Ticket ' + ticketCount + ' <button type="button" class="btn btn-remove" onclick="this.parentElement.parentElement.remove()">Remove</button></h3>' +
                '<div class="form-row">' +
                '<div class="form-group"><label>Ticket Name *</label><input type="text" name="ticket_name[]" required></div>' +
                '<div class="form-group"><label>Price (LKR) *</label><input type="number" name="ticket_price[]" required></div>' +
                '</div>' +
                '<div class="form-row">' +
                '<div class="form-group"><label>Quantity</label><input type="number" name="ticket_quantity[]"></div>' +
                '<div class="form-group"><label>Status</label><select name="ticket_status[]">' +
                '<option value="available">Available</option>' +
                '<option value="sold-out">Sold Out</option>' +
                '</select></div>' +
                '</div>' +
                '</div>';
            
            document.getElementById('ticketContainer').insertAdjacentHTML('beforeend', html);
        }
        
        function updateQty(index, change, price) {
            if (!quantities[index]) quantities[index] = 0;
            if (!prices[index]) prices[index] = price;
            
            quantities[index] = Math.max(0, quantities[index] + change);
            document.getElementById('qty-' + index).textContent = quantities[index];
            
            updateTotal();
        }
        
        function updateTotal() {
            var totalTickets = 0;
            var totalPrice = 0;
            
            for (var key in quantities) {
                totalTickets += quantities[key];
                totalPrice += quantities[key] * prices[key];
            }
            
            document.getElementById('totalTickets').textContent = totalTickets;
            document.getElementById('totalPrice').textContent = totalPrice.toLocaleString() + ' LKR';
        }
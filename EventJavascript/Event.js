        var ticketCount = 1;
        
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
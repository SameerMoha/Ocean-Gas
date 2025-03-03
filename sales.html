<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sales Process - LakeGas Enterprise Portal</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <div class="top">
      <h1>Sales Process</h1>
      <button onclick="window.history.back();" class="btn">Back</button>
    </div>

    <!-- Sales Form -->
    <div class="flow-card">
      <h3>Record a Sale</h3>
      <form id="salesForm">
        <div class="form-group">
          <label>Select Item:</label>
          <select id="saleItemSelect" class="form-control" required>
            <option value="">Choose item...</option>
          </select>
        </div>
        <div class="form-group">
          <label>Quantity Sold:</label>
          <input type="number" id="saleQuantity" class="form-control" min="1" required />
        </div>
        <div class="form-group">
          <label>Sale Price per Unit:</label>
          <input type="number" id="salePrice" class="form-control" min="0" step="0.01" required />
        </div>
        <div class="form-group">
          <label>Payment Method:</label>
          <select id="paymentMethod" class="form-control" required>
            <option value="">Choose payment method...</option>
            <option value="Cash">Cash</option>
            <option value="Credit">Credit</option>
          </select>
        </div>
        <button type="submit" class="btn primary">Submit Sale</button>
      </form>
    </div>

    <!-- Cash Balance Display -->
    <div class="cash-balance">
      <h3>Cash Balance: $<span id="cashBalance">0.00</span></h3>
    </div>

    <!-- Sales History -->
    <div class="transactions">
      <h2>Sales History</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Item</th>
              <th>Quantity</th>
              <th>Sale Price</th>
              <th>Total Amount</th>
              <th>Payment Method</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody id="salesHistory"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Inventory Data (same as in procurement)
    let inventory = [
      { id: 1, name: '50kg Gas Cylinders', quantity: 150, reorderLevel: 50 },
      { id: 2, name: 'Propane Tanks', quantity: 200, reorderLevel: 75 },
      { id: 3, name: 'Butane Containers', quantity: 100, reorderLevel: 30 }
    ];

    // Sales Records array
    let salesRecords = [];

    // Cash Balance variable
    let cashBalance = 0;

    // Initialize App
    document.addEventListener('DOMContentLoaded', () => {
      populateSaleItems();
      updateSalesHistory();
      updateCashBalance();
    });

    // Populate the sale item dropdown using the inventory
    function populateSaleItems() {
      const select = document.getElementById('saleItemSelect');
      select.innerHTML = '<option value="">Choose item...</option>' +
        inventory.map(item => `<option value="${item.id}">${item.name} (Stock: ${item.quantity})</option>`).join('');
    }

    // Handle Sales Form submission
    document.getElementById('salesForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const itemValue = document.getElementById('saleItemSelect').value;
      const quantitySold = parseInt(document.getElementById('saleQuantity').value);
      const salePrice = parseFloat(document.getElementById('salePrice').value);
      const paymentMethod = document.getElementById('paymentMethod').value;
      
      if (!itemValue || !quantitySold || !salePrice || !paymentMethod) {
        alert('Please fill all fields');
        return;
      }
      
      const itemId = parseInt(itemValue);
      const item = inventory.find(i => i.id === itemId);
      if (!item) {
        alert('Invalid item selected.');
        return;
      }
      
      // Check inventory for sufficient stock
      if (item.quantity < quantitySold) {
        alert(`Insufficient stock. Available: ${item.quantity}`);
        return;
      }
      
      // Deduct the sold quantity from inventory
      item.quantity -= quantitySold;
      
      // Calculate total sale amount
      const totalAmount = quantitySold * salePrice;
      
      // For cash sales, update the cash balance
      if (paymentMethod === 'Cash') {
        cashBalance += totalAmount;
        updateCashBalance();
      }
      
      // Create a sale record
      const saleRecord = {
        id: Date.now(),
        item: item.id,
        quantity: quantitySold,
        salePrice: salePrice.toFixed(2),
        totalAmount: totalAmount.toFixed(2),
        paymentMethod: paymentMethod,
        date: new Date().toLocaleDateString()
      };
      salesRecords.push(saleRecord);
      
      updateSalesHistory();
      populateSaleItems();  // Update dropdown to reflect new stock levels
      e.target.reset();
    });

    // Update the Sales History table
    function updateSalesHistory() {
      const tbody = document.getElementById('salesHistory');
      tbody.innerHTML = salesRecords.map(record => {
        // Retrieve item name from inventory (or "Unknown Item")
        const itemObj = inventory.find(i => i.id === record.item);
        const itemName = itemObj ? itemObj.name : 'Unknown Item';
        return `
          <tr>
            <td>${itemName}</td>
            <td>${record.quantity}</td>
            <td>$${record.salePrice}</td>
            <td>$${record.totalAmount}</td>
            <td>${record.paymentMethod}</td>
            <td>${record.date}</td>
          </tr>
        `;
      }).join('');
    }

    // Update the Cash Balance display
    function updateCashBalance() {
      document.getElementById('cashBalance').innerText = cashBalance.toFixed(2);
    }
  </script>
</body>
</html>

function addTransaction() {
    const description = document.getElementById('description').value;
    const category = document.getElementById('category').value;
    const amount = parseFloat(document.getElementById('amount').value);
    const type = document.getElementById('type').value;
    const date = document.getElementById('date').value || new Date().toISOString().split('T')[0];

	if (type === 'expense' && description && amount > 0) {
        // Add transaction to the table and database (this function should include AJAX to add it to the server and database)
        
        // Update pie chart data
        window.location.reload();
        fetchUpdatedChartData();
        
    }

    if (!description || isNaN(amount)) {
        alert("Please enter a valid description and amount.");
        return;
    }

    // AJAX call to add the transaction to the database
    fetch('add_expense.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ description,category,amount, type, date })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table and balance
	    //window.location.reload();
            const transactionTable = document.getElementById('transaction-table').querySelector('tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${date}</td>
                <td>${description}</td>
                <td>${amount.toFixed(2)}</td>
                <td>${type}</td>
                <td><button class="delete-button" onclick="deleteTransaction(${data.id})">Delete</button></td>
            `;
            transactionTable.appendChild(row);
            updateBalance(data.incomeTotal, data.expenseTotal);
        } else {
            alert("Error adding transaction.");
        }
    });
}

function deleteTransaction(id) {
window.location.reload();

    fetch('delete_expense.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Select the row using the `data-id` attribute and remove it
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                row.remove();
            }
            updateBalance(data.incomeTotal, data.expenseTotal); // Update balance after deletion
        } else {
            alert("Error deleting transaction.");
        }
    })
    .catch(error => console.error("Error:", error));

}
function exportToCSV() {
    let csv = [];
    const rows = document.querySelectorAll("#transaction-table tr");
    for (const row of rows) {
        const cols = row.querySelectorAll("th, td");
        const csvRow = [];
        for (const col of cols) {
            csvRow.push(col.innerText);
        }
        csv.push(csvRow.join(","));
    }
    const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    const downloadLink = document.createElement("a");
    downloadLink.download = "transactions.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
}

function updateBalance(incomeTotal, expenseTotal) {
    const balance = incomeTotal - expenseTotal;
    document.getElementById('balance').textContent = balance.toFixed(2);
}

function deleteTransaction(transactionId) {
    if (!confirm("Are you sure you want to delete this transaction?")) {
        return;
    }

    fetch('delete_transaction.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${transactionId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the transaction row from the table
            const row = document.querySelector(`tr[data-id='${transactionId}']`);
            if (row) {
                row.remove();
            }
            
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:',error));
}
function fetchUpdatedChartData() {
    fetch('get_chart_data.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update chart with new data
            updateChart(data.labels, data.data);
        } else {
            console.error('Failed to fetch updated chart data');
        }
    })
    .catch(error => console.error('Error:', error));
}

<head>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>

<div class="attendance-reports">
    <h2>Attendance Reports</h2>

    <!-- Filter Form for Attendance Reports -->
<div class="filter-reports">
    <h3>Filter Reports</h3>
    <form id="attendanceForm" action="reports.php" method="GET">
        <label for="course-select">Select Course:</label>
        <select id="course-select" name="course_id" required>
            <option value="" disabled selected>Select a course</option>
            <!-- Dynamically populate course options -->
        </select>

        <label for="time-period">Time Period:</label>
        <select id="time-period" name="time_period" required onchange="handleTimePeriodChange()">
            <option value="" disabled selected>Select time period</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
        </select>

        <!-- Dynamic session dropdown for 'daily' period -->
        <div id="session-select-container" style="display:none;">
            <label for="session-select">Select Session:</label>
            <select id="session-select" name="session_id">
                <option value="" disabled selected>Select a session</option>
                <!-- Sessions will be dynamically populated here -->
            </select>
        </div>

        <button type="submit">View Report</button>
    </form>
</div>

    <!-- Report Details Section -->
    <div class="report-results">
        <h3>Report Details</h3>

        <!-- Dynamic table based on selected time period -->
        <table id="report-table">
            <thead id="report-table-head">
                <!-- Headers will be dynamically populated based on selected time period -->
            </thead>
            <tbody id="report-table-body">
                <!-- Report data will be populated here -->
            </tbody>
        </table>

        <!-- Export to Excel Button -->
        <button id="export-excel" style="margin-top: 20px;" onclick="exportTableToExcel('report-table', 'attendance_report')">Export to Excel</button>
    </div>
</div>

<script>
    // Handle changes to the time period selection
    function handleTimePeriodChange() {
        const timePeriod = document.getElementById("time-period").value;
        const sessionSelectContainer = document.getElementById("session-select-container");

        // Show session dropdown if daily is selected, otherwise hide it
        if (timePeriod === "daily") {
            sessionSelectContainer.style.display = "block";
            // Fetch available sessions for the selected course via AJAX (for demo, it's a placeholder)
            fetchSessionsForCourse();
        } else {
            sessionSelectContainer.style.display = "none";
        }
    }

    // Placeholder function to simulate fetching sessions from the server
    function fetchSessionsForCourse() {
        const courseId = document.getElementById("course-select").value;

        // Example: Use AJAX to fetch session data for the selected course
        // For now, we'll manually populate sessions for demo purposes
        const sessionSelect = document.getElementById("session-select");
        sessionSelect.innerHTML = `
            <option value="1">2024-09-24: 09:00 - 10:00</option>
            <option value="2">2024-09-25: 11:00 - 12:00</option>
        `;
    }

    // Function to export the report table to Excel
    function exportTableToExcel(tableID, filename = '') {
        const downloadLink = document.createElement('a');
        const dataType = 'application/vnd.ms-excel';
        const tableSelect = document.getElementById(tableID);
        const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;

        // Trigger download
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
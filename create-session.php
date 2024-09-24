<head>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>


<div class="create-session">
    <h2>Create New Attendance Session</h2>

    <!-- Form to Create New Session -->
<div class="session-form">
    <h3>Session Details</h3>
    <form action="create_session.php" method="POST">
        <label for="course-select">Select Course:</label>
        <select id="course-select" name="course_id" required>
            <option value="" disabled selected>Select a course</option>
            <!-- Dynamically populate courses -->
            <!-- Example options -->
            <option value="1">Math 101</option>
            <option value="2">Physics 101</option>
        </select>

        <label for="session-date">Session Date:</label>
        <input type="date" id="session-date" name="session_date" required>

        <label for="session-begin">Session Begin Time:</label>
        <input type="time" id="session-begin" name="session_begin" required>

        <label for="session-end">Session End Time:</label>
        <input type="time" id="session-end" name="session_end" required>

        <button type="submit">Create Session & Generate QR Code</button>
    </form>
</div>

</div>

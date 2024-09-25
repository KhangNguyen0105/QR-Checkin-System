<head>  
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>

<div class="manage-courses">
    <h2>Manage Courses</h2>
    
    <!-- Form to Add New Course -->
    <div class="add-course">
        <h3>Add New Course</h3>
        <form action="add_course.php" method="POST">
            <label for="course-name">Course Name:</label>
            <input type="text" id="course-name" name="course_name" required>

            <label for="course-description">Course Description:</label>
            <textarea id="course-description" name="course_description" rows="4" required></textarea>

            <button type="submit">Add Course</button>
        </form>
    </div>

    <!-- List of Existing Courses -->
    <div class="existing-courses">
        <h3>Existing Courses</h3>
        <table class="course-table">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Manually added sample courses -->
            <tr>
                <td>1</td>
                <td>Introduction to Programming</td>
                <td>
                    <a href="view_course.php?course_id=1" class="action-btn view-btn">View Students</a>
                    <a href="#" class="action-btn edit-btn" data-course-id="1" data-course-name="Introduction to Programming" data-course-desc="Learn the basics of programming in this introductory course.">Edit</a>
                    <a href="delete_course.php?course_id=1" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Web Development Basics</td>
                <td>
                    <a href="view_course.php?course_id=2" class="action-btn view-btn">View Students</a>
                    <a href="#" class="action-btn edit-btn" data-course-id="2" data-course-name="Web Development Basics" data-course-desc="Fundamentals of HTML, CSS, and JavaScript for web development.">Edit</a>
                    <a href="delete_course.php?course_id=2" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Database Management Systems</td>
                <td>
                    <a href="view_course.php?course_id=3" class="action-btn view-btn">View Students</a>
                    <a href="#" class="action-btn edit-btn" data-course-id="3" data-course-name="Database Management Systems" data-course-desc="Learn relational database concepts and SQL queries.">Edit</a>
                    <a href="delete_course.php?course_id=3" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                </td>
            </tr>

        </tbody>
    </table>

    <!-- Edit Course Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Course</h2>
            <form id="editCourseForm" action="edit_course.php" method="post">
                <input type="hidden" name="course_id" id="course_id">

                <label for="course_name">Course Name</label>
                <input type="text" name="course_name" id="course_name" required>

                <label for="course_desc">Course Description</label>
                <textarea name="course_desc" id="course_desc" rows="4" required></textarea>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    </div>
</div>

<script>

    // Get the modal and close button
    var modal = document.getElementById("editModal");
    var closeBtn = document.getElementsByClassName("close")[0];

    // Get the edit buttons
    var editButtons = document.querySelectorAll(".edit-btn");

    // Loop through all edit buttons and add click event
    editButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            var courseId = this.getAttribute("data-course-id");
            var courseName = this.getAttribute("data-course-name");
            var courseDesc = this.getAttribute("data-course-desc");

            // Populate the form with course data
            document.getElementById("course_id").value = courseId;
            document.getElementById("course_name").value = courseName;
            document.getElementById("course_desc").value = courseDesc;

            // Open the modal
            modal.style.display = "block";
        });
    });

    // Close the modal when clicking the close button
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>
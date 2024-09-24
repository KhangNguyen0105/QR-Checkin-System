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
    <div class="course-list">
        <h3>Existing Courses</h3>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through courses dynamically in PHP -->
                <?php
                // Example: Fetch courses from the database
                // $courses = getCoursesFromDatabase();
                // foreach ($courses as $course) {
                //     echo "<tr>
                //             <td>{$course['name']}</td>
                //             <td>{$course['description']}</td>
                //             <td>
                //                 <a href='edit_course.php?id={$course['id']}' class='edit-btn'>Edit</a>
                //                 <a href='delete_course.php?id={$course['id']}' class='delete-btn'>Delete</a>
                //             </td>
                //           </tr>";
                // }
                ?>
                <tr>
                    <td>Math 101</td>
                    <td>Basic Algebra</td>
                    <td>
                        <a href="#" class="edit-btn">Edit</a>
                        <a href="#" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Physics 101</td>
                    <td>Introduction to Physics</td>
                    <td>
                        <a href="#" class="edit-btn">Edit</a>
                        <a href="#" class="delete-btn">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];

$courses_sql = 'SELECT id, course_name FROM courses WHERE teacher_id = :teacher_id';
$courses_stmt = $pdo->prepare($courses_sql);
$courses_stmt->execute(['teacher_id' => $teacher_id]);
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <style>
        /* CSS cho phần Chọn khoảng thời gian */
        .filter-reports {
            margin-bottom: 20px;
        }
        .filter-reports div {
            margin-bottom: 10px;
        }
        #date-range {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="attendance-reports">
    <h2>Báo cáo điểm danh</h2>
    <div class="filter-reports">
        <form id="attendanceForm">
            <div>
                <label for="course-select">Chọn lớp:</label>
                <select id="course-select" required>
                    <option value="" disabled selected>Chọn lớp học</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="time-period">Chọn chu kỳ:</label>
                <select id="time-period" required>
                    <option value="" selected disabled>Chọn loại báo cáo</option>
                    <option value="by_session">Theo buổi học</option>
                    <option value="by_date">Theo khoảng thời gian</option>
                </select>
            </div>
            <div id="session-select-container" style="display:none;">
                <label for="session-select">Chọn buổi học:</label>
                <select id="session-select" required>
                    <option value="" disabled selected>Chọn buổi học</option>
                </select>
            </div>
            <div id="date-range-container" style="display:none;">
                <label for="date-range">Chọn khoảng thời gian:</label>
                <input type="text" id="date-range" placeholder="Chọn khoảng thời gian" required>
            </div>
            <button type="button" onclick="loadReport()">Xem báo cáo</button>
        </form>
    </div>
    <div class="report-results">
        <h3>Chi tiết điểm danh</h3>
        <table id="report-table">
            <thead>
                <tr>
                    <th>Tên sinh viên</th>
                    <th>Ngày học</th>
                    <th>Trạng thái</th>
                    <th>Thời gian điểm danh</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('time-period').addEventListener('change', function () {
        const timePeriod = this.value;
        if (timePeriod === 'by_session') {
            document.getElementById('session-select-container').style.display = 'block';
            document.getElementById('date-range-container').style.display = 'none';
            loadSessions(document.getElementById('course-select').value);
        } else if (timePeriod === 'by_date') {
            document.getElementById('session-select-container').style.display = 'none';
            document.getElementById('date-range-container').style.display = 'block';
            flatpickr('#date-range', {
                mode: 'range',
                dateFormat: 'Y-m-d'
            });
        }
    });

    document.getElementById('course-select').addEventListener('change', function () {
        if (document.getElementById('time-period').value === 'by_session') {
            loadSessions(this.value);
        }
    });
});

function loadSessions(courseId) {
    fetch(`?load_sessions=1&course_id=${courseId}`)
        .then(response => response.json())
        .then(data => {
            const sessionSelect = document.getElementById('session-select');
            sessionSelect.innerHTML = '<option value="" disabled selected>Chọn buổi học</option>';
            data.forEach(session => {
                const option = document.createElement('option');
                option.value = session.id;
                option.textContent = session.session_date;
                sessionSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

function loadReport() {
    const courseSelect = document.getElementById('course-select');
    const timePeriod = document.getElementById('time-period').value;
    const reportType = timePeriod === 'by_session' ? 'by_session' : 'by_date';
    let periodValue;

    if (reportType === 'by_session') {
        periodValue = document.getElementById('session-select').value;
    } else if (reportType === 'by_date') {
        const dateRange = document.getElementById('date-range').value.split(' to ');
        periodValue = {
            start_date: dateRange[0],
            end_date: dateRange[1]
        };
    }

    fetch('?fetch_report=1', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            course_id: courseSelect.value,
            time_period: periodValue,
            report_type: reportType
        })
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('#report-table tbody');
        tbody.innerHTML = '';
        data.forEach(record => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${record.full_name}</td>
                <td>${record.session_date}</td>
                <td>${record.status}</td>
                <td>${record.check_in_time}</td>
            `;
            tbody.appendChild(row);
        });
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php
if (isset($_GET['load_sessions'])) {
    $course_id = $_GET['course_id'];
    $sessions_sql = 'SELECT id, session_date FROM sessions WHERE course_id = :course_id';
    $sessions_stmt = $pdo->prepare($sessions_sql);
    $sessions_stmt->execute(['course_id' => $course_id]);
    $sessions = $sessions_stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sessions);
    exit;
}

if (isset($_GET['fetch_report'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $course_id = $data['course_id'];
    $time_period = $data['time_period'];
    $report_type = $data['report_type'];

    $sql = 'SELECT u.full_name, s.session_date, a.status, a.check_in_time 
            FROM attendance a
            JOIN users u ON a.student_id = u.id
            JOIN sessions s ON a.session_id = s.id
            WHERE s.course_id = :course_id';

    $params = ['course_id' => $course_id];

    if ($report_type == 'by_session') {
        $sql .= ' AND a.session_id = :session_id';
        $params['session_id'] = $time_period;
    } else if ($report_type == 'by_date') {
        $sql .= ' AND s.session_date BETWEEN :start_date AND :end_date';
        $params['start_date'] = $time_period['start_date'];
        $params['end_date'] = $time_period['end_date'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($attendance);
    exit;
}
?>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Gantt Chart Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: calc(100% - 10px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="button"],
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 10px 20px;
        }
        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .task {
            fill: #64B5F6;
        }
        .dependency {
            stroke: #FFD54F;
            stroke-width: 2;
            fill: none;
        }
        .chart {
            margin-top: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            overflow-x: auto;
        }
        svg {
            display: block;
            margin: auto;
        }
        text {
            font-size: 14px;
            fill: #333;
        }
    </style>
</head>
<body>
    <h2>Gantt Chart Generator</h2>
    <form method="post" action="">
        <div id="tasks">
            <div class="task">
                <label>Task Name:</label>
                <input type="text" name="task_name[]" required><br>
                <label>Start Date:</label>
                <input type="date" name="start_date[]" required><br>
                <label>End Date:</label>
                <input type="date" name="end_date[]" required><br>
                <label>Dependency (Optional):</label>
                <select name="dependency[]">
                    <option value="">None</option>
                    <!-- Add options dynamically based on existing tasks -->
                </select><br><br>
            </div>
        </div>
        <input type="button" value="Add Task" onclick="addTask()">
        <input type="submit" value="Generate Chart">
    </form>

    <div class="chart">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Process form submission and generate the chart
            $taskNames = $_POST["task_name"];
            $startDates = $_POST["start_date"];
            $endDates = $_POST["end_date"];
            $dependencies = $_POST["dependency"];

            // Validate input data (basic validation)
            foreach ($taskNames as $index => $taskName) {
                if (empty($taskName) || empty($startDates[$index]) || empty($endDates[$index])) {
                    echo "<p style='color: red;'>All fields are required for each task.</p>";
                    exit;
                }
            }

            // Calculate chart dimensions
            $chartWidth = 800; // Adjust as needed
            $chartHeight = count($taskNames) * 50; // Adjust as needed
            $barHeight = 30;
            $barMargin = 10;

            // Start generating SVG
            echo "<svg width='$chartWidth' height='$chartHeight'>";

            // Render tasks
            $y = $barMargin;
            foreach ($taskNames as $index => $taskName) {
                $startDate = new DateTime($startDates[$index]);
                $endDate = new DateTime($endDates[$index]);
                $duration = $startDate->diff($endDate)->days;

                // Randomly generate a color for each task
                $taskColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

                // Render task bar
                echo "<rect class='task' x='100' y='$y' width='$duration' height='$barHeight' fill='$taskColor' />";
                echo "<text x='5' y='" . ($y + $barHeight / 2 + 5) . "'>$taskName</text>";

                // Handle dependency if any
                if (!empty($dependencies[$index])) {
                    // Render dependency arrow or line
                    // Example: echo "<line class='dependency' x1='...' y1='...' x2='...' y2='...' />";
                }

                // Update Y position for next task
                $y += $barHeight + $barMargin;
            }

            echo "</svg>";
        }
        ?>
    </div>

    <script>
        function addTask() {
            var tasksDiv = document.getElementById('tasks');
            var newTaskDiv = tasksDiv.children[0].cloneNode(true);
            tasksDiv.appendChild(newTaskDiv);
        }
    </script>
</body>
</html>

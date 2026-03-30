<?php
header('Content-Type: application/json');

if (isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {
    $name     = htmlspecialchars($_POST['student_name']);
    $semester = htmlspecialchars($_POST['semester']);
    $courses  = $_POST['course'];
    $credits  = $_POST['credits'];
    $grades   = $_POST['grade'];
    
    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml = '<table class="table table-hover mt-3"><thead><tr><th>Course</th><th>Credits</th><th>Grade</th></tr></thead><tbody>';

    for ($i = 0; $i < count($courses); $i++) {
        $cr = floatval($credits[$i]);
        $g  = floatval($grades[$i]);
        if ($cr <= 0) continue;

        $totalPoints  += ($cr * $g);
        $totalCredits += $cr;
        $tableHtml .= "<tr><td>{$courses[$i]}</td><td>$cr</td><td>$g</td></tr>";
    }
    $tableHtml .= '</tbody></table>';

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        $percent = ($gpa / 4) * 100;

        // Step 4 logic: Color coding based on GPA
        if ($gpa >= 3.7) { $status = "Distinction"; $color = "bg-success"; }
        elseif ($gpa >= 3.0) { $status = "Merit"; $color = "bg-info"; }
        elseif ($gpa >= 2.0) { $status = "Pass"; $color = "bg-warning"; }
        else { $status = "Fail"; $color = "bg-danger"; }

        $resMessage = "Result for <strong>$name</strong> ($semester):";
        
        $progressBar = '
        <div class="progress mt-3" style="height: 30px; border-radius: 15px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated '.$color.'" 
                 role="progressbar" style="width: '.$percent.'%">
                 GPA: '.number_format($gpa, 2).' ('.$status.')
            </div>
        </div>';

        echo json_encode([
            'success'   => true,
            'message'   => $resMessage,
            'progress'  => $progressBar,
            'tableHtml' => $tableHtml
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Please enter valid course data.']);
    }
}
exit;
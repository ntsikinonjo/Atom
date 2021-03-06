<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once __DIR__ . '../../config/Database.php';
    include_once __DIR__ . '../../models/Basic.php';
    include_once __DIR__ . '../../models/Assessment.php';
    include_once __DIR__ . '../../models/Mark.php';
    include_once __DIR__ . '../../models/Statistic.php';

    // instanciate database and connect
    $database = new Database('127.0.0.1','risk','root','');
    $db = $database->connect();

    // instantiate basic object
    $basic = new Basic($db);

    // get id from url
    $basic->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

    // get subjects
    $result = $basic->getSubjects();

    $num = $result->rowCount();

    if ($num > 0) {

        // subject exist
        $summaryArray = array(
            'name' => 'Summary',
            'description' => 'Summary for home page',
            'message' => 'success'
        );
        $summaryArray['contents'] = array();
        $i = 0;

        // temporary array holding course id
        $courseArray = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            /*
            $subjectItem = array(
                'course_id' => $course_id,
                'course_name' => $course_name
            );
            */
            $courseArray[$i] = $course_id + 0;

            // push to content and id value in array
            //$summaryArray['contents'][$i] = $subjectItem;
            $i++;
        }

        // now for each course get assessments place in assessment key in contents
        // instantiate user object
        $assessment = new Assessment($db);

        $summaryArray['contents']['assessments'] = array();

        // statement
        $statement = $assessment->getSummary($courseArray);

        $num = $statement->rowCount();

        if ($num > 0) {

            // index
            $i = 0;
            $getMarksByAssessmentId;

            while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                // get marks by assessment id
                if ($i == 0) $getMarksByAssessmentId = $assessment_id;

                // pass rate array
                $rate = $assessment->getPassRate($assessment_id, $assessment_total);

                // assessment
                $assessmentItem = array(
                    'assessment_name' => $assessment_name,
                    'assessment_date' => $assessment_date,
                    'assessment_total' => $assessment_total,
                    'pass' => $rate[0],
                    'fail' => $rate[1]
                );
    
                // push to content and id value in array
                $summaryArray['contents']['assessments'][$i] = $assessmentItem;

                $i++;
            }

            // get average of recent assessment
            $mark = new Mark($db);
            $statistic = new Statistic($db);

            // get marks and data
            $marks = $mark->getMarksByAssessmentId($getMarksByAssessmentId);
            $average = $statistic->getAverage($marks);

            // put average
            $summaryArray['contents']['assessments'][0]['average'] = $average;

            echo json_encode($summaryArray);

        }
        else {
            // could not find assessments
            echo json_encode(array('message' => 'error'));
        }

        //echo json_encode($courseArray);
    }
    else {

        // user doesn't have subjects
        echo json_encode(array('message' => 'error'));
    }
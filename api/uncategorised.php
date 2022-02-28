<?php

include_once '../config/Database.php';
include_once '../model/QuestionSet.php';
include_once '../model/Facilitator.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: http://uniquechange.com');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');

function typeArray($type) {
    if (is_array($type) || empty($type)) {
        return $type;
    }
    return explode(",", $type);
}

function filter_results_to_enabled_groups($question_sets)
{
    // Filter results to only contain question sets available in the current group

    // Are you in a group
    $database = new Database();
    $conn = $database->connect();
    $stmt = $conn->prepare("SELECT * FROM user_groups WHERE user_email = ?");
    $stmt->bind_param("s", $_GET["email"]);
    $stmt->execute();
    $user_groups_count = count($stmt->get_result()->fetch_all());
    if ($user_groups_count > 0) {
        // Get the question sets available to your group
        $stmt = $conn->prepare("SELECT * FROM `user_groups` INNER JOIN group_question_set ON group_question_set.group_id = user_groups.group_id WHERE user_email = ? ");
        $stmt->bind_param("s", $_GET["email"]);
        $stmt->execute();
        $groups_result = $stmt->get_result();

        $filtered_results = [];

        // Take only the questions that match the query and are in your available groups
        while ($row = $groups_result->fetch_assoc()) {
            foreach ($question_sets as $r) {
                if ($row["question_set_id"] == $r["ID"]) {
                    $filtered_results[] = $r;
                }
            }
        }
        return $filtered_results;
    } else {
        $unfiltered_results = [];
        foreach ($question_sets as $r) {
            $unfiltered_results[] = $r;
        }
        return $unfiltered_results;
    }
}


$phase = $_GET["phase"];
if (isset($phase)) {
    $question = new QuestionSet();
    $type = typeArray($_GET["type"]);
    $specialism = typeArray($_GET["specialism"]);
    if (empty($type) && empty($specialism)) {
        $results = $question->uncategorised_question_set($phase);
        $results = filter_results_to_enabled_groups($results);
        echo json_encode($results);
    } else {
        $output_specialism = array();
        $output_type = array();

        if (!empty($type)) {
            $type = "'" . implode("', '", $type) . "'";

            $results = $question->question_set_type_uncategorised($phase, $type);

            while ($row = $results->fetch_assoc()) {
                $output_type[] = $row;
            }
        }
        if (!empty($specialism)) {
            $specialism = "'" . implode("', '", $specialism) . "'";

            $results = $question->question_set_specilism_uncategorised($phase, $specialism);

            while ($row = $results->fetch_assoc()) {
                $output_specialism[] = $row;
            }
        }
        $results = array_merge($output_specialism, $output_type);
        $results = filter_results_to_enabled_groups($results);
        echo json_encode($results);
    }
} else {
    die("No phase");
}

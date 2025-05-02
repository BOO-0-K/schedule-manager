<?php
class Schedule {
    private $db;

    public function __construct() {
        $this->db = new DB;
        $this->db->connect();
    }

    /**
     * Get Stats
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getStats($startDate, $endDate) {
        $stats = array();
        $startTime = $startDate . " " . "00:00:00";
        $endTime = $endDate . " " . "23:59:59";
        $selectSQL = "SELECT `type`, COUNT(*) as `count` FROM `schedules` WHERE `is_deleted` = '0' AND `start_time` >= '{$startTime}' AND `end_time` <= '{$endTime}' GROUP BY `type`";
        $selectQuery = $this->db->q($selectSQL);
        while ($selectResult = $selectQuery->fetch_assoc()) {
            array_push($stats, $selectResult);
        }
        return $stats;
    }

    /**
     * Get ScheduleList
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getScheduleList($startDate, $endDate) {
        $schedules = array();
        $startTime = $startDate . " " . "00:00:00";
        $endTime = $endDate . " " . "23:59:59";
        $selectSQL = "SELECT * FROM `schedules` WHERE `is_deleted` = '0' AND `start_time` >= '{$startTime}' AND `end_time` <= '{$endTime}' ORDER BY `idx` DESC;";
        $selectQuery = $this->db->q($selectSQL);
        while ($selectResult = $selectQuery->fetch_assoc()) {
            array_push($schedules, $selectResult);
        }
        return $schedules;
    }

    /**
     * Get ScheduleDetail
     * @param int $idx
     * @return array|null
     */
    public function getScheduleDetail($idx) {
        if (empty($idx)) return null;
        $selectSQL = "SELECT * FROM `schedules` WHERE `idx` = '{$idx}';";
        $selectQuery = $this->db->q($selectSQL);
        $selectResult = $selectQuery->fetch_assoc();
        return $selectResult;
    }

    /**
     * Get Schedule's Participants
     * @param int $idx
     * @return array|null
     */
    public function getScheduleParticipants($idx) {
        if (empty($idx)) return null;
        $participants = array();
        $selectSQL = "SELECT * FROM `schedule_participants` WHERE `schedule_idx` = '{$idx}'";
        $selectQuery = $this->db->q($selectSQL);
        while ($selectResult = $selectQuery->fetch_assoc()) {
            array_push($participants, $selectResult["user_idx"]);
        }
        return $participants;
    }

    /**
     * Create Schedule
     * @param int $userIdx
     * @param string $title
     * @param string $location
     * @param int $type
     * @param string $startTime
     * @param string $endTime
     * @return int|string
     */
    public function createSchedule($userIdx, $title, $location, $type, $startTime, $endTime) {
        $insertData = [
            "user_idx" => $userIdx,
            "title" => $title,
            "location" => $location,
            "type" => $type,
            "start_time" => $startTime,
            "end_time" => $endTime,
        ];
        $insertSQL = $this->db->insertSQL("schedules", $insertData);
        $this->db->q($insertSQL);
        return $this->db->lastInsertIndex;
    }

    /**
     * Create Participant
     * @param int $scheduleIdx
     * @param int $userIdx
     * @return void
     */
    public function createParticipant($scheduleIdx, $userIdx) {
        $insertData = [
            "schedule_idx" => $scheduleIdx,
            "user_idx" => $userIdx,
        ];
        $insertSQL = $this->db->insertSQL("schedule_participants", $insertData);
        $this->db->q($insertSQL);
        return;
    }

    /**
     * Update Schedule
     * @param int $scheduleIdx
     * @param string $title
     * @param string $location
     * @param int $type
     * @param string $startTime
     * @param string $endTime
     * @return void
     */
    public function updateSchedule($scheduleIdx, $title, $location, $type, $startTime, $endTime) {
        $updateData = [
            "title" => $title,
            "location" => $location,
            "type" => $type,
            "start_time" => $startTime,
            "end_time" => $endTime,
        ];
        $updateTarget["idx"] = $scheduleIdx;
        $updateSQL = $this->db->updateSQL("schedules", $updateData, $updateTarget);
        $this->db->q($updateSQL);
        return;
    }

    /**
     * Delete Participants
     * @param int $scheduleIdx
     * @return void
     */
    public function deleteParticipants($scheduleIdx) {
        $deleteSQL = "DELETE FROM `schedule_participants` WHERE `schedule_idx` = '{$scheduleIdx}';";
        $this->db->q($deleteSQL);
        return;
    }

    /**
     * Delete Schedule
     * @param int $scheduleIdx
     * @return void
     */
    public function deleteSchedule($scheduleIdx) {
        $updateData["is_deleted"] = 1;
        $updateTarget["idx"] = $scheduleIdx;
        $updateSQL = $this->db->updateSQL("schedules", $updateData, $updateTarget);
        $this->db->q($updateSQL);
        return;
    }

    /**
     * Check My Schedule
     * @param int $userIdx
     * @param int $scheduleIdx
     * @return bool
     */
    public function isMySchedule($userIdx, $scheduleIdx) {
        if (empty($userIdx) || empty($scheduleIdx)) return false;
        $selectSQL = "SELECT `user_idx` FROM `schedules` WHERE `idx` = '{$scheduleIdx}';";
        $selectQuery = $this->db->q($selectSQL);
        $selectResult = $selectQuery->fetch_assoc();
        if ($userIdx == $selectResult["user_idx"]) {
            return true;
        }
        return false;
    }
}
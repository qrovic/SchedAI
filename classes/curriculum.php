<?php
require_once('db.php');

class Curriculum {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addcurriculum($academicyear, $semester, $curriculumplan) {
        $name = $academicyear . "-" . ($academicyear + 1); 

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM calendar WHERE year = :year AND sem = :sem");
        $stmt->bindParam(':year', $academicyear); 
        $stmt->bindParam(':sem', $semester);      
        $stmt->execute();
        $curriculumexists = $stmt->fetchColumn();

        if ($curriculumexists) {
            $sql = "UPDATE calendar SET curriculumplan = :curriculumplan WHERE year = :year AND sem = :sem";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':sem', $semester);
            $stmt->bindParam(':curriculumplan', $curriculumplan);
            return $stmt->execute();
        } else {
            $sql = "INSERT INTO calendar (sem, year, name, curriculumplan) VALUES (:sem, :year, :name, :curriculumplan)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':sem', $semester);
            $stmt->bindParam(':curriculumplan', $curriculumplan);
            return $stmt->execute();
        }
    }

    
    
    public function getroombyid($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateroom($id, $name, $capacity) {
        $sql = "UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':capacity' => $capacity,
            ':id' => $id
        ]);
    }

    public function deletecurriculum($id) {
        $sql = "DELETE FROM calendar WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getallcurriculums() {
        $sqlcalendar = "SELECT * FROM calendar WHERE curriculumplan=1 ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getallcurriculumsschedule() {
        $sqlcalendar = "SELECT * FROM calendar ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getdistinctcurriculumsschedule() {
        $sqlcalendar = "SELECT DISTINCT year as year,name as name FROM calendar  WHERE curriculumplan=1 ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findcurriculumid($academicyear, $semester) {
        $query = "SELECT id FROM calendar WHERE year = :academicyear AND sem = :semester LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':academicyear', $academicyear);
        $stmt->bindParam(':semester', $semester);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    
}
?>
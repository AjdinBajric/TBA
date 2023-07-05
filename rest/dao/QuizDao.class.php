<?php
require_once __DIR__ . '/BaseDao.class.php';

class QuizDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("quiz");
    }

    public function get_by_quiz_type($quiz_type_id, $category_name)
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT q.* FROM quiz q 
                     WHERE q.quiz_type_id = :quiz_type_id AND q.category_id = (SELECT id FROM category WHERE name = :category_name)");
        $stmt->bindParam(':quiz_type_id', $quiz_type_id); // SQL injection prevention
        $stmt->bindParam(':category_name', $category_name); // SQL injection prevention
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_quiz_data_by_id($id)
    {
        $quiz_stmt = $this->conn->prepare("
            SELECT q.id, q.name, q.description, q.percentage_to_pass, q.max_points, c.name as category_name, qt.type as quiz_type 
            FROM quiz q   
            LEFT JOIN category c ON c.id = q.category_id
            LEFT JOIN quiz_type qt ON qt.id = q.quiz_type_id
            WHERE q.id = :id
        ");
        $quiz_stmt->bindParam(':id', $id); // SQL injection prevention
        $quiz_stmt->execute();
        $quiz_data = $quiz_stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $data['quiz'] = $quiz_data[0];

        $question_stmt = $this->conn->prepare("
            SELECT q.id, q.points, q.text, q.image_url
            FROM questions q
            LEFT JOIN quiz qu ON qu.id = q.quiz_id
            WHERE q.quiz_id = :quiz_id
        ");
        $question_stmt->bindParam(':quiz_id', $id); // SQL injection prevention
        $question_stmt->execute();

        while ($row = $question_stmt->fetch(PDO::FETCH_ASSOC)) {
            // get question id
            $question_id = $row['id'];

            // get answers for question
            $answer_stmt = $this->conn->prepare("
                SELECT a.id, a.text, a.is_correct
                FROM answers a   
                LEFT JOIN questions q ON q.id = a.question_id
                WHERE a.question_id = :question_id
            ");
            $answer_stmt->bindParam(':question_id', $question_id); // SQL injection prevention
            $answer_stmt->execute();
            $answer_data = $answer_stmt->fetchAll(PDO::FETCH_ASSOC);

            // add answers to question
            $row['answers'] = $answer_data;

            // add question to data quiz
            $data['quiz']['questions'][] = $row;
        }

        return $data;
    }
}

?>
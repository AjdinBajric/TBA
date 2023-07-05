<?php
require_once __DIR__ . '/BaseDao.class.php';

class QuizTypeDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("quiz_type");
    }

    public function get_by_category_name($category)
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT qt.* FROM quiz q JOIN quiz_type qt ON q.quiz_type_id = qt.id
        WHERE q.category_id = (SELECT id FROM category WHERE name = :category)");
        $stmt->bindParam(':category', $category); // SQL injection prevention
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
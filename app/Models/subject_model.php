<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class subject_model extends Model
{
    public function getAllSubjects(){
        $db = \Config\Database::connect();
        $query = $db->query('SELECT id, name, description FROM subjects');
        $subjects = $query->getResult();
        return $subjects;
    }

    public function getQuestions($subject){
        $db = \Config\Database::connect();
        $query = $db->query('SELECT id, user_id, title, description FROM questions 
        WHERE subjectID = '.$subject.' ORDER BY likes DESC, time DESC');
        $questions = $query->getResult();
        return $questions;
    }

    public function addQuestion($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('questions');
        if ($builder->insert($data)){
            return  $db->insertID();
        } else {
            return false;
        }
    }

    public function addFile($name, $id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('files');

        $data = array(
            'name' => $name,
            'question_id' => $id
        );

        if ($builder->insert($data)){
            return  true;
        } else {
            return false;
        }
    }


    public function addAnswer($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('answers');
        if ($builder->insert($data)){
            return true;
        } else {
            return false;
        }
    }


    public function getUserQuestions($username)
    {
        $db = \Config\Database::connect();
            $query = $db->query("SELECT q.id, q.title, q.description, s.name as subject_name, q.likes FROM questions q
            INNER JOIN subjects s ON q.subjectID = s.id
            INNER JOIN users u ON q.user_id = u.id
            WHERE u.username = '".$username."'");

        $questions = $query->getResult();
        return $questions;
    }

    public function getAnswers($questionId)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT id, description, likes FROM answers 
        WHERE question_id = '.$questionId.' ORDER BY likes DESC');
        $answers = $query->getResult();
        return $answers;
    }

    public function getFiles($questionId)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT name FROM files 
        WHERE question_id = '.$questionId);
        $answers = $query->getResult();
        return $answers;
    }

    public function endorseAnswer($AnswerId)
    {
        $db = \Config\Database::connect();
        $query = $db->query('UPDATE answers 
        SET likes = likes + 1 WHERE id = '.$AnswerId);
      
    }
    public function unEndorseAnswer($AnswerId)
    {
        $db = \Config\Database::connect();
        $query = $db->query('UPDATE answers 
        SET likes = 0 WHERE id = '.$AnswerId);
      
    }

    public function getSearch($subject, $query){
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, user_id, title, description FROM questions 
        WHERE subjectID = $subject AND (title LIKE '%".$query."%' OR description LIKE '%".$query."%') ORDER BY time DESC");
        $questions = $query->getResult();
        return $questions;
    }

    public function getStats($subject){
        
        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT likes.*, users.username FROM likes
        JOIN users ON likes.user_id = users.id
        WHERE likes.subject_id = '$subject'");
        
        $data = $query->getResult();

        return $data;
    }

    public function getQuestionName($id){
        
        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT title FROM questions
        WHERE id = '$id'");
        
        $questions = $query->getrow();

        return $questions;
    }
    public function getBookmarks($id){
        
        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT questionId, title, description
        FROM questions
        JOIN bookmarks ON questions.id = bookmarks.questionId
        WHERE bookmarks.userId = '$id'");
        
        $questions = $query->getResult();

        return $questions;
    }

    public function addSubject($name, $description)
    {
        
        $db = \Config\Database::connect();
        $builder = $db->table('subjects');

        $data = array(
            'id' => rand(1000, 999999999),
            'name' => $name,
            'description' => $description
        );

        $builder->insert($data);
        
    }

    public function bookmarkQuestion($id, $user, $num)
    {
        
        $db = \Config\Database::connect();

        if($num == 1) {
            $builder = $db->table('bookmarks');

            $builder->where('questionId', $id);
            $builder->where('userId', $user);
            $count = $builder->countAllResults();

            if ($count == 0) {
                $data = array(
                    'questionId' => $id,
                    'userId' => $user,
                );

                $builder->insert($data);
            }
        } else {
            $builder = $this->db->table('bookmarks');

            $builder->where('questionId', $id);
            $builder->where('userId', $user);

            $builder->delete();
        }
    }
}
?>
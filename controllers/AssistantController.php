<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Dictionary;
use app\models\Book;

class AssistantController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
  public function actionAsk()
{
    $request = Yii::$app->request;
    $query = trim($request->get('q'));
    
    if (empty($query)) {
        return $this->redirect(['index']);
    }
    
    $originalQuery = $query;
    $normalizedQuery = $this->normalizeWord($query);
    
    $dictionaryEntry = null;
    
    $dictionaryEntry = Dictionary::find()
        ->where(['term' => $originalQuery])
        ->one();
    
    if (!$dictionaryEntry && $normalizedQuery != $originalQuery) {
        $dictionaryEntry = Dictionary::find()
            ->where(['term' => $normalizedQuery])
            ->one();
    }
    
    if (!$dictionaryEntry) {
        $dictionaryEntry = Dictionary::find()
            ->where(['like', 'term', $originalQuery . '%', false])
            ->orWhere(['like', 'term', $normalizedQuery . '%', false])
            ->one();
    }
    
    if (!$dictionaryEntry && mb_strlen($originalQuery) > 3) {
        $dictionaryEntry = Dictionary::find()
            ->where(['like', 'term', $originalQuery])
            ->andWhere(['not', ['term' => $originalQuery]])
            ->one();
    }
    
    if (!$dictionaryEntry && mb_strlen($originalQuery) > 4) {
        $dictionaryEntry = $this->fuzzySearch($normalizedQuery);
    }
    
    $books = Book::find()
        ->where(['like', 'title', $originalQuery])
        ->orWhere(['like', 'title', $normalizedQuery])
        ->limit(6)
        ->all();
    
    return $this->render('result', [
        'originalQuery' => $originalQuery,
        'normalizedQuery' => $normalizedQuery,
        'dictionaryEntry' => $dictionaryEntry,
        'books' => $books,
    ]);
} 
    
    private function normalizeWord($word)
    {
        $word = mb_strtolower($word, 'UTF-8');
        
        $endings = ['ами', 'ями', 'ов', 'ев', 'ей', 'ам', 'ям', 'ах', 'ях', 'ом', 'ем', 'ём', 'ой', 'ей', 'е', 'и', 'ы', 'а', 'я', 'у', 'ю', 'ть', 'л', 'ла', 'ло', 'ли', 'ешь', 'ет', 'ем', 'ете'];
        
        usort($endings, function($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });
        
        foreach ($endings as $ending) {
            if (mb_substr($word, -mb_strlen($ending)) === $ending) {
                $word = mb_substr($word, 0, -mb_strlen($ending));
                break;
            }
        }
        
        return $word;
    }
    
    private function fuzzySearch($query)
    {
        $allTerms = Dictionary::find()->all();
        $bestMatch = null;
        $bestPercent = 0;
        
        foreach ($allTerms as $term) {
            $termLower = mb_strtolower($term->term, 'UTF-8');
            similar_text($query, $termLower, $percent);
            
            if ($percent > $bestPercent && $percent > 55) {
                $bestPercent = $percent;
                $bestMatch = $term;
            }
        }
        
        return $bestMatch;
    }
}